<?php

/**
 * Template Name: Homepage
 * 
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package medifar
 */
get_header();
?>
<div id="main" class="site-main">
	<div class="main_inner">
		<div class="main-container category left-sidebar single-product-sidebar">
			<div class="main-inner-container">
				<!-- category block -->
				<div class="category-list">
					<div class="category-box">
						<div class="home-category widget_product_categories">
							<!--<ul class="product-categories">
								<li class="cat-item cat-item-57"><a>Anestesiología</a></li>
								<li class="cat-item cat-item-41"><a>Cardiología</a></li>
								<li class="cat-item cat-item-64"><a>Cirugía</a></li>
								<li class="cat-item cat-item-67"><a>Diálisis</a></li>
								<li class="cat-item cat-item-63"><a>Emergencias</a></li>
								<li class="cat-item cat-item-43 cat-parent"><a>Endoscopía digestiva</a>
								<li class="cat-item cat-item-69 cat-parent"><a>Enfermería</a></li>
								<li class="cat-item cat-item-56"><a>Esterilización</a></li>
								<li class="cat-item cat-item-50"><a>Fertilidad</a></li>
								<li class="cat-item cat-item-44 cat-parent"><a>Neonatología</a></li>
								<li class="cat-item cat-item-65"><a>Neurocirugía</a></li>
								<li class="cat-item cat-item-60 cat-parent"><a>Oncología</a></li>
								<li class="cat-item cat-item-15"><a>Terapia intensiva</a></li>
							</ul>-->
							<?php dynamic_sidebar('home-categorias'); ?>
						</div>
					</div>
				</div>
				<!-- end category block -->
				<div class="mainbanner-sidebanner-inner cz-slider col-main">
					<!--  main slider -->
					<div class="mainbanner">
						<div id="revolutionslider">
							<div class="revolutionslider-inner">
								<!-- START tmpmela_homeslider REVOLUTION SLIDER 6.2.9 -->
								<?php dynamic_sidebar('home-encabezado'); ?>
								<!-- END REVOLUTION SLIDER -->
							</div>
						</div>
					</div>
					<!-- End main slider -->
				</div>
			</div>
		</div>
	</div>

	<div class="main-content-inner-full">

		<div id="main-content" class="main-content home-page left-sidebar wide-page ">

			<div id="primary" class="content-area">
				<?php
				while (have_posts()) :
					the_post();
					get_template_part('template-parts/content', 'homepage');
					// If comments are open or we have at least one comment, load up the comment template.
					if (comments_open() || get_comments_number()) :
						comments_template();
					endif;
				endwhile; // End of the loop.
				?>				
			</div>
			<?php
			get_sidebar(); ?>
		</div>
	</div>
</div>

</div><!-- end #page -->
<?php

get_footer();
