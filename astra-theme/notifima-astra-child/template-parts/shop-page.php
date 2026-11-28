<?php
/**
 * The WooCommerce Shop URL when the Shop page is one of our Kadence pages (e.g. Pricing).
 * Shows that page exactly as built in Kadence, without WooCommerce's product list, sorting and result count.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

$nf_shop = get_post( nf_shop_page_id() );

get_header();
?>
<main id="nf-main" class="nf-main nf-canvas nf-shop-page">
	<?php
	if ( $nf_shop ) {
		global $post;
		$post = $nf_shop; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );
		// The page's own blocks, through the_content so Kadence prints its styles with them.
		echo apply_filters( 'the_content', $nf_shop->post_content ); // phpcs:ignore
		wp_reset_postdata();
	}
	?>
</main>
<?php
get_footer();
