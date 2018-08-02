<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Woocommerce;

use Timber\Image;
use WC_Product_Variation;

class WoocommerceProductVariant
{
  private $woocommerceVariant;

  public function __construct(array $woocommerceVariant)
  {
    $this->woocommerceVariant = new WC_Product_Variation($woocommerceVariant['variation_id']);
  }

  public function getLink(): string {
    return $this->woocommerceVariant->get_permalink();
  }

  public function getAttributes(): array {
    return $this->woocommerceVariant->get_variation_attributes();
  }

  public function getAddToCartUrl(): string {
    return $this->woocommerceVariant->add_to_cart_url();
  }

  public function getRegularPrice(): float {
    return (float)$this->woocommerceVariant->get_regular_price();
  }

  public function getSalePrice(): float {
    return (float)$this->woocommerceVariant->get_sale_price();
  }

  /**
   * @return null|Image
   */
  public function getImage(): ?Image {
    $imageId = $this->woocommerceVariant->get_image_id();
    if (!$imageId) {
      return null;
    }

    return new Image($imageId);
  }
}
