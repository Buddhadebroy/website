<?php
/**
 * Graphic: Sources card
 *
 * Props: title, slug (one comparison; empty = every comparison).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$cmp  = vm_compare();
$list = array();
if ( ! empty( $a['slug'] ) ) {
	$c    = vm_competitor( $a['slug'] );
	$list = $c ? $c['sources'] : array();
} else {
	foreach ( $cmp['competitors'] as $c ) {
		$list = array_merge( $list, $c['sources'] );
	}
}
$list = array_merge( $list, $cmp['vulomail']['sources'] );
echo '<div class="vm:card vm:p-8"><p class="vm:eyebrow vm:text-ink-mute">' . esc_html( $a['title'] ?? 'Sources' ) . '</p><div class="vm:mt-4">' . vm_sources( $list, $cmp['researchedOn'] ) . '</div></div>'; // phpcs:ignore
