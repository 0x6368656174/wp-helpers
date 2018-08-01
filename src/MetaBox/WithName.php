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
 * Добавляет имя в класс
 */
trait WithName
{
  /** @var string|null */
  private $name;

  /**
   * Возвращает имя.
   *
   * @return string|null
   */
  public function getName(): ?string
  {
    return $this->name;
  }

  /**
   * Устанавливает имя.
   *
   * @param string|null $name Имя
   *
   * @return self
   */
  public function setName(?string $name): self
  {
    $this->name = $name;

    return $this;
  }
}
