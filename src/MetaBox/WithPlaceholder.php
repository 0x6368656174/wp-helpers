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
 * Добавляет текст замещения в класс
 */
trait WithPlaceholder
{
  /** @var string */
  private $placeholder;

  /**
   * Возвращает текст замещения.
   *
   * @return string
   */
  public function getPlaceholder(): string
  {
    return $this->placeholder;
  }

  /**
   * Устанавливает текст замещения.
   *
   * @param string $placeholder Текст замещения
   *
   * @return self
   */
  public function setPlaceholder(string $placeholder): self
  {
    $this->placeholder = $placeholder;

    return $this;
  }
}
