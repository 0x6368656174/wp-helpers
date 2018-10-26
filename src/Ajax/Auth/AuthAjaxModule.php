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

class AuthAjaxModule extends AbstractAjaxHandler
{
  public function actionName(): string
  {
    return 'get_auth_ajax_version';
  }

  public function callback(): array
  {
    return [
      'version' => '1.0.0',
    ];
  }

  public function init()
  {
    parent::init();

    // Зарегистрируем оставшиеся хендлеры
    /** @var AbstractAjaxHandler[] $handlers */
    $handlers = [
      new AuthCheckLoginNotExistsAjax(),
      new AuthCheckEmailNotExistsAjax(),
      new AuthSignInAjax(),
      new AuthSignOutAjax(),
      new AuthSignUpAjax(),
      new AuthRestorePasswordAjax(),
      new AuthChangeMyPasswordAjax(),
      new AuthChangeMyAccountDataAjax(),
    ];

    foreach ($handlers as $ajax) {
      $ajax->init();
    }
  }
}
