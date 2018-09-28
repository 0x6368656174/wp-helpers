<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DedMorozTheme\MetaBox;

use ItQuasar\WpHelpers\MetaBox\AbstractMetaBoxBaseField;

/**
 * Выпадающее меню
 */
class Select extends AbstractMetaBoxBaseField
{
  private $options;
  private $multiple = false;
  private $displayAllNone = true;
  private $flatten = false;

  public function __construct(string $id, array $options, ?string $name = null)
  {
    parent::__construct($id, $name);

    $this->options = $options;
  }

  /**
   * Устанавливает опции меню.
   *
   * @param array $options Массив с опциями: значение => описание значения
   *
   * @return self
   */
  public function setOptions(array $options): self
  {
    $this->options = $options;

    return $this;
  }

  /**
   * Устанавлиает возможность выбрать несколько значений.
   *
   * По-умолчанию, `false`.
   *
   * @param bool $multiple Признак того, что можно выбрать несколько значений
   * @param bool $displayAllNone Признак того, что будет отображаться поле "Выбрать Все/Ничего"
   *
   * @return self
   */
  public function setMultiple(bool $multiple, bool $displayAllNone = true): self
  {
    $this->multiple = $multiple;
    $this->displayAllNone = $displayAllNone;

    return $this;
  }

  /**
   * Устанавлиает "плоское" отображение подъэлементов, т.е. они будут отображаться без отступов
   *
   * По-умолчанию, `false`.
   *
   * @param bool $flatten Признак того, что подъэлементы должны быть без отступа
   *
   * @return self
   */
  public function setFlatten(bool $flatten): self
  {
    $this->flatten = $flatten;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'select_advanced',
      'options' => $this->options,
    ];

    if ($this->multiple) {
      $result['multiple'] = true;
    }

    if ($this->flatten) {
      $result['flatten'] = true;
    }

    if (!$this->displayAllNone) {
      $result['select_all_none'] = false;
    }

    return $result;
  }
}
