<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Woocommerce;

use Timber\Post;
use Timber\PostQuery;
use Timber\Term;
use function array_unshift;
use function explode;
use function get_query_var;
use function is_product_category;
use function is_product_tag;

class WoocommerceHelper
{
  public const URL_SHOP_PAGE = 'urlShopPage';
  public const URL_MY_ACCOUNT_PAGE = 'urlMyAccountPage';
  public const URL_CART_PAGE = 'urlCartPage';
  public const URL_CHECKOUT_PAGE = 'urlCheckoutPage';
  public const URL_PAYMENT_PAGE = 'urlPaymentPage';
  public const URL_LOGOUT = 'urlLogout';

  /**
   * @param $page
   *
   * @return false|string
   */
  public static function getUrl($page)
  {
    $result = false;

    switch ($page) {
  case static::URL_SHOP_PAGE:
  $result = get_permalink(wc_get_page_id('shop'));
  break;

  case static::URL_MY_ACCOUNT_PAGE:
  $myAccountPageId = get_option('woocommerce_myaccount_page_id');

  if ($myAccountPageId) {
    $result = get_permalink($myAccountPageId);
  }

  break;

  case static::URL_CART_PAGE:
  global $woocommerce;
  $result = $woocommerce->cart->get_cart_url();

  break;

  case static::URL_CHECKOUT_PAGE:
  global $woocommerce;
  $result = $woocommerce->cart->get_checkout_url();

  break;

  case static::URL_PAYMENT_PAGE:
  $result = get_permalink(wc_get_page_id('pay'));

  break;

  case static::URL_LOGOUT:
  $myAccountPageId = get_option('woocommerce_myaccount_page_id');

  if ($myAccountPageId) {
    $result = wp_logout_url(get_permalink($myAccountPageId));
  }

  break;
  }

    if ('yes' == get_option('woocommerce_force_ssl_checkout')) {
      $result = str_replace('http:', 'https:', $result);
    }

    return $result;
  }

  /**
   * @param int $count
   *
   * @return WoocommerceProduct[]
   */
  public static function getBestSellingProducts(int $count): array
  {
    $posts = new PostQuery([
      'post_type' => 'product',
      'meta_key' => 'total_sales',
      'orderby' => 'meta_value_num',
      'posts_per_page' => $count,
    ]);

    $result = [];

    foreach ($posts as $post) {
      $result[] = new WoocommerceProduct($post);
    }

    return $result;
  }

  /**
   * @return Term[]
   */
  public static function getBreadcrumbs(): array
  {
    if (static::isShop() || static::isProductCategory() || static::isProductTag()) {
      $current = static::getCurrentProductCategory();
    } elseif (static::isProduct()) {
      $product = static::getCurrentProduct();
      $categories = $product->getCategories();
      $current = $categories[0];
    }

    $result = [];

    while ($current) {
      array_unshift($result, $current);

      if ($current->parent) {
        $current = new Term($current->parent);
      } else {
        $current = null;
      }
    }

    return $result;
  }

  /**
   * @return null|Term
   */
  public static function getCurrentProductCategory()
  {
    $result = new Term();
    if (is_null($result->id)) {
      return null;
    }

    return $result;
  }

  public static function getCurrentProduct(): WoocommerceProduct
  {
    $post = new Post();

    return new WoocommerceProduct($post);
  }

  public static function getCurrentProducts(array $defaults = ['orderBy' => 'title', 'order' => 'ASC', 'perPage' => 16]): WoocommerceProducts
  {
    // Взято из https://cfxdesign.com/create-a-custom-woocommerce-product-loop-the-right-way/

    $termVar = get_query_var('term');
    $pagedVar = get_query_var('paged');
    $term = $termVar ? $termVar : null;
    $paged = $pagedVar ? absint($pagedVar) : 1;
    if (isset($_GET['orderby'])) {
      $ordering = WC()->query->get_catalog_ordering_args();
    } else {
      $ordering = WC()->query->get_catalog_ordering_args($defaults['orderBy'], $defaults['order']);
    }

    if ($ordering['meta_key']) {
      $orderBy = 'meta_value_num';
    } else {
      $orderBy = $ordering['orderby'];
    }
    $orderBy = explode(' ', $orderBy)[0];

    $requestOrderBy = $orderBy;
    if (isset($_GET['orderby'])) {
      $requestOrderBy = explode('-', $_GET['orderby'])[0];
    }

    $productsPerPage = $defaults['perPage'];
    if (isset($_GET['limit'])) {
      $productsPerPage = (int) $_GET['limit'];
    }

    $args = [
      'status' => 'publish',
      'meta_key' => $ordering['meta_key'],
      'limit' => $productsPerPage,
      'page' => $paged,
      'paginate' => true,
      'return' => 'ids',
      'orderby' => $orderBy,
      'order' => $ordering['order'],
      'request_order_by' => $requestOrderBy,
    ];

    if (static::isProductCategory()) {
      $args['category'] = $term;
    } elseif (static::isProductTag()) {
      $args['tag'] = $term;
    }

    return new WoocommerceProducts($args);
  }

  /**
   * Обновляет колличество товаров, отображаемых на одной странице
   * из GET запроса limit.
   */
  public static function updateProductsPerPage(): void
  {
    if (isset($_GET['limit'])) {
      add_filter('loop_shop_per_page', function () {
        return (int) $_GET['limit'];
      }, 20);
    }
  }

  /**
   * Данный метод необходимо вызывать в functions.php, для того, чтобы
   * включилась полная поддержка ItQuasar/Woocommerce.
   */
  public static function init(): void
  {
    static::updateProductsPerPage();
  }

  public static function isShop(): bool
  {
    return is_shop();
  }

  public static function isProductCategory(): bool
  {
    return is_product_category();
  }

  public static function isProductTag(): bool
  {
    return is_product_tag();
  }

  public static function isProduct(): bool
  {
    return is_product();
  }
}
