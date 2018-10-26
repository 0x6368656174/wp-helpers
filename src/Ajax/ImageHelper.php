<?php

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Ajax;

use Timber\Image;
use Timber\ImageHelper as TimberImageHelper;
use function preg_match;

class ImageHelper extends AbstractAjaxHandler
{
  public function actionName(): string
  {
    return 'image_helper';
  }

  public function callback(): array
  {
    if (!isset($_GET['image_id'])) {
      throw new AjaxException('Not found image_id GET param', 1);
    }
    $imageId = $_GET['image_id'];

    $image = new Image($imageId);
    if (!$image) {
      throw new AjaxException("Not found image with id = $imageId", 2);
    }
    $result = [
      'id' => $imageId,
      'link' => $image->link(),
      'src' => $image->src(),
    ];

    // Обработаем фильтры
    if (isset($_GET['filters'])) {
      if (!preg_match_all('/^((resize|tojpg|letterbox|retina)\([^\)]*\),?)+$/', $_GET['filters'])) {
        throw new AjaxException('Invalid filters format', 3);
      }

      if (!preg_match_all('((resize|tojpg|letterbox|retina)\([^\)]*\))', $_GET['filters'], $filters)) {
        throw new AjaxException('Invalid filters format', 3);
      }
      $filters = $filters[0];

      $filteredSrc = '';
      foreach ($filters as $filter) {
        if (!preg_match('/(resize|tojpg|letterbox|retina)\([^\)]*\)/', $filter, $filterMatch)) {
          throw new AjaxException('Invalid filters format', 3);
        }

        switch ($filterMatch[1]) {
    case 'resize':
    preg_match(
    '/resize\((\d+),(\d+)(,[\'"](default|center|top|bottom|left|right)[\'"])?(,(true|false))?/',
    $filter, $filterParams);
    $filteredSrc = TimberImageHelper::resize(
    $image->src(),
    (int) $filterParams[1],
    (int) $filterParams[2],
    ($filterParams[4] ? $filterParams[4] : 'default'),
    ('true' === $filterParams[6] ? true : false));
     break;
    case 'tojpg':
    // TODO: Доделать
    break;
    case 'letterbox':
    // TODO: Доделать
    break;
    case 'retina':
    // TODO: Доделать
    break;
  }
      }
      if ($filteredSrc) {
        $result['filteredSrc'] = $filteredSrc;
      }
    }

    return $result;
  }
}
