<?php

declare(strict_types=1);
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace ItQuasar\WpHelpers;

use WP_Query;

/**
 * Конструктор для удобного формирования WP_Query.
 */
class WpQueryBuilder
{
  /** @var string Опубликованный */
  public const STATUS_PUBLISH = 'publish';
  /** @var string На модерации */
  public const STATUS_PENDING = 'pending';
  /** @var string Черновик */
  public const STATUS_DRAFT = 'draft';
  /** @var string Черновик, сохраненный самим WordPress (авто-сохранение) */
  public const STATUS_AUTO_DRAFT = 'auto-draft';
  /** @var string Запланированный пост */
  public const STATUS_FUTURE = 'future';
  /** @var string Личный пост */
  public const STATUS_PRIVATE = 'private';
  /** @var string Ревизия */
  public const STATUS_INHERIT = 'inherit';
  /** @var string Удаленный пост (в корзине) */
  public const STATUS_TRASH = 'trash';
  /** @var string Все статусы, кроме типов постов с `exclude_from_search=true` */
  public const STATUS_ANY = 'any';

  /** @var int Вывести все посты */
  public const PER_PAGE_ALL = -1;

  private $status = null;
  private $perPage = null;
  private $page = null;
  /** @var null|WpQueryOrderBy[] */
  private $orders = null;
  private $loadPageFromGetQuery = false;
  private $authorId = null;

  /**
   * Возвращает массив опций, используемый в конструкторе WP_Query, сформированный конструктором
   *
   * @return array
   */
  public function toWpQueryOptions(): array
  {
    $options = [];
    if ($this->status) {
      $options['status'] = $this->status;
    }

    if (-1 === $this->perPage) {
      $options['nopagin'] = true;
    } else {
      $options['posts_per_page'] = $this->perPage;
    }

    if ($this->page) {
      $options['paged'] = $this->page;
    }

    if ($this->loadPageFromGetQuery) {
      $options['paged'] = get_query_var('paged');
    }

    if ($this->orders) {
      $queryOrderBy = [];
      foreach ($this->orders as $orderBy => $order) {
        $queryOrderBy[$orderBy] = $order;
      }
      $options['orderby'] = $queryOrderBy;
    }

    if ($this->authorId) {
      $options['author'] = $this->authorId;
    }

    return $options;
  }

  /**
   * Возвращает WP_Query, сформированный конструктором
   *
   * @return WP_Query
   */
  public function toWpQuery(): WP_Query
  {
    return new WP_Query($this->toWpQueryOptions());
  }

  /**
   * Устанавливает статус постов, которые должны быть получены.
   *
   * По умолчанию static::STATUS_PUBLISH, а если пользователь авторизован добавляется еще и static::STATUS_PRIVATE.
   * Если запрос запущен из админ части, добавляются еще и защищенные типы статусов: static::STATUS_FUTURE,
   * static::STATUS_DRAFT и static::STATUS_PENDING.
   *
   * @param string $status Статус
   *
   * @return self
   */
  public function setStatus(string $status): self
  {
    $this->status = $status;

    return $this;
  }

  /**
   * Устанавливает сортировки.
   *
   * Можно передать массив из нескольких сортировок, для сортировки по нескольким полям
   *
   * @param WpQueryOrderBy[] $orders Массив сортировок
   *
   * @return WpQueryBuilder
   */
  public function setOrders(array $orders): self
  {
    $this->orders = $orders;

    return $this;
  }

  /**
   * Устанавливает сортировку.
   *
   * @param WpQueryOrderBy $order Сортировка
   *
   * @return WpQueryBuilder
   */
  public function setOrder(WpQueryOrderBy $order): self
  {
    $this->orders = [$order];

    return $this;
  }

  /**
   * Устанавливает количество постов на одной странице.
   *
   * Если передать, static::PER_PAGE_ALL, то будут выведены все посты.
   *
   * @param int $perPage Количество постов на одной странице
   *
   * @return WpQueryBuilder
   */
  public function setPerPage(int $perPage): self
  {
    $this->perPage = $perPage;

    return $this;
  }

  /**
   * Устанавливает страницу, для которой должны быть выведены посты.
   *
   * @param int $page Номер страницы
   *
   * @return WpQueryBuilder
   */
  public function setPage(int $page): self
  {
    $this->page = $page;

    return $this;
  }

  /**
   * Устанавлиает ID автора, для которого должны быть выведены посы.
   *
   * @param int $id
   *
   * @return WpQueryBuilder
   */
  public function setAuthorId(int $id): self
  {
    $this->authorId = $id;

    return $this;
  }

  /**
   * Устанавливает признак того, что номер страницы должен быть загружен из GET-запроса.
   *
   * Данный параметр перезаписывает номер страницы, установленный через self::setPage().
   *
   * По умолчанию, false.
   *
   * @param bool $load Признак того, что номер страницы должен быть загружен из GET-запроса
   *
   * @return WpQueryBuilder
   */
  public function setLoadPageFromGetQuery(bool $load): self
  {
    $this->loadPageFromGetQuery = $load;

    return $this;
  }
}
