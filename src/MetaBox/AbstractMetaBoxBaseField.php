<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

abstract class AbstractMetaBoxBaseField extends AbstractMetaBoxField
{
  abstract protected function getMetaBoxBaseConfig(): array;

  protected function getMetaBoxConfig(string $prefix): array {
    return $this->getMetaBoxBaseConfig();
  }
}
