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
 * Поле выбора поста.
 */
class Post extends AbstractMetaBoxField
{
  use WithPlaceholder;

  /** @var string[]|null */
  private $postType = null;

  /**
   * Text constructor.
   *
   * @param string $id   Уникальный ID
   * @param string $name Имя
   */
  public function __construct(string $id, ?string $name = null)
  {
    $this->setName($name);
    $this->setId($id);
  }

  /**
   * Возвращает список типов постов, которые должны отображаться в поле.
   *
   * @return string[]|null
   */
  public function getPostType(): ?array
  {
    return $this->postType;
  }

  /**
   * Устанавлиает список типов постов, которые должны отображаться в поле.
   *
   * @param string[]|null $postType
   */
  public function setPostType(?array $postType): void
  {
    $this->postType = $postType;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'post',
    ];

    if ($this->postType) {
      $result['post_type'] = $this->postType;
    }

    return $result;
  }
}
