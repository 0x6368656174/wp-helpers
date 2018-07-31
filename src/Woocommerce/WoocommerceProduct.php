<?php
/**
 * This file is part of the it-quasar/wp-helpers library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ItQuasar\WpHelpers\Woocommerce;

use ItQuasar\WpHelpers\Helpers;
use Timber\CommentThread;
use Timber\Image;
use Timber\Post;
use Timber\PostPreview;
use Timber\Term;
use WC_Product;
use WC_Product_Variable;
use function wc_get_product;

class WoocommerceProduct
{
  /** @var Post */
  private $post;

  /** @var WC_Product | WC_Product_Variable */
  private $product;

  public function __construct(Post $post)
  {
    $this->post = $post;
    $this->product = wc_get_product($post->id);
  }

  /**
   * @return Image[]
   */
  public function getGallery(): array
  {
    return Helpers::mapIdToImages($this->product->get_gallery_image_ids());
  }

  public function isInStock(): bool
  {
    return $this->product->is_in_stock();
  }

  public function isOutOfStock(): bool
  {
    return !$this->product->is_in_stock();
  }

  public function getPost(): Post
  {
    return $this->post;
  }

  public function hasTag(string $tag): bool
  {
    $terms = $this->getTags();

    foreach ($terms as $term) {
      if ($term->slug == $tag) {
        return true;
      }
    }

    return false;
  }

  /**
   * @return Term[]
   */
  public function getTags(): array
  {
    return $this->post->terms('product_tag');
  }

  /**
   * @return float|string
   */
  public function getRegularPrice()
  {
    return $this->product->get_regular_price();
  }

  public function getId(): int
  {
    return $this->post->id;
  }

  /**
   * @return float|null
   */
  public function getSalePrice()
  {
    return $this->product->get_sale_price();
  }

  public function getName(): string
  {
    return $this->post->name();
  }

  public function getLink(): string
  {
    return $this->post->link();
  }

  /**
   * @return Image|null
   */
  public function getThumbnail()
  {
    return $this->post->thumbnail();
  }

  /**
   * @return Term[]
   */
  public function getCategories(): array
  {
    return $this->post->terms('product_cat');
  }

  public function getDescriptionPreview(): PostPreview
  {
    return $this->post->preview();
  }

  public function getDescription(): string
  {
    return $this->post->content();
  }

  public function isVariable(): bool
  {
    return $this->product->is_type('variable');
  }

  public function getVariants(): array
  {
    if (!$this->isVariable()) {
      return [];
    }

    return array_map(function (array $variant) {
      return new WoocommerceProductVariant($variant);
    }, $this->product->get_available_variations());
  }

  /**
   * @param int|null $count
   * @param string   $order
   * @param string   $type
   * @param string   $status
   *
   * @return CommentThread
   */
  public function getComments(?int $count = null, string $order = 'wp', string $type = 'comment', string $status = 'approve'): CommentThread
  {
    return $this->post->comments($count, $order, $type, $status);
  }

  /**
   * @return WoocommerceProduct[]
   */
  public function getCrossSellProducts(): array
  {
    $crosSellIds = $this->product->get_cross_sell_ids();

    return array_map(function (int $id) {
      $post = new Post($id);

      return new WoocommerceProduct($post);
    }, $crosSellIds);
  }
}
