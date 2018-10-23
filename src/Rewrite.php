<?php

declare(strict_types=1);
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace ItQuasar\WpHelpers;

/**
 * Регистратор кастомных правил обработки URL-запросов.
 */
class Rewrite
{
  /**
   * Регистрирует обработчик определенного пути.
   *
   * При вызове /$path сработает обработчик $handler()
   *
   * @param string   $path    Путь
   * @param callable $handler Обработчик
   */
  public static function registerRewriteRule(string $path, callable $handler): void
  {
    add_action('init', function () use ($path) {
      global $wp_rewrite;
      //set up our query variable %path% which equates to index.php?test=
      add_rewrite_tag("%$path%", '([^&]+)');
      //add rewrite rule that matches /$path
      add_rewrite_rule("^$path/?", "index.php?$path=$path", 'top');
      //add endpoint, in this case '$path' to satisfy our rewrite rule /$path
      add_rewrite_endpoint($path, EP_PERMALINK | EP_PAGES);
      //flush rules to get this to work properly (do this once, then comment out)
      $wp_rewrite->flush_rules();
    });

    add_action('template_redirect', function () use ($path, $handler) {
      global $wp;

      //retrieve the query vars and store as variable $template
      $template = $wp->query_vars;

      //pass the $template variable into the conditional statement and
      //check if the key $path is one of the query_vars held in the $template array
      //and that $path is equal to the value of the key which is set
      if (array_key_exists($path, $template) && $path == $template[$path]) {
        //if the key $path exists and $path matches the value of that key
        //then invoke $handler
        $handler();
        exit;
      }
    });
  }
}
