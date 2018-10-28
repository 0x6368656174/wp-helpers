<?php

declare(strict_types=1);
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace ItQuasar\WpHelpers\Ajax\Auth;

use WP_User;

/**
 * Провайдер email'ов для регистрации пользователей на сайте.
 */
class AuthSignUpEmailProvider
{
  /**
   * Возвращает email для администратора сайта, в случае регистрации нового пользователя.
   *
   * @param WP_User $user Новый пользователь
   *
   * @return array Email: [to: string, subject: string, message: string, headers: string]
   */
  public function signUpAdminEmail(WP_user $user): array
  {
    $blogName = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $switchedLocale = switch_to_locale(get_locale());

    $message = sprintf(__('New user registration on your site %s:'), $blogName)."\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user->user_login)."\r\n\r\n";
    $message .= sprintf(__('Email: %s'), $user->user_email)."\r\n";

    $email = [
      'to' => get_option('admin_email'),
      // translators: Password change notification email subject. %s: Site title
      'subject' => sprintf(__('[%s] New User Registration'), $blogName),
      'message' => $message,
      'headers' => '',
    ];

    if ($switchedLocale) {
      restore_previous_locale();
    }

    return $email;
  }

  /**
   * Возвращает email для нового пользователя сайта, в случае его регистрации на сайте.
   *
   * @param WP_User $user             Новый пользователь
   * @param string  $resetPasswordUrl URL для сброса (задания нового) пароля
   * @param string  $singInUrl         URL для входа на сайт
   *
   * @return array Email: [to: string, subject: string, message: string, headers: string]
   */
  public function signUpUserEmail(WP_User $user, string $resetPasswordUrl, string $singInUrl): array
  {
    $blogName = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $switchedLocale = switch_to_locale(get_user_locale($user));

    $message = sprintf(__('Username: %s'), $user->user_login)."\r\n\r\n";
    $message .= __('To set your password, visit the following address:')."\r\n\r\n";
    $message .= "<$resetPasswordUrl>\r\n\r\n";
    $message .= "$singInUrl\r\n";

    $email = [
      'to' => $user->user_email,
      // translators: Password change notification email subject. %s: Site title
      'subject' => sprintf(__('[%s] Your username and password info'), $blogName),
      'message' => $message,
      'headers' => '',
    ];

    if ($switchedLocale) {
      restore_previous_locale();
    }

    return $email;
  }
}
