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
 * Несколько картинок.
 */
class MultipleImages extends AbstractMetaBoxBaseField
{
  /** @var int */
  private $maxCount = null;
  /** @var bool */
  private $forceDelete = false;

  /**
   * @param string   $id       Уникальный ID
   * @param string   $name     Имя
   * @param int|null $maxCount Максимальное колличество изображений
   */
  public function __construct(string $id, string $name, ?int $maxCount = null)
  {
    parent::__construct($id, $name);

    $this->setMaxCount($maxCount);
  }

  /**
   * Возвращает максимальное количество изображений.
   *
   * @return int|null
   */
  public function getMaxCount(): ?int
  {
    return $this->maxCount;
  }

  /**
   * Устанавливает максимальное количество изображений.
   *
   * @param int|null $maxCount
   *
   * @return self
   */
  public function setMaxCount(?int $maxCount): self
  {
    $this->maxCount = $maxCount;

    return $this;
  }

  /**
   * Устанавливает признак того, что изображение должно принудительно удаляться из библиотеки Медиа, если
   * его удалят из MetaBox'a.
   *
   * Будтье внимательны, т.к. изображение может использоваться в каком-нибудь другом месте.
   *
   * По-умолчанию, `false`.
   *
   * @param bool $forceDelete
   *
   * @return self
   */
  public function setForceDelete(bool $forceDelete): self
  {
    $this->forceDelete = $forceDelete;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'image_advanced',
    ];

    if (null !== $this->maxCount) {
      $result['max_file_uploads'] = $this->maxCount;
    }

    if ($this->forceDelete) {
      $result['force_delete'] = true;
    }

    return $result;
  }
}
