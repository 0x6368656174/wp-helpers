<?php

declare(strict_types=1);
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace ItQuasar\WpHelpers\Ajax\Auth;

use ItQuasar\WpHelpers\Ajax\AbstractAjaxHandler;
use ItQuasar\WpHelpers\Ajax\AjaxException;
use ItQuasar\WpHelpers\Page;
use ItQuasar\WpHelpers\Rewrite;
use WP_User;
use const FILTER_SANITIZE_STRING;
use const INPUT_POST;
use function check_password_reset_key;
use function filter_input;
use function get_user_by_email;
use function home_url;
use function sanitize_email;
use function sanitize_text_field;
use function sanitize_user;
use function wp_redirect;

class AuthRestorePasswordAjax extends AbstractAjaxHandler
{
  public const ERROR_CODE_NOT_SET_EMAIL = 1;
  public const ERROR_CODE_NOT_FOUND_USER = 2;
  /** @var string */
  private static $restorePasswordPath = 'auth/restore-password';
  /** @var string */
  private static $successRestorePasswordPath;
  /** @var AuthRestorePasswordEmailProvider */
  private static $emailProvider;

  public function __construct()
  {
    // Добавим обработчик восстановления паролей
    // TODO: Доделать нормальную обработку ошибок
    Rewrite::registerRewriteRule(static::$restorePasswordPath, function () {
      $login = filter_input(INPUT_GET, 'login', FILTER_SANITIZE_STRING);
      if ($login) {
        $login = sanitize_user($login);
      } else {
        Page::throwNotFoundException();
      }

      $key = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);
      if ($key) {
        $key = sanitize_text_field($key);
      } else {
        Page::throwNotFoundException();
      }

      // Проверим ключ
      $user = check_password_reset_key($key, $login);

      // Если ключ не подошел, то ошибка
      if (is_wp_error($user)) {
        Page::throwNotFoundException();
      }

      // Войдем
      wp_set_current_user($user->ID);
      wp_set_auth_cookie($user->ID);

      // Перейдем на URL успешного восстановления пароля
      wp_redirect(static::getSuccessRestorePasswordPath());
      exit();
    });
  }

  /**
   * Устанавливает путь к странице обработки восстановления пароля.
   *
   * По-умолчанию, 'auth/restore-password'.
   *
   * @param string $path
   */
  public static function setRestorePasswordPath(string $path): void
  {
    static::$restorePasswordPath = $path;
  }

  /**
   * Возвращает URL, на который будет перенаправлен пользователь после успешного восстановления пароля.
   *
   * По-умолчанию, это `home_url()`.
   *
   * @return string
   */
  public static function getSuccessRestorePasswordPath(): string
  {
    if (!static::$successRestorePasswordPath) {
      static::$successRestorePasswordPath = home_url();
    }

    return static::$successRestorePasswordPath;
  }

  /**
   * Устанавливает URL, на который будет перенпаравлен пользователь после успешного восстановления пароля.
   *
   * По-умолчанию, это `home_url()`.
   *
   * @param string $path URL
   */
  public static function setSuccessRestorePasswordPath(string $path): void
  {
    static::$successRestorePasswordPath = $path;
  }

  /**
   * Возвращает провайдера email'ов, используемых модулем восстановления пароля.
   *
   * @return AuthRestorePasswordEmailProvider
   */
  public static function getEmailProvider(): AuthRestorePasswordEmailProvider
  {
    if (!static::$emailProvider) {
      static::$emailProvider = new AuthRestorePasswordEmailProvider();
    }

    return static::$emailProvider;
  }

  /**
   * Устанавливает провайдера email'ов, используемых модулем восстановления пароля.
   *
   * @param AuthRestorePasswordEmailProvider $emailProvider Провайдер email'ов
   */
  public static function setEmailProvider(AuthRestorePasswordEmailProvider $emailProvider): void
  {
    static::$emailProvider = $emailProvider;
  }

  /**
   * Создает для пользователя URL для восстановления пароля и возвращает его.
   *
   * @param WP_User $user Пользователь
   *
   * @return string
   */
  public static function createRestorePasswordUrl(WP_User $user): string
  {
    $key = get_password_reset_key($user);
    $login = $user->user_login;

    return home_url().'/'.static::$restorePasswordPath."?key=$key&login=$login";
  }

  public function actionName(): string
  {
    return 'auth_restore_password';
  }

  public function callback(): array
  {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (!$email) {
      throw new AjaxException('Not set email', self::ERROR_CODE_NOT_SET_EMAIL);
    }
    $email = sanitize_email($email);

    // Найдем пользователя
    $user = get_user_by_email($email);
    if (!$user) {
      throw new AjaxException('Not found user with requested email', self::ERROR_CODE_NOT_FOUND_USER);
    }

    // Создадим URL для восстановления
    $url = self::createRestorePasswordUrl($user);
    // Отправим письмо новому пользователю сайта
    $mail = static::getEmailProvider()->restorePasswordUserEmail($user, $url);
    wp_mail(
  $mail['to'],
  $mail['subject'],
  $mail['message'],
  $mail['headers']
  );

    return [];
  }
}
