<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\TwigExtension;

use Twig_Environment;

class TwigExtension
{
  public static function init(array $extensions)
  {
    add_filter('timber/twig', function (Twig_Environment $twig) use ($extensions) {
      foreach ($extensions as $extension) {
        $twig->addExtension(new $extension());
      }

      return $twig;
    });
  }
}
