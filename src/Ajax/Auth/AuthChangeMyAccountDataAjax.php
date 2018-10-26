<?php
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Ajax\Auth;

use ItQuasar\WpHelpers\Ajax\AbstractAjaxHandler;
use ItQuasar\WpHelpers\Ajax\AjaxException;
use const FILTER_SANITIZE_STRING;
use const INPUT_POST;
use function esc_url;
use function filter_input;
use function is_user_logged_in;
use function is_wp_error;
use function sanitize_text_field;
use function sanitize_textarea_field;
use function wp_get_current_user;
use function wp_update_user;

/**
 * Изменяет данные текущего авторизованного пользователя.
 */
class AuthChangeMyAccountDataAjax extends AbstractAjaxHandler
{
  public const ERROR_CODE_NOT_AUTHORIZED = 1;

  public function actionName(): string
  {
    return 'auth_change_my_account_data';
  }

  public function callback(): array
  {
    if (!is_user_logged_in()) {
      throw new AjaxException('Not authorized', self::ERROR_CODE_NOT_AUTHORIZED);
    }

    $user = wp_get_current_user();

    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    if ($firstName) {
      $firstName = sanitize_text_field($firstName);
      $user->first_name = $firstName;
    }

    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    if ($lastName) {
      $lastName = sanitize_text_field($lastName);
      $user->last_name = $lastName;
    }

    $nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING);
    if ($nickname) {
      $nickname = sanitize_text_field($nickname);
      $user->nickname = $nickname;
    }

    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    if ($description) {
      $description = sanitize_textarea_field($description);
      $user->description = $description;
    }

    $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
    if ($url) {
      $url = esc_url($url);
      $user->user_url = $url;
    }

    $result = wp_update_user($user);
    if (is_wp_error($result)) {
      throw new AjaxException($result->get_error_message());
    }

    return [];
  }
}
