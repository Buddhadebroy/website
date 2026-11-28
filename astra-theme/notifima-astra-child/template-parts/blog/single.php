<?php
/**
 * Single blog post, like the VuloForm / VuloMail posts in the Notifima design: breadcrumb, category, title,
 * author · date · reading time, the Kadence body (assets/css/nf-blog.css), "On this page" from the H2s with a
 * call to action, the author box and "Keep reading".
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();
	$content = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore
	// Give every H2 an id for the "On this page" list.
	$toc     = array();
	$content = preg_replace_callback(
		'/<h2([^>]*)>(.*?)<\/h2>/is',
		function ( $m ) use ( &$toc ) {
			$text = wp_strip_all_tags( $m[2] );
			$id   = sanitize_title( $text );
			if ( preg_match( '/\sid="([^"]+)"/', $m[1], $x ) ) {
				$id = $x[1];
			} else {
				$m[1] .= ' id="' . esc_attr( $id ) . '"';
			}
			$toc[] = array( $id, $text );
			return '<h2' . $m[1] . '>' . $m[2] . '</h2>';
		},
		$content
	);
	$blog   = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' );
	$cat    = get_the_category();
	$author = get_the_author();
	?>
	<main id="nf-main" class="nf-main nf-tpl">
		<section class="bp-hero">
			<div class="wrap">
				<div class="bp-head rv">
					<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'notifima' ); ?></a>/<a href="<?php echo esc_url( $blog ); ?>"><?php esc_html_e( 'Blog', 'notifima' ); ?></a><?php if ( $cat ) : ?>/<a href="<?php echo esc_url( get_category_link( $cat[0] ) ); ?>"><?php echo esc_html( $cat[0]->name ); ?></a><?php endif; ?></div>
					<?php if ( $cat ) : ?><span class="bp-cat"><?php echo esc_html( implode( ' · ', wp_list_pluck( array_slice( $cat, 0, 2 ), 'name' ) ) ); ?></span><?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<div class="bp-meta">
						<span class="bp-av"><?php echo esc_html( strtoupper( mb_substr( $author, 0, 1 ) ) ); ?></span>
						<span><?php esc_html_e( 'By', 'notifima' ); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><b><?php echo esc_html( $author ); ?></b></a></span><i></i>
						<span><?php echo esc_html( get_the_date() ); ?></span><i></i>
						<span><?php echo nf_icon( 'clock' ) . esc_html( nf_read_time() ); // phpcs:ignore ?> <?php esc_html_e( 'read', 'notifima' ); ?></span>
					</div>
				</div>
			</div>
		</section>
		<section class="bp-body">
			<div class="wrap bp-grid<?php echo $toc ? '' : ' no-toc'; ?>">
				<article class="bp-art">
					<?php if ( has_post_thumbnail() ) : ?><div class="bp-banner bp-banner-img"><?php the_post_thumbnail( 'large' ); ?></div><?php endif; ?>
					<?php echo $content; // phpcs:ignore ?>
					<div class="bp-author">
						<span class="bp-av"><?php echo esc_html( strtoupper( mb_substr( $author, 0, 1 ) ) ); ?></span>
						<div><b><?php echo esc_html( $author ); ?></b><p><?php echo esc_html( get_the_author_meta( 'description' ) ? get_the_author_meta( 'description' ) : __( 'We build Notifima, the back in stock and waitlist plugin for WooCommerce stores.', 'notifima' ) ); ?></p></div>
					</div>
				</article>
				<aside class="bp-side">
					<?php if ( $toc ) : ?>
						<div class="bp-toc"><h6><?php esc_html_e( 'On this page', 'notifima' ); ?></h6>
							<?php foreach ( $toc as $t ) : ?><a href="#<?php echo esc_attr( $t[0] ); ?>"><?php echo esc_html( $t[1] ); ?></a><?php endforeach; ?>
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
		<?php
		$nf_more = new WP_Query( array( 'posts_per_page' => 3, 'post__not_in' => array( get_the_ID() ), 'ignore_sticky_posts' => true ) );
		if ( $nf_more->have_posts() ) :
			?>
			<section class="nf-k sec tint">
				<div class="wrap">
					<div class="bl-head rv"><div><span class="kicker"><?php esc_html_e( 'Keep reading', 'notifima' ); ?></span><h2 class="title" style="margin-bottom:0"><?php esc_html_e( 'More', 'notifima' ); ?> <span class="ac"><?php esc_html_e( 'guides', 'notifima' ); ?></span></h2></div><a class="btn btn-g" href="<?php echo esc_url( $blog ); ?>"><?php esc_html_e( 'View all posts', 'notifima' ); ?> <?php echo nf_icon( 'arrow' ); // phpcs:ignore ?></a></div>
					<div class="blog">
						<?php
						$i = 0;
						while ( $nf_more->have_posts() ) :
							$nf_more->the_post();
							get_template_part( 'template-parts/blog/card', null, array( 'i' => $i++ ) );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</main>
	<?php
endwhile;
get_footer();
