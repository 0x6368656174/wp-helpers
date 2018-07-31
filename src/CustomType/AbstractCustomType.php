<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\CustomType;

abstract class AbstractCustomType
{
  abstract public static function getPostType(): string;

  public static function registerPostType(): void
  {
    register_post_type(
  static::getPostType(),
  static::getDefinition()
  );
    flush_rewrite_rules();
  }

  abstract protected static function getDefinition(): array;
}
