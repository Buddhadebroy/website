<?php
/**
 * Blog index and single post markup (same structure and classes as the original src/app/blog).
 * Used by template-parts/blog/index.php and single.php.
 *
 * A post is passed as an array:
 *   title, url, category, published (Y-m-d), updated, answer (the excerpt: "The short answer"),
 *   content (rendered blocks), minutes, description, related (array of posts)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

function vm_category_pill( $cat ) {
	$tones = array( 'Troubleshooting' => 'red', 'Comparison' => 'blue', 'Guide' => 'green', 'SMS & WooCommerce' => 'amber' );
	if ( '' === trim( (string) $cat ) ) {
		return '';
	}
	return vm_pill( esc_html( $cat ), isset( $tones[ $cat ] ) ? $tones[ $cat ] : 'ink' );
}

/** <PostCard> */
function vm_post_card( $p, $featured = false ) {
	return '<div data-vm-reveal class="' . ( $featured ? 'vm:md:col-span-2' : '' ) . '"><a href="' . esc_url( $p['url'] ) . '" class="vm:group vm:flex vm:h-full vm:flex-col vm:rounded-[1.6rem] vm:border vm:border-line vm:bg-surface vm:p-7 vm:transition-all vm:duration-300 vm:hover:-translate-y-1 vm:hover:border-line-strong vm:hover:shadow-[0_24px_50px_-30px_rgba(19,20,27,0.4)]' . ( $featured ? ' vm:sm:p-10' : '' ) . '">'
		. '<div class="vm:flex vm:items-center vm:justify-between vm:gap-3">' . vm_category_pill( $p['category'] ) . vm_icon( 'arrow-up-right', 'vm:h-5 vm:w-5 vm:text-ink-mute vm:transition-all vm:group-hover:translate-x-0.5 vm:group-hover:-translate-y-0.5 vm:group-hover:text-ink' ) . '</div>'
		. '<h3 class="vm:display vm:mt-5 vm:text-balance ' . ( $featured ? 'vm:text-4xl vm:sm:text-5xl' : 'vm:text-[1.7rem] vm:leading-tight' ) . '">' . esc_html( $p['title'] ) . '</h3>'
		. '<p class="vm:mt-4 vm:text-pretty vm:leading-relaxed vm:text-ink-soft">' . esc_html( $p['description'] ) . '</p>'
		. '<div class="vm:mt-auto vm:flex vm:items-center vm:gap-4 vm:pt-6 vm:text-xs vm:text-ink-mute"><time datetime="' . esc_attr( $p['published'] ) . '">' . esc_html( vm_fmt_date( $p['published'] ) ) . '</time><span class="vm:inline-flex vm:items-center vm:gap-1.5">' . vm_icon( 'clock', 'vm:h-3.5 vm:w-3.5' ) . (int) $p['minutes'] . ' min read</span></div></a></div>';
}

/** Blog index: hero pattern, featured post, grid, CTA. */
function vm_blog_index_html( $posts, $hero, $cta ) {
	$h = $hero . '<section class="vm:mx-auto vm:max-w-7xl vm:px-4 vm:pb-24 vm:sm:px-8"><div class="vm:grid vm:gap-6 vm:md:grid-cols-2">';
	foreach ( $posts as $i => $p ) {
		$h .= vm_post_card( $p, 0 === $i );
	}
	return $h . '</div></section>' . $cta;
}

/** Single post. $h2s: [[id, label], …] from the content's h2 anchors. */
function vm_blog_single_html( $p, $h2s, $cta ) {
	$index = $h2s;
	if ( false !== strpos( $p['content'], 'id="faq"' ) ) {
		$index[] = array( 'faq', 'Frequently asked questions' );
	}
	$h  = '<article><header class="vm:grain vm:relative vm:overflow-hidden vm:pb-12 vm:pt-36 vm:sm:pt-44"><div class="vm:dot-grid vm:pointer-events-none vm:absolute vm:inset-0 vm:[mask-image:radial-gradient(ellipse_at_top,black_20%,transparent_70%)]"></div>';
	$h .= '<div class="vm:relative vm:mx-auto vm:max-w-6xl vm:px-4 vm:sm:px-8"><nav aria-label="Breadcrumb" class="vm:text-sm vm:text-ink-mute"><a href="' . esc_url( $p['index_url'] ) . '" class="vm:inline-flex vm:items-center vm:gap-1.5 vm:hover:text-ink">' . vm_icon( 'arrow-left', 'vm:h-4 vm:w-4' ) . 'All articles</a></nav>';
	$h .= '<div class="vm:mt-6 vm:flex vm:flex-wrap vm:items-center vm:gap-3">' . vm_category_pill( $p['category'] ) . '</div>';
	$h .= '<h1 class="vm:display vm:mt-5 vm:max-w-4xl vm:text-balance vm:text-[2.6rem] vm:leading-[1.05] vm:sm:text-6xl">' . esc_html( $p['title'] ) . '</h1>';
	$h .= '<p class="vm:mt-6 vm:flex vm:flex-wrap vm:items-center vm:gap-x-4 vm:gap-y-1 vm:text-sm vm:text-ink-mute"><span>By VuloLabs</span><span>Published <time datetime="' . esc_attr( $p['published'] ) . '">' . esc_html( vm_fmt_date( $p['published'] ) ) . '</time></span>';
	if ( $p['updated'] && $p['updated'] !== $p['published'] ) {
		$h .= '<span>Updated <time datetime="' . esc_attr( $p['updated'] ) . '">' . esc_html( vm_fmt_date( $p['updated'] ) ) . '</time></span>';
	}
	$h .= '<span class="vm:inline-flex vm:items-center vm:gap-1.5">' . vm_icon( 'clock', 'vm:h-3.5 vm:w-3.5' ) . (int) $p['minutes'] . ' min read</span></p></div></header>';
	$h .= '<div class="vm:mx-auto vm:grid vm:max-w-6xl vm:gap-x-14 vm:px-4 vm:pb-20 vm:sm:px-8 vm:lg:grid-cols-[15rem_minmax(0,1fr)]"><aside class="vm:hidden vm:lg:block">' . vm_part( 'side-index', array( 'items' => $index ) ) . '</aside>';
	$h .= '<div class="vm:min-w-0 vm:max-w-[52rem]">';
	if ( $p['answer'] ) {
		$h .= '<section aria-label="The short answer" class="vm:rounded-[1.6rem] vm:border vm:border-line vm:bg-surface vm:p-7 vm:shadow-[0_20px_50px_-35px_rgba(19,20,27,0.35)] vm:sm:p-8"><span class="vm:eyebrow vm:inline-flex vm:items-center vm:gap-2 vm:rounded-full vm:border vm:border-line vm:bg-surface/80 vm:px-3 vm:py-1.5 vm:text-ink-soft vm:backdrop-blur"><span class="vm:relative vm:h-1.5 vm:w-1.5 vm:rounded-full vm:bg-green"><span class="vm:absolute vm:inset-0 vm:animate-ping vm:rounded-full vm:bg-green vm:opacity-60"></span></span>The short answer</span><p class="vm:mt-4 vm:text-pretty vm:text-[1.12rem] vm:leading-relaxed vm:text-ink">' . vm_rich( $p['answer'] ) . '</p></section>';
	}
	if ( count( $h2s ) > 2 ) {
		$h .= '<nav aria-label="In this article" class="vm:mt-8 vm:rounded-2xl vm:border vm:border-line vm:p-6 vm:lg:hidden"><p class="vm:eyebrow vm:text-ink-mute">In this article</p><ol class="vm:mt-3 vm:space-y-2 vm:text-[0.97rem]">';
		foreach ( $index as $i ) {
			$h .= '<li><a href="#' . esc_attr( $i[0] ) . '" class="vm:link-draw vm:text-ink-soft vm:hover:text-ink">' . esc_html( $i[1] ) . '</a></li>';
		}
		$h .= '</ol></nav>';
	}
	$h .= '<div class="vm-post-body">' . $p['content'] . '</div></div></div></article>';
	if ( ! empty( $p['related'] ) ) {
		$h .= '<section class="vm:mx-auto vm:max-w-7xl vm:px-4 vm:pb-24 vm:sm:px-8"><h2 class="vm:display vm:text-[2rem] vm:sm:text-[2.4rem]">Keep reading</h2><div class="vm:mt-8 vm:grid vm:gap-6 vm:md:grid-cols-3">';
		foreach ( $p['related'] as $r ) {
			$h .= vm_post_card( $r );
		}
		$h .= '</div></section>';
	}
	return $h . $cta;
}

/** [[id, label]] for every h2 with an id in the rendered content (the FAQ heading is added separately). */
function vm_h2_index( $html ) {
	$out = array();
	if ( preg_match_all( '#<h2[^>]*\sid="([^"]+)"[^>]*>(.*?)</h2>#s', $html, $m, PREG_SET_ORDER ) ) {
		foreach ( $m as $x ) {
			if ( 'faq' !== $x[1] ) {
				$out[] = array( $x[1], trim( html_entity_decode( wp_strip_all_tags( $x[2] ) ) ) );
			}
		}
	}
	return $out;
}

/** A WP_Post as the array above. */
function vm_post_array( $post, $with_related = false ) {
	$post = get_post( $post );
	$cats = get_the_category( $post->ID );
	$p    = array(
		'title'       => '' !== trim( get_the_title( $post ) ) ? get_the_title( $post ) : __( '(Untitled post)', 'vulomail' ),
		'url'         => get_permalink( $post ),
		'index_url'   => get_permalink( (int) get_option( 'page_for_posts' ) ) ?: home_url( '/' ),
		'category'    => $cats && 'uncategorized' !== $cats[0]->slug ? $cats[0]->name : '',
		'published'   => get_the_date( 'Y-m-d', $post ),
		'updated'     => get_the_modified_date( 'Y-m-d', $post ),
		'answer'      => $post->post_excerpt,
		'description' => (string) get_post_meta( $post->ID, '_vm_description', true ) ?: wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 ),
		'minutes'     => vm_read_minutes( $post ),
		'content'     => '',
		'related'     => array(),
	);
	if ( $with_related ) {
		$slugs = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $post->ID, '_vm_related', true ) ) ) );
		$rel   = $slugs ? get_posts( array( 'post_name__in' => $slugs, 'numberposts' => 3, 'orderby' => 'post_name__in' ) ) : get_posts( array( 'category__in' => wp_list_pluck( $cats, 'term_id' ), 'post__not_in' => array( $post->ID ), 'numberposts' => 3 ) );
		foreach ( $rel as $r ) {
			$p['related'][] = vm_post_array( $r );
		}
	}
	return $p;
}
