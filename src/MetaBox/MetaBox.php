<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

use ItQuasar\WpHelpers\CoreHelpers;
use ReflectionClass;
use SplFileInfo;
use function array_filter;

class MetaBox
{
  public static function initFromFolder(string $folder)
  {
    // Добавим перевод МетоБокса
    Translate::addFilters();

    $files = CoreHelpers::getRecursiveDirFiles($folder);

    // Пропустим, все, что не PHP
    $files = array_filter($files, function (SplFileInfo $file) {
      return 'php' === $file->getExtension();
    });

    $metaBoxes = [];

    foreach ($files as $file) {
      // Импортируем файл
      require_once $file;

      // Найдем имя класса, указанное в файле
      $fullClass = CoreHelpers::getFileClass($file->getPathname());

      $reflectionClass = new ReflectionClass($fullClass);
      // Если класс, является потомком AbstractMetaBox
      if ($reflectionClass->isSubclassOf(AbstractMetaBox::class) && $reflectionClass->isInstantiable()) {
        // То выполним метод init()
        $instance = $reflectionClass->newInstanceWithoutConstructor();
        $metaBoxes[] = $instance->toMetaBoxArray();
      }
    }

    add_filter('rwmb_meta_boxes', function () use ($metaBoxes) {
      return $metaBoxes;
    });
  }
}
