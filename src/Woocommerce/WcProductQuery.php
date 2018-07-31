<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Woocommerce;

use Timber\PostQuery;
use WC_Product_Query;

class WcProductQuery extends WC_Product_Query
{
  public function get_products(): PostQuery
  {
    $args = apply_filters('woocommerce_product_object_query_args', $this->get_query_vars());
    $dataStore = new WcProductDataStoreCpt();

    return $dataStore->query($args);
  }
}
