<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\MetaBox;

class Translate
{
  public static function addFilters()
  {
    add_filter('rwmb_media_add_string', function () {
      return '+ Новый Файл';
    });

    add_filter('rwmb_media_single_images_string', function () {
      return 'изображение';
    });

    add_filter('rwmb_media_single_files_string', function () {
      return 'файл';
    });

    add_filter('rwmb_media_multiple_images_string', function () {
      return 'изображений';
    });

    add_filter('rwmb_media_multiple_files_string', function () {
      return 'файлов';
    });

    add_filter('rwmb_media_remove_string', function () {
      return 'Удалить';
    });

    add_filter('rwmb_media_edit_string', function () {
      return 'Изменить';
    });

    add_filter('rwmb_media_view_string', function () {
      return 'Просмотреть';
    });

    add_filter('rwmb_media_select_string', function () {
      return 'Выбрать Файл';
    });

    add_filter('rwmb_media_or_string', function () {
      return 'или';
    });

    add_filter('rwmb_media_upload_instructions_string', function () {
      return 'Перетащите файлы для загрузки';
    });
  }
}
