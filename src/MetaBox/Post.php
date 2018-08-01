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
class Post extends AbstractMetaBoxBaseField
{
  use WithPlaceholder;

  /** @var string[]|null */
  private $postType = null;

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
   *
   * @return self
   */
  public function setPostType(?array $postType): self
  {
    $this->postType = $postType;

    return $this;
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
