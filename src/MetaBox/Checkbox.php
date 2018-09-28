<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DedMorozTheme\MetaBox;

use ItQuasar\WpHelpers\MetaBox\AbstractMetaBoxBaseField;

/**
 * Чекбокс
 */
class Checkbox extends AbstractMetaBoxBaseField
{
  protected function getMetaBoxConfig(): array
  {
    return [
      'type' => 'checkbox',
    ];
  }
}
