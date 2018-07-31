<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class CoreHelpers
{
  /**
   * Возвращает все файлы в коталоге $dir и его подкаталогах (рекурсивно).
   *
   * @param string $dir Путь к каталогу
   *
   * @return SplFileInfo[]
   */
  public static function getRecursiveDirFiles(string $dir): array
  {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    $result = [];

    /** @var DirectoryIterator $file */
    foreach ($rii as $file) {
      if ($file->isDir()) {
        continue;
      }

      $result[] = $file;
    }

    return $result;
  }

  /**
   * Возвращает полное имя класса (вклчая пространство имен), описанного в фале $file.
   *
   * @param string $file Путь к файлу
   *
   * @return string
   */
  public static function getFileClass(string $file): string
  {
    // Взято с https://stackoverflow.com/questions/7153000/get-class-name-from-file
    $fp = fopen($file, 'r');
    $class = '';
    $namespace = '';
    $buffer = '';
    $i = 0;
    while (!$class) {
      if (feof($fp)) {
        break;
      }

      $buffer .= fread($fp, 512);
      $tokens = token_get_all($buffer);

      if (false === strpos($buffer, '{')) {
        continue;
      }

      for (; $i < count($tokens); ++$i) {
        if (T_NAMESPACE === $tokens[$i][0]) {
          for ($j = $i + 1; $j < count($tokens); ++$j) {
            if (T_STRING === $tokens[$j][0]) {
              $namespace .= '\\'.$tokens[$j][1];
            } elseif ('{' === $tokens[$j] || ';' === $tokens[$j]) {
              break;
            }
          }
        }

        if (T_CLASS === $tokens[$i][0]) {
          for ($j = $i + 1; $j < count($tokens); ++$j) {
            if ('{' === $tokens[$j]) {
              $class = $tokens[$i + 2][1];
            }
          }
        }
      }
    }

    return $namespace.'\\'.$class;
  }
}
