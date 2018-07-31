<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Woocommerce;

use Timber\Pagination;

class WoocommerceProducts
{
  /** @var int */
  private $perPage;
  /** @var int */
  private $total;
  /** @var WoocommerceProduct[] */
  private $products;
  /** @var string */
  private $orderBy;
  /** @var string */
  private $order;
  /** @var Pagination */
  private $pagination;

  public function __construct($args)
  {
    $query = new WcProductQuery($args);
    $timberQuery = $query->get_products();

    $result = [];

    $this->pagination = $timberQuery->pagination();
    foreach ($timberQuery->get_posts() as $post) {
      $result[] = new WoocommerceProduct($post);
    }

    $this->products = $result;
    $this->perPage = $args['limit'];
    $this->total = $timberQuery->count();
    $this->orderBy = $args['request_order_by'];
    $this->order = $args['order'];
  }

  /**
   * @return int
   */
  public function getPerPage(): int
  {
    return $this->perPage;
  }

  /**
   * @return int
   */
  public function getTotal(): int
  {
    return $this->total;
  }

  /**
   * @return WoocommerceProduct[]
   */
  public function getProducts(): array
  {
    return $this->products;
  }

  /**
   * @return string
   */
  public function getOrderBy(): string
  {
    return $this->orderBy;
  }

  /**
   * @return string
   */
  public function getOrder(): string
  {
    return $this->order;
  }

  /**
   * @return Pagination
   */
  public function getPagination(): Pagination
  {
    return $this->pagination;
  }
}
