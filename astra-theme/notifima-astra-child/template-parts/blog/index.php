<?php
/**
 * Blog index (Settings → Reading → Posts page, or Appearance → Notifima → "Show your real posts on the Blog page").
 *
 * Shows the Blog page exactly as you built it in Kadence (hero, newsletter, FAQ, call to action …).
 * Only the sample cards and the pager in it are swapped for your real posts.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/** Real posts in the original Notifima blog layout: a big card and a list on page 1, a grid after that. */
function nf_blog_posts_html() {
	global $wp_query;
	if ( ! have_posts() ) {
		return '<div class="nf-tpl"><p class="intro" style="margin:0 auto;text-align:center">' . esc_html__( 'No posts yet.', 'notifima' ) . '</p></div>';
	}
	$arts  = array( array( 'pa1', 'bell' ), array( 'pa2', 'shield' ), array( 'pa3', 'chart' ), array( 'pa4', 'mail' ), array( 'pa5', 'package' ) );
	$cards = array();
	$i     = 0;
	while ( have_posts() ) {
		the_post();
		$art  = $arts[ $i % 5 ];
		$cat  = get_the_category();
		$name = get_the_author();
		$ini  = strtoupper( implode( '', array_map( function ( $w ) { return mb_substr( $w, 0, 1 ); }, array_slice( preg_split( '/\s+/', trim( $name ) ), 0, 2 ) ) ) );
		$pic  = has_post_thumbnail()
			? '<div class="post-art" style="background:var(--tint)">' . get_the_post_thumbnail( null, 'medium_large', array( 'style' => 'width:100%;height:100%;object-fit:cover;position:absolute;inset:0' ) ) . '</div>'
			: '<div class="post-art ' . esc_attr( $art[0] ) . '" aria-hidden="true">' . str_replace( 'class="i"', 'class="i pa-big"', nf_icon( $art[1] ) ) . '</div>';
		$cards[] = array(
			'url'  => get_permalink(),
			'cat'  => $cat ? $cat[0]->name : '',
			'slug' => implode( ' ', wp_list_pluck( $cat, 'slug' ) ),
			'h'    => get_the_title(),
			'p'    => wp_trim_words( get_the_excerpt(), 22 ),
			'meta' => $name . ' · ' . get_the_date( 'M j, Y' ) . ' · ' . nf_read_time(),
			'ini'  => $ini,
			'pic'  => $pic,
		);
		$i++;
	}
	wp_reset_postdata();

	$card = function ( $c, $class, $excerpt ) {
		return '<a class="' . esc_attr( $class ) . ' post rv" data-cat="' . esc_attr( $c['slug'] ) . '" href="' . esc_url( $c['url'] ) . '">' . $c['pic']
			. '<div class="post-body">' . ( $c['cat'] ? '<span class="cat">' . esc_html( $c['cat'] ) . '</span>' : '' )
			. '<h3>' . esc_html( $c['h'] ) . '</h3>' . ( $excerpt ? '<p>' . esc_html( $c['p'] ) . '</p>' : '' )
			. '<div class="meta">' . ( $excerpt ? '<span class="av">' . esc_html( $c['ini'] ) . '</span>' : '' ) . esc_html( $c['meta'] ) . '</div></div></a>';
	};

	$html = '';
	if ( ! is_paged() && count( $cards ) > 1 ) {
		$first = array_shift( $cards );
		$list  = array_splice( $cards, 0, 4 );
		$html .= '<div class="feat-post">' . $card( $first, 'fp-big', true ) . '<div class="fp-list">';
		foreach ( $list as $c ) {
			$html .= $card( $c, 'fp-s', false );
		}
		$html .= '</div></div>';
	}
	if ( $cards ) {
		$html .= '<div class="blog"' . ( '' === $html ? ' style="margin-top:0"' : '' ) . '>';
		foreach ( $cards as $c ) {
			$html .= $card( $c, '', true );
		}
		$html .= '</div>';
	}
	return '<div class="nf-tpl nf-posts">' . $html . '</div>';
}

