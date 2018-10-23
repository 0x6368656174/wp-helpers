<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers;

use Exception;
use ReflectionClass;
use Timber\Timber;
use function strtolower;

/**
 * Страница.
 */
abstract class Page
{
  /** @var Page */
  private static $notFoundPage;

  /**
   * Устанавливает страницу для 404 (не найдена) ошибку.
   *
   * Данная страница будет отображаться в случае вызова @see static::throwNotFoundException().
   *
   * @param Page $notFoundPage
   */
  public static function setNotFoundPage(Page $notFoundPage): void
  {
    static::$notFoundPage = $notFoundPage;
  }

  /**
   * Возвращает сгенерированую страницу.
   *
   * @return string
   *
   * @throws Exception
   */
  public static function render(): string
  {
    $file = static::getTemplateName();

    $context = static::getRenderContext();

    // jsData превратим в json
    $context['jsData'] = json_encode($context['jsData']);

    return Timber::render($file, $context);
  }

  /**
   * Возвращает признак того, что текущая страница активна.
   *
   * @return bool
   */
  abstract public static function isActive(): bool;

  /**
   * Возвращает 404 ошибку (Страница не найдена).
   *
   * Вернет страницу со статусом 404, а так же содержимым, которое возвращает страница,
   * установленная через функцию @see static::setNotFoundPage().
   */
  public static function throwNotFoundException(): void
  {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    nocache_headers();
    if (static::$notFoundPage) {
      try {
        static::$notFoundPage->render();
      } catch (Exception $e) {
      }
    }
    die();
  }

  /**
   * Возвращает контекст, который будет использоваться для генерации страницы.
   *
   * @return array
   */
  protected static function getRenderContext(): array
  {
    $context = static::getContext();
    $extraJsData = [
      'ajaxUrl' => admin_url('admin-ajax.php'),
    ];
    if (isset($context['jsData'])) {
      $context['jsData'] = array_merge($context['jsData'], $extraJsData);
    } else {
      $context['jsData'] = $extraJsData;
    }

    return array_merge_recursive(Timber::get_context(), $context);
  }

  /**
   * Вовзращает контекст
   *
   * @return array
   */
  abstract protected static function getContext(): array;

  /**
   * Возвращает имя шаблона, который должен быть использован для генерации страницы.
   *
   * @return string
   *
   * @throws \ReflectionException
   */
  protected static function getTemplateName(): string
  {
    $rc = new ReflectionClass(static::class);
    $file = basename($rc->getFileName(), '.php');
    // Взято с https://stackoverflow.com/a/19533226/1778685
    $file = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $file));

    return "pages/p-$file/p-$file.twig";
  }
}

