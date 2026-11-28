<?php
/**
 * Feature card visual: the submissions inbox (three rows slide in one by one).
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;
?>
<?php
$subs = array(
	array( '#131', 'Priya S.', 'New', 'bg-mint-soft text-[#0f7a53]' ),
	array( '#130', 'Marco R.', 'Read', 'bg-sky-soft text-navy' ),
	array( '#129', 'free-$$$', 'Spam', 'bg-peach-soft text-[#b4532a]' ),
);
?>
<div class="vf-stagger mt-6 overflow-hidden rounded-2xl border border-line bg-white" style="--vf-step:.12s">
	<?php foreach ( $subs as $s ) : ?>
	<div class="vf-reveal-left flex items-center justify-between border-b border-line px-4 py-3 text-sm last:border-0" style="--vf-delay:.3s"><span class="font-mono text-ink-soft"><?php echo esc_html( $s[0] ); ?></span><span class="flex-1 px-4 font-medium"><?php echo esc_html( $s[1] ); ?></span><span class="rounded-md px-2 py-0.5 text-xs font-bold <?php echo esc_attr( $s[3] ); ?>"><?php echo esc_html( $s[2] ); ?></span></div>
	<?php endforeach; ?>
</div>
