<?php
/**
 * Graphic: Definition list (term + description rows)
 *
 * Props: items ([[term, description], …]).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

echo '<dl class="vm:space-y-5">';
foreach ( (array) ( $a['items'] ?? array() ) as $it ) {
	echo '<div class="vm:border-b vm:border-line vm:pb-5 vm:last:border-0"><dt class="vm:font-medium">' . esc_html( $it[0] ) . '</dt><dd class="vm:mt-1 vm:text-ink-soft">' . esc_html( $it[1] ) . '</dd></div>';
}
echo '</dl>';
