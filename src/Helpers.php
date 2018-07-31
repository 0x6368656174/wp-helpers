<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers;

use Timber\Image;
use function array_filter;
use function is_array;
use function is_numeric;
use function is_string;

class Helpers
{
  /**
   * @param mixed $ids
   *
   * @return Image[]|null
   */
  public static function mapIdToImages($ids)
  {
    if (!is_array($ids)) {
      return null;
    }

    // Пропустим все, что не строка или число
    $filteredArray = array_filter($ids, function ($id) {
      return is_numeric($id) || is_string($id);
    });

    // Вернем изображения
    return array_map(function ($id) {
      return new Image($id);
    }, $filteredArray);
  }
}
