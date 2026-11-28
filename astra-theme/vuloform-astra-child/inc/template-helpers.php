<?php
/**
 * Pieces used by several templates (port of components/CompareViews.tsx + GetItBanner.tsx).
 *
 *   vf_cell()            one comparison cell (✓ / $ Paid / + Add-on / –)
 *   vf_feature_table()   the big "Feature by feature" table
 *   vf_cost_chart()      the "What it costs" bar chart
 *   vf_get_it_banner()   the "Try VuloForm free" band
 *   vf_read_minutes()    reading time of a post
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** One comparison cell. $c = [ 'k' => yes|paid|addon|no, 'note' => '…' ]. */
function vf_cell( $c ) {
	switch ( $c['k'] ) {
		case 'yes':
			$icon = '<span class="grid h-6 w-6 place-items-center rounded-full bg-mint text-xs font-bold text-white" aria-label="Included free">✓</span>';
			break;
		case 'paid':
			$icon = '<span class="rounded-md bg-butter-soft px-1.5 py-0.5 text-[11px] font-bold text-[#8a6a00] ring-1 ring-[#f0dc95]" aria-label="Paid plan">$ Paid</span>';
			break;
		case 'addon':
			$icon = '<span class="rounded-md bg-peach-soft px-1.5 py-0.5 text-[11px] font-bold text-[#a84d24]" aria-label="Add-on">+ Add-on</span>';
			break;
		default:
			$icon = '<span class="grid h-6 w-6 place-items-center rounded-full bg-line text-xs font-bold text-ink-soft" aria-label="Not included">–</span>';
	}
	$note = empty( $c['note'] ) ? '' : '<span class="max-w-[118px] text-[11px] leading-tight text-ink-soft">' . esc_html( $c['note'] ) . '</span>';
	return '<div class="flex flex-col items-center gap-1 text-center">' . $icon . $note . '</div>';
}

/** Legend under the table. */
function vf_table_legend() {
	return '<div class="flex flex-wrap items-center gap-x-6 gap-y-2 border-t border-line px-5 py-4 text-xs text-ink-soft">'
		. '<span class="flex items-center gap-2"><span class="grid h-4 w-4 place-items-center rounded-full bg-mint text-[9px] text-white">✓</span> In the free version</span>'
		. '<span class="flex items-center gap-2"><span class="rounded bg-butter-soft px-1 font-bold text-[#8a6a00]">$</span> Paid plan (cheapest named)</span>'
		. '<span class="flex items-center gap-2"><span class="rounded bg-peach-soft px-1 font-bold text-[#a84d24]">+</span> Paid add-on</span>'
		. '<span class="flex items-center gap-2"><span class="grid h-4 w-4 place-items-center rounded-full bg-line text-[9px]">–</span> Not included</span>'
		. '<span class="sm:ml-auto">Scroll sideways on small screens →</span></div>';
}

/**
 * Feature table. $ids limits the columns, e.g. array( 'vulo', 'wpf' ) on a head-to-head page.
 */
function vf_feature_table( $ids = null ) {
	$data = vf_compare();
	$cols = array();
	foreach ( $data['plugins'] as $i => $p ) {
		if ( ! $ids || in_array( $p['id'], $ids, true ) ) {
			$cols[] = array( $p, $i );
		}
	}
	$last = count( $data['rows'] ) - 1;
	ob_start();
	?>
	<div class="overflow-hidden rounded-[28px] border border-line bg-white">
		<div class="overflow-x-auto" data-lenis-prevent>
			<table class="vf-table w-full border-collapse text-sm <?php echo count( $cols ) <= 2 ? 'min-w-[560px]' : 'min-w-[1040px]'; ?>">
				<caption class="sr-only">Feature comparison of WordPress form plugins, free versions</caption>
				<thead>
					<tr>
						<th scope="col" class="sticky left-0 z-10 w-[220px] bg-white p-5 text-left align-bottom text-xs font-semibold tracking-wider text-ink-soft uppercase">Free version includes</th>
						<?php foreach ( $cols as $c ) : $vulo = 'vulo' === $c[0]['id']; ?>
						<th scope="col" class="p-4 align-bottom <?php echo $vulo ? 'rounded-t-2xl bg-sky-soft' : ''; ?>">
							<span class="block text-[15px] font-bold <?php echo $vulo ? 'text-navy' : 'text-ink'; ?>"><?php echo esc_html( $c[0]['name'] ); ?></span>
							<span class="mt-0.5 block text-xs font-normal text-ink-soft"><?php echo esc_html( $c[0]['sub'] ); ?></span>
						</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $data['rows'] as $ri => $r ) : ?>
					<tr class="group border-t border-line">
						<th scope="row" class="sticky left-0 z-10 bg-white p-5 text-left font-semibold group-hover:bg-cream">
							<?php echo esc_html( $r['label'] ); ?>
							<?php if ( ! empty( $r['hint'] ) ) : ?><span class="mt-0.5 block text-xs font-normal text-ink-soft"><?php echo esc_html( $r['hint'] ); ?></span><?php endif; ?>
						</th>
						<?php foreach ( $cols as $c ) : $vulo = 'vulo' === $c[0]['id']; ?>
						<td class="px-2 py-4 align-top transition-colors <?php echo $vulo ? 'bg-sky-soft group-hover:bg-[#dfe7ff]' : 'group-hover:bg-cream'; ?> <?php echo $ri === $last && $vulo ? 'rounded-b-2xl' : ''; ?>"><?php echo vf_cell( $r['cells'][ $c[1] ] ); // phpcs:ignore ?></td>
						<?php endforeach; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php echo vf_table_legend(); // phpcs:ignore ?>
	</div>
	<?php
	return ob_get_clean();
}

