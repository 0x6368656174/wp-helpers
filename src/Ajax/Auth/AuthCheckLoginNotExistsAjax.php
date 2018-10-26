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
use function sanitize_user;
use function username_exists;

/**
 * Проверяет, что пользователь с данным логином не существует в системе.
 */
class AuthCheckLoginNotExistsAjax extends AbstractAjaxHandler
{
  public const ERROR_CODE_NOT_SET_LOGIN = 1;

  public function actionName(): string
  {
    return 'auth_check_login_not_exists';
  }

  public function callback(): array
  {
    $login = filter_input(INPUT_POST, 'login');
    if ($login) {
      $login = sanitize_user($login);
    } else {
      throw new AjaxException('Not set login', self::ERROR_CODE_NOT_SET_LOGIN);
    }

    return [
      'exists' => !username_exists(sanitize_user($login)),
    ];
  }
}
