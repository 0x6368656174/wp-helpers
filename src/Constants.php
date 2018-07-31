<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers;

class Constants
{
  public const WP_PAGE_ON_FRONT_ID = 'page_on_front';

  public static function getFrontPageId(): int
  {
    return (int) get_option('page_on_front');
  }
}
