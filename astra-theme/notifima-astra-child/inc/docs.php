<?php
/**
 * BetterDocs pages in the Notifima design (like the single blog post):
 *   - single doc        → template-parts/docs/single.php
 *   - docs archive      → template-parts/docs/archive.php
 *   - a docs category   → template-parts/docs/archive.php (that category's docs)
 * BetterDocs keeps working (post type "docs", taxonomy "doc_category", its settings, search and reactions);
 * only the page layout is ours. Styles: assets/css/nf-docs.css.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/** Is this a BetterDocs page we draw? */
function nf_is_docs_page() {
	if ( ! post_type_exists( 'docs' ) ) {
		return false;
	}
	return is_singular( 'docs' ) || is_post_type_archive( 'docs' ) || ( taxonomy_exists( 'doc_category' ) && is_tax( 'doc_category' ) );
}

/** The docs home URL. */
function nf_docs_url() {
	$url = get_post_type_archive_link( 'docs' );
	return $url ? $url : nf_link( 'docs' );
}

/**
 * Docs categories with their docs, in the BetterDocs order.
 *
 * @return array[] Each: term (WP_Term), docs (WP_Post[]).
 */
function nf_docs_tree() {
	static $tree = null;
	if ( null !== $tree ) {
		return $tree;
	}
	$tree = array();
	if ( ! taxonomy_exists( 'doc_category' ) ) {
		return $tree;
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'doc_category',
			'hide_empty' => true,
			'parent'     => 0,
			'meta_key'   => 'doc_category_order', // phpcs:ignore -- BetterDocs' own category order
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $terms ) || ! $terms ) {
		$terms = get_terms( array( 'taxonomy' => 'doc_category', 'hide_empty' => true, 'parent' => 0 ) );
	}
	if ( is_wp_error( $terms ) ) {
		return $tree;
	}
	foreach ( $terms as $term ) {
		$docs = get_posts(
			array(
				'post_type'      => 'docs',
				'posts_per_page' => 200,
				'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
				'tax_query'      => array( array( 'taxonomy' => 'doc_category', 'terms' => $term->term_id, 'include_children' => true ) ), // phpcs:ignore
			)
		);
		$tree[] = array( 'term' => $term, 'docs' => $docs );
	}
	return $tree;
}

/** The left docs menu (used on the single doc). */
function nf_docs_nav( $current_id = 0, $current_term = 0 ) {
	$tree = nf_docs_tree();
	if ( ! $tree ) {
		return;
	}
	echo '<nav class="dc-nav" aria-label="' . esc_attr__( 'Documentation', 'notifima' ) . '">';
	nf_docs_search( 'sm' );
	foreach ( $tree as $node ) {
		$open = (int) $node['term']->term_id === (int) $current_term;
		if ( ! $open && $current_id ) {
			foreach ( $node['docs'] as $d ) {
				if ( (int) $d->ID === (int) $current_id ) {
					$open = true;
				}
			}
		}
		echo '<details class="dc-cat"' . ( $open ? ' open' : '' ) . '><summary><span class="dc-ic">' . nf_icon( 'book' ) . '</span><b>' . esc_html( $node['term']->name ) . '</b><span class="dc-n">' . count( $node['docs'] ) . '</span>' . nf_icon( 'chev' ) . '</summary><ul>'; // phpcs:ignore
		foreach ( $node['docs'] as $d ) {
			$on = (int) $d->ID === (int) $current_id;
			echo '<li><a href="' . esc_url( get_permalink( $d ) ) . '"' . ( $on ? ' class="on" aria-current="page"' : '' ) . '>' . esc_html( get_the_title( $d ) ) . '</a></li>';
		}
		echo '</ul></details>';
	}
	echo '</nav>';
}

/** Search box that searches the docs only. */
function nf_docs_search( $size = '' ) {
	?>
	<form class="dc-search<?php echo $size ? ' ' . esc_attr( $size ) : ''; ?>" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php echo nf_icon( 'search' ); // phpcs:ignore ?>
		<input type="search" name="s" placeholder="<?php esc_attr_e( 'Search the docs…', 'notifima' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" aria-label="<?php esc_attr_e( 'Search the docs', 'notifima' ); ?>">
		<input type="hidden" name="post_type" value="docs">
		<?php if ( 'lg' === $size ) : ?><button type="submit" class="btn btn-p btn-sm"><?php esc_html_e( 'Search', 'notifima' ); ?></button><?php endif; ?>
	</form>
	<?php
}

/**
 * Give H2/H3 an id, drop BetterDocs' "#" copy links, and collect an "On this page" list.
 *
 * @return array [ content, toc[] ] with toc rows [ id, text, level ].
 */
function nf_docs_prepare( $content ) {
	$toc     = array();
	$content = preg_replace( '/<a[^>]*class="[^"]*b[ae]tterdocs-anchor[^"]*"[^>]*>.*?<\/a>/is', '', $content );
	$content = preg_replace_callback(
		'/<h([23])([^>]*)>(.*?)<\/h\1>/is',
		function ( $m ) use ( &$toc ) {
			$text = trim( wp_strip_all_tags( $m[3] ) );
			if ( '' === $text ) {
				return $m[0];
			}
			if ( preg_match( '/\sid="([^"]+)"/', $m[2], $x ) ) {
				$id = $x[1];
			} else {
				$id    = sanitize_title( $text ) . '-' . count( $toc );
				$m[2] .= ' id="' . esc_attr( $id ) . '"';
			}
			$toc[] = array( $id, $text, (int) $m[1] );
			return '<h' . $m[1] . $m[2] . '>' . $m[3] . '</h' . $m[1] . '>';
		},
		$content
	);
	return array( $content, $toc );
}

/* Route BetterDocs pages to our templates (after BetterDocs' own template_include). */
add_filter(
	'template_include',
	function ( $template ) {
		if ( ! nf_is_docs_page() || ! apply_filters( 'nf_docs_templates', true ) ) {
			return $template;
		}
		return NF_DIR . ( is_singular( 'docs' ) ? '/template-parts/docs/single.php' : '/template-parts/docs/archive.php' );
	},
	10000
);

/* Our docs pages use the blog post styles + the docs styles. */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( nf_is_docs_page() ) {
			wp_enqueue_style( 'nf-blog' );
		}
	},
	30
);
