<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package medifar
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel='stylesheet' id='tmpmela-fonts-css'
		href='//fonts.googleapis.com/css?family=Source+Sans+Pro%3A300%2C400%2C700%2C300italic%2C400italic%2C700italic%7CBitter%3A400%2C700&#038;subset=latin%2Clatin-ext'
		media='all' />
	<link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css' />
	<?php wp_head(); ?>
	<script src="https://kit.fontawesome.com/4f120f5ae8.js" crossorigin="anonymous"></script>
	<script type='text/javascript'>
		/* <![CDATA[ */
		var php_var = { "tmpmela_loadmore": "", "tmpmela_pagination": "", "tmpmela_nomore": "" };
/* ]]> */
	</script>
	<?php $current_user = wp_get_current_user(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'medifar'); ?></a>

		<!-- Header -->
		<header id="masthead" class="site-header site-header-fix  header left-sidebar">
			<div class="topbar-outer">
				<div class="theme-container">
					<!-- top-welcomecms -->

					<?php if(isset($current_user) && $current_user->user_firstname !='') : ?>
					<div class="header-welcome">
						<div class="welcome-block">Hola, <?php echo $current_user->user_firstname; ?></div>
					</div>
					<?php endif; ?>
					<!-- End top-welcomecms -->

					<!-- Topbar link -->
					<div class="topbar-link">
						<span class="topbar-link-toggle">Mi cuenta</span>
						<div class="topbar-link-wrapper">
							<div class="header-menu-links">							

								<?php
										wp_nav_menu(
											array(
												'theme_location' => 'header-menu',
												'menu_id'        => 'menu-header-top-links',
												'menu_class'	 => 'header-menu'
											)
										);
										?>

							</div>
						</div>
					</div>
					<!--whislist-->

					<!--<div class="whislist-counter">
						<div class="header-whislist">
							<div class="whislist-text"><a href="./wishlist/">Mis
									favoritos</a></div><span class="count">0</span>
						</div>
					</div>-->




					<!--Cart -->
					<div class="header-cart headercart-block">

						<div class="cart togg">

							<div class="shopping_cart tog" title="View your shopping cart">
								<a class="cart-contents" href="#">
									<div class="cart-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&dollar;</span>0.00</span></div>
									<div class="cart-qty">0<div class="cart-text"> item</div>
									</div>
								</a>
							</div>
							<aside id="woocommerce_widget_cart-1" class="widget woocommerce widget_shopping_cart tab_content">
								<h3 class="widget-title">Cart</h3>
								<div class="widget_shopping_cart_content"></div>
							</aside>
						</div>

					</div>


				</div>
			</div>
			<div class="site-header-main header-fix">
				<div class="header-main">
					<div class="header-top">

						<div class="theme-container">

							<!-- Header LOGO-->
							<div class="header-logo">
								<?php the_custom_logo(); ?>
							</div>
							<!-- Header Mob LOGO-->
							<div class="header-mob-logo">
								<?php the_custom_logo(); ?>
							</div>





							<div class="header-cms">
								<span class="header-cms-toggle"></span>
								<div class="header-cms-inner">
									<div class="cms-list cms-1">
										<div class="cms-content">
											<span class="left"> <?php echo do_shortcode('[ht-ctc-chat style=3]'); ?> </span>
											<div class="content">
												<div class="cms_other_text">Consultas </div>
												<div class="cms-title"><?php echo do_shortcode('[ht-ctc-chat style=6 call_to_action="2914375533"]'); ?>
 </div>
											</div>
										</div>
									</div>
									<div class="cms-list cms-2">
										<div class="cms-content">
											<span class="icon-image"> </span>
											<div class="content">
												<div class="cms_other_text">Envíos a todo el país </div>
												<div class="cms-title">Envío gratis a Bahía Blanca</div>
											</div>
										</div>
									</div>
								</div>
							</div>


							<!-- header top navigation -->
							<div class="header-top-menu">
								<span class="header-top-menu-toggle"></span>
								<div class="header-top-menu-inner">
									<div class="menu-header-navigation-container">										

										<?php
										wp_nav_menu(
											array(
												'theme_location' => 'header-navigation',
												'menu_id'        => 'menu-header-navigation',
												'menu_class'	 => 'top-menu'
											)
										);
										?>

									</div>
								</div>
							</div>

							<!-- End header top navigation -->

							<!--Search-->
							<div class="header-search">
								<div class="header-toggle"></div>
								<?php dynamic_sidebar( 'search' ); ?>
							</div>

							<!--End Search-->



						</div>
					</div>

					<div class="header-bottom">
						<div class="theme-container">
							<div class="category-list">
								<div class="box-category-heading">
									<div class="box-category">
										<span class="heading-img"></span>Especialidades
									</div>
								</div>
							</div>




							<!-- #site-navigation -->
							<nav id="site-navigation" class="navigation-bar main-navigation">
								<h3 class="menu-toggle">Menu</h3>
								<a class="screen-reader-text skip-link" href="#content" title="Skip to content">Skip to
									content</a>
								<div class="mega-menu">
									<div class="menu-mainmenu-container">
										<?php
										wp_nav_menu(
											array(
												'theme_location' => 'menu-1',
												'menu_id'        => 'menu-mainmenu',
												'menu_class'	 => 'mega'
											)
										);
										?>
									</div>
								</div>
								<div class="mobile-menu">
									<span class="close-menu"></span>
									<div class="menu-mainmenu-container">
										<?php
										wp_nav_menu(
											array(
												'theme_location' => 'menu-1',
												'menu_id'        => 'menu-mainmenu-1',
												'menu_class'	 => 'mobile-menu-inner'
											)
										);
										?>
									</div>
								</div>
							</nav><!-- #site-navigation -->


						</div>
					</div>

				</div>
				<!-- End header-main -->
			</div>
		</header>
		<!-- end mainbanner sidebanner -->