<?php
/**
 * The template for displaying the footer
 *
 * Contains the opening of the #site-footer div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

?>

<script>
// For Remove Cart Extra Note
$(document).ready( function(){

	val1 = $('.woocommerce-notices-wrapper > .woocommerce-info:first-child').html();

	if(val1){
		$('.cart-empty').hide();
	}else{
		$('.cart-empty').show();
	}

});


</script>

			<footer id="site-footer" role="contentinfo" class="header-footer-group">

				<div class="section-inner">

					<div class="footer-credits">

						<p class="footer-copyright">&copy;
							<?php
							echo date_i18n(
								/* translators: Copyright date format, see https://secure.php.net/date */
								_x( 'Y', 'copyright date format', 'twentytwenty' )
							);
							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
							<!-- <a href="<?php 
							// echo esc_url( home_url( '/' ) ); ?>"> -->
								<?php 
								// _e( 'Powered by Biogratees', 'twentytwenty' ); ?>
								<!-- <a href="mailto:support@biogratees.com">| support@biogratees.com -->
								<a href="mailto:customerservice@biographytees.com">| customerservice@biographytees.com
							</a>
						</p><!-- .powered-by-wordpress -->
						<div class="footerlinks">
							<a href="<?php echo site_url(); ?>/privacy-policy/">Privacy Policy</a>
							<a href="<?php echo site_url(); ?>/refund_returns/">Refund and Returns Policy</a>
							<a href="/index.php/terms-condition/">Terms & Condition</a>
						</div>

					</div><!-- .footer-credits -->

					<a class="to-the-top" href="#site-header">
						<span class="to-the-top-long">
							<?php
							/* translators: %s: HTML character for up arrow */
							printf( __( 'To the top %s', 'twentytwenty' ), '<span class="arrow" aria-hidden="true">&uarr;</span>' );
							?>
						</span><!-- .to-the-top-long -->
						<span class="to-the-top-short">
							<?php
							/* translators: %s: HTML character for up arrow */
							printf( __( 'Up %s', 'twentytwenty' ), '<span class="arrow" aria-hidden="true">&uarr;</span>' );
							?>
						</span><!-- .to-the-top-short -->
					</a><!-- .to-the-top -->

				</div><!-- .section-inner -->

			</footer><!-- #site-footer -->

		<?php wp_footer(); ?>

	</body>
</html>
