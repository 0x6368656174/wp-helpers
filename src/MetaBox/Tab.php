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
 * Закладка
 *
 * Позволяет сгруппировать поля по закладкам. Для работы необходимо, чтоб был установлен и включен плагин
 * Meta Box Tabs.
 *
 * Для установки плагина при помощи Composer:
 * ```
 * $ composer require meta-box/meta-box-tabs:dev-master
 * ```
 *
 * @see https://docs.metabox.io/extensions/meta-box-tabs/
 */
class Tab extends AbstractMetaBoxControl
{
  use WithName, WithIcon, WithFields;

  /**
   * Tab constructor.
   *
   * @param string $name  Название
   * @param AbstractMetaBoxField[] $fields Поля
   */
  public function __construct(string $name, array $fields = [])
  {
    $this->name = $name;
    $this->fields = $fields;
  }

  public function addToMetaBoxFields(?string $prefix, array &$fields): void {
    foreach ($this->getFields() as $childField) {
      $childField->addToMetaBoxFields($prefix, $fields);
    }
  }
}
