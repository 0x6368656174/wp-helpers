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
 * Поле ввода числа
 */
class Number extends AbstractMetaBoxBaseField
{
  private $min = null;
  private $max = null;
  private $step = 1;

  /**
   * Устанавливает минимальное значение
   *
   * По-умолчанию, `null`.
   *
   * @param int|null $min Значение
   *
   * @return self
   */
  public function setMin(?int $min): self {
    $this->min = $min;

    return $this;
  }

  /**
   * Устанавливает максимальное значение
   *
   * По-умолчанию, `null`.
   *
   * @param int|null $max Значение
   *
   * @return self
   */
  public function setMax(?int $max): self {
    $this->max = $max;

    return $this;
  }

  /**
   * Устанавлиает шаг значения
   *
   * Если установить `null`, то можно будет ввести любое число (с плавующей точкой).
   *
   * По-умолчанию, `1`.
   *
   * @param int|null $step Шаг
   *
   * @return self
   */
  public function setStep(?int $step): self {
    $this->step = $step;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'number',
    ];

    if (null !== $this->min) {
      $result['min'] = $this->min;
    }

    if (null !== $this->max) {
      $result['max'] = $this->max;
    }

    if (1 !== $this->step) {
      if (null === $this->step) {
        $result['step'] = 'any';
      } else {
        $result['step'] = $this->step;
      }
    }

    return $result;
  }
}
