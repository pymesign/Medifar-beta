<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package medifar
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<div id="secondary" class="left-col">
		<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">			
				
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			
			<!--<aside id="staticlinkswidget-2" class="widget widgets-static-links">
				<h3 class="widget-title">Información</h3>
				<ul class="toggle-block">
					<li>
						<div class="static-links-list">
							<span><a href="#">
									Delivery</a></span>

							<span><a href="#">
									Aviso legal</a></span>

							<span><a href="#">
									Acerca de</a></span>

							<span><a href="#">
									Pago seguro</a></span>
							<span><a href="#">
									Contacto</a></span>
							<span><a href="#">
									Tiendas</a></span>
							<span><a href="#">
									Descuentos</a></span>
							<span><a href="#">
									Nuevos productos</a></span>
						</div>
					</li>
				</ul>
			</aside>-->
			
			<!--<aside id="woocommerce_product_tag_cloud-1" class="widget woocommerce widget_product_tag_cloud">
				<h3 class="widget-title">Product tags</h3>
				<div class="tagcloud"><a
						
						class="tag-cloud-link tag-link-32 tag-link-position-1" style="font-size: 18.266666666667pt;"
						aria-label="Adipiscing (4 products)">Adipiscing</a>
					<a 
						class="tag-cloud-link tag-link-33 tag-link-position-2" style="font-size: 13.6pt;"
						aria-label="augue (3 products)">augue</a>
					<a 
						class="tag-cloud-link tag-link-34 tag-link-position-3" style="font-size: 8pt;"
						aria-label="Bibendum (2 products)">Bibendum</a>
					<a 
						class="tag-cloud-link tag-link-37 tag-link-position-4" style="font-size: 13.6pt;"
						aria-label="Cras (3 products)">Cras</a>
					<a 
						class="tag-cloud-link tag-link-38 tag-link-position-5" style="font-size: 8pt;"
						aria-label="Dapibus (2 products)">Dapibus</a>
					<a 
						class="tag-cloud-link tag-link-39 tag-link-position-6" style="font-size: 22pt;"
						aria-label="eget (5 products)">eget</a>
					<a 
						class="tag-cloud-link tag-link-40 tag-link-position-7" style="font-size: 8pt;"
						aria-label="Etiam (2 products)">Etiam</a>
					<a 
						class="tag-cloud-link tag-link-47 tag-link-position-8" style="font-size: 8pt;"
						aria-label="Leo (2 products)">Leo</a>
					<a 
						class="tag-cloud-link tag-link-54 tag-link-position-9" style="font-size: 18.266666666667pt;"
						aria-label="Pretium (4 products)">Pretium</a>
					<a 
						class="tag-cloud-link tag-link-55 tag-link-position-10" style="font-size: 18.266666666667pt;"
						aria-label="quis (4 products)">quis</a>
					<a 
						class="tag-cloud-link tag-link-58 tag-link-position-11" style="font-size: 18.266666666667pt;"
						aria-label="sem (4 products)">sem</a>
					<a 
						class="tag-cloud-link tag-link-66 tag-link-position-12" style="font-size: 8pt;"
						aria-label="ultricies (2 products)">ultricies</a>
					<a 
						class="tag-cloud-link tag-link-68 tag-link-position-13" style="font-size: 8pt;"
						aria-label="viverra (2 products)">viverra</a></div>
			</aside>-->
		</div>
		<!-- #primary-sidebar -->
	</div>
	<!-- #secondary -->

	<?php //dynamic_sidebar( 'sidebar-1' ); ?>

