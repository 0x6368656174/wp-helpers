<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

use Timber\Comment;

/**
 * MetaBox для комментария.
 *
 * Для работы этого типа метобоксов нужно, чтоб был установлен и включен плагин MB Comment Meta.
 *
 * Для установки плагина при помощи Composer:
 * ```
 * $ composer require wpackagist-plugin/mb-comment-meta
 * ```
 *
 * @see https://metabox.io/plugins/mb-comment-meta/
 */
abstract class AbstractCommentMetaBox extends AbstractMetaBox
{
  /**
   * Возвращает значение для комментария с указанным ID $commentId для контрола c ID $controlId.
   *
   * @param int $commentId ID комментария
   * @param string $controlId ID контрола
   *
   * @return mixed
   * @throws NotFoundMetaBoxException
   */
  public static function getValue(int $commentId, string $controlId)
  {
    $comment = new Comment($commentId);

    return static::getCommentValue($comment, $controlId);
  }

  /**
   * Возврвщает значение для комментария $comment для контрола с ID $controlId.
   *
   * @param Comment $comment Комментарий
   * @param string $controlId ID контрола
   *
   * @return mixed
   * @throws NotFoundMetaBoxException
   */
  public static function getCommentValue(Comment $comment, string $controlId)
  {
    $control = static::getControl($controlId);
    return $control->mapValue($comment->meta(static::getFullControlId($controlId)));
  }

  public static function toMetaBoxArray(): array
  {
    $metaBox = parent::toMetaBoxArray();
    $metaBox['type'] = 'comment';

    return $metaBox;
  }
}
