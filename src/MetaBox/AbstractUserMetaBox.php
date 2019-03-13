<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

use Timber\User;

/**
 * MetaBox для пользователя.
 *
 * Для работы этого типа метобоксов нужно, чтоб был установлен и включен плагин MB User Meta.
 *
 * Для установки плагина при помощи Composer:
 * ```
 * $ composer require meta-box/mb-user-meta:dev-master
 * ```
 *
 * @see https://docs.metabox.io/extensions/mb-user-meta/
 */
abstract class AbstractUserMetaBox extends AbstractMetaBox
{
  /**
   * Возвращает значение для пользователя с указанным ID $userId для контрола c ID $controlId.
   *
   * @param int $userId ID пользователя
   * @param string $controlId ID контрола
   *
   * @return mixed
   * @throws NotFoundMetaBoxException
   */
  public static function getValue(int $userId, string $controlId)
  {
    $comment = new User($userId);

    return static::getCommentValue($comment, $controlId);
  }

  /**
   * Возврвщает значение для пользователя $user для контрола с ID $controlId.
   *
   * @param User $user Пользователь
   * @param string $controlId ID контрола
   *
   * @return mixed
   * @throws NotFoundMetaBoxException
   */
  public static function getCommentValue(User $user, string $controlId)
  {
    $control = static::getControl($controlId);
    return $control->mapValue($user->meta(static::getFullControlId($controlId)));
  }

  public static function toMetaBoxArray(): array
  {
    $metaBox = parent::toMetaBoxArray();
    $metaBox['type'] = 'user';

    return $metaBox;
  }
}
