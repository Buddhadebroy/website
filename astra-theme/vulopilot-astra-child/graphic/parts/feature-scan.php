<?php
/**
 * Features hub hero graphic: one scan running down all eight areas, each row linking to its page.
 * Ported from components/FeatureScan.tsx. The counts are illustrative, not a real site's results.
 * Graphic name: feature-scan  ·  Shortcode: [vp_feature_scan]
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$site = $args['site'] ?? 'yourwebsite.com';

// slug, icon, title, question, fixes found (0 = "Looks good").
$areas = $args['areas'] ?? array(
	array( 'search-visibility', 'search', 'Search Visibility', 'Can people find you?', 3 ),
	array( 'ai-search', 'radar', 'AI Search', 'Does AI know your business?', 2 ),
	array( 'website-health', 'heart-pulse', 'Website Health', 'Is my website okay?', 0 ),
	array( 'performance', 'gauge', 'Performance', 'How slow, really?', 4 ),
	array( 'security', 'shield-check', 'Security', 'What’s hiding underneath?', 1 ),
	array( 'content', 'pen-line', 'Content', 'Are you answering them?', 2 ),
	array( 'woocommerce', 'shopping-bag', 'WooCommerce', 'Is my store doing okay?', 0 ),
	array( 'automation', 'calendar-clock', 'Automation', 'Did my fix work?', 1 ),
);
$t = array_merge(
	array(
		'scanning' => 'Scanning ' . $site,
		'done'     => 'Scan complete',
		'areas'    => 'areas',
		'fix'      => '%d to fix',
		'good'     => 'Looks good',
		'fixes'    => 'fixes',
		'foot'     => 'in one list, most serious first',
		'example'  => 'Example',
	),
	(array) ( $args['text'] ?? array() )
);
$count = count( $areas );
?>
<div class="card overflow-hidden vpg-scan" data-vpg="scan" data-step="450" data-hold="8" data-scanning="<?php echo esc_attr( $t['scanning'] ); ?>" data-done="<?php echo esc_attr( $t['done'] ); ?>">
	<div class="flex items-center justify-between gap-3 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-medium">
			<span class="vpg-dot" data-dot></span>
			<span data-status><?php echo esc_html( $t['done'] ); ?></span>
		</div>
		<span class="font-mono text-xs text-muted"><span data-shown><?php echo (int) $count; ?></span>/<?php echo (int) $count; ?> <?php echo esc_html( $t['areas'] ); ?></span>
	</div>
	<div class="vpg-track"><div class="vpg-track-fill" data-bar style="width:100%"></div></div>
	<ul class="vpg-list">
		<?php foreach ( $areas as $i => $a ) : $n = (int) $a[4]; ?>
			<li class="vpg-scan-row is-ready" data-row data-n="<?php echo (int) $n; ?>">
				<a href="<?php echo esc_url( vp_url( '/features/' . $a[0] . '/' ) ); ?>" class="group flex items-center gap-3.5 px-5 py-3 transition hover:bg-bg">
					<span class="vpg-scan-ic grid h-8 w-8 shrink-0 place-items-center rounded-sm"><?php vp_i( $a[1], 16 ); ?></span>
					<span class="min-w-0 flex-1">
						<span class="block truncate text-[15px] font-medium group-hover:text-sage"><?php echo esc_html( $a[2] ); ?></span>
						<span class="block truncate text-xs text-muted"><?php echo esc_html( $a[3] ); ?></span>
					</span>
					<span class="vpg-scan-wait" aria-hidden="true"></span>
					<span class="vpg-scan-tag <?php echo $n ? 'is-fix' : 'is-good'; ?>"><?php echo $n ? esc_html( sprintf( $t['fix'], $n ) ) : vp_icon( 'check', 12 ) . esc_html( $t['good'] ); // phpcs:ignore ?></span>
					<?php vp_i( 'arrow-right', 14, 'vpg-scan-arrow shrink-0 text-muted' ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<div class="flex items-center justify-between gap-3 border-t border-line bg-bg px-5 py-3.5 text-sm">
		<span><span class="font-semibold"><span data-total><?php echo (int) array_sum( array_column( $areas, 4 ) ); ?></span> <?php echo esc_html( $t['fixes'] ); ?></span> <span class="text-muted"><?php echo esc_html( $t['foot'] ); ?></span></span>
		<span class="text-xs text-muted"><?php echo esc_html( $t['example'] ); ?></span>
	</div>
</div>
