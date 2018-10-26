<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Ajax;

use ItQuasar\WpHelpers\CoreHelpers;
use ReflectionClass;
use SplFileInfo;
use function array_filter;

class Ajax
{
  public static function initFromFolder(string $folder)
  {
    $files = CoreHelpers::getRecursiveDirFiles($folder);

    // Пропустим, все, что не PHP
    $files = array_filter($files, function (SplFileInfo $file) {
      return 'php' === $file->getExtension();
    });

    foreach ($files as $file) {
      // Импортируем файл
      require_once $file;

      // Найдем имя класса, указанное в файле
      $fullClass = CoreHelpers::getFileClass($file->getPathname());

      $reflectionClass = new ReflectionClass($fullClass);
      // Если класс, является потомком AbstractMetaBox
      if ($reflectionClass->isSubclassOf(AbstractAjaxHandler::class)) {
        // То выполним метод init()
        $instance = $reflectionClass->newInstanceWithoutConstructor();
        $instance->init();
      }
    }
  }

  /**
   * @param AbstractAjaxHandler[] $handlers
   */
  public static function init(array $handlers): void
  {
    foreach ($handlers as $handler) {
      $handler->init();
    }
  }
}
