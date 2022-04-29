<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package medifar
 */

?>

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="footer-top">
		<div class="theme-container">
			<div class="footer-before">
				<!--<aside id="newsletterwidget-1" class="widget widget_newsletterwidget">
						<h3 class="widget-title">Reciba nuestras novedades por email</h3>
						<div class="tnp tnp-widget">
							<form method="post" action="http://capricathemes.com/wordpress/WCM02/WCM020030/?na=s"
								onsubmit="return newsletter_check(this)">

								<input type="hidden" name="nlang" value="">
								<input type="hidden" name="nr" value="widget">
								<input type='hidden' name='nl[]' value='0'>
								<div class="tnp-field tnp-field-email"><label>Email</label><input class="tnp-email"
										type="email" name="ne" required></div>
								<div class="tnp-field tnp-field-button"><input class="tnp-submit" type="submit"
										value="Suscribite">
								</div>
							</form>
						</div>
					</aside>-->
				<?php dynamic_sidebar('newsletter'); ?>
				<aside id="followmewidget-2" class="widget widgets-follow-us">
					<div id="follow_us" class="follow-us">
						<ul class="toggle-block">
							<li>
								<?php echo do_shortcode('[ht-ctc-chat style=3_1]'); ?>
								<a href="https://www.facebook.com/medifarbahiablanca" target="_blank" title="Facebook" class="facebook icon"><i class="fa fa-facebook"></i></a>
								<a href="https://www.instagram.com/medifar_oral_care/" target="_blank" title="Instagram" class="instagram icon"><i class="fa fa-instagram"></i></a>
							</li>
						</ul>
					</div>
				</aside>
			</div>

		</div>
	</div>
	<div class="footer-center">
		<div class="theme-container">
			<div id="footer-widget-area">
				<div id="first" class="first-widget footer-widget">
					<aside id="text-4" class="widget widget_text">
						<div class="textwidget">
							<p><img class="alignnone size-full" src="https://medifar.com/wp-content/uploads/2021/01/LOGO-MEDIFAR-bueno.jpg" width="188" height="45" /></p>
							<div class="about-dec"> </div>
						</div>
					</aside>
				</div>

				<!-- #second .widget-area -->
				<div id="second" class="third-widget footer-widget">
					<aside id="footercontactuswidget-2" class="widget widgets-footercontact">
						<h3 class="widget-title">Casa Central</h3>
						<ul class="toggle-block">
							<li>
								<div class="contact_wrapper">
									<div class="address">
										<div class="address_content">
											<i class="fa fa-map-marker" aria-hidden="true"></i>
											<div class="contact_address">Saavedra 845 - (8000) Bahía Blanca</div>

										</div>
									</div>
									<div class="phone">
										<i class="fa fa-phone" aria-hidden="true"></i>
										<div class="contact_phone">Teléfono: +54 291 455-0395</div>

									</div>
									<div class="email">
										<i class="fa fa-envelope" aria-hidden="true"></i>
										<div class="contact_email"><a href="#" target="_Self">
												E-mail : info@medifar.com</a>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</aside>
				</div>

				<!-- #third .widget-area -->
				<div id="third" class="second-widget footer-widget">
					<aside id="footercontactuswidget-2" class="widget widgets-footercontact">
						<h3 class="widget-title">Medifar Fueguina</h3>
						<ul class="toggle-block">
							<li>
								<div class="contact_wrapper">
									<div class="address">
										<div class="address_content">
											<i class="fa fa-map-marker" aria-hidden="true"></i>
											<div class="contact_address">Primer Argentino 108 - (9410) Ushuaia</div>

										</div>
									</div>
									<div class="phone">
										<i class="fa fa-phone" aria-hidden="true"></i>
										<div class="contact_phone">Teléfono: +54 2901 414889 </div>

									</div>
									<div class="email">
										<i class="fa fa-envelope" aria-hidden="true"></i>
										<div class="contact_email"><a href="#" target="_Self">
												E-mail : ushuaia@medifar.com</a>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</aside>
				</div>

				<!-- #forth .widget-area -->
				<div id="forth" class="forth-widget footer-widget">
					<!--<aside id="staticlinkswidget-5" class="widget widgets-static-links">
							<h3 class="widget-title">Links de interés</h3>
							<ul class="toggle-block">
								<li>
									<div class="static-links-list">



										<span><a href="#">
												Secure payment</a></span>
										<span><a href="#">
												Contact us</a></span>
										<span><a href="#">
												Stores</a></span>
										<span><a href="#">
												Prices drop</a></span>
										<span><a href="#">
												New products</a></span>
									</div>
								</li>
							</ul>
						</aside>-->
				</div>
				<!-- #fifth .widget-area -->
				<!--<div id="fifth" class="fifth-widget footer-widget">
						<aside id="footercontactuswidget-2" class="widget widgets-footercontact">
							<h3 class="widget-title">Sucursal Bahía Blanca</h3>
							<ul class="toggle-block">
								<li>
									<div class="contact_wrapper">
										<div class="address">
											<div class="address_content">
												<i class="fa fa-map-marker" aria-hidden="true"></i>
												<div class="contact_address">Saavedra 845 - (8000) Bahia Blanca</div>

											</div>
										</div>
										<div class="phone">
											<i class="fa fa-phone" aria-hidden="true"></i>
											<div class="contact_phone">Teléfono : 0291-4550411 </div>

										</div>
										<div class="email">
											<i class="fa fa-envelope" aria-hidden="true"></i>
											<div class="contact_email"><a
													href="#"
													target="_Self">
													E-mail : info@medifar.com</a>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</aside>
					</div>-->
				<!-- #forth .widget-area -->
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="theme-container">
			<div class="footer-bottom-container">
				<div class="footer-bottom-left">
					<div class="site-info"> Copyright &copy; 2020 Medifar
					</div>
					<div class="paymentcms">
						<ul class="accepted-payment-methods">
							<li class="discover"><span>Discover</span></li>
							<li class="maestro"><span>Maestro</span></li>
							<li class="mastercard"><span>MasterCard</span></li>
							<li class="paypal"><span>PayPal</span></li>
							<li class="visa"><span>Visa</span></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>