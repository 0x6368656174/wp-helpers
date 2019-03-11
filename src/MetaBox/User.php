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
 * Выбор пользоватлея
 */
class User extends AbstractMetaBoxBaseField
{
  private $queryArgs = null;
  private $multiple = false;

  /**
   * @param string $id ID
   * @param array|null $queryArgs Параметры выбора пользователя, @see https://codex.wordpress.org/Function_Reference/get_users
   * @param string|null $name Название
   */
  public function __construct(string $id, ?array $queryArgs = null, ?string $name = null)
  {
    parent::__construct($id, $name);

    $this->queryArgs = $queryArgs;
  }

  /**
   * Устанавлиает возможность выбрать несколько значений.
   *
   * По-умолчанию, `false`.
   *
   * @param bool $multiple Признак того, что можно выбрать несколько значений
   *
   * @return self
   */
  public function setMultiple(bool $multiple): self
  {
    $this->multiple = $multiple;

    return $this;
  }

  protected function getMetaBoxConfig(): array
  {
    $result = [
      'type' => 'user',
    ];

    if ($this->queryArgs) {
      $result['query_args'] = $this->queryArgs;
    }

    if ($this->multiple) {
      $result['field_type'] = 'checkbox_list';
    }

    return $result;
  }
}
