<?php
/**
 * How it works – step 5 graphic: "Verify" (score before and after, with history bars).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$url     = 'VuloPilot · Scan again';
$before  = 42;
$after   = 91;
$history = array( 42, 44, 47, 51, 58, 66, 78, 91 ); // Last bar is highlighted.
$labels  = array( 'before' => 'Before', 'after' => 'After', 'history' => 'Score history', 'done' => 'Problem gone, 12 findings fixed' );
vp_frame_open( $url, 'sm' );
?>
<div class="p-5">
	<div class="flex items-center justify-around gap-2">
		<div class="text-center">
			<?php echo vp_ring( $before, 104, 'var(--rose)' ); // phpcs:ignore ?>
			<div class="mt-1 text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $labels['before'] ); ?></div>
		</div>
		<div class="grid h-12 w-12 place-items-center rounded-full bg-sage-soft text-sage" <?php vp_m( 's:0 r:-90 d:.8 e:spring' ); ?>><?php vp_i( 'arrow-right', 20 ); ?></div>
		<div class="text-center">
			<?php echo vp_ring( $after, 104, 'var(--sage)' ); // phpcs:ignore ?>
			<div class="mt-1 text-[11px] font-semibold uppercase tracking-widest text-sage"><?php echo esc_html( $labels['after'] ); ?></div>
		</div>
	</div>
	<div class="mt-5 rounded-sm bg-bg/60 p-3.5">
		<div class="flex items-end gap-1.5">
			<?php $last = count( $history ) - 1; foreach ( $history as $i => $v ) : ?>
				<div class="flex-1 rounded-t-sm" <?php vp_m( 'H:' . ( $v * 0.55 ) . 'px t:.5 e:ease d:' . ( 0.2 + $i * 0.08 ), 'background:' . ( $i === $last ? 'var(--sage)' : 'var(--sage-soft)' ) ); ?>></div>
			<?php endforeach; ?>
		</div>
		<div class="mt-2 flex items-center justify-between text-[11px] text-muted">
			<span><?php echo esc_html( $labels['history'] ); ?></span>
			<span class="inline-flex items-center gap-1 font-semibold text-sage" <?php vp_m( 'o:0 d:1.2' ); ?>><?php vp_i( 'badge-check', 13 ); ?><?php echo esc_html( $labels['done'] ); ?></span>
		</div>
	</div>
</div>
</div>
