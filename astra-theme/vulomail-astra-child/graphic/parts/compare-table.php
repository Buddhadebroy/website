<?php
/**
 * Graphic: Overview table of every compared plugin (Compare)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$cmp  = vm_compare();
$cols = array( array( 'log', 'Email log' ), array( 'backup', 'Backup' ), array( 'failAlerts', 'Failure alerts' ), array( 'smsAlerts', 'SMS alerts' ), array( 'wooCustomer', 'Customer texts' ) );
?>
<div class="vm:card vm:overflow-x-auto">
	<table class="vm:w-full vm:min-w-[1100px] vm:text-left vm:text-sm">
		<thead><tr class="vm:border-b vm:border-line"><th class="vm:px-5 vm:py-4 vm:font-semibold">Plugin</th><th class="vm:px-4 vm:py-4 vm:font-semibold">Installs</th><th class="vm:px-4 vm:py-4 vm:font-semibold">Rating</th><th class="vm:px-4 vm:py-4 vm:font-semibold">Paid plans from</th><?php foreach ( $cols as $c ) : ?><th class="vm:px-4 vm:py-4 vm:font-semibold"><?php echo esc_html( $c[1] ); ?></th><?php endforeach; ?></tr></thead>
		<tbody class="vm:divide-y vm:divide-line/70">
			<tr class="vm:bg-blue-soft/40">
				<td class="vm:px-5 vm:py-4"><p class="vm:font-bold">VuloMail</p><p class="vm:text-xs vm:text-ink-mute">Listed <?php echo esc_html( $cmp['vulomail']['stats']['listed'] ); ?></p></td>
				<td class="vm:px-4 vm:py-4">New</td><td class="vm:px-4 vm:py-4"><?php echo vm_stars( 0, 0 ); // phpcs:ignore ?></td><td class="vm:px-4 vm:py-4">No paid plan</td>
				<?php foreach ( $cols as $c ) : ?><td class="vm:px-4 vm:py-4"><?php echo vm_avail_badge( $cmp['vulomail']['features'][ $c[0] ]['v'], true ); // phpcs:ignore ?></td><?php endforeach; ?>
			</tr>
			<?php foreach ( $cmp['competitors'] as $c ) : ?>
				<tr>
					<td class="vm:px-5 vm:py-4"><a href="<?php echo esc_url( vm_url( '/compare/' . $c['slug'] ) ); ?>" class="vm:font-semibold vm:text-blue vm:underline-offset-4 vm:hover:underline"><?php echo esc_html( $c['name'] ); ?></a><p class="vm:text-xs vm:text-ink-mute"><?php echo esc_html( $c['vendor'] ); ?></p></td>
					<td class="vm:px-4 vm:py-4"><?php echo esc_html( vm_format_installs( $c['stats']['activeInstalls'] ) ); ?></td>
					<td class="vm:px-4 vm:py-4 vm:whitespace-nowrap"><?php echo vm_stars( $c['stats']['rating'], $c['stats']['reviews'] ); // phpcs:ignore ?></td>
					<td class="vm:px-4 vm:py-4"><?php echo esc_html( ! empty( $c['plans'] ) ? $c['plans'][0]['price'] : 'No paid plan' ); ?></td>
					<?php foreach ( $cols as $col ) : ?><td class="vm:px-4 vm:py-4"><?php echo vm_avail_badge( isset( $c['features'][ $col[0] ] ) ? $c['features'][ $col[0] ]['v'] : 'na' ); // phpcs:ignore ?></td><?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
