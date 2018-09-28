<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

use Timber\Post;

/**
 * MetaBox для термина.
 */
abstract class AbstractPostMetaBox extends AbstractMetaBox
{
  /**
   * Возвращает значение для поста с указанным ID $postId для контрола c ID $controlId.
   *
   * @param int $postId
   * @param string $controlId ID контрола
   *
   * @return mixed
   * @throws NotFoundMetaBoxException
   */
  public static function getValue(int $postId, string $controlId)
  {
    $post = new Post($postId);

    return static::getPostValue($post, $controlId);
  }

  /**
   * Возврвщает значение для поста $post для контрола с ID $controlId.
   *
   * @param Post $post Пост
   * @param string $controlId ID контрола
   *
   * @return mixed
   * @throws NotFoundMetaBoxException
   */
  public static function getPostValue(Post $post, string $controlId)
  {
    $control = static::getControl($controlId);
    return $control->mapValue($post->meta(static::getId().'__'.$controlId));
  }

  public static function toMetaBoxArray(): array
  {
    $metaBox = parent::toMetaBoxArray();
    $metaBox['post_types'] = static::getPostTypes();

    return $metaBox;
  }

  /**
   * Возвращает список типов постов, для которых необходимо применить данный методбокс. Списко должен представлять
   * из себя массив строк в нижмен регистре (как слаг поста).
   *
   * По-умолчанию, ['post']
   *
   * @return array
   */
  protected static function getPostTypes(): array
  {
    return ['post'];
  }
}
