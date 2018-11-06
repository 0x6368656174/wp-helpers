<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\ThemeCustomize;

use ItQuasar\WpHelpers\CoreHelpers;
use ReflectionClass;
use SplFileInfo;
use WP_Customize_Manager;
use function array_filter;
use function count;

class ThemeCustomize
{
  public static function initFromFolder(string $folder)
  {
    $files = CoreHelpers::getRecursiveDirFiles($folder);

    // Пропустим, все, что не PHP
    $files = array_filter($files, function (SplFileInfo $file) {
      return 'php' === $file->getExtension();
    });

    $sections = [];

    foreach ($files as $file) {
      // Импортируем файл
      require_once $file;

      // Найдем имя класса, указанное в файле
      $fullClass = CoreHelpers::getFileClass($file->getPathname());

      $reflectionClass = new ReflectionClass($fullClass);
      // Если класс, является потомком AbstractSection
      if ($reflectionClass->isSubclassOf(AbstractSection::class) && $reflectionClass->isInstantiable()) {
        $sections[] = $reflectionClass->newInstanceWithoutConstructor();
      }
    }

    if (count($sections) > 0) {
      add_action('customize_register', function (WP_Customize_Manager $wpCustomize) use ($sections) {
        foreach ($sections as $section) {
          $section->addToCustomize($wpCustomize);
        }
      });
    }
  }
}
