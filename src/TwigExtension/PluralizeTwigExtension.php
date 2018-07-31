<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\TwigExtension;

use Exception;
use Twig_Extension;

class PluralizeTwigExtension extends Twig_Extension
{
  /**
   * Returns the name of this extension.
   *
   * @return string
   */
  public function getName()
  {
    return 'pluralize';
  }

  /**
   * Returns the functions this extension adds.
   *
   * @return array
   */
  public function getFunctions()
  {
    return [
      new \Twig_SimpleFunction('pluralize', [$this, 'getPluralizedString']),
    ];
  }

  public function getPluralizedString($count, $one, $several, $many, $none = null)
  {
    if (!is_numeric($count)) {
      throw new Exception('$count must be numeric.');
    }
    // If the option for $none is null, use the option for $many
    if (null === $none) {
      $none = $many;
    }
    // Handle 0
    switch ($count) {
  case 0:
  $string = $none;
  break;
  case 1:
  $string = $one;
  break;
  case 2:
  case 3:
  case 4:
  $string = $several;
  break;
  default:
  $string = $many;
  break;
  }

    return sprintf($string, $count);
  }
}