/** Real pager, or nothing when there is one page. */
function nf_blog_pager_html() {
	$links = paginate_links(
		array(
			'prev_text' => __( 'Previous', 'notifima' ),
			'next_text' => __( 'Next', 'notifima' ) . ' ' . nf_icon( 'arrow' ),
		)
	);
	return $links ? '<div class="nf-tpl"><nav class="pager" aria-label="' . esc_attr__( 'Blog pages', 'notifima' ) . '">' . str_replace( 'current', 'current on', $links ) . '</nav></div>' : '';
}

/** Swap the sample cards (graphics "blog-fp-…") and the sample pager for the real ones. */
function nf_blog_swap( $block, &$done ) {
	$plain = function ( $html ) {
		return array( 'blockName' => null, 'attrs' => array(), 'innerBlocks' => array(), 'innerHTML' => $html, 'innerContent' => array( $html ) );
	};
	$cls = (string) ( $block['attrs']['className'] ?? '' );
	if ( preg_match( '/(^|\s)pager(\s|$)/', $cls ) ) {
		return $plain( nf_blog_pager_html() );
	}
	$raw = serialize_block( $block );
	if ( false === strpos( $raw, '"blog-fp' ) ) {
		return $block;
	}
	if ( 'kadence/rowlayout' === $block['blockName'] ) {
		$inner = false;
		foreach ( $block['innerBlocks'] as $b ) {
			if ( false !== strpos( serialize_block( $b ), '"blog-fp' ) && false !== strpos( serialize_block( $b ), 'wp:kadence/rowlayout' ) ) {
				$inner = true;
			}
		}
		if ( ! $inner && ! $done ) {
			$done = true;
			return $plain( nf_blog_posts_html() );
		}
	}
	if ( 'notifima/graphic' === $block['blockName'] ) {
		return $plain( '' );
	}
	foreach ( $block['innerBlocks'] as $k => $b ) {
		$block['innerBlocks'][ $k ] = nf_blog_swap( $b, $done );
	}
	return $block;
}

$nf_page    = (int) get_option( 'page_for_posts' );
$nf_content = $nf_page ? (string) get_post_field( 'post_content', $nf_page ) : '';
if ( false === strpos( $nf_content, '"blog-fp' ) ) {
	$nf_content = function_exists( 'nf_page_markup' ) ? nf_page_markup( 'blog' ) : '';
}
/* Category chips in the hero: your real categories (the ones that have posts), filtering the cards below. */
add_filter(
	'render_block',
	function ( $html, $block ) {
		if ( 'notifima/graphic' !== ( $block['blockName'] ?? '' ) || 'blog-cats' !== ( $block['attrs']['name'] ?? '' ) ) {
			return $html;
		}
		$cats = get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ) );
		$cats = array_filter( $cats, function ( $c ) { return 'uncategorized' !== $c->slug; } );
		$btns = '<button class="cat-b on" data-cat="all" type="button">' . esc_html__( 'All posts', 'notifima' ) . '</button>';
		foreach ( $cats as $c ) {
			$btns .= '<button class="cat-b" data-cat="' . esc_attr( $c->slug ) . '" type="button">' . esc_html( $c->name ) . '</button>';
		}
		return preg_replace( '#(<div class="cats\b[^"]*"[^>]*>).*?(</div>)#s', '$1' . str_replace( '$', '\\$', $btns ) . '$2', $html, 1 );
	},
	10,
	2
);

$nf_done = false;
$nf_out  = '';
foreach ( parse_blocks( $nf_content ) as $nf_block ) {
	$nf_out .= render_block( nf_blog_swap( $nf_block, $nf_done ) );
}
if ( ! $nf_done ) {
	$nf_out .= '<section class="nf-k sec nf-blog-list nf-tpl" style="padding-top:24px"><div class="wrap">' . nf_blog_posts_html() . nf_blog_pager_html() . '</div></section>';
}

get_header();
?>
<main id="nf-main" class="nf-main nf-canvas">
	<?php echo do_shortcode( $nf_out ); // phpcs:ignore ?>
</main>
<?php
get_footer();
