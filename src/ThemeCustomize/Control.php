<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\ThemeCustomize;

class Control
{
  /** @var string */
  private $label;
  /** @var string */
  private $type;
  private $default;
  /** @var string */
  private $description;

  /**
   * ThemeCustomizeControl конструктор.
   *
   * Допустимые значения $type: 'text', 'checkbox', 'radio', 'select', 'textarea', 'dropdown-pages', 'email',
   * 'url', 'number', 'hidden', и 'date'
   *
   * @param string $label
   * @param string $type
   * @param $default
   */
  public function __construct(string $label, string $type, $default = false)
  {
    $this->label = $label;
    $this->type = $type;
    $this->default = $default;
  }

  public function getLabel(): string
  {
    return $this->label;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function getDefault()
  {
    return $this->default;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description): void
  {
    $this->description = $description;
  }
}
