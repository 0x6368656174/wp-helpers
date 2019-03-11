<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

use function array_merge;

abstract class AbstractMetaBoxField extends AbstractMetaBoxControl
{
  use WithId, WithName;

  /** @var bool */
  private $cloneable = false;

  /**
   * AbstractMetaBoxField constructor.
   *
   * @param string $id ID
   * @param string|null $name Название
   */
  public function __construct(string $id, ?string $name = null) {
    $this->setId($id);
    $this->setName($name);
  }

  /**
   * Возрващает признак того, что поле можно клонировать.
   *
   * По-умолчанию, возвращает false
   *
   * @return bool
   */
  public function isCloneable(): bool
  {
    return $this->cloneable;
  }

  /**
   * Устанавливает признак того, что поле можно клонировать.
   *
   * @param bool $cloneable
   *
   * @return self
   */
  public function setCloneable(bool $cloneable): self
  {
    $this->cloneable = $cloneable;

    return $this;
  }

  public function addToMetaBoxFields(?string $prefix, array &$fields): void
  {
    $commonConfig = $this->getCommonMetaBoxConfig($prefix);
    $config = $this->getMetaBoxConfig($prefix);
    $fields[] = array_merge($commonConfig, $config);
  }

  abstract protected function getMetaBoxConfig(): array;

  private function getCommonMetaBoxConfig(?string $prefix): array
  {
    $result = [
      'id' => $prefix ? $prefix.'__'.$this->getId() : $this->getId(),
    ];

    if ($this->getName()) {
      $result['name'] = $this->getName();
    }

    if ($this->isCloneable()) {
      $result['clone'] = $this->isCloneable();
    }

    return $result;
  }
}
