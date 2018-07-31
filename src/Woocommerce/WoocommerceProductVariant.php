<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Woocommerce;

use function var_dump;

class WoocommerceProductVariant
{
  private $sku;
  private $regularPrice;
  private $salePrice;
  private $inStock;
  private $thumbnail;
  private $description;

  public function __construct(array $woocommerceVariant)
  {
    var_dump($woocommerceVariant);
  }
}
