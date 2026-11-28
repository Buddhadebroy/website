<?php
/**
 * Graphic: Everything VuloMail stores (Security)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$data = array(
	array( '{prefix}vulomail_logs', 'Table', 'One row per message. Bodies are NULL unless you store content.' ),
	array( 'vulomail_settings', 'Option', 'Every setting you can change in the admin.' ),
	array( 'vulomail_connections', 'Option', 'Your connections, with secret fields encrypted.' ),
	array( 'vulomail_key_material', 'Option', 'Random half of the encryption key material.' ),
	array( 'vulomail_prune_logs', 'Daily event', 'Deletes entries older than your retention period.' ),
);
?>
<div class="vm:card vm:overflow-hidden" data-vm-reveal>
	<div class="vm:flex vm:items-center vm:gap-3 vm:border-b vm:border-line vm:px-6 vm:py-5"><?php echo vm_icon( 'database', 'vm:h-5 vm:w-5' ); // phpcs:ignore ?><h2 class="vm:text-lg vm:font-medium">Everything VuloMail stores, in your own database</h2></div>
	<div class="vm:divide-y vm:divide-line">
		<?php foreach ( $data as $d ) : ?>
			<div class="vm:grid vm:gap-2 vm:px-6 vm:py-4 vm:transition-colors vm:hover:bg-paper vm:sm:grid-cols-[1.2fr_0.6fr_2fr] vm:sm:items-center"><code class="vm:font-mono vm:text-sm"><?php echo esc_html( $d[0] ); ?></code><span class="vm:text-xs vm:text-ink-mute"><?php echo esc_html( $d[1] ); ?></span><span class="vm:text-sm vm:text-ink-soft"><?php echo esc_html( $d[2] ); ?></span></div>
		<?php endforeach; ?>
	</div>
	<p class="vm:border-t vm:border-line vm:bg-paper vm:px-6 vm:py-4 vm:text-sm vm:text-ink-soft">No post types, no user meta, no post meta. On uninstall, nothing is removed unless you chose <b>Delete everything</b>. Then the table, options, transients and scheduled event are all cleared.</p>
</div>
