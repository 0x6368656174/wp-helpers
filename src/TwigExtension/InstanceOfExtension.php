<?php

/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\TwigExtension;

use ReflectionClass;
use Twig_Extension;
use function is_array;

class InstanceOfExtension extends Twig_Extension
{
  public function getTests()
  {
    return [
      new \Twig_SimpleTest('instanceof', [$this, 'isInstanceOf']),
    ];
  }

  public function isInstanceOf($object, $class)
  {
    if (!$object) {
      return false;
    }

    if (is_array($object)) {
      return false;
    }

    $reflectionClass = new ReflectionClass($class);

    return $reflectionClass->isInstance($object);
  }
}
