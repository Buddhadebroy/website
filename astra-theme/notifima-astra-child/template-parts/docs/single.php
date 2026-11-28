<?php
/**
 * Single doc (BetterDocs "docs" post) in the Notifima blog-post design:
 * hero (breadcrumb, category, title, updated · read time), docs menu | article | "On this page" + CTA.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();
	list( $content, $toc ) = nf_docs_prepare( apply_filters( 'the_content', get_the_content() ) ); // phpcs:ignore
	$terms = get_the_terms( get_the_ID(), 'doc_category' );
	$term  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;

	// Previous / next doc inside the same category.
	$prev = null;
	$next = null;
	foreach ( nf_docs_tree() as $node ) {
		$ids = wp_list_pluck( $node['docs'], 'ID' );
		$at  = array_search( get_the_ID(), $ids ); // phpcs:ignore
		if ( false !== $at ) {
			$prev = $at > 0 ? $node['docs'][ $at - 1 ] : null;
			$next = isset( $node['docs'][ $at + 1 ] ) ? $node['docs'][ $at + 1 ] : null;
			break;
		}
	}
	?>
	<main id="nf-main" class="nf-main nf-tpl nf-docs">
		<section class="bp-hero dc-hero">
			<div class="wrap">
				<div class="bp-head rv">
					<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'notifima' ); ?></a>/<a href="<?php echo esc_url( nf_docs_url() ); ?>"><?php esc_html_e( 'Docs', 'notifima' ); ?></a><?php if ( $term ) : ?>/<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a><?php endif; ?></div>
					<?php if ( $term ) : ?><span class="bp-cat"><?php echo esc_html( $term->name ); ?></span><?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<div class="bp-meta">
						<span class="dc-badge"><?php echo nf_icon( 'book' ); // phpcs:ignore ?><?php esc_html_e( 'Documentation', 'notifima' ); ?></span><i></i>
						<span><?php esc_html_e( 'Updated', 'notifima' ); ?> <b><?php echo esc_html( get_the_modified_date() ); ?></b></span><i></i>
						<span><?php echo nf_icon( 'clock' ) . esc_html( nf_read_time() ); // phpcs:ignore ?> <?php esc_html_e( 'read', 'notifima' ); ?></span>
					</div>
				</div>
			</div>
		</section>
		<section class="bp-body dc-body">
			<div class="wrap dc-grid<?php echo $toc ? '' : ' no-toc'; ?>">
				<aside class="dc-left"><?php nf_docs_nav( get_the_ID(), $term ? $term->term_id : 0 ); ?></aside>
				<article class="bp-art dc-art">
					<?php echo $content; // phpcs:ignore ?>

					<?php if ( shortcode_exists( 'betterdocs_article_reactions' ) ) : ?>
						<div class="dc-react"><?php echo do_shortcode( '[betterdocs_article_reactions]' ); ?></div>
					<?php endif; ?>

					<?php if ( $prev || $next ) : ?>
						<div class="dc-pn">
							<?php if ( $prev ) : ?><a class="dc-prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>"><small><?php esc_html_e( 'Previous', 'notifima' ); ?></small><b><?php echo esc_html( get_the_title( $prev ) ); ?></b></a><?php else : ?><span></span><?php endif; ?>
							<?php if ( $next ) : ?><a class="dc-next" href="<?php echo esc_url( get_permalink( $next ) ); ?>"><small><?php esc_html_e( 'Next', 'notifima' ); ?></small><b><?php echo esc_html( get_the_title( $next ) ); ?></b></a><?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="dc-help">
						<span class="bp-cta-ic"><?php echo nf_icon( 'help' ); // phpcs:ignore ?></span>
						<div><b><?php esc_html_e( 'Still stuck?', 'notifima' ); ?></b><p><?php esc_html_e( 'Our team answers every ticket, usually within one business day.', 'notifima' ); ?></p></div>
						<a class="btn btn-g btn-sm" href="<?php echo esc_url( nf_link( 'contact' ) ); ?>"><?php esc_html_e( 'Contact support', 'notifima' ); ?> <?php echo nf_icon( 'arrow' ); // phpcs:ignore ?></a>
					</div>
				</article>
				<aside class="bp-side dc-right">
					<?php if ( $toc ) : ?>
						<div class="bp-toc"><h6><?php esc_html_e( 'On this page', 'notifima' ); ?></h6>
							<?php foreach ( $toc as $t ) : ?><a class="lv<?php echo (int) $t[2]; ?>" href="#<?php echo esc_attr( $t[0] ); ?>"><?php echo esc_html( $t[1] ); ?></a><?php endforeach; ?>
						</div>
					<?php endif; ?>
					<div class="bp-cta-card">
						<span class="bp-cta-ic"><?php echo nf_icon( 'bell' ); // phpcs:ignore ?></span>
						<b><?php esc_html_e( 'Stop losing sales to "Out of stock"', 'notifima' ); ?></b>
						<p><?php esc_html_e( 'Add back in stock alerts to your WooCommerce store in minutes.', 'notifima' ); ?></p>
						<a class="btn btn-p" href="<?php echo esc_url( nf_link( 'pricing' ) ); ?>"><?php esc_html_e( 'Get Notifima', 'notifima' ); ?> <?php echo nf_icon( 'arrow' ); // phpcs:ignore ?></a>
					</div>
				</aside>
			</div>
		</section>
	</main>
	<?php
endwhile;
get_footer();
