<?php

/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

get_header('shop'); ?>

<div id="main" class="site-main">
	<div class="main_inner">
		<div class="main-container category left-sidebar single-product-sidebar">
			<div class="main-inner-container">
				<!-- category block -->
				<div class="category-list">
					<div class="category-box">
						<div class="home-category widget_product_categories">
						<?php dynamic_sidebar('home-categorias'); ?>
						</div>
					</div>
				</div>
				<!-- end category block -->

			</div>
		</div>
	</div>
	<div class="page-title header">
		<div class="page-title-inner">
			<h3 class="entry-title-main">
			<?php echo get_the_title(); ?> </h3>			
		</div>
	</div>
	<div class="main-content-inner">

		<div class="main-content">

			<div class="single-product-sidebar">

				<div id="primary" class="content-area">
					<?php
					/**
					 * woocommerce_before_main_content hook.
					 *
					 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
					 * @hooked woocommerce_breadcrumb - 20
					 */
					do_action('woocommerce_before_main_content');
					?>

					<?php while (have_posts()) : ?>
						<?php the_post(); ?>

						<?php wc_get_template_part('content', 'single-product'); ?>

					<?php endwhile; // end of the loop. 
					?>

					<?php
					/**
					 * woocommerce_after_main_content hook.
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action('woocommerce_after_main_content');
					?>
				</div>
				<?php
				/**
				 * woocommerce_sidebar hook.
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				do_action('woocommerce_sidebar');
				?>
			</div>
		</div>
	</div>



</div>

<?php
get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
