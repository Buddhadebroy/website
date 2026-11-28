<?php
/**
 * Home › Ready-made forms marquee (port of components/Templates.tsx). Screenshot 1.
 * Also the block "Ready-made Forms Marquee (VuloForm)". Text: vf_section_defaults( 'templates' ).
 * Effects: vf-marquee / vf-marquee-reverse (endless scroll, the JS copies the items), vf-fade-edges.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$a      = vf_section_args( 'templates', $args ?? array() );
$colors = array(
	array( 'bg-sky-soft', 'bg-mint-soft', 'bg-peach-soft', 'bg-butter-soft', 'bg-lilac-soft' ),
	array( 'bg-mint-soft', 'bg-sky-soft', 'bg-butter-soft', 'bg-peach-soft', 'bg-lilac-soft' ),
);
?>
<section class="py-16" aria-label="Ready-made forms">
	<p class="vf-reveal mb-8 text-center text-sm font-semibold tracking-widest text-ink-soft uppercase"><?php echo esc_html( $a['kicker'] ); ?></p>
	<div class="space-y-4">
		<?php foreach ( array( $a['row1'], $a['row2'] ) as $r => $row ) : ?>
		<div class="vf-fade-edges flex overflow-hidden">
			<ul class="<?php echo $r ? 'vf-marquee-reverse' : 'vf-marquee'; ?> flex shrink-0 gap-4 pr-4" style="--vf-marquee-t:<?php echo $r ? '44s' : '38s'; ?>">
				<?php
				foreach ( vf_lines( $row ) as $i => $line ) :
					// "💬 Contact" → icon + label (first word is the icon when it has no letters).
					$parts = preg_split( '/\s+/u', $line, 2 );
					$icon  = ( count( $parts ) > 1 && ! preg_match( '/[\p{L}\p{N}]/u', $parts[0] ) ) ? $parts[0] : '';
					$label = $icon ? $parts[1] : $line;
					?>
				<li class="flex items-center gap-3 rounded-2xl <?php echo esc_attr( $colors[ $r ][ $i % 5 ] ); ?> px-5 py-4 text-lg font-semibold whitespace-nowrap text-ink"><?php if ( $icon ) : ?><span class="text-2xl"><?php echo esc_html( $icon ); ?></span><?php endif; ?><?php echo esc_html( $label ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endforeach; ?>
	</div>
</section>
