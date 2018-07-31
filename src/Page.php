<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers;

use ReflectionClass;
use Timber\Timber;

class Page
{
  public static function render(): string
  {
    $context = static::getContext();

    $file = static::getTemplateName();

    return Timber::render($file, $context);
  }

  protected static function getContext(): array
  {
    return Timber::get_context();
  }

  protected static function getTemplateName(): string
  {
    $rc = new ReflectionClass(static::class);
    $file = basename($rc->getFileName(), '.php');
    $file = strtolower($file);

    return "pages/p-$file/p-$file.twig";
  }
}
