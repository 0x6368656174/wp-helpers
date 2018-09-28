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
 * Обычное текстовое поле.
 */
class Text extends AbstractMetaBoxBaseField
{
  /** @var int  */
  private $size = 30;
  /** @var string[] */
  private $options = [];

  /**
   * Устанавлиает размер поля.
   *
   * По-умолчанию, `30'.
   *
   * @param int $size Размер поля
   *
   * @return self
   */
  public function setSize(int $size): self
  {
    $this->size = $size;

    return $this;
  }

  /**
   * Устанавливает варианты текстового поля
   *
   * @param string[] $options Варианты
   *
   * @return self
   */
  public function setOptions(array $options): self
  {
    $this->options = $options;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'text',
    ];

    if (30 !== $this->size) {
      $result['size'] = $this->size;
    }

    if (count($this->options) > 0) {
      $result['datalist'] = [
        'options' => $this->options,
      ];
    }

    return $result;
  }
}
