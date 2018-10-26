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
use const FILTER_SANITIZE_STRING;

/**
 * Вход в систему.
 */
class AuthSignInAjax extends AbstractAjaxHandler
{
  public const ERROR_CODE_NOT_SET_LOGIN = 1;
  public const ERROR_CODE_NOT_SET_PASSWORD = 2;
  /** @var string */
  private static $signInUrl;

  /**
   * Возвращает URL, используемый для авторизации пользователя на сайте.
   *
   * @return string
   */
  public static function getSignInUrl(): string
  {
    if (!static::$signInUrl) {
      static::$signInUrl = wp_login_url();
    }

    return static::$signInUrl;
  }

  /**
   * Устанавливает URL, используемый для авторизации пользователя на сайте.
   *
   * @param string $signInUrl URL, используемый для авторизации пользователя на сайте
   */
  public static function setSignInUrl(string $signInUrl): void
  {
    static::$signInUrl = $signInUrl;
  }

  public function actionName(): string
  {
    return 'auth_sign_in';
  }

  public function callback(): array
  {
    $loginOrEmail = filter_input(INPUT_POST, 'login_or_email', FILTER_SANITIZE_STRING);
    if (!$loginOrEmail) {
      throw new AjaxException('Not set login', self::ERROR_CODE_NOT_SET_LOGIN);
    }

    $password = filter_input(INPUT_POST, 'password');
    if (!$password) {
      throw new AjaxException('Not set password', self::ERROR_CODE_NOT_SET_PASSWORD);
    }

    $remember = (bool) filter_input(INPUT_POST, 'remember_me');

    $credentials = [
      'user_login' => $loginOrEmail,
      'user_password' => $password,
      'remember' => $remember,
    ];

    $user = wp_signon($credentials, true);

    if (is_wp_error($user)) {
      throw new AjaxException($user->get_error_message());
    }

    return [];
  }
}
