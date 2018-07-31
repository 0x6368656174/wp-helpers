<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

/**
 * Поле выбора цвета.
 */
class Color extends AbstractMetaBoxField
{
  /**
   * Text constructor.
   *
   * @param string $id   Уникальный ID
   * @param string $name Имя
   */
  public function __construct(string $id, string $name)
  {
    $this->setName($name);
    $this->setId($id);
  }

  protected function getMetaBoxConfig(): array
  {
    return [
      'type' => 'color',
    ];
  }
}
