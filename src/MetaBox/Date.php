<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

use DateTime;

/**
 * Поле выбора даты
 */
class Date extends AbstractMetaBoxBaseField
{
  /** @var bool  */
  private $timestamp = true;
  /** @var bool  */
  private $inline = false;
  /** @var int  */
  private $size = 30;

  /**
   * Устанавливает признак того, что значение поля должно храниться в виде UNIX timestamp
   *
   * По-умолчанию, равно true.
   *
   * @param bool $timestamp Признак того, что значени поля должно храниться в виде UNIX timestamp
   *
   * @return self
   */
  public function setTimestamp(bool $timestamp): self {
    $this->timestamp = $timestamp;

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

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'date',
      'js_options' => [
        'dateFormat'      => 'dd.mm.yy',
      ],
    ];

    if ($this->timestamp) {
      $result['timestamp'] = true;
    }

    if (30 !== $this->size) {
      $result['size'] = $this->size;
    }

    if ($this->inline) {
      $result['inline'] = true;
    }

    return $result;
  }

  /**
   * Если мы храним в поле UNIX timestamp, то преобразуем его в DateTime
   *
   * @param mixed $value
   *
   * @return DateTime
   */
  public function mapValue( $value ): DateTime {
    if ($this->timestamp) {
      $result = new DateTime();
      $result->setTimestamp((int) $value);
      return $result;
    }

    return parent::mapValue($value);
  }
}
