<?php
/**
 * How it works – step 2 graphic: "Understand" (a missing page title, explained).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$url   = 'yourstore.com/services/web-design';
$box   = '<title> (empty)';
$badge = 'Missing title';
$issue = 'Page title is missing';
$sev   = 'High';
$why   = '<span class="font-semibold text-ink">Why it matters:</span> Google shows a random snippet instead of your offer, so fewer people click from search.';
vp_frame_open( $url, 'sm' );
?>
<div class="p-5">
	<div class="rounded-sm bg-bg/60 p-4">
		<div class="relative inline-block">
			<div class="vp-pulse-ring rounded-sm border-2 border-dashed border-rose bg-rose/5 px-3 py-1.5 text-sm text-muted"><?php echo esc_html( $box ); ?></div>
			<span class="absolute -right-3 -top-3 rounded-sm bg-rose px-2.5 py-0.5 text-[10px] font-semibold text-white" <?php vp_m( 'o:0 s:.6 y:6 d:.5 e:spring' ); ?>><?php echo esc_html( $badge ); ?></span>
		</div>
		<?php foreach ( array( 85, 60, 72 ) as $w ) : ?>
			<div class="mt-3 h-2 rounded-full bg-line" style="width:<?php echo (int) $w; ?>%"></div>
		<?php endforeach; ?>
	</div>
	<div class="mt-4 rounded-sm border border-line bg-surface p-4" <?php vp_m( 'o:0 y:14 d:.9' ); ?>>
		<div class="flex items-center justify-between">
			<span class="text-sm font-semibold"><?php echo esc_html( $issue ); ?></span>
			<span class="rounded-sm bg-rose/10 px-2.5 py-0.5 text-[11px] font-semibold text-rose"><?php echo esc_html( $sev ); ?></span>
		</div>
		<p class="mt-1.5 text-sm text-muted"><?php echo wp_kses_post( $why ); ?></p>
	</div>
</div>
</div>
