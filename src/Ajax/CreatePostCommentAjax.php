<?php
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Ajax;

use const FILTER_SANITIZE_EMAIL;
use const FILTER_SANITIZE_NUMBER_INT;
use const FILTER_SANITIZE_STRING;
use const FILTER_SANITIZE_URL;
use const INPUT_POST;
use function esc_url;
use function filter_input;
use function is_wp_error;
use function sanitize_email;
use function sanitize_text_field;
use function sanitize_textarea_field;

/**
 * Создает комментарий для поста.
 */
class CreatePostCommentAjax extends AbstractAjaxHandler
{
  public const ERROR_CODE_NOT_SET_POST_ID = 1;
  public const ERROR_CODE_NOT_SET_AUTHOR = 2;
  public const ERROR_CODE_NOT_SET_EMAIL = 3;
  public const ERROR_CODE_NOT_SET_COMMENT = 4;

  public function actionName(): string
  {
    return 'create_post_comment';
  }

  public function callback(): array
  {
    $postId = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    if ($postId) {
      $postId = (int) $postId;
    } else {
      throw new AjaxException('Not set post_id', static::ERROR_CODE_NOT_SET_POST_ID);
    }

    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
    if ($author) {
      $author = sanitize_text_field($author);
    } else {
      throw new AjaxException('Not set author', static::ERROR_CODE_NOT_SET_AUTHOR);
    }

    $authorEmail = filter_input(INPUT_POST, 'author_email', FILTER_SANITIZE_EMAIL);
    if ($authorEmail) {
      $authorEmail = sanitize_email($authorEmail);
    } else {
      throw new AjaxException('Not set author_email', static::ERROR_CODE_NOT_SET_EMAIL);
    }

    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    if ($comment) {
      $comment = sanitize_textarea_field($comment);
    } else {
      throw new AjaxException('Not set comment', static::ERROR_CODE_NOT_SET_COMMENT);
    }

    $authorUrl = filter_input(INPUT_POST, 'author_url', FILTER_SANITIZE_URL);
    if ($authorUrl) {
      $authorUrl = esc_url($authorUrl);
    }

    $parentCommentId = filter_input(INPUT_POST, 'parent_comment_id', FILTER_SANITIZE_NUMBER_INT);
    if ($parentCommentId) {
      $parentCommentId = (int) $parentCommentId;
    }

    $options = [
      'comment_post_ID' => $postId,
      'author' => $author,
      'email' => $authorEmail,
      'comment' => $comment,
    ];

    if ($authorUrl) {
      $options['url'] = $authorUrl;
    }

    if ($parentCommentId) {
      $options['comment_parent'] = $parentCommentId;
    }

    $result = wp_handle_comment_submission($options);

    if (is_wp_error($result)) {
      throw new AjaxException($result->get_error_message());
    }

    return [];
  }
}
