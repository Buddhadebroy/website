<?php
/**
 * Graphic: REST routes list (Developers)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="vm:mt-12 vm:divide-y vm:divide-line vm:border-y vm:border-line" data-vm-stagger>
	<?php foreach ( vm_data( 'developers' )['routes'] as $r ) : ?>
		<div data-vm-st class="vm:group vm:grid vm:gap-2 vm:py-4 vm:sm:grid-cols-[140px_1fr_1.2fr] vm:sm:items-center">
			<span class="vm:font-mono vm:text-xs vm:font-semibold vm:text-red"><?php echo esc_html( $r[0] ); ?></span>
			<code class="vm:font-mono vm:text-sm vm:transition-transform vm:duration-300 vm:group-hover:translate-x-1"><?php echo esc_html( $r[1] ); ?></code>
			<span class="vm:text-sm vm:text-ink-soft"><?php echo esc_html( $r[2] ); ?></span>
		</div>
	<?php endforeach; ?>
</div>
