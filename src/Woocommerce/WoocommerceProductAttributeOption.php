<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace ItQuasar\WpHelpers\Woocommerce;


class WoocommerceProductAttributeOption {
  /**
   * @return mixed
   */
  public function getSlug() {
    return $this->slug;
  }

  /**
   * @return mixed
   */
  public function getName() {
    return $this->name;
  }
  private $slug;
  private $name;

  /**
   * WoocommerceProductAttributeOption constructor.
   *
   * @param $slug
   * @param $name
   */
  public function __construct( $slug, $name ) {
    $this->slug = $slug;
    $this->name = $name;
  }
}
