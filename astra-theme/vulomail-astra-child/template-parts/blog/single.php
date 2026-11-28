<?php
/**
 * Single post, like the original /blog/[slug]: breadcrumb, category, title, meta, "On this page",
 * "The short answer" (the post excerpt), the Kadence body, FAQ, "Keep reading" and the banner.
 * The byline and Article schema show the post's real author.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) {
	the_post();

	// Real post author.
	$vm_author_id   = (int) get_the_author_meta( 'ID' );
	$vm_author_name = get_the_author();
	$vm_author_url  = get_author_posts_url( $vm_author_id );

	$p            = vm_post_array( get_the_ID(), true );
	$p['content'] = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals

	// Pass the author to the helper in case it reads one of these keys.
	$p['author']      = $vm_author_name;
	$p['author_name'] = $vm_author_name;
	$p['author_url']  = $vm_author_url;

	$cta  = vm_render_pattern( 'blog-cta' );
	$html = vm_blog_single_html( $p, vm_h2_index( $p['content'] ), $cta );

	// Swap any fixed team byline for the real, linked author.
	$vm_author_link = '<a href="' . esc_url( $vm_author_url ) . '" rel="author">' . esc_html( $vm_author_name ) . '</a>';
	$html           = preg_replace(
		'/\bBy\s+(?:the\s+)?(?:VuloMail|VuloLabs|Vulo\s*Mail)(?:\s+team)?\b/i',
		'By ' . $vm_author_link,
		$html
	);

	// Make any Article schema name the real author.
	$html = preg_replace_callback(
		'#<script type="application/ld\+json">(.*?)</script>#is',
		function ( $m ) use ( $vm_author_name, $vm_author_url ) {
			$data = json_decode( $m[1], true );
			if ( ! is_array( $data ) ) {
				return $m[0];
			}
			$fix = function ( &$node ) use ( &$fix, $vm_author_name, $vm_author_url ) {
				if ( ! is_array( $node ) ) {
					return;
				}
				$type = isset( $node['@type'] ) ? (array) $node['@type'] : array();
				if ( array_intersect( $type, array( 'Article', 'BlogPosting', 'NewsArticle' ) ) ) {
					$node['author'] = array( '@type' => 'Person', 'name' => $vm_author_name, 'url' => $vm_author_url );
					$node['dateModified'] = get_the_modified_date( 'c' );
				}
				foreach ( $node as &$child ) {
					$fix( $child );
				}
			};
			$fix( $data );
			return '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
		},
		$html
	);

	echo '<main id="content" class="vm-main vm:relative">';
	echo $html; // phpcs:ignore
	echo '</main>';
}
get_footer();
