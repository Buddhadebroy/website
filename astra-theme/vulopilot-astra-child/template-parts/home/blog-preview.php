
<?php
/**
 * Home: "From the blog" – three latest WordPress posts.
 *
 * Fetches the latest published posts dynamically.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = 'From the blog';
$title   = 'Fresh ideas for a <span class="text-sage">better website.</span>';
$all     = array(
	'text' => 'View all posts',
	'url'  => '/blog/',
);
$more    = 'Read more';

// Card colours: icon + background gradient.
$tones = array(
	'blue' => array(
		'radar',
		'linear-gradient(135deg, #002991 0%, #3a5bd0 100%)',
	),
	'red'  => array(
		'heart-pulse',
		'linear-gradient(135deg, #ff5d50 0%, #f9a59d 100%)',
	),
	'ink'  => array(
		'gauge',
		'linear-gradient(135deg, #0a1230 0%, #2a3d86 100%)',
	),
);

// Fetch the three latest published WordPress posts.
$latest = get_posts(
	array(
		'numberposts'      => 3,
		'post_status'      => 'publish',
		'post_type'        => 'post',
		'orderby'          => 'date',
		'order'            => 'DESC',
		'suppress_filters' => false,
	)
);

$posts = array();
$keys  = array_keys( $tones );

foreach ( $latest as $i => $p ) {
	// Get the post category.
	$cats = get_the_category( $p->ID );

	$category = ! empty( $cats )
		? $cats[0]->name
		: 'Blog';

	// Prepare post content for reading-time calculation.
	$content = get_post_field( 'post_content', $p->ID );
	$content = strip_shortcodes( $content );
	$content = wp_strip_all_tags( $content );

	$words = str_word_count( $content );

	// Calculate estimated reading time.
	$reading_time = max(
		1,
		(int) ceil( $words / 220 )
	) . ' min read';

	// Use the manual excerpt, or fall back to post content.
	$excerpt = get_the_excerpt( $p->ID );

	if ( '' === trim( $excerpt ) ) {
		$excerpt = $content;
	}

	$excerpt = wp_trim_words( $excerpt, 22 );

	// Build the card data.
	$posts[] = array(
		get_the_title( $p->ID ),
		$excerpt,
		$category,
		$reading_time,
		get_the_date( 'M Y', $p->ID ),
		$keys[ $i % count( $keys ) ],
		get_permalink( $p->ID ),
	);
}

// Display the section only when published posts exist.
if ( ! empty( $posts ) ) :
	?>
	<section class="px-5 py-16">
		<div class="mx-auto max-w-6xl">

			<div
				class="flex flex-wrap items-end justify-between gap-4"
				<?php vp_reveal(); ?>
			>
				<div class="max-w-2xl">
					<?php vp_eyebrow( $eyebrow ); ?>

					<h2 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-5xl">
						<?php echo wp_kses_post( $title ); ?>
					</h2>
				</div>

				<a
					href="<?php echo esc_url( vp_url( $all['url'] ) ); ?>"
					class="group inline-flex items-center gap-2 rounded-sm border border-line bg-surface px-5 py-2.5 text-sm font-semibold transition hover:border-ink"
				>
					<?php echo esc_html( $all['text'] ); ?>

					<?php
					vp_i(
						'arrow-right',
						15,
						'transition group-hover:translate-x-1'
					);
					?>
				</a>
			</div>

			<div class="mt-9 grid gap-6 md:grid-cols-3">

				<?php foreach ( $posts as $i => $p ) : ?>
					<?php
					$tone = $tones[ $p[5] ] ?? $tones['blue'];
					?>

					<div <?php vp_reveal( $i * 0.1 ); ?>>

						<a
							href="<?php echo esc_url( $p[6] ); ?>"
							class="group block h-full"
						>
							<article
								class="vp-hover card flex h-full flex-col overflow-hidden"
								style="--hy:-8px"
							>

								<!-- Card visual -->
								<div
									class="relative h-48 overflow-hidden"
									style="background:<?php echo esc_attr( $tone[1] ); ?>"
								>
									<div
										class="vp-drift absolute -right-6 -top-6 h-36 w-36 rounded-full bg-white/20 blur-2xl"
										style="--dx:20px;--dy:-10px;--dd:<?php echo esc_attr( 7 + $i ); ?>s"
									></div>

									<div class="absolute inset-0 opacity-30 [background-image:radial-gradient(white_1px,transparent_1px)] [background-size:20px_20px]"></div>

									<?php
									vp_i(
										$tone[0],
										84,
										'absolute bottom-3 right-5 text-white/30 transition duration-500 group-hover:-rotate-6 group-hover:scale-110',
										1.2
									);
									?>

									<span class="absolute left-5 top-5 rounded-sm bg-white/90 px-3 py-1 text-xs font-semibold text-ink">
										<?php echo esc_html( $p[2] ); ?>
									</span>
								</div>

								<!-- Card content -->
								<div class="flex flex-1 flex-col p-6">

									<div class="text-xs text-muted">
										<?php
										echo esc_html(
											$p[4] . ' · ' . $p[3]
										);
										?>
									</div>

									<h3 class="mt-2 text-xl font-semibold leading-snug tracking-tight transition-colors group-hover:text-sage">
										<?php echo esc_html( $p[0] ); ?>
									</h3>

									<p class="mt-2 flex-1 text-muted">
										<?php echo esc_html( $p[1] ); ?>
									</p>

									<span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-sage">
										<?php echo esc_html( $more ); ?>

										<?php
										vp_i(
											'arrow-up-right',
											15,
											'transition group-hover:-translate-y-0.5 group-hover:translate-x-0.5'
										);
										?>
									</span>

								</div>

							</article>
						</a>

					</div>

				<?php endforeach; ?>

			</div>
		</div>
	</section>
	<?php
endif;
?>
