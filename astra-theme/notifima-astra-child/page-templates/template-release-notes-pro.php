<?php
/**
 * Template Name: Notifima Pro Release Notes
 * Template Post Type: page
 *
 * The Notifima Pro Release Notes page in the same design as the free Release Notes page. The versions come from
 * the page editor (the Kadence Section "nf-relpro", see inc/release-pro.php). Pages holding that Section use this
 * template automatically.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

get_header();
$nf_rel = nf_relpro_releases( get_queried_object_id() );
$nf_ver = $nf_rel ? $nf_rel[0]['version'] : '';
$nf_rn  = get_page_by_path( 'release-notes' );
?>
<main id="nf-main" class="nf-main nf-canvas nf-rel-page nf-relpro-page">
	<section class="nf-rp-hero">
		<div class="nf-rp-wrap">
			<div class="nf-rp-crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'notifima' ); ?></a>/<a href="<?php echo esc_url( $nf_rn ? get_permalink( $nf_rn ) : home_url( '/release-notes/' ) ); ?>"><?php esc_html_e( 'Release notes', 'notifima' ); ?></a>/<span><?php esc_html_e( 'Notifima Pro', 'notifima' ); ?></span></div>
			<h1><?php esc_html_e( 'Pro release', 'notifima' ); ?> <span class="ac"><?php esc_html_e( 'notes', 'notifima' ); ?></span></h1>
			<p class="nf-rp-lead">
				<?php
				esc_html_e( 'Every update, fix and improvement to Notifima Pro, newest first.', 'notifima' );
				if ( $nf_ver ) {
					/* translators: %s: version number */
					echo ' ' . esc_html( sprintf( __( 'Current version is %s.', 'notifima' ), $nf_ver ) );
				}
				?>
			</p>
		</div>
	</section>
	<section class="nf-rp-body">
		<div class="nf-rp-wrap nf-release-notes"><?php echo $nf_rel ? nf_release_notes_html( $nf_rel, 'pro-v' ) : '<p>' . esc_html__( 'No Pro releases yet. Add a version in the page editor.', 'notifima' ) . '</p>'; // phpcs:ignore ?></div>
	</section>
</main>
<?php
get_footer();
