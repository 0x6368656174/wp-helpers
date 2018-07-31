<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

trait WithDescription
{
  /** @var string|null */
  private $description;

  /**
   * Возвращает описание.
   *
   * @return string|null
   */
  public function getDescription(): ?string
  {
    return $this->description;
  }

  /**
   * Устанавливает описание.
   *
   * @param string|null $description
   *
   * @return self
   */
  public function setDescription(?string $description): self
  {
    $this->description = $description;

    return $this;
  }
}
