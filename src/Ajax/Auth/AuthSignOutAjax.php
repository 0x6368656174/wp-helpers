<?php
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace ItQuasar\WpHelpers\Ajax\Auth;

use ItQuasar\WpHelpers\Ajax\AbstractAjaxHandler;
use function wp_logout;

/**
 * Выход из системы.
 */
class AuthSignOutAjax extends AbstractAjaxHandler
{
  public function actionName(): string
  {
    return 'auth_sign_out';
  }

  public function callback(): array
  {
    wp_logout();

    return [];
  }
}