/** "Yearly price to get all five essentials" bar chart. Bars grow when scrolled into view; hover shows the plan. */
function vf_cost_chart() {
	$max = 300;
	ob_start();
	?>
	<div class="rounded-[28px] border border-line bg-white p-6 sm:p-8">
		<h3 class="text-lg font-bold">Yearly price to get all five essentials on one site</h3>
		<p class="mt-1 text-sm text-ink-soft">Conditional logic, multi-step forms, calculations, file uploads and webhooks — cheapest plan that has them all, as shown on each pricing page in October 2026. Hover a bar for the plan.</p>
		<ul class="vf-bars vf-inview mt-8 space-y-3" role="list">
			<?php foreach ( vf_compare()['costs'] as $i => $c ) :
				$price = $c['price'];
				$vulo  = 'vulo' === $c['id'];
				?>
			<li class="vf-bar-row relative grid grid-cols-[110px_1fr] items-center gap-3 sm:grid-cols-[150px_1fr]" tabindex="0" style="--i:<?php echo (int) $i; ?>">
				<span class="text-sm font-semibold <?php echo $vulo ? 'text-navy' : ''; ?>"><?php echo esc_html( $c['name'] ); ?></span>
				<div class="flex h-9 items-center gap-3">
					<?php if ( $price ) : ?>
					<span class="vf-bar h-5 flex-none rounded-r-[4px] bg-navy" style="--w:<?php echo esc_attr( round( $price / $max * 66, 2 ) ); ?>%"></span>
					<?php else : ?>
					<span class="h-5 w-1 rounded-r-[4px] <?php echo 0 === $price ? 'bg-mint' : 'bg-line'; ?>"></span>
					<?php endif; ?>
					<span class="text-sm font-bold whitespace-nowrap tabular-nums">
						<?php
						if ( null === $price ) {
							echo '<span class="font-medium text-ink-soft">Not available</span>';
						} elseif ( 0 == $price ) { // phpcs:ignore
							echo '<span class="text-[#0f7a53]">Free</span>';
						} else {
							echo esc_html( '$' . ( fmod( $price, 1 ) ? number_format( $price, 2 ) : $price ) . '/yr' );
						}
						?>
					</span>
				</div>
				<div role="tooltip" class="vf-tip pointer-events-none absolute top-full left-[110px] z-20 mt-1 max-w-xs rounded-xl border border-line bg-white p-3 text-xs shadow-xl sm:left-[150px]">
					<p class="font-bold"><?php echo esc_html( $c['name'] . ' · ' . $c['plan'] ); ?></p>
					<p class="mt-1 text-ink-soft"><?php echo esc_html( $c['detail'] ); ?></p>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
		<p class="mt-6 text-xs text-ink-soft">Several vendors show a first-year discount; where the page states a renewal price it's in the tooltip. Prices in USD, excluding tax.</p>
	</div>
	<?php
	return ob_get_clean();
}

/** The "Try VuloForm free" band at the end of inner pages. */
function vf_get_it_banner( $title = 'Try VuloForm free' ) {
	ob_start();
	?>
	<section class="vf-reveal mx-auto mt-20 max-w-6xl px-4 sm:px-6">
		<div class="rounded-[32px] bg-gradient-to-br from-sky-soft via-white to-mint-soft px-6 py-14 text-center ring-1 ring-line sm:py-16">
			<h2 class="mx-auto max-w-2xl text-3xl leading-tight font-bold tracking-tight sm:text-4xl"><?php echo esc_html( $title ); ?></h2>
			<p class="mx-auto mt-4 max-w-lg text-ink-soft">Install from Plugins › Add New, pick a template, hit Publish. No account, no subscription, no limits.</p>
			<div class="mt-8 flex flex-wrap justify-center gap-3">
				<a href="<?php echo esc_url( vf_download_url() ); ?>" class="rounded-full bg-navy px-7 py-3.5 font-semibold text-white transition-all hover:-translate-y-0.5 hover:bg-navy-light">Download VuloForm — free</a>
				<a href="<?php echo esc_url( vf_docs_url() ); ?>" class="rounded-full border border-line bg-white px-7 py-3.5 font-semibold text-ink transition-colors hover:border-navy/30">Read the docs</a>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** Reading time in minutes (220 words a minute, like the original). */
function vf_read_minutes( $post = null ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post ) ) );
	return max( 1, (int) ceil( $words / 220 ) );
}
