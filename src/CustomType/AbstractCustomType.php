<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\CustomType;

use ItQuasar\WpHelpers\WpQueryBuilder;
use Timber\PostQuery;

/**
 * Пользовательский тип
 */
abstract class AbstractCustomType
{
  /** @var string Блок заголовка */
  public const SUPPORT_TITLE = 'title';
  /** @var string Блок для ввода контента */
  public const SUPPORT_EDITOR = 'editor';
  /** @var string Блок выбора автора */
  public const SUPPORT_AUTHOR = 'author';
  /** @var string Блок выбора миниатюры */
  public const SUPPORT_THUMBNAIL = 'thumbnail';
  /** @var string Блок ввода цитаты */
  public const SUPPORT_EXCERPT = 'excerpt';
  /** @var string Поддержка трекбеков и пингов (за блоки не отвечает) */
  public const SUPPORT_TRACK_BACKS = 'trackbacks';
  /** @var string Блок установки произвольных полей */
  public const SUPPORT_CUSTOM_FIELDS = 'custom-fields';
  /** @var string Блок комментариев (обсуждение) */
  public const SUPPORT_COMMENTS = 'comments';
  /** @var string Блок ревизий (не отображается пока нет ревизий) */
  public const SUPPORT_REVISIONS = 'revisions';
  /** @var string Блок атрибутов постоянных страниц (шаблон и древовидная связь записей,
   * древовидность должна быть включена)
   */
  public const SUPPORT_PAGE_ATTRIBUTES = 'page-attributes';
  /** @var string Блок форматов записи, если они включены в теме */
  public const SUPPORT_POST_FORMATS = 'post-formats';

  /**
   * Возвращает уникальный ID пользовательского типа.
   *
   * @return string
   */
  abstract public static function getId(): string;

  /**
   * @return array
   */
  public static function getNames(): array
  {
    return [
      'plural' => static::getId(),
      'singular' => static::getId(),
    ];
  }

  /**
   * Возвращает подписи.
   *
   * @return array [pluralName: string, singularName: string, addNew: string, addNewItem: string, editItem: string]
   *               pluralName - название нескольких сущностей типа
   *               singularName - название одной сущности типа
   *               addNew - подпись кнопки "Добавить новую"
   *               addNewItem - подпись кнопки "Добавить новую сущности типа"
   *               editItem - подпись кнопки "Редактировать сущности типа"
   */
  public static function getLabels(): array
  {
    return [
      'pluralName' => static::getId(),
      'singularName' => static::getId(),
      'addNew' => __('Add new'),
      'addNewItem' => __('Add new').' '.static::getId(),
      'editItem' => __('Edit').' '.static::getId(),
    ];
  }

  /**
   * Возвращает массив расширений, доступных типу.
   *
   * По-умолчанию, `[static::SUPPORT_TITLE, static::SUPPORT_EDITOR]`.
   *
   * @return array
   */
  public static function getSupports(): array
  {
    return [static::SUPPORT_TITLE, static::SUPPORT_EDITOR];
  }

  /**
   * Возвращает иконку.
   *
   * Можно указать любую иконку из набора https://developer.wordpress.org/resource/dashicons
   *
   * По-умолчанию, `dashicons-admin-post`
   *
   * @return string
   */
  public static function getMenuIcon(): string
  {
    return 'dashicons-admin-post';
  }

  /**
   * Возвращает признак того, что тип будет публичным
   *
   * По-умолчанию, true.
   *
   * @return bool
   */
  public static function getPublic(): bool
  {
    return true;
  }

  /**
   * Возвращает маркер для установки прав для этого типа записи.
   *
   * Встроенным маркеры это: `post` и `page`.
   *
   * По-умолчанию, `post`.
   *
   * @return string
   */
  public static function getCapabilityType(): string
  {
    return 'post';
  }

  /**
   * Возвращает тип записи поста.
   *
   * @return string
   */
  final public static function getPostType(): string
  {
    return static::getId();
  }

  public static function registerPostType(): void
  {
    $labels = static::getLabels();

    register_post_type(
  static::getPostType(),
  [
    'labels' => [
      'name' => $labels['pluralName'],
      'singular_name' => $labels['singularName'],
      'add_new' => $labels['addNew'],
      'add_new_item' => $labels['addNewItem'],
      'edit_item' => $labels['editItem'],
    ],
    'supports' => static::getSupports(),
    'menu_icon' => static::getMenuIcon(),
    'public' => static::getPublic(),
    'capability_type' => static::getCapabilityType(),
  ]
  );
    flush_rewrite_rules();
  }

  /**
   * Возвращает запрос выборки пользовательских типов из БД.
   *
   * В параметре может быть передан конструктор запроса в БД для касторизации запроса.
   *
   * @param null|WpQueryBuilder $query Конструктор запроса
   *
   * @return PostQuery
   */
  public static function getPostQuery(WpQueryBuilder $query = null): PostQuery
  {
    $options = [
      'post_type' => static::getId(),
    ];

    if ($query) {
      $options = array_replace_recursive($query->toWpQueryOptions(), $options);
    }

    return new PostQuery($options);
  }

//  /**
//   * Создает новый экземпляр пользовательского типа в БД
//   *
//   * @param array $data Данные:
//   *                    authorId: int|null - ID автора
//   *                    date: DateTime|null - Дата добавления
//   *                    content: string|null - Содержимое
//   *                    title: string|null - Заголовок
//   *                    excerpt: string|null - Краткое содержимое
//   *                    status: string|null - Статус
//   *                    commentStatus: 'open'|'closed'|null - Статус комментариев
//   *                    pingStatus: 'open'|'closed'|null - Статус трасировки
//   *                    password: string|null - Пароль
//   *                    slug: string|null - Слаг поста
//   *                    parentId: int|null - ID родительского поста
//   *                    menuOrder: int|null - Порядок в меню
//   *                    tags: array|null - Список ID, название или слагов тегов
//   */
//  public static function insertNew(array $data): void {
//
//  }
}
