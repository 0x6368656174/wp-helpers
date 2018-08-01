<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;


use function array_merge;

/**
 * Создает группу полей
 *
 * Позволяет сгруппировать поля в одну группу. От Section отличается тем, что группу можно сделать клонируемой при
 * помощи метода `setCloneable(true)`. Для работы необходимо, чтоб был установлен и включен плагин Meta Box Group.
 *
 * Для установки плагина при помощи Composer:
 * ```
 * $ composer require meta-box/meta-box-group:dev-master
 * ```
 *
 * @see https://docs.metabox.io/extensions/meta-box-group/
 */
class Group extends AbstractMetaBoxField {
  /** @var AbstractMetaBoxBaseField[] */
  private $fields = [];

  /**
   * Group constructor.
   *
   * @param string $id ID
   * @param string $name Название
   * @param AbstractMetaBoxBaseField[] $fields Поля
   */
  public function __construct(string $id, string $name, array $fields = [] ) {
    parent::__construct($id, $name);
    $this->fields = $fields;
  }

  /**
   * Возвращает поля.
   *
   * @return AbstractMetaBoxBaseField[]
   */
  public function getFields(): array
  {
    return $this->fields;
  }

  /**
   * Добавляет поле.
   *
   * @param AbstractMetaBoxBaseField $field Поле
   *
   * @return self
   */
  public function addField(AbstractMetaBoxBaseField $field): self
  {
    $this->fields[] = $field;

    return $this;
  }

  /**
   * Устанавливает поля.
   *
   * @param AbstractMetaBoxBaseField[] $fields
   *
   * @return self
   */
  public function setFields(array $fields): self
  {
    $this->fields = $fields;

    return $this;
  }

  protected function getMetaBoxConfig(string $prefix): array {
    $fields = [];

    foreach ($this->getFields() as $childField) {
      $localFields = [];
      $childField->addToMetaBoxFields($prefix, $localFields);

      $fields = array_merge($fields, $localFields);
    }

    return [
      'type' => 'group',
      'fields' => $fields,
    ];
  }
}
