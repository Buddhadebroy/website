<?php
/**
 * "Website being scanned" graphic: a scan line passes, numbered problems turn into ticks and the score rises.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$url     = 'yourwebsite.com';
$label   = 'Site score';
$start   = 58; // Score before any fix.
$step    = 9;  // Points added per fixed problem.
$markers = array( array( 78, 7 ), array( 30, 33 ), array( 62, 66 ), array( 14, 88 ) ); // Problem markers: left %, top %.

$bar = function ( $w, $extra = '' ) {
	return '<div class="h-2 rounded-full bg-ink/10 ' . $extra . '" style="width:' . esc_attr( $w ) . '"></div>';
};
?>
<div data-vp="scanner" data-start="<?php echo (int) $start; ?>" data-step="<?php echo (int) $step; ?>">
<?php vp_frame_open( $url ); ?>
	<div class="relative aspect-[16/10] overflow-hidden bg-surface sm:aspect-[16/9]">
		<div class="absolute inset-0 flex flex-col gap-[5%] p-[4%]">
			<div class="flex items-center gap-3">
				<div class="h-5 w-5 rounded-sm bg-sage/80"></div>
				<?php echo $bar( '14%', 'bg-ink/15' ); // phpcs:ignore ?>
				<div class="ml-auto hidden gap-3 sm:flex"><?php echo $bar( '40px' ) . $bar( '40px' ) . $bar( '40px' ); // phpcs:ignore ?></div>
				<div class="ml-auto h-5 w-16 rounded-sm bg-ink/80 sm:ml-0"></div>
			</div>
			<div class="grid flex-1 grid-cols-[1.1fr_0.9fr] items-center gap-[5%]">
				<div class="space-y-3">
					<div class="h-4 w-[85%] rounded-sm bg-ink/25"></div>
					<div class="h-4 w-[60%] rounded-sm bg-ink/25"></div>
					<?php echo $bar( '90%' ) . $bar( '75%' ); // phpcs:ignore ?>
					<div class="mt-4 h-6 w-24 rounded-sm bg-sage/80"></div>
				</div>
				<div class="relative h-full overflow-hidden rounded-sm bg-gradient-to-br from-sage-soft via-lilac-soft to-apricot-soft">
					<div class="absolute bottom-[18%] left-[16%] h-[34%] w-[34%] rounded-full bg-sage/25"></div>
					<div class="absolute bottom-0 right-0 h-[55%] w-[70%] rounded-tl-[60%] bg-lilac/25"></div>
				</div>
			</div>
			<div class="grid grid-cols-3 gap-[3%]">
				<?php for ( $i = 0; $i < 3; $i++ ) : ?>
					<div class="space-y-2 rounded-sm border border-line p-3"><div class="h-8 rounded-sm bg-sage-soft/70"></div><?php echo $bar( '80%' ) . $bar( '55%' ); // phpcs:ignore ?></div>
				<?php endfor; ?>
			</div>
			<div class="flex gap-3"><?php echo $bar( '20%' ) . $bar( '12%' ) . $bar( '16%' ); // phpcs:ignore ?></div>
		</div>

		<div class="vp-scanline vp-scanline-gap pointer-events-none absolute inset-x-0 h-24 bg-gradient-to-b from-transparent via-sage/10 to-transparent" style="top:-25%;--sd:3.8s"><div class="absolute inset-x-0 top-1/2 h-px bg-sage/50"></div></div>

		<?php foreach ( $markers as $i => $m ) : ?>
			<div class="absolute -translate-x-1/2 -translate-y-1/2" style="left:<?php echo (float) $m[0]; ?>%;top:<?php echo (float) $m[1]; ?>%" data-mark>
				<span class="absolute inset-0 animate-ping rounded-full bg-rose/40" data-ping></span>
				<span class="relative grid h-6 w-6 place-items-center rounded-full text-[11px] font-semibold text-white shadow-md transition-colors duration-500 bg-rose" data-badge>
					<span data-num><?php echo (int) $i + 1; ?></span>
					<span data-tick hidden><?php vp_i( 'check', 13, '', 3 ); ?></span>
				</span>
			</div>
		<?php endforeach; ?>

		<div class="absolute bottom-[5%] right-[4%] flex items-center gap-3 rounded-sm border border-line bg-surface/95 px-4 py-3 shadow-lg backdrop-blur">
			<div class="text-left">
				<div class="text-[10px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $label ); ?></div>
				<div class="text-2xl font-semibold tabular-nums"><span data-score><?php echo (int) $start; ?></span><span class="text-xs text-muted"> / 100</span></div>
			</div>
			<div class="flex h-10 w-1.5 flex-col justify-end overflow-hidden rounded-full bg-line"><div class="w-full rounded-sm bg-sage transition-all duration-700" style="height:<?php echo (int) $start; ?>%" data-scorebar></div></div>
		</div>
	</div>
</div>
</div>
