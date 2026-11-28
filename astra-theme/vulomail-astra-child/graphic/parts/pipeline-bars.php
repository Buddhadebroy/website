<?php
/**
 * Graphic: How a message travels, the progress bars under the steps
 *
 * Props: count (number of steps, default 5).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$count = isset( $a['count'] ) ? max( 1, (int) $a['count'] ) : 5;
echo '<div class="vm:mt-5 vm:flex vm:items-center vm:gap-3 vm:sm:mt-8">';
for ( $i = 0; $i < $count; $i++ ) {
	echo '<span class="vm:relative vm:h-1 vm:flex-1 vm:overflow-hidden vm:rounded-full vm:bg-line"><span data-vm-bar="' . (int) $i . '" class="vm:absolute vm:inset-0 vm:origin-left vm:rounded-full vm:bg-ink" style="transform:scaleX(' . ( 0 === $i ? 1 : 0 ) . ')"></span></span>';
}
echo '</div>';
