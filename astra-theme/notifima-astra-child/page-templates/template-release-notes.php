<?php
/**
 * Template Name: Notifima Release Notes (automatic)
 * Template Post Type: page
 *
 * The Release Notes page, filled automatically: the page title, then every release read from WordPress.org
 * (and GitHub, if set) in Customize → Notifima → General. Whatever is in the page editor is not shown, so the
 * list never goes out of date. A new release appears within an hour (see inc/features/releases.php).
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

get_header();
$nf_ver = nf_latest_version();
?>
<main id="nf-main" class="nf-main nf-canvas nf-rel-page">
	<section class="nf-rp-hero">
		<div class="nf-rp-wrap">
			<div class="nf-rp-crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'notifima' ); ?></a>/<span><?php esc_html_e( 'Release notes', 'notifima' ); ?></span></div>
			<h1><?php esc_html_e( 'Release', 'notifima' ); ?> <span class="ac"><?php esc_html_e( 'notes', 'notifima' ); ?></span></h1>
			<p class="nf-rp-lead">
				<?php
				esc_html_e( 'Every update, fix and improvement to Notifima, newest first.', 'notifima' );
				if ( $nf_ver ) {
					/* translators: %s: version number */
					echo ' ' . esc_html( sprintf( __( 'Current version is %s.', 'notifima' ), $nf_ver ) );
				}
				?>
			</p>
		</div>
	</section>
	<section class="nf-rp-body">
		<div class="nf-rp-wrap nf-release-notes"><?php echo nf_release_notes_html(); // phpcs:ignore ?></div>
	</section>
</main>
<?php
get_footer();
