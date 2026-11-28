<?php
/**
 * Blog post list: the newest post as a big gradient card, the rest as cards in two columns, then page numbers.
 * On the Posts page it lists that page's posts; anywhere else it runs its own query (newest first).
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$vf_main = is_home();
$q       = $vf_main ? $GLOBALS['wp_query'] : new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => (int) get_option( 'posts_per_page', 10 ),
		'paged'               => max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) ),
		'ignore_sticky_posts' => true,
	)
);
$first = (int) ( $q->get( 'paged' ) ?: 1 ) <= 1;

if ( ! $q->have_posts() ) :
	echo '<p class="mt-12 text-ink-soft">No posts yet. Appearance → VuloForm → <b>Import the 10 blog posts</b>.</p>';
	return;
endif;

if ( $first ) :
	$q->the_post();
	$cat = get_the_category();
	?>
<a href="<?php the_permalink(); ?>" class="vf-reveal group mt-12 block rounded-[32px] bg-gradient-to-br from-sky-soft via-white to-mint-soft p-8 text-ink ring-1 ring-line transition-all hover:-translate-y-0.5 hover:shadow-xl sm:p-12">
	<span class="text-xs font-semibold tracking-wide text-navy">Latest<?php echo $cat ? ' · ' . esc_html( $cat[0]->name ) : ''; ?></span>
	<h2 class="mt-3 max-w-3xl text-3xl leading-tight font-bold tracking-tight sm:text-4xl"><?php the_title(); ?></h2>
	<p class="mt-4 max-w-2xl text-ink-soft"><?php echo esc_html( get_the_excerpt() ); ?></p>
	<p class="mt-5 text-sm text-ink-soft"><?php echo esc_html( get_the_date( 'j F Y' ) . ' · ' . vf_read_minutes() . ' min read' ); ?><span class="ml-3 font-semibold text-navy">Read →</span></p>
</a>
<?php endif; ?>

<ul class="vf-stagger mt-6 grid gap-5 md:grid-cols-2">
	<?php
	while ( $q->have_posts() ) :
		$q->the_post();
		$cat = get_the_category();
		?>
	<li>
		<a href="<?php the_permalink(); ?>" class="group flex h-full flex-col rounded-[28px] border border-line bg-white p-7 text-ink transition-all hover:-translate-y-0.5 hover:border-navy/30 hover:shadow-lg">
			<?php if ( $cat ) : ?><span class="text-xs font-semibold tracking-wide text-[#0f7a53]"><?php echo esc_html( $cat[0]->name ); ?></span><?php endif; ?>
			<h2 class="mt-2 text-xl leading-snug font-bold"><?php the_title(); ?></h2>
			<p class="mt-3 text-[15px] leading-relaxed text-ink-soft"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<p class="mt-auto pt-5 text-sm text-ink-soft"><?php echo esc_html( get_the_date( 'j F Y' ) . ' · ' . vf_read_minutes() . ' min read' ); ?></p>
		</a>
	</li>
	<?php endwhile; ?>
</ul>

<div class="vf-pager mt-10 flex justify-center gap-2"><?php echo paginate_links( array( 'type' => 'plain', 'total' => $q->max_num_pages ) ); // phpcs:ignore ?></div>
<?php
if ( ! $vf_main ) {
	wp_reset_postdata();
}
