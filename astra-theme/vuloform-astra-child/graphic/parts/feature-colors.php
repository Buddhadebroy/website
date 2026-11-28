<?php
/**
 * Feature card visual: five colour dots that pop in + custom CSS class tag.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mt-6 flex items-center gap-3">
	<?php foreach ( array( 'var(--navy)', 'var(--mint)', 'var(--lilac-ink)', 'var(--orange)', 'var(--ink)' ) as $i => $c ) : ?>
	<span class="vf-reveal-pop h-9 w-9 rounded-full ring-4 ring-white" style="background:<?php echo esc_attr( $c ); ?>;--vf-delay:<?php echo esc_attr( 0.3 + $i * 0.07 ); ?>s"></span>
	<?php endforeach; ?>
	<span class="ml-2 rounded-lg bg-white px-3 py-2 text-xs font-semibold">+ custom CSS class</span>
</div>
