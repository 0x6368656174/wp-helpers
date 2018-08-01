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
 * Добавляет иконку в класс
 */
trait WithIcon
{
  /** @var string|null */
  private $icon;

  /**
   * Возвращает иконку.
   *
   * @return string|null
   */
  public function getIcon(): ?string
  {
    return $this->icon;
  }

  /**
   * Устанавливает иконку.
   *
   * @param string|null $icon Иконка
   *
   * @return self
   */
  public function setIcon(?string $icon): self
  {
    $this->icon = $icon;

    return $this;
  }
}
