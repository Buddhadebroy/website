<?php
/**
 * How it works – step 1 graphic: "Find" (a scan runs and areas tick off).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$url   = 'yourstore.com · scanning';
$areas = array( // label, result
	array( 'Pages', '128' ),
	array( 'Plugins', '24' ),
	array( 'Speed', '6 tests' ),
	array( 'Search', '41 checks' ),
	array( 'Content', '52 posts' ),
	array( 'AI visibility', '9 signals' ),
);
vp_frame_open( $url, 'sm' );
?>
<div class="p-5">
	<div class="relative overflow-hidden rounded-sm bg-bg/60 p-4">
		<?php foreach ( array( 90, 70, 82, 55, 76 ) as $w ) : ?>
			<div class="mb-2.5 h-2.5 rounded-full bg-line" style="width:<?php echo (int) $w; ?>%"></div>
		<?php endforeach; ?>
		<div class="vp-scanline absolute inset-x-0 h-10 -translate-y-1/2 bg-gradient-to-b from-transparent via-sage/25 to-transparent" style="top:-10%"></div>
		<div class="vp-scanline absolute inset-x-0 h-px bg-sage" style="top:-10%"></div>
	</div>
	<div class="mt-4 grid grid-cols-2 gap-2.5 sm:grid-cols-3">
		<?php foreach ( $areas as $i => $a ) : ?>
			<div class="flex items-center gap-2 rounded-sm border border-line bg-surface px-3 py-2.5 text-xs" <?php vp_m( 'o:0 y:10 d:' . ( 0.3 + $i * 0.28 ) ); ?>>
				<span class="grid h-5 w-5 shrink-0 place-items-center rounded-full bg-sage text-white" <?php vp_m( 's:0 e:spring d:' . ( 0.6 + $i * 0.28 ) ); ?>><?php vp_i( 'check', 11 ); ?></span>
				<span><span class="block font-semibold"><?php echo esc_html( $a[0] ); ?></span><span class="text-muted"><?php echo esc_html( $a[1] ); ?></span></span>
			</div>
		<?php endforeach; ?>
	</div>
</div>
</div>
