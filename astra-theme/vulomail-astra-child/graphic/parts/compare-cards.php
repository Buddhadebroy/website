<?php
/**
 * Graphic: Comparison cards
 *
 * Props: class (grid classes), exclude (slug), limit.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$list = vm_competitors();
if ( ! empty( $a['exclude'] ) ) {
	$list = array_values( array_filter( $list, function ( $c ) use ( $a ) { return $c['slug'] !== $a['exclude']; } ) );
}
if ( ! empty( $a['limit'] ) ) {
	$list = array_slice( $list, 0, (int) $a['limit'] );
}
echo '<div class="' . esc_attr( $a['class'] ?? 'vm:mt-12 vm:grid vm:gap-5 vm:sm:grid-cols-2 vm:lg:grid-cols-3' ) . '" data-vm-stagger>';
foreach ( $list as $c ) {
	echo '<div data-vm-st>' . vm_compare_card( $c ) . '</div>'; // phpcs:ignore
}
echo '</div>';
