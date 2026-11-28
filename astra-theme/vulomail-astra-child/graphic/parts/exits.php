<?php
/**
 * Graphic: Reset or remove (FAQ)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$fixes = array(
	array( 'Stop VuloMail delivering email, keep everything', 'Settings → Email → switch off Send email through VuloMail' ),
	array( 'Stop all text messages', 'Settings → SMS → switch off Send SMS through VuloMail' ),
	array( 'Go back to the WordPress mailer, keep connections', 'Settings → Connections → Set as primary on WordPress default mailer' ),
	array( 'Clear the log', 'Logs → Delete all logs' ),
	array( 'Remove the plugin, keep your data', 'Deactivate and delete. With Keep data (the default) nothing is removed' ),
	array( 'Remove the plugin and all its data', 'Settings → Data → Delete everything, then deactivate and delete' ),
);
?>
<div class="vm:mt-10 vm:divide-y vm:divide-line vm:border-y vm:border-line" data-vm-stagger>
	<?php foreach ( $fixes as $f ) : ?>
		<div data-vm-st class="vm:py-5"><p class="vm:font-medium"><?php echo esc_html( $f[0] ); ?></p><p class="vm:mt-1 vm:font-mono vm:text-xs vm:leading-relaxed vm:text-ink-soft"><?php echo esc_html( $f[1] ); ?></p></div>
	<?php endforeach; ?>
</div>
