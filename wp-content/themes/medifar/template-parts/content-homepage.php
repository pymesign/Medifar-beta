<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package medifar
 */

?>

<div id="content" class="site-content" role="main">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php medifar_post_thumbnail(); ?>

		<!-- owl carousel categorias -->
		<!--<div class="vc_row wpb_row vc_row-fluid cz-categories">
			<div class="wpb_column vc_column_container vc_col-sm-12">
				<div class="vc_column-inner">
					<div class="wpb_wrapper">
						<div id="6_category_carousel" class="category-carousel categorylist">
							<div class="cat-item">
								<div class="cat-img"><a title="Electrosurgical"><img title="Electrosurgical" src="http://capricathemes.com/wordpress/WCM02/WCM020030/wp-content/uploads/2020/06/catlegory-1.png" alt="Electrosurgical" width="140" height="140" /></a></div>
								<div class="cat-description">
									<div class="cat-title"><a class="cat-name" title="Electrosurgical">Anestesiología</a></div>
								</div>
							</div>
							<div class="cat-item">
								<div class="cat-img"><a title="First Aid Kit"><img title="First Aid Kit" src="http://capricathemes.com/wordpress/WCM02/WCM020030/wp-content/uploads/2020/06/catlegory-3.png" alt="First Aid Kit" width="140" height="140" /></a></div>
								<div class="cat-description">
									<div class="cat-title"><a class="cat-name" title="First Aid Kit">Cardiología</a></div>
								</div>
							</div>
							<div class="cat-item">
								<div class="cat-img"><a title="Forceps"><img title="Forceps" src="http://capricathemes.com/wordpress/WCM02/WCM020030/wp-content/uploads/2020/06/catlegory-4.png" alt="Forceps" width="140" height="140" /></a></div>
								<div class="cat-description">
									<div class="cat-title"><a class="cat-name" title="Forceps">Cirugía</a></div>
								</div>
							</div>
							<div class="cat-item">
								<div class="cat-img"><a title="Health Drinks"><img title="Health Drinks" src="http://capricathemes.com/wordpress/WCM02/WCM020030/wp-content/uploads/2020/06/catlegory-5.png" alt="Health Drinks" width="140" height="140" /></a></div>
								<div class="cat-description">
									<div class="cat-title"><a class="cat-name" title="Health Drinks">Diálisis</a></div>
								</div>
							</div>
							<div class="cat-item">
								<div class="cat-img"><a title="Healthy Teens"><img title="Healthy Teens" src="http://capricathemes.com/wordpress/WCM02/WCM020030/wp-content/uploads/2020/06/catlegory-6.png" alt="Healthy Teens" width="140" height="140" /></a></div>
								<div class="cat-description">
									<div class="cat-title"><a class="cat-name" title="Healthy Teens">Emergencias</a></div>
								</div>
							</div>
							<div class="cat-item">
								<div class="cat-img"><a title="Medicine"><img title="Medicine" src="http://capricathemes.com/wordpress/WCM02/WCM020030/wp-content/uploads/2020/06/catlegory-2.png" alt="Medicine" width="140" height="140" /></a></div>
								<div class="cat-description">
									<div class="cat-title"><a class="cat-name" title="Medicine">Fertilidad</a></div>
								</div>
							</div>
							<div class="cat-item">
								<div class="cat-img"><a title="Oxygen Mask"><img title="Oxygen Mask" src="http://capricathemes.com/wordpress/WCM02/WCM020030/wp-content/uploads/2020/06/catlegory-3.png" alt="Oxygen Mask" width="140" height="140" /></a></div>
								<div class="cat-description">
									<div class="cat-title"><a class="cat-name" title="Oxygen Mask">Oxígenoterapia</a></div>
								</div>
							</div>
							<div class="cat-item">
								<div class="cat-img"><a title="Special Mask"><img title="Special Mask" src="http://capricathemes.com/wordpress/WCM02/WCM020030/wp-content/uploads/2020/06/catlegory-3.png" alt="Special Mask" width="140" height="140" /></a></div>
								<div class="cat-description">
									<div class="cat-title"><a class="cat-name" title="Special Mask">Neurocirugía</a></div>
								</div>
							</div>
							<div class="cat-item">
								<div class="cat-img"><a title="Stethoscope"><img title="Stethoscope" src="http://capricathemes.com/wordpress/WCM02/WCM020030/wp-content/uploads/2020/06/catlegory-1.png" alt="Stethoscope" width="140" height="140" /></a></div>
								<div class="cat-description">
									<div class="cat-title"><a class="cat-name" title="Stethoscope">Oncología</a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>-->
		<!-- end owl carousel categorias -->

		<div class="entry-content">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'medifar'),
					'after'  => '</div>',
				)
			);
			?>
			<div class="vc_row wpb_row vc_row-fluid vc_custom_1577783772386">
				<div class="wpb_column vc_column_container vc_col-sm-12">
					<div class="vc_column-inner">
						<div class="wpb_wrapper productos">
							<div id="horizontalTab" class="cz-tab">
								<ul class="resp-tabs-list">
									<li>
										<div class="tab-title">Ofertas del mes</div>
									</li>
									<li>
										<div class="tab-title">Nuevos arribos</div>
									</li>
									<li>
										<div class="tab-title">Más vendidos</div>
									</li>
								</ul>
								<div class="resp-tabs-container">
									<div id="woo-products" class="woo-content products_block shop woofeature">
										<div id="4_woo_carousel" class="woo-carousel cols-4">
											<div class="woocommerce columns-4 ">
												<!-- WooCommerce On-Sale Products -->
												<?php $onsale = wc_get_product_ids_on_sale(); 
												//print_r($onsale); ?>
												<ul class="products">
													<?php
													$args = array(
														"post_type" => "product",
														"orderby" => "post__in",
														"order" => "ASC",
														"posts_per_page" => "12",
														"post__in" => $onsale
													);
													$loop = new WP_Query($args);
													if ($loop->have_posts()) {
														while ($loop->have_posts()) : $loop->the_post();
															get_template_part('template-parts/content', 'product');
														endwhile;
													} else {
														echo __('No products found');
													}
													wp_reset_postdata();
													?>
												</ul>
												<!--<ul class="products"><?php echo do_shortcode('[sale_products]'); ?></ul>-->
												<!-- WooCommerce On-Sale Products -->
											</div>
										</div>
									</div>
									<div id="woo-products" class="woo-content products_block shop woonew">
										<div id="4_woo_carousel" class="woo-carousel cols-4">
											<div class="woocommerce columns-4 ">
												<?php $onfeatured = woocommerce_get_featured_product_ids();
												//print_r($onfeatured); ?>

												<ul class="products">
													<?php
													$args = array(
														'post_type' => 'product',
														'posts_per_page' => 12,
														'tax_query' => array(
															array(
																'taxonomy' => 'product_visibility',
																'field'    => 'name',
																'terms'    => 'featured',
															),
														),
													);
													$loop = new WP_Query($args);
													if ($loop->have_posts()) {
														while ($loop->have_posts()) : $loop->the_post();
															get_template_part('template-parts/content', 'product');
														endwhile;
													} else {
														echo __('No products found');
													}
													wp_reset_postdata();
													?>
												</ul>


											</div>
										</div>
									</div>
									<div id="woo-products" class="woo-content products_block shop woobest">
										<div id="4_woo_carousel" class="woo-carousel cols-4">
											<div class="woocommerce columns-4 ">

												<ul class="products">
													<?php
													$args = array(
														'post_type' => 'product',
														'posts_per_page' => 12,
														'tax_query' => array(
															array(
																'meta_key' => 'total_sales',
																'orderby'   => array('meta_value_num' => 'ASC', 'title' => 'ASC'),
															),
														),
													);
													$loop = new WP_Query($args);
													if ($loop->have_posts()) {
														while ($loop->have_posts()) : $loop->the_post();
															get_template_part('template-parts/content', 'product');
														endwhile;
													} else {
														echo __('No products found');
													}
													wp_reset_postdata();
													?>
												</ul>


											</div>
										</div>
									</div>

								</div>
								<!--/.products-->
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="vc_row wpb_row vc_row-fluid vc_custom_1577783772386">
				<div class="wpb_column vc_column_container vc_col-sm-12">
					<div class="vc_column-inner">
						<div class="wpb_wrapper">
							<div class="shortcode-title left home">
								<h3 class="normal-title" style="color:#ffffff;">Laboratorios</h3>
							</div>
							<div id="brand-products" class="tmpmela_logocontent">
								<div id="5_brand_carousel2" class="brand-carousel2 tm-logo-content">

									<div class="item brand_main">
										<div class="product-block"><a href="https://www.andromaco.com/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/andromaco.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.conosur.astrazeneca.com/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/astrazeneca-otro.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.bago.com.ar/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/bago.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.laboratoriosbernabo.com/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/bernabo.jpg" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.casasco.com.ar/es/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/casasco.jpg" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.gador.com/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/gador.jpg" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.gsk.com/en-gb/contact-us/worldwide/argentina/es-arg/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/glaxo.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.investi.com.ar/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/investi.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.raffo.com.ar/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/raffo.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.roemmers.com.ar/" target="_blank"><img src="./wp-content/themes/medifar/images/laboratorios/roemmers.jpg" alt="Logo Image" /></a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="vc_row wpb_row vc_row-fluid vc_custom_1577783772386">
				<div class="wpb_column vc_column_container vc_col-sm-12">
					<div class="vc_column-inner">
						<div class="shortcode-title left home">
							<h3 class="normal-title" style="color:#ffffff;">Representaciones</h3>
						</div>
						<div class="wpb_wrapper">
							<div id="brand-products" class="tmpmela_logocontent">
								<div id="5_brand_carousel" class="brand-carousel tm-logo-content">
									<div class="item brand_main">
										<div class="product-block"><a href="http://www.3m.com.ar/" target="_blank"><img src="./wp-content/themes/medifar/images/logos/3m-logo.gif.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="http://www.anios.com/es/inicio.html" target="_blank"><img src="./wp-content/themes/medifar/images/logos/ANIOS_logo_3.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.bd.com/es-ar" target="_blank"><img src="./wp-content/themes/medifar/images/logos/becton-dickinson-and-company-bd-vector-logo.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.bostonscientific.com/es-ar/home.html" target="_blank"><img src="./wp-content/themes/medifar/images/logos/BSC_Spanish.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="http://www.convatec.com.ar/" target="_blank"><img src="./wp-content/themes/medifar/images/logos/Convatec.jpg" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.freseniusmedicalcare.com.ar/es-ar/inicio/" target="_blank"><img src="./wp-content/themes/medifar/images/logos/2500px-Fresenius_Medical_Care_logo.svg.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="https://www.medtronic.com/ar-es/index.html" target="_blank"><img src="./wp-content/themes/medifar/images/logos/medtronic.png" alt="Logo Image" /></a></div>
									</div>
									<div class="item brand_main">
										<div class="product-block"><a href="http://www.gruposilmag.com/" target="_blank"><img src="./wp-content/themes/medifar/images/logos/silmag.png" alt="Logo Image" /></a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div><!-- .entry-content -->



		<?php if (get_edit_post_link()) : ?>
			<footer class="entry-footer">
				<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__('Edit <span class="screen-reader-text">%s</span>', 'medifar'),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post(get_the_title())
					),
					'<span class="edit-link">',
					'</span>'
				);
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</article><!-- #post-<?php the_ID(); ?> -->

</div>