<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

use Timber\Term;

/**
 * MetaBox для термина.
 *
 * Для работы этого типа метобоксов нужно, чтоб был установлен и включен плагин MB Term Meta.
 *
 * Для установки плагина при помощи Composer:
 * ```
 * $ composer require meta-box/mb-term-meta:dev-master
 * ```
 *
 * @see https://docs.metabox.io/extensions/mb-term-meta/
 */
abstract class AbstractTermMetaBox extends AbstractMetaBox
{
  /**
   * Возвращает значение для термина с указанным ID $termId для контрола c ID $controlId.
   *
   * @param int $termId
   * @param string $controlId ID контрола
   *
   * @return mixed
   * @throws NotFoundMetaBoxException
   */
  public static function getValue(int $termId, string $controlId)
  {
    $term = new Term($termId);

    return static::getTermValue($term, $controlId);
  }

  /**
   * Возврвщает значение для термина $term для контрола с ID $controlId.
   *
   * @param Term $term Термин
   * @param string $controlId ID контрола
   *
   * @return mixed
   * @throws NotFoundMetaBoxException
   */
  public static function getTermValue(Term $term, string $controlId)
  {
    $control = static::getControl($controlId);
    return $control->mapValue($term->meta(static::getFullControlId($controlId)));
  }

  /**
   * Возвращает список таксономий, для которых должен работать данный метобокс
   *
   * @return string[]
   */
  abstract public static function getTaxonomies(): array;

  public static function toMetaBoxArray(): array
  {
    $metaBox = parent::toMetaBoxArray();
    $metaBox['taxonomies'] = static::getTaxonomies();

    return $metaBox;
  }
}
