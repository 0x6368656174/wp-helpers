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

  /** @var string В виде выпадающего меню */
  const FIELD_TYPE_SELECT = 'select_advanced';
  /** @var string В виде нескольких выпадающих меню, для работы с детьми */
  const FIELD_TYPE_SELECT_TREE = 'select_tree';
  /** @var string В виде спика чекбоксов */
  const FIELD_TYPE_CHECKBOX_LIST = 'checkbox_list';
  /** @var string В виде дерева чекбоксов */
  const FIELD_TYPE_CHECKBOX_TREE = 'checkbox_tree';
  /** @var string В виде списка радио-кнопок */
  const FIELD_TYPE_RADIO_LIST = 'radio_list';

  /** @var string[]|null */
  private $postType = null;
  private $fieldType = self::FIELD_TYPE_SELECT;

  /**
   * @param string $id ID
   * @param string|null $name Название
   * @param array|null $postType Список типов постов, которые должны отображаться в поле.
   */
  public function __construct(string $id, ?string $name = null, ?array $postType = null)
  {
    parent::__construct($id, $name);

    $this->postType = $postType;
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
   *
   * @return self
   */
  public function setPostType(?array $postType): self
  {
    $this->postType = $postType;

    return $this;
  }

  /**
   * Устанавливает вид контрола.
   *
   * По-умолчанию, `self::FIELD_TYPE_SELECT`.
   *
   * @param string $type
   *
   * @return Post
   */
  public function setFieldType(string $type): self
  {
    $this->fieldType = $type;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'post',
      'field_type' => $this->fieldType,
    ];

    if ($this->postType) {
      $result['post_type'] = $this->postType;
    }

    return $result;
  }
}
