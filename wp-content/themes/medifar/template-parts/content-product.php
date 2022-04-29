<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
	return;
}
?>

<li <?php wc_product_class('', $product); ?>>
	<div class="container-inner">
		<span class="product-loading"></span>
		<div class="product-block-inner">
			<a class="woocommerce-LoopProduct-link woocommerce-loop-product__link" href="<?php echo get_permalink($product->ID); ?>"></a>
			<div class="image-block">
				<a class="woocommerce-LoopProduct-link woocommerce-loop-product__link" href="<?php echo get_permalink($product->ID); ?>"><?php echo $product->get_image(); ?></a>
				<div class="product-button-hover">
						
					<a data-quantity="1" href="<?php echo get_permalink($product->ID); ?>" class="button view" data-product_id="<?php echo $product->get_id(); ?>" data-product_sku="<?php echo $product->get_sku(); ?>" aria-label="View products in the &ldquo;Mauris eget diam&rdquo; group" rel="nofollow">Ver Producto</a>

				</div>
			</div>
			<div class="product-detail-wrapper">
				<a href="<?php echo get_permalink($product->ID); ?>">
					<h3 class="product-name">
						<?php echo $product->get_title(); ?>
					</h3>
				</a>

				<span class="price"><span class="woocommerce-Price-amount amount"><?php echo wc_price($product->get_price()); ?></span></span>
				
				
			</div>
		</div>
	</div>
</li>