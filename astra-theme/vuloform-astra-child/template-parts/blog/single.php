<?php
/**
 * Single blog post (port of app/blog/[slug]/page.tsx + components/BlogToc.tsx).
 * The table of contents is built from the post's H2 headings (sticky on the left, a fold-out on phones).
 * "Related comparisons": custom field vf_compare = comma list of compare slugs, e.g. "contact-form-7,wpforms".
 * Byline and Article schema use the post's real author.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();

	// Content with ids on every H2, and the TOC list from the same headings.
	$toc     = array();
	$content = preg_replace_callback(
		'/<h2([^>]*)>(.*?)<\/h2>/is',
		function ( $m ) use ( &$toc ) {
			$id    = preg_match( '/id="([^"]+)"/', $m[1], $x ) ? $x[1] : vf_slug( $m[2] );
			$toc[] = array( $id, wp_strip_all_tags( $m[2] ) );
			$attrs = preg_replace( '/\sid="[^"]*"/', '', $m[1] );
			return '<h2 id="' . esc_attr( $id ) . '"' . $attrs . '>' . $m[2] . '</h2>';
		},
		apply_filters( 'the_content', get_the_content() ) // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
	);

	$toc_list = '<ol class="vf-toc space-y-1 border-l border-line">';
	foreach ( $toc as $i => $t ) {
		$toc_list .= '<li><a href="#' . esc_attr( $t[0] ) . '" class="vf-toc-link -ml-px block border-l-2 border-transparent py-1.5 pr-2 pl-4 text-sm leading-snug text-ink-soft transition-colors hover:text-ink">' . esc_html( $t[1] ) . '</a></li>';
	}
	$toc_list .= '</ol>';

	$cat     = get_the_category();
	$related = array_filter( array_map( 'vf_profile', array_map( 'trim', explode( ',', (string) get_post_meta( get_the_ID(), 'vf_compare', true ) ) ) ) );
	$more    = get_posts( array( 'numberposts' => 3, 'post__not_in' => array( get_the_ID() ) ) );

	// Real post author.
	$author_id   = (int) get_the_author_meta( 'ID' );
	$author_name = get_the_author();
	$author_url  = get_author_posts_url( $author_id );

	vf_jsonld(
		array(
			'@context'         => 'https://schema.org',
			'@type'            => 'Article',
			'headline'         => get_the_title(),
			'description'      => get_the_excerpt(),
			'datePublished'    => get_the_date( 'c' ),
			'dateModified'     => get_the_modified_date( 'c' ),
			'mainEntityOfPage' => get_permalink(),
			'author'           => array(
				'@type' => 'Person',
				'name'  => $author_name,
				'url'   => $author_url,
			),
			'publisher'        => array( '@type' => 'Organization', 'name' => 'VuloLabs' ),
		)
	);
	?>
<main id="vf-main" class="vf-template-main">
	<div class="vf-scope vf-page pt-28 sm:pt-36">
		<div class="mx-auto max-w-6xl gap-12 px-4 sm:px-6 lg:grid lg:grid-cols-[14rem_minmax(0,1fr)]">
			<?php if ( $toc ) : ?>
			<div class="hidden lg:col-start-1 lg:row-start-1 lg:block lg:pt-1">
				<nav aria-label="Table of contents" class="vf-toc-nav sticky top-28 max-h-[calc(100vh-8rem)] overflow-y-auto" data-lenis-prevent>
					<p class="mb-3 text-xs font-bold tracking-wide text-ink-soft uppercase">On this page</p>
					<?php echo $toc_list; // phpcs:ignore ?>
				</nav>
			</div>
			<?php endif; ?>

			<article class="min-w-0 lg:col-start-2 lg:row-start-1">
				<nav aria-label="Breadcrumb" class="text-sm text-ink-soft"><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="text-ink-soft hover:text-ink">Blog</a><?php echo $cat ? ' <span aria-hidden="true">/</span> ' . esc_html( $cat[0]->name ) : ''; ?></nav>
				<h1 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl"><?php the_title(); ?></h1>
				<p class="mt-5 text-sm text-ink-soft">By <a href="<?php echo esc_url( $author_url ); ?>" rel="author" class="text-ink-soft hover:text-ink"><?php echo esc_html( $author_name ); ?></a> · <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time> · <?php echo esc_html( vf_read_minutes() . ' min read' ); ?></p>

				<?php if ( $toc ) : ?>
				<details class="mt-8 rounded-2xl border border-line bg-white px-5 py-3 lg:hidden">
					<summary class="cursor-pointer text-sm font-bold">Table of contents</summary>
					<nav aria-label="Table of contents" class="mt-3"><?php echo $toc_list; // phpcs:ignore ?></nav>
				</details>
				<?php endif; ?>

				<div class="vf-prose entry-content mt-10"><?php echo $content; // phpcs:ignore ?></div>

				<?php if ( $related ) : ?>
				<aside class="mt-12" aria-label="Related comparisons">
					<h2 class="text-lg font-bold">Related comparisons</h2>
					<ul class="mt-3 flex flex-wrap gap-2">
						<?php foreach ( $related as $r ) : ?>
						<li><a href="<?php echo esc_url( home_url( '/compare/' . $r['slug'] . '/' ) ); ?>" class="inline-block rounded-full border border-line bg-white px-4 py-2 text-sm font-semibold text-ink transition-colors hover:border-navy/30 hover:bg-sky-soft">VuloForm vs <?php echo esc_html( $r['name'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</aside>
				<?php endif; ?>
			</article>
		</div>

		<?php if ( $more ) : ?>
		<section class="mx-auto mt-16 max-w-6xl px-4 sm:px-6">
			<h2 class="text-2xl font-bold">Keep reading</h2>
			<ul class="vf-stagger mt-5 grid gap-4 md:grid-cols-3">
				<?php foreach ( $more as $m ) : $mc = get_the_category( $m->ID ); ?>
				<li><a href="<?php echo esc_url( get_permalink( $m ) ); ?>" class="block h-full rounded-[24px] border border-line bg-white p-6 text-ink transition-all hover:-translate-y-0.5 hover:border-navy/30 hover:shadow-lg">
					<?php if ( $mc ) : ?><span class="text-xs font-semibold tracking-wide text-[#0f7a53] uppercase"><?php echo esc_html( $mc[0]->name ); ?></span><?php endif; ?>
					<span class="mt-2 block leading-snug font-bold"><?php echo esc_html( get_the_title( $m ) ); ?></span>
				</a></li>
				<?php endforeach; ?>
			</ul>
		</section>
		<?php endif; ?>

		<?php echo vf_get_it_banner(); // phpcs:ignore ?>
	</div>
</main>
	<?php
endwhile;
get_footer();
