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
 * Поле выбора даты
 */
class Date extends AbstractMetaBoxBaseField
{
  private $timestamp = true;

  /**
   * Устанавливает признак того, что значение поля должно храниться в виде UNIX timestamp
   *
   * По-умолчанию, равно true.
   *
   * @param bool $timestamp
   * @return self
   */
  public function setTimestamp(bool $timestamp): self {
    $this->timestamp = $timestamp;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    return [
      'type' => 'date',
      'js_options' => array(
        'dateFormat'      => 'dd.mm.yy',
      ),
      'timestamp' => $this->timestamp,
    ];
  }
}
