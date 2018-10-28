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
use const FILTER_SANITIZE_EMAIL;
use const FILTER_SANITIZE_STRING;
use const INPUT_POST;
use function email_exists;
use function esc_url;
use function filter_input;
use function sanitize_email;
use function sanitize_text_field;
use function sanitize_textarea_field;
use function username_exists;
use function wp_generate_password;

/**
 * Авторизация.
 */
class AuthSignUpAjax extends AbstractAjaxHandler
{
  public const ERROR_CODE_NOT_SET_LOGIN = 1;
  public const ERROR_CODE_NOT_SET_EMAIL = 2;
  public const ERROR_CODE_NOT_SET_PASSWORD = 3;
  public const ERROR_CODE_LOGIN_EXISTS = 4;
  public const ERROR_CODE_EMAIL_EXISTS = 5;
  /** @var bool */
  private static $signUpWithPasswordEnabled = false;
  /** @var AuthSignUpEmailProvider */
  private static $emailProvider;

  /**
   * Включает регистрацию с использованием пароля.
   *
   * По-умолчанию, регистрация с использованием пароля запрещена, т.к. WordPress не отправляет клиенту письмо
   * о подтверждении пароля, и есть возможность зарегистрироваться не на свой email.
   *
   * @param bool $enabled Признак того, что регистрация с использованием пароля разрешена
   */
  public static function setSignUpWithPasswordEnabled(bool $enabled): void
  {
    static::$signUpWithPasswordEnabled = $enabled;
  }

  /**
   * Возвращает признак того, что регистрация с использованием пароля разрешена.
   *
   * По-умолчанию, регистрация с использованием пароля запрещена, т.к. WordPress не отправляет клиенту письмо
   * о подтверждении пароля, и есть возможность зарегистрироваться не на свой email.
   *
   * @return bool
   */
  public static function isSignUpWithPasswordEnabled(): bool
  {
    return static::$signUpWithPasswordEnabled;
  }

  /**
   * Возвращает провайдера email'ов, используемых модулем регистрации.
   *
   * @return AuthSignUpEmailProvider
   */
  public static function getEmailProvider(): AuthSignUpEmailProvider
  {
    if (!static::$emailProvider) {
      static::$emailProvider = new AuthSignUpEmailProvider();
    }

    return static::$emailProvider;
  }

  /**
   * Устанавливает провайдера email'ов, используемых модулем регистрации.
   *
   * @param AuthSignUpEmailProvider $emailProvider Провайдер email'ов
   */
  public static function setEmailProvider(AuthSignUpEmailProvider $emailProvider): void
  {
    static::$emailProvider = $emailProvider;
  }

  public function actionName(): string
  {
    return 'auth_sign_up';
  }

  public function callback(): array
  {
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    if ($login) {
      $login = sanitize_user($login);
    } else {
      throw new AjaxException('Not set login', self::ERROR_CODE_NOT_SET_LOGIN);
    }

    // Проверим, что пользователь не существует
    if (username_exists($login)) {
      throw new AjaxException('Login exists', self::ERROR_CODE_LOGIN_EXISTS);
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($email) {
      $email = sanitize_email($email);
    } else {
      throw new AjaxException('Not set valid email', self::ERROR_CODE_NOT_SET_EMAIL);
    }

    // Проверим, что почта не занята
    if (email_exists($email)) {
      throw new AjaxException('Email exists', self::ERROR_CODE_EMAIL_EXISTS);
    }

    // Если разрешена регистрация с паролем, то возьмем пароль из запроса, иначе сгенириуем случайны
    if (static::isSignUpWithPasswordEnabled()) {
      $password = filter_input(INPUT_POST, 'password');

      if (!$password) {
        throw new AjaxException('Not set password', self::ERROR_CODE_NOT_SET_PASSWORD);
      }
    } else {
      $password = wp_generate_password();
    }

    // Подготовим данные для создания нового пользователя
    $data = [
      'user_login' => $login,
      'user_email' => $email,
      'user_pass' => $password,
    ];

    $url = filter_input(INPUT_POST, 'url');
    if ($url) {
      $url = esc_url($url);
      $data['user_url'] = $url;
    }

    $nickname = filter_input(INPUT_POST, 'nickname');
    if ($nickname) {
      $nickname = sanitize_text_field($nickname);
      $data['nickname'] = $nickname;
    }

    $firstName = filter_input(INPUT_POST, 'first_name');
    if ($firstName) {
      $firstName = sanitize_text_field($firstName);
      $data['first_name'] = $firstName;
    }

    $lastName = filter_input(INPUT_POST, 'last_name');
    if ($lastName) {
      $lastName = sanitize_text_field($lastName);
      $data['last_name'] = $lastName;
    }

    $description = filter_input(INPUT_POST, 'description');
    if ($description) {
      $description = sanitize_textarea_field($description);
      $data['description'] = $description;
    }

    // Создадим нового пользователя
    $user = wp_insert_user($data);
    if (is_wp_error($user)) {
      throw new AjaxException($user->get_error_message());
    }

    // Попробуем сразу же войти под новым пользователем
    $remember = (bool) filter_input(INPUT_POST, 'remember_me');

    $credentials = [
      'user_login' => $login,
      'user_password' => $password,
      'remember' => $remember,
    ];
    $user = wp_signon($credentials, true);
    if (is_wp_error($user)) {
      throw new AjaxException($user->get_error_message());
    }

    // Отправим пользователю письмо об успешной авторизации
    $emailProvider = static::getEmailProvider();

    // Отправим письмо администратору сайта
    $mail = $emailProvider->signUpAdminEmail($user);
    wp_mail(
    $mail['to'],
    $mail['subject'],
    $mail['message'],
    $mail['headers']
  );

    // Отправим письмо новому пользователю сайта
    $mail = $emailProvider->signUpUserEmail($user, AuthRestorePasswordAjax::createRestorePasswordUrl($user), AuthSignInAjax::getSignInUrl());
    wp_mail(
    $mail['to'],
    $mail['subject'],
    $mail['message'],
    $mail['headers']
  );

    return [];
  }
}
