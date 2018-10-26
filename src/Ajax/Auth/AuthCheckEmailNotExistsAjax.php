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
use function email_exists;
use function sanitize_email;

/**
 * Проверяет, что пользователь с данным email не существует в системе.
 */
class AuthCheckEmailNotExistsAjax extends AbstractAjaxHandler
{
  public const ERROR_CODE_NOT_SET_EMAIL = 1;

  public function actionName(): string
  {
    return 'auth_check_email_not_exists';
  }

  public function callback(): array
  {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($email) {
      $email = sanitize_email($email);
    } else {
      throw new AjaxException('Not set email', self::ERROR_CODE_NOT_SET_EMAIL);
    }

    return [
      'exists' => !email_exists($email),
    ];
  }
}
