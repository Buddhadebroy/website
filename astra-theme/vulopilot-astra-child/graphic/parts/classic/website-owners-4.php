<?php
/**
 * Website Owners › an always-on automation panel.
 * Graphic name: website-owners-4  ·  Shortcode: [vp_website_owners_4]  ·  /for/website-owners/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'eyebrow'  => 'Automations',
		'scan'     => 'Run Full Site Scan',
		'freq'     => array( 'Daily', 'Weekly', 'Monthly', 'Manually' ),
		'active'   => 'Weekly',
		'days'     => array( 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN' ),
		'day_on'   => 0,
		'caption'  => 'For owners who just want to know their site is okay.',
		'report'   => 'Send Visibility Report',
		'note'     => 'Run a scan and see six scores with a plain-language rating.',
		'footnote' => '',
	),
	(array) ( $args['text'] ?? array() )
);
?>
<div class="vpg-wrap">
	<div class="card overflow-hidden" data-vpg="static">
		<div class="border-b border-line px-5 py-3.5 text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['eyebrow'] ); ?></div>
		<div class="p-5">
			<div class="vpg-row-title">
				<span class="grid h-8 w-8 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'scan-search', 15 ); ?></span>
				<?php echo esc_html( $t['scan'] ); ?>
			</div>
			<div class="vpg-tabs mt-4">
				<?php foreach ( $t['freq'] as $f ) : ?>
					<span class="vpg-tab<?php echo $f === $t['active'] ? ' is-on' : ''; ?>"><?php echo esc_html( $f ); ?></span>
				<?php endforeach; ?>
			</div>
			<div class="vpg-days mt-3">
				<?php foreach ( $t['days'] as $i => $d ) : $on = ( $i === (int) $t['day_on'] ); ?>
					<span class="vpg-day<?php echo $on ? ' is-on' : ''; ?>">
						<?php if ( $on ) : ?><span class="vpg-day-dot"><?php vp_i( 'check', 10 ); ?></span><?php endif; ?>
						<?php echo esc_html( $d ); ?>
					</span>
				<?php endforeach; ?>
			</div>
			<p class="mt-3 text-xs text-muted"><?php echo esc_html( $t['caption'] ); ?></p>
		</div>
		<div class="flex items-center justify-between gap-3 border-t border-line px-5 py-4">
			<div class="vpg-row-title"><?php vp_i( 'mail', 15, 'text-muted' ); ?><?php echo esc_html( $t['report'] ); ?></div>
			<span class="vpg-switch is-on"></span>
		</div>
		<div class="border-t border-line px-5 py-3.5">
			<span class="vpg-note"><?php vp_i( 'check', 11 ); ?><?php echo esc_html( $t['note'] ); ?></span>
		</div>
	</div>
</div>
