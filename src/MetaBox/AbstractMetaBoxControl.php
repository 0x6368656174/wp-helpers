<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

abstract class AbstractMetaBoxControl {
  /**
   * Добавляет к полям кофигурации MetaBox.io.
   *
   * @param string|null $prefix Префикс
   * @param array $fields Текущия поля конфигурации MetaBox.io
   */
  abstract public function addToMetaBoxFields(?string $prefix, array &$fields): void;

  /**
   * Преобразовывает значени из значения MetaBox'a в стандартное
   *
   * @param mixed $value Значение MetaBox'a
   *
   * @return mixed
   */
  public function mapValue($value) {
    return $value;
  }
}
