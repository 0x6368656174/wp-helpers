<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ItQuasar\WpHelpers\Woocommerce;

use WC_Product_Attribute;
use WP_Term;

class WoocommerceProductAttribute {
  /** @var WC_Product_Attribute */
  private $wcAttribute;

  public function __construct(WC_Product_Attribute $attribute) {
    $this->wcAttribute = $attribute;
  }

  /**
   * @return WoocommerceProductAttributeOption[]
   */
  public function getOptions(): array {
    return array_map(function(WP_Term $term) {
      return new WoocommerceProductAttributeOption($term->slug, $term->name);
    }, $this->wcAttribute->get_terms());
  }
}
