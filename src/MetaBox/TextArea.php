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
 * Многострочное текстовое поле.
 */
class TextArea extends AbstractMetaBoxBaseField
{
  /** @var int */
  private $columns = 60;
  /** @var int */
  private $rows = 4;

  /**
   * Устанавливате количество колонок тектсового поля.
   *
   * Это НЕ количество колонок текста, вверденного в текстовое поле.
   *
   * По-умолчанию, `60`.
   *
   * @param int $columns Количество колонок текстового поля
   *
   * @return self
   */
  public function setColumnsCount(int $columns): self
  {
    $this->columns = $columns;

    return $this;
  }

  /**
   * Устаналвиает количество строк текстового поля.
   *
   * Это НЕ количество строк текста, вверденного в текстовое поле.
   *
   * По-умолчанию, `4`.
   *
   * @param int $rows Количество строк текстового поля
   *
   * @return TextArea
   */
  public function setRowsCount(int $rows): self
  {
    $this->rows = $rows;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'textarea',
    ];

    if (60 !== $this->columns) {
      $result['columns'] = $this->columns;
    }

    if (4 !== $this->rows) {
      $result['rows'] = $this->rows;
    }

    return $result;
  }
}
