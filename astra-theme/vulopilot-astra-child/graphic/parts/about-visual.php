<?php
/**
 * About hero graphic: the same site seen through three separate plugins, then through one ranked scan.
 * Flips between the two views on its own; the tabs let visitors pick one. Ported from components/AboutVisual.tsx.
 * Graphic name: about-visual  ·  Shortcode: [vp_about_visual]
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args   = isset( $args ) && is_array( $args ) ? $args : array();
$tabs   = $args['tabs'] ?? array( 'Three tools', 'One scan' );
$before = $args['before'] ?? array( // icon, plugin, count, messages
	array( 'search', 'SEO plugin', '12 warnings', array( 'Missing meta description', 'Title too long', 'Low keyword density' ) ),
	array( 'gauge', 'Caching plugin', '4 notices', array( 'Preload cache', 'Minify CSS' ) ),
	array( 'shield-check', 'Security plugin', '7 alerts', array( 'Plugin update available', 'Login attempts' ) ),
);
$after  = $args['after'] ?? array( // problem, severity, scope
	array( 'Homepage is blocked from search', 'Critical', '1 page' ),
	array( 'Outdated plugin with a known hole', 'High', 'Whole site' ),
	array( 'Hero image slows mobile by 2.1s', 'Medium', '14 pages' ),
);
$t = array_merge(
	array(
		'messages' => '23 messages.',
		'question' => 'Which one matters this week?',
		'first'    => 'Fix first',
		'rescan'   => 'After the fix, re-scan:',
		'from'     => 'Visibility 61',
		'to'       => '84',
		'foot'     => 'Example findings',
	),
	(array) ( $args['text'] ?? array() )
);
$tilt = array( -0.8, 0.6, -0.4 );
$sev  = array(
	'Critical' => 'background:var(--rose);color:#fff',
	'High'     => 'background:var(--apricot-soft);color:var(--rose)',
	'Medium'   => 'background:var(--lilac-soft);color:var(--sage)',
);
?>
<div class="card overflow-hidden" data-vpg="flip" data-interval="4200">
	<div role="tablist" class="grid grid-cols-2 border-b border-line text-sm font-medium">
		<?php foreach ( $tabs as $i => $tab ) : ?>
			<button type="button" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" class="vpg-flip-tab relative px-5 py-3.5 transition" data-flip-tab="<?php echo (int) $i; ?>"><?php echo esc_html( $tab ); ?><span class="vpg-flip-line"></span></button>
		<?php endforeach; ?>
	</div>
	<div class="relative p-5 sm:p-6" style="min-height:340px">
		<div class="vpg-flip-panel is-on" data-flip-panel="0">
			<div class="space-y-3">
				<?php foreach ( $before as $i => $b ) : ?>
					<div class="rounded-sm border border-line bg-bg p-4" style="transform:rotate(<?php echo esc_attr( $tilt[ $i % 3 ] ); ?>deg)">
						<div class="flex items-center gap-2.5"><?php vp_i( $b[0], 15, 'text-muted' ); ?><span class="text-sm font-medium"><?php echo esc_html( $b[1] ); ?></span><span class="ml-auto text-xs text-muted"><?php echo esc_html( $b[2] ); ?></span></div>
						<div class="mt-2 flex flex-wrap gap-1.5">
							<?php foreach ( $b[3] as $x ) : ?><span class="rounded-sm border border-line bg-surface px-2 py-0.5 text-xs text-muted"><?php echo esc_html( $x ); ?></span><?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<p class="mt-5 text-sm text-muted"><?php echo esc_html( $t['messages'] ); ?> <span class="font-medium text-ink"><?php echo esc_html( $t['question'] ); ?></span></p>
		</div>
		<div class="vpg-flip-panel" data-flip-panel="1">
			<div class="text-xs font-semibold uppercase tracking-widest text-sage"><?php echo esc_html( $t['first'] ); ?></div>
			<ol class="vpg-ranked mt-3">
				<?php foreach ( $after as $i => $a ) : ?>
					<li class="flex items-center gap-4">
						<span class="vpg-rank-num"><?php echo (int) $i + 1; ?></span>
						<span class="min-w-0 flex-1"><span class="block text-[15px] font-medium"><?php echo esc_html( $a[0] ); ?></span><span class="block text-xs text-muted"><?php echo esc_html( $a[2] ); ?></span></span>
						<span class="shrink-0 rounded-sm px-2 py-0.5 text-xs font-semibold" style="<?php echo esc_attr( $sev[ $a[1] ] ?? $sev['Medium'] ); ?>"><?php echo esc_html( $a[1] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ol>
			<div class="mt-5 flex flex-wrap items-center gap-3 rounded-sm bg-sage-soft px-4 py-3 text-sm">
				<span class="text-muted"><?php echo esc_html( $t['rescan'] ); ?></span>
				<span class="font-semibold"><?php echo esc_html( $t['from'] ); ?></span><?php vp_i( 'arrow-right', 14, 'text-sage' ); ?><span class="font-semibold text-sage"><?php echo esc_html( $t['to'] ); ?></span>
			</div>
		</div>
	</div>
	<div class="border-t border-line bg-bg px-5 py-2.5 text-right text-xs text-muted"><?php echo esc_html( $t['foot'] ); ?></div>
</div>
