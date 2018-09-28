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
 * Поле выбора времени
 */
class Time extends AbstractMetaBoxBaseField
{
  /** @var int  */
  private $size = 10;
  /** @var bool  */
  private $inline = false;

  /**
   * Устанавлиает размер поля.
   *
   * По-умолчанию, `10'.
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
   * Устанавлиает признак того, что поле должно быть "встроенным", т.е без выпадающего меню.
   *
   * По-умолчанию, `false`.
   *
   * @param bool $inline Признак того, что поле должно быть "встроенным"
   *
   * @return self
   */
  public function setInline(bool $inline): self {
    $this->inline = $inline;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'time',
    ];

    if (10 !== $this->size) {
      $result['size'] = $this->size;
    }

    if ($this->inline) {
      $result['inline'] = true;
    }

    return $result;
  }
}
