<?php

declare(strict_types=1);
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace ItQuasar\WpHelpers;

/**
 * Сортировка для WpQuery.
 *
 * Используется в WpQueryBuilder
 */
class WpQueryOrderBy
{
  /** @var string По порядку, от меньшего к большему (1, 2, 3; a, b, c) */
  public const ORDER_ASC = 'asc';
  /** @var string В обратном порядке, от большего к меньшему (3, 2, 1; c, b, a) */
  public const ORDER_DESC = 'desc';

  /** @var string Сортировка по ID */
  public const ORDER_BY_ID = 'ID';
  /** @var string Сортировка по ID авторов */
  public const ORDER_BY_AUTHOR = 'author';
  /** @var string Сортировка по заголовку */
  public const ORDER_BY_TITLE = 'title';
  /** @var string Сортировка по названию поста (ярлык, слаг поста) */
  public const ORDER_BY_NAME = 'name';
  /** @var string Сортировка по дате публикации */
  public const ORDER_BY_DATE = 'date';
  /** @var string Сортировка по дате изменения */
  public const ORDER_BY_MODIFIED = 'modified';
  /** @var string Сортировка по типу поста (post_type) */
  public const ORDER_BY_POST_TYPE = 'type';
  /** @var string Сортировка по значению поля `parent` */
  public const ORDER_BY_PARENT = 'parent';
  /** @var string Сортировка в случайном порядке */
  public const ORDER_BY_RANDOM = 'rand';
  /** @var string Сортировка по количеству комментариев */
  public const ORDER_BY_COMMENT_COUNT = 'comment_count';
  /** @var string Стандартно используется для страниц и вложений. Порядковый номер указывается на странице редактирования поста */
  public const ORDER_BY_MENU_ORDER = 'menu_order';

  private $order = null;
  private $orderBy = null;

  public function __construct(string $orderBy, string $order = self::ORDER_ASC)
  {
    $this->orderBy = $orderBy;
    $this->order = $order;
  }

  /**
   * Возвращает направление сортировки.
   *
   * @return string
   */
  public function getOrder(): string
  {
    return $this->order;
  }

  /**
   * Возвращает поле сортировки.
   *
   * @return string
   */
  public function getOrderBy(): string
  {
    return $this->orderBy;
  }
}
