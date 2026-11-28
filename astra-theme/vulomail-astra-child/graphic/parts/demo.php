<?php
/**
 * Graphic: Small live demo inside a Kadence card (home "What's inside")
 *
 * The card's pill, heading and text are Kadence blocks; only the moving part is this graphic.
 * Props: name = failover | diagnostics | secret | toggles | source | livelog,
 * items (diagnostics: [label, good|warning|info]; toggles: [label, on]; source: labels), rows (livelog).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$name = isset( $a['name'] ) ? $a['name'] : 'failover';

if ( 'failover' === $name ) {
	$steps = isset( $a['items'] ) ? (array) $a['items'] : array( 'Primary', 'Backup', 'WordPress' );
	echo '<div class="vm:mt-8 vm:flex vm:items-center vm:gap-2 vm:sm:gap-3" data-vm-failover>';
	foreach ( $steps as $i => $n ) {
		echo '<div class="vm:flex vm:flex-1 vm:items-center vm:gap-2 vm:sm:gap-3"><div data-vm-fo="' . (int) $i . '" class="vm:flex-1 vm:rounded-xl vm:border-2 vm:px-2 vm:py-3 vm:text-center vm:text-xs vm:font-medium vm:sm:text-sm" style="background-color:' . ( 0 === $i ? 'var(--green-soft)' : 'var(--surface)' ) . ';border-color:' . ( 0 === $i ? 'var(--green)' : 'var(--line)' ) . ';transform:translateY(' . ( 0 === $i ? '-4px' : '0px' ) . ')">'
			. esc_html( $n ) . '<span class="vm:mt-1 vm:block vm:text-[0.65rem] vm:text-ink-mute">' . ( 0 === $i ? 'delivered' : 'standby' ) . '</span></div>'
			. ( $i < count( $steps ) - 1 ? '<span class="vm:text-ink-mute">→</span>' : '' ) . '</div>';
	}
	echo '</div>';
} elseif ( 'diagnostics' === $name ) {
	$checks = isset( $a['items'] ) ? (array) $a['items'] : array( array( 'Email routing', 'good' ), array( 'wp_mail() delivery', 'good' ), array( 'Sender domain DNS', 'warning' ), array( 'Other email plugins', 'good' ), array( 'Scheduled tasks', 'info' ) );
	$tone   = array( 'good' => 'vm:text-green vm:bg-green-soft', 'warning' => 'vm:text-amber-ink vm:bg-amber-soft', 'info' => 'vm:text-blue vm:bg-blue-soft' );
	$text   = array( 'good' => 'Passed', 'warning' => 'Needs attention', 'info' => 'Info' );
	echo '<ul class="vm:mt-6 vm:space-y-2" data-vm-diag>';
	foreach ( $checks as $i => $c ) {
		$t = isset( $tone[ $c[1] ] ) ? $c[1] : 'info';
		echo '<li class="vm:flex vm:items-center vm:justify-between vm:rounded-xl vm:bg-paper vm:px-3 vm:py-2 vm:text-sm" data-vm-diag-i="' . (int) $i . '" style="opacity:0;transform:translateX(-12px)">' . esc_html( $c[0] )
			. '<span class="vm:rounded-full vm:px-2 vm:py-0.5 vm:text-[0.68rem] vm:font-medium ' . esc_attr( $tone[ $t ] ) . '">' . esc_html( $text[ $t ] ) . '</span></li>';
	}
	echo '</ul>';
} elseif ( 'secret' === $name ) {
	echo '<div class="vm:mt-6 vm:rounded-xl vm:border vm:border-line vm:bg-paper vm:p-4" data-vm-secret><p class="vm:eyebrow vm:text-ink-mute" data-vm-secret-label>What the database holds</p>'
		. '<p class="vm:mt-2 vm:truncate vm:font-mono vm:text-base" data-vm-secret-text>vm1:Qm9nYXNzZWNyZXQ…</p><p class="vm:mt-2 vm:text-xs vm:text-ink-mute">Hover to swap views</p></div>';
} elseif ( 'toggles' === $name ) {
	$items = isset( $a['items'] ) ? (array) $a['items'] : array( array( 'Keep a log', true ), array( 'Store message content', false ), array( 'Mask recipients', true ) );
	echo '<ul class="vm:mt-6 vm:space-y-2.5">';
	foreach ( $items as $t ) {
		echo '<li class="vm:flex vm:items-center vm:justify-between vm:text-sm">' . esc_html( $t[0] ) . vm_toggle( ! empty( $t[1] ), 'Toggle ' . $t[0] ) . '</li>'; // phpcs:ignore
	}
	echo '</ul>';
} elseif ( 'source' === $name ) {
	$labels = isset( $a['items'] ) ? (array) $a['items'] : array( 'core', 'woocommerce', 'my-theme', 'my-forms', 'vulomail-test' );
	echo '<div class="vm:mt-6 vm:flex vm:items-center vm:gap-2 vm:font-mono vm:text-sm"><span class="vm:text-ink-mute">source:</span>'
		. '<span class="vm:relative vm:inline-flex vm:h-8 vm:min-w-[9rem] vm:items-center vm:overflow-hidden vm:rounded-lg vm:bg-paper vm:px-3" data-vm-sources="' . esc_attr( wp_json_encode( array_values( $labels ) ) ) . '"><span>' . esc_html( $labels[0] ) . '</span></span></div>';
} elseif ( 'livelog' === $name ) {
	echo vm_live_log( isset( $a['rows'] ) ? (int) $a['rows'] : 4 ); // phpcs:ignore
}
