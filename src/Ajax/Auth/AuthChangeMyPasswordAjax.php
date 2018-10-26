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
use const INPUT_POST;
use function filter_input;
use function is_user_logged_in;
use function wp_get_current_user;
use function wp_set_password;

/**
 * Изменяет текущий пароль авторизованного пользователя.
 */
class AuthChangeMyPasswordAjax extends AbstractAjaxHandler
{
  public const ERROR_CODE_NOT_AUTHORIZED = 1;
  public const ERROR_CODE_NOT_SET_PASSWORD = 2;

  public function actionName(): string
  {
    return 'auth_change_my_password';
  }

  public function callback(): array
  {
    if (!is_user_logged_in()) {
      throw new AjaxException('Not authorized', self::ERROR_CODE_NOT_AUTHORIZED);
    }

    $password = filter_input(INPUT_POST, 'password');
    if (!$password) {
      throw new AjaxException('Not set password', self::ERROR_CODE_NOT_SET_PASSWORD);
    }

    $user = wp_get_current_user();

    wp_set_password($password, $user->ID);

    return [];
  }
}
