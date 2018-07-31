<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

trait WithTitle
{
  /** @var string */
  private $title;

  /**
   * Возвращает название.
   *
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * Устанавливает название.
   *
   * @param string $title
   *
   * @return self
   */
  public function setTitle(string $title): self
  {
    $this->title = $title;

    return $this;
  }
}
