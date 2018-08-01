<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

/**
 * Поле для oEmded данных, например видео на YouTube
 */
class OEmbed extends AbstractMetaBoxBaseField
{
  protected function getMetaBoxBaseConfig(): array
  {
    return [
      'type' => 'oembed',
    ];
  }
}

