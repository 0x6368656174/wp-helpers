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
  private $clone = false;

  /**
   * Возрващает признак того, что поле можно клонировать.
   *
   * По-умолчанию, возвращает false
   *
   * @return bool
   */
  public function isClone(): bool
  {
    return $this->clone;
  }

  /**
   * Устанавливает признак того, что поле можно клонировать.
   *
   * @param bool $clone
   */
  public function setClone(bool $clone): void
  {
    $this->clone = $clone;
  }

  public function addToMetaBoxFields(string $prefix, array &$fields): void
  {
    $commonConfig = $this->getCommonMetaBoxConfig($prefix);
    $config = $this->getMetaBoxConfig();
    $fields[] = array_merge($commonConfig, $config);
  }

  abstract protected function getMetaBoxConfig(): array;

  private function getCommonMetaBoxConfig(string $prefix): array
  {
    $result = [
      'id' => $prefix.'__'.$this->getId(),
    ];

    if ($this->getName()) {
      $result['name'] = $this->getName();
    }

    if ($this->isClone()) {
      $result['clone'] = $this->isClone();
    }

    return $result;
  }
}
