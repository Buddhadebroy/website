<?php
/**
 * How it works – step 4 graphic: "Fix" (AI types a new title, then it is approved).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$url      = 'VuloPilot · AI fix · /services/web-design';
$before   = '(no title)';
$draft    = 'Web Design for Growing Brands | Studio North';
$labels   = array(
	'before'   => 'Before',
	'ai'       => 'AI draft',
	'approve'  => 'Approve & publish',
	'approved' => 'Approved and applied',
	'send'     => 'Send to developer',
	'undo'     => 'Undo',
);
vp_frame_open( $url, 'sm' );
?>
<div class="p-5" data-fx="swap" data-after="3.3">
	<div class="grid gap-3 sm:grid-cols-2">
		<div class="rounded-sm bg-bg/70 p-3.5">
			<div class="text-[10px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $labels['before'] ); ?></div>
			<p class="mt-1.5 text-sm text-muted line-through decoration-rose/60"><?php echo esc_html( $before ); ?></p>
		</div>
		<div class="rounded-sm bg-sage-soft/70 p-3.5">
			<div class="flex items-center gap-1 text-[10px] font-semibold uppercase tracking-widest text-sage"><?php vp_i( 'sparkles', 11 ); ?><?php echo esc_html( $labels['ai'] ); ?></div>
			<p class="mt-1.5 min-h-[40px] text-sm font-semibold"><span data-fx="type" data-step="2" data-ms="45"><?php echo esc_html( $draft ); ?></span></p>
		</div>
	</div>
	<div class="mt-4 flex flex-wrap items-center gap-2">
		<span class="rounded-sm bg-ink px-4 py-2 text-sm font-semibold text-bg ring-4 ring-sage/20" data-when="before"><?php echo esc_html( $labels['approve'] ); ?></span>
		<span class="inline-flex items-center gap-1.5 rounded-sm bg-sage px-4 py-2 text-sm font-semibold text-white" data-when="after" hidden <?php vp_m( 'o:0 s:.9 t:.3' ); ?>><?php vp_i( 'check', 15 ); ?><?php echo esc_html( $labels['approved'] ); ?></span>
		<span class="inline-flex items-center gap-1.5 rounded-sm border border-line px-4 py-2 text-sm" data-when="before"><?php vp_i( 'send', 14 ); ?><?php echo esc_html( $labels['send'] ); ?></span>
		<span class="inline-flex items-center gap-1.5 rounded-sm border border-line px-4 py-2 text-sm text-muted" data-when="after" hidden><?php vp_i( 'undo-2', 14 ); ?><?php echo esc_html( $labels['undo'] ); ?></span>
	</div>
</div>
</div>
