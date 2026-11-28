<?php
/** Test server: blog index / single post from data/blog.json + data/blog/<slug>.html (originally the build-blog.py output; posts are now written directly as Kadence blocks). */
function vmt_minutes( $p ) {
	$parts = array( $p['answer'] );
	foreach ( $p['blocks'] as $b ) {
		if ( in_array( $b['t'], array( 'p', 'h2', 'h3', 'code' ), true ) ) { $parts[] = $b['x']; }
		elseif ( in_array( $b['t'], array( 'ul', 'ol' ), true ) ) { $parts = array_merge( $parts, $b['items'] ); }
		elseif ( 'table' === $b['t'] ) { $parts[] = implode( ' ', $b['head'] ); foreach ( $b['rows'] as $r ) { $parts[] = implode( ' ', $r ); } }
		elseif ( 'callout' === $b['t'] ) { $parts[] = $b['title']; $parts[] = $b['x']; }
	}
	foreach ( $p['faqs'] as $f ) { $parts[] = $f['q']; $parts[] = $f['a']; }
	$t = preg_replace( array( '/\[([^\]]+)\]\([^)]+\)/', '/\*\*/', '/`/' ), array( '$1', '', '' ), implode( ' ', $parts ) );
	return max( 1, (int) round( count( preg_split( '/\s+/', trim( $t ) ) ) / 220 ) );
}
function vmt_arr( $p ) {
	return array( 'title' => $p['title'], 'url' => '/blog/' . $p['slug'], 'index_url' => '/blog', 'category' => $p['category'], 'published' => $p['published'], 'updated' => $p['updated'], 'answer' => $p['answer'], 'description' => $p['description'], 'minutes' => vmt_minutes( $p ), 'content' => '', 'related' => array() );
}
$posts = vm_data( 'blog' );
usort( $posts, function ( $a, $b ) { return strcmp( $b['published'], $a['published'] ); } );
if ( preg_match( '#^/blog/([a-z0-9-]+)#', $uri, $m ) ) {
	foreach ( $posts as $p ) {
		if ( $p['slug'] === $m[1] ) {
			$a = vmt_arr( $p );
			$a['content'] = vmt_do_blocks( file_get_contents( VM_DIR . 'data/blog/' . $p['slug'] . '.html' ) );
			foreach ( $p['related'] as $r ) { foreach ( $posts as $q ) { if ( $q['slug'] === $r ) { $a['related'][] = vmt_arr( $q ); } } }
			$GLOBALS['vmt_template'] = vm_blog_single_html( $a, vm_h2_index( $a['content'] ), vmt_do_blocks( vmt_pattern( 'blog-cta' ) ) );
		}
	}
} else {
	$GLOBALS['vmt_template'] = vm_blog_index_html( array_map( 'vmt_arr', $posts ), vmt_do_blocks( vmt_pattern( 'blog-hero' ) ), vmt_do_blocks( vmt_pattern( 'shared-cta' ) ) );
}
