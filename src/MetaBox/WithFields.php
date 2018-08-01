<?php
/**
 * Created by PhpStorm.
 * User: pashok
 * Date: 01.08.18
 * Time: 9:35
 */

namespace ItQuasar\WpHelpers\MetaBox;

/**
 * Добавляет в класс поля
 */
trait WithFields {
  /** @var AbstractMetaBoxField[] */
  private $fields = [];

  /**
   * Возвращает поля.
   *
   * @return AbstractMetaBoxField[]
   */
  public function getFields(): array
  {
    return $this->fields;
  }

  /**
   * Добавляет поле.
   *
   * @param AbstractMetaBoxField $field Поле
   *
   * @return self
   */
  public function addField(AbstractMetaBoxField $field): self
  {
    $this->fields[] = $field;

    return $this;
  }

  /**
   * Устанавливает поля.
   *
   * @param AbstractMetaBoxField[] $fields
   *
   * @return self
   */
  public function setFields(array $fields): self
  {
    $this->fields = $fields;

    return $this;
  }
}
