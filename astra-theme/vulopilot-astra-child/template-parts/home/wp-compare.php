<?php
/**
 * Home: "One plugin where you might need five." – comparison table with other WordPress plugins.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = 'WordPress, compared';
$title   = 'One plugin where you might <span class="text-sage">need five.</span>';
$intro   = 'Popular WordPress plugins are excellent at one job. VuloPilot looks across the whole site and tells you what to do next.';
$corner  = 'Core focus';
$rows    = array( 'On-page SEO', 'AI search (GEO / AEO)', 'Speed & Core Web Vitals', 'Security', 'Backups & site health', 'Accessibility', 'WooCommerce checks', 'Site-wide scoring' );

// name, kind, rows it focuses on (index in $rows), link text, link URL, highlight (us)
$products = array(
	array( 'VuloPilot', 'Site-wide growth OS', array( 0, 1, 2, 3, 4, 5, 6, 7 ), 'See plans', '/pricing/', true ),
	array( 'Yoast SEO', 'On-page SEO', array( 0 ), 'vs Yoast', '/compare/vulopilot-vs-yoast-seo/', false ),
	array( 'Rank Math', 'On-page SEO', array( 0 ), 'vs Rank Math', '/compare/vulopilot-vs-rank-math/', false ),
	array( 'All in One SEO', 'On-page SEO', array( 0 ), 'vs AIOSEO', '/compare/vulopilot-vs-all-in-one-seo/', false ),
	array( 'SEOPress', 'On-page SEO', array( 0 ), 'vs SEOPress', '/compare/vulopilot-vs-seopress/', false ),
	array( 'The SEO Framework', 'On-page SEO', array( 0 ), 'vs SEO Framework', '/compare/vulopilot-vs-the-seo-framework/', false ),
	array( 'WP Rocket', 'Caching & speed', array( 2 ), 'vs WP Rocket', '/compare/vulopilot-vs-wp-rocket/', false ),
	array( 'Wordfence', 'Security', array( 3 ), 'vs Wordfence', '/compare/vulopilot-vs-wordfence/', false ),
	array( 'Jetpack', 'Multi-purpose suite', array( 2, 3, 4 ), 'vs Jetpack', '/compare/vulopilot-vs-jetpack/', false ),
);
$legend = array(
	'yes'  => 'Core focus of the product',
	'no'   => 'Not its core focus (it may still offer extras)',
	'note' => 'Based on each product’s main purpose, not an exhaustive feature list. Check each vendor for current features.',
);
$outro = 'They can work side by side. Keep one plugin in charge of meta tags and sitemaps, and let VuloPilot audit, prioritize and verify. <a href="' . esc_url( vp_url( '/compare/' ) ) . '" class="font-semibold text-sage hover:underline">See all comparisons</a>';
?>
<section id="compare" class="px-5 py-16">
	<div class="mx-auto max-w-6xl">
		<div class="mx-auto max-w-3xl text-center" <?php vp_reveal(); ?>>
			<?php vp_eyebrow( $eyebrow ); ?>
			<h2 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-6xl"><?php echo wp_kses_post( $title ); ?></h2>
			<p class="mt-4 text-lg text-muted"><?php echo esc_html( $intro ); ?></p>
		</div>

		<div class="mt-10" <?php vp_reveal(); ?>>
			<div class="card overflow-x-auto">
				<table class="w-full min-w-[860px] border-collapse text-sm" data-vp="colhover">
					<thead>
						<tr>
							<th class="sticky left-0 z-10 w-[210px] bg-surface p-4 text-left text-xs font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $corner ); ?></th>
							<?php foreach ( $products as $i => $p ) : ?>
								<th class="p-3 text-center align-bottom transition-colors <?php echo $p[5] ? 'bg-sage text-white' : ''; ?>" data-col="<?php echo (int) $i; ?>" <?php echo $p[5] ? '' : 'data-hl="bg-sage-soft"'; ?>>
									<div class="text-[13px] font-semibold leading-tight <?php echo $p[5] ? '' : 'text-ink'; ?>"><?php echo esc_html( $p[0] ); ?></div>
									<div class="mt-0.5 text-[10px] font-medium uppercase tracking-wide <?php echo $p[5] ? 'text-white/70' : 'text-muted'; ?>"><?php echo esc_html( $p[1] ); ?></div>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $rows as $o => $row ) : ?>
							<tr class="border-t border-line">
								<td class="sticky left-0 z-10 bg-surface px-4 py-3 font-medium"><?php echo esc_html( $row ); ?></td>
								<?php foreach ( $products as $i => $p ) : $yes = in_array( $o, $p[2], true ); ?>
									<td class="px-3 py-3 text-center transition-colors <?php echo $p[5] ? 'bg-sage-soft/60' : ''; ?>" data-col="<?php echo (int) $i; ?>" <?php echo $p[5] ? '' : 'data-hl="bg-sage-soft/40"'; ?>>
										<span class="inline-block h-3.5 w-3.5 rounded-full <?php echo $yes ? ( $p[5] ? 'bg-sage ring-4 ring-sage/15' : 'bg-ink' ) : 'border border-line bg-transparent'; ?>" aria-hidden="true" <?php vp_m( 's:0 e:spring t:.5 d:' . round( 0.15 + $o * 0.04 + $i * 0.03, 2 ) ); ?>></span>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
						<tr class="border-t border-line">
							<td class="sticky left-0 z-10 bg-surface px-4 py-3"></td>
							<?php foreach ( $products as $p ) : ?>
								<td class="px-2 py-3 text-center <?php echo $p[5] ? 'bg-sage-soft/60' : ''; ?>"><a href="<?php echo esc_url( vp_url( $p[4] ) ); ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-sage hover:underline"><?php echo esc_html( $p[3] ); ?> <?php vp_i( 'arrow-right', 11 ); ?></a></td>
							<?php endforeach; ?>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 px-1 text-xs text-muted">
				<span class="flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-ink"></span><?php echo esc_html( $legend['yes'] ); ?></span>
				<span class="flex items-center gap-2"><span class="h-3 w-3 rounded-full border border-line"></span><?php echo esc_html( $legend['no'] ); ?></span>
				<span class="sm:ml-auto"><?php echo esc_html( $legend['note'] ); ?></span>
			</div>
		</div>

		<div class="mt-8 text-center" <?php vp_reveal(); ?>>
			<p class="text-muted"><?php echo wp_kses_post( $outro ); ?></p>
		</div>
	</div>
</section>
