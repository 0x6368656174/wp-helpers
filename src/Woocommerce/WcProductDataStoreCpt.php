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
use WC_Product_Data_Store_CPT;

class WcProductDataStoreCpt extends WC_Product_Data_Store_CPT
{
  public function query($query_vars): PostQuery
  {
    $args = $this->get_wp_query_args($query_vars);

    if (!empty($args['errors'])) {
      return new PostQuery([]);
    }

    return new PostQuery($args);
  }
}
