<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

trait WithId
{
  /** @var string */
  private $id;

  /**
   * Возвращает ID.
   *
   * @return string
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * Устанавливает ID.
   *
   * @param string $id
   *
   * @return self
   */
  public function setId(string $id): self
  {
    $this->id = $id;

    return $this;
  }
}
