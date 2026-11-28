<?php
/**
 * Docs home (all categories as cards) and a single docs category (its docs as a list),
 * in the Notifima design.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

$nf_term = ( taxonomy_exists( 'doc_category' ) && is_tax( 'doc_category' ) ) ? get_queried_object() : null;
$nf_tree = nf_docs_tree();

get_header();
?>
<main id="nf-main" class="nf-main nf-tpl nf-docs">
	<section class="bp-hero dc-hero dc-hero-c">
		<div class="wrap">
			<div class="bp-head rv">
				<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'notifima' ); ?></a>/<a href="<?php echo esc_url( nf_docs_url() ); ?>"><?php esc_html_e( 'Docs', 'notifima' ); ?></a><?php if ( $nf_term ) : ?>/<span><?php echo esc_html( $nf_term->name ); ?></span><?php endif; ?></div>
				<span class="bp-cat"><?php esc_html_e( 'Documentation', 'notifima' ); ?></span>
				<h1><?php echo $nf_term ? esc_html( $nf_term->name ) : esc_html__( 'How can we help you?', 'notifima' ); ?></h1>
				<p class="dc-sub"><?php echo $nf_term && $nf_term->description ? esc_html( $nf_term->description ) : esc_html__( 'Guides for setting up and getting the most out of Notifima and Notifima Pro.', 'notifima' ); ?></p>
				<?php nf_docs_search( 'lg' ); ?>
			</div>
		</div>
	</section>

	<section class="bp-body dc-body">
		<div class="wrap">
			<?php if ( $nf_term ) : ?>
				<?php
				$nf_docs = array();
				foreach ( $nf_tree as $node ) {
					if ( (int) $node['term']->term_id === (int) $nf_term->term_id ) {
						$nf_docs = $node['docs'];
					}
				}
				?>
				<div class="dc-list">
					<?php foreach ( $nf_docs as $d ) : ?>
						<a class="dc-row" href="<?php echo esc_url( get_permalink( $d ) ); ?>">
							<span class="dc-ic"><?php echo nf_icon( 'file' ); // phpcs:ignore ?></span>
							<span class="dc-row-t"><b><?php echo esc_html( get_the_title( $d ) ); ?></b><small><?php echo esc_html( wp_trim_words( wp_strip_all_tags( has_excerpt( $d ) ? get_the_excerpt( $d ) : preg_replace( '/<a[^>]*b[ae]tterdocs-anchor[^>]*>.*?<\/a>|<h[1-6][^>]*>.*?<\/h[1-6]>/is', ' ', $d->post_content ) ), 22 ) ); ?></small></span>
							<?php echo nf_icon( 'arrow' ); // phpcs:ignore ?>
						</a>
					<?php endforeach; ?>
					<?php if ( ! $nf_docs ) : ?><p class="dc-empty"><?php esc_html_e( 'No docs in this category yet.', 'notifima' ); ?></p><?php endif; ?>
				</div>
				<p class="dc-back"><a href="<?php echo esc_url( nf_docs_url() ); ?>"><?php echo nf_icon( 'arrow' ); // phpcs:ignore ?> <?php esc_html_e( 'All docs', 'notifima' ); ?></a></p>
			<?php else : ?>
				<div class="dc-cards">
					<?php
					$nf_icons = array( 'book', 'settings', 'star', 'layers', 'bell', 'help' );
					foreach ( $nf_tree as $i => $node ) :
						$n = count( $node['docs'] );
						?>
						<div class="dc-card">
							<a class="dc-card-h" href="<?php echo esc_url( get_term_link( $node['term'] ) ); ?>">
								<span class="dc-ic lg"><?php echo nf_icon( $nf_icons[ $i % count( $nf_icons ) ] ); // phpcs:ignore ?></span>
								<b><?php echo esc_html( $node['term']->name ); ?></b>
								<span class="dc-n"><?php echo esc_html( sprintf( _n( '%d doc', '%d docs', $n, 'notifima' ), $n ) ); ?></span>
							</a>
							<ul>
								<?php foreach ( array_slice( $node['docs'], 0, 5 ) as $d ) : ?>
									<li><a href="<?php echo esc_url( get_permalink( $d ) ); ?>"><?php echo nf_icon( 'file' ); // phpcs:ignore ?><?php echo esc_html( get_the_title( $d ) ); ?></a></li>
								<?php endforeach; ?>
							</ul>
							<a class="dc-more" href="<?php echo esc_url( get_term_link( $node['term'] ) ); ?>"><?php esc_html_e( 'View all', 'notifima' ); ?> <?php echo nf_icon( 'arrow' ); // phpcs:ignore ?></a>
						</div>
					<?php endforeach; ?>
					<?php if ( ! $nf_tree ) : ?><p class="dc-empty"><?php esc_html_e( 'No docs published yet.', 'notifima' ); ?></p><?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="dc-help dc-help-wide">
				<span class="bp-cta-ic"><?php echo nf_icon( 'help' ); // phpcs:ignore ?></span>
				<div><b><?php esc_html_e( 'Can\'t find what you need?', 'notifima' ); ?></b><p><?php esc_html_e( 'Our team answers every ticket, usually within one business day.', 'notifima' ); ?></p></div>
				<a class="btn btn-p btn-sm" href="<?php echo esc_url( nf_link( 'contact' ) ); ?>"><?php esc_html_e( 'Contact support', 'notifima' ); ?> <?php echo nf_icon( 'arrow' ); // phpcs:ignore ?></a>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
