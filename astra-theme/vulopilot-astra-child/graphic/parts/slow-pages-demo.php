<?php
/**
 * Performance › "Which pages are slow, and why": slow pages grouped by cause, the biggest opportunity
 * and a plain-language answer from the AI speed assistant. Causes cycle on their own; click one to stop.
 * Graphic name: slow-pages-demo  ·  Shortcode: [vp_slow_pages_demo]
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'title'    => 'Slow pages, grouped by cause',
		'tested'   => 'Mobile · 37 pages tested',
		'big'      => 'Biggest opportunity',
		'big_t'    => 'Resize and compress 18 hero images',
		'big_s'    => 'Saves about 1.8 s on 14 pages',
		'big_btn'  => 'Fix with AI',
		'pages'    => 'pages',
		'ask'      => 'Why is /shop/ slow?',
		'answer'   => 'Your product photos are about three times larger than they appear on screen. Compressing them should save around 1.2 seconds on phones.',
		'seconds'  => 's',
	),
	(array) ( $args['text'] ?? array() )
);

// icon, cause, page count, pages: [ url, load time in seconds, reason ].
$causes = $args['causes'] ?? array(
	array( 'image', 'Images', 14, array( array( '/', 4.8, 'hero.jpg is 2.4 MB' ), array( '/shop/', 4.1, '32 product photos, not resized' ), array( '/blog/summer-menu/', 3.6, 'Images load before they are seen' ) ) ),
	array( 'server', 'Server', 6, array( array( '/checkout/', 3.9, 'Server took 1.6 s to answer' ), array( '/my-account/', 3.2, 'No page cache for logged-in pages' ), array( '/search/', 2.9, 'One slow database query' ) ) ),
	array( 'code', 'Code', 5, array( array( '/contact/', 3.4, '3 scripts block the page' ), array( '/', 3.1, '210 KB of unused CSS' ), array( '/events/', 2.7, 'Slider script loads on every page' ) ) ),
	array( 'layers', 'Caching', 9, array( array( '/about/', 3.0, 'Browser caching is off' ), array( '/services/', 2.8, 'Page cache cleared on every update' ), array( '/team/', 2.6, 'Images are not cached' ) ) ),
	array( 'heading', 'Fonts', 3, array( array( '/', 2.5, '4 font files, 2 weights unused' ), array( '/blog/', 2.3, 'Text hidden while fonts load' ), array( '/faq/', 2.1, 'Icon font for 3 icons' ) ) ),
);
$max = 6; // Seconds that fill a bar.
$col = function ( $s ) {
	return $s >= 4 ? 'var(--rose)' : ( $s >= 3 ? 'var(--flare)' : 'var(--lilac)' );
};
?>
<div class="card overflow-hidden" data-vpg="tabs" data-interval="3800">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'gauge', 15 ); ?></span><?php echo esc_html( $t['title'] ); ?></div>
		<span class="font-mono text-[11px] text-muted"><?php echo esc_html( $t['tested'] ); ?></span>
	</div>

	<div class="p-5">
		<div class="vpg-callout flex flex-wrap items-center gap-3 rounded-sm p-4" <?php vp_m( 'o:0 y:10 t:.5' ); ?>>
			<span class="grid h-9 w-9 shrink-0 place-items-center rounded-sm bg-surface" style="color:var(--flare)"><?php vp_i( 'target', 18 ); ?></span>
			<span class="min-w-0 flex-1">
				<span class="block text-[10px] font-semibold uppercase tracking-widest" style="color:var(--flare)"><?php echo esc_html( $t['big'] ); ?></span>
				<span class="block text-sm font-semibold"><?php echo esc_html( $t['big_t'] ); ?></span>
				<span class="block text-xs text-muted"><?php echo esc_html( $t['big_s'] ); ?></span>
			</span>
			<span class="inline-flex items-center gap-1.5 rounded-sm bg-ink px-3 py-1.5 text-xs font-semibold text-bg"><?php vp_i( 'sparkles', 12 ); ?><?php echo esc_html( $t['big_btn'] ); ?></span>
		</div>

		<div class="vpg-tabs mt-5" role="tablist">
			<?php foreach ( $causes as $i => $c ) : ?>
				<button type="button" role="tab" class="vpg-tab<?php echo 0 === $i ? ' is-on' : ''; ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" data-vpg-tab="<?php echo (int) $i; ?>">
					<?php vp_i( $c[0], 14 ); ?><span><?php echo esc_html( $c[1] ); ?></span><span class="vpg-tab-count"><?php echo (int) $c[2]; ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="vpg-panels mt-3">
			<?php foreach ( $causes as $i => $c ) : ?>
				<ul class="vpg-panel space-y-2<?php echo 0 === $i ? ' is-on' : ''; ?>" data-vpg-panel="<?php echo (int) $i; ?>">
					<?php foreach ( $c[3] as $k => $p ) : $w = min( 100, round( $p[1] / $max * 100 ) ); ?>
						<li class="rounded-sm border border-line bg-bg/40 px-3.5 py-2.5" <?php vp_m( 'o:0 x:-8 t:.4 d:' . ( $k * 0.08 ) ); ?>>
							<div class="flex items-center gap-3">
								<span class="min-w-0 flex-1 truncate font-mono text-xs"><?php echo esc_html( $p[0] ); ?></span>
								<span class="text-sm font-semibold tabular-nums" style="color:<?php echo esc_attr( $col( $p[1] ) ); ?>"><?php echo esc_html( number_format_i18n( $p[1], 1 ) . ' ' . $t['seconds'] ); ?></span>
							</div>
							<div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-line"><div class="h-full rounded-full" <?php vp_m( 'W:' . $w . '% t:.8 e:ease d:' . ( 0.1 + $k * 0.08 ), 'background:' . $col( $p[1] ) ); ?>></div></div>
							<div class="mt-1 text-xs text-muted"><?php echo esc_html( $p[2] ); ?></div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endforeach; ?>
		</div>

		<div class="mt-5 flex gap-3 border-t border-line pt-4">
			<span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-ink text-bg"><?php vp_i( 'bot', 15 ); ?></span>
			<div class="min-w-0 flex-1 text-sm">
				<div class="font-semibold"><?php echo esc_html( $t['ask'] ); ?></div>
				<p class="mt-1 text-muted" style="min-height:3em"><span data-fx="type" data-step="2" data-ms="22"><?php echo esc_html( $t['answer'] ); ?></span></p>
			</div>
		</div>
	</div>
</div>
