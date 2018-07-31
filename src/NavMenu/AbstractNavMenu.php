<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\NavMenu;

use Timber\Menu;

abstract class AbstractNavMenu
{
  abstract public static function location(): string;

  abstract public static function description(): string;

  public static function registerMenu(): void
  {
    register_nav_menu(static::location(), static::description());
  }

  public static function timberMenu(): Menu
  {
    return new Menu(static::location());
  }
}
