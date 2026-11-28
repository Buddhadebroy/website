<?php
/**
 * Graphic: Feature-by-feature table (comparison page)
 *
 * Props: slug.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$c = vm_competitor( $a['slug'] ?? '' );
if ( ! $c ) {
	return;
}
$cmp  = vm_compare();
$omit = isset( $c['omit'] ) ? $c['omit'] : array();
$row  = function ( $label, $us, $them ) use ( $c ) {
	return '<div class="vm:grid vm:grid-cols-2 vm:gap-x-4 vm:gap-y-3 vm:px-6 vm:py-4 vm:sm:grid-cols-[1.25fr_1fr_1fr] vm:sm:gap-6"><p class="vm:col-span-2 vm:text-[0.95rem] vm:font-medium vm:sm:col-span-1">' . esc_html( $label ) . '</p>'
		. '<div><p class="vm:mb-1.5 vm:text-[0.7rem] vm:font-semibold vm:uppercase vm:tracking-wide vm:text-ink-mute vm:sm:hidden">VuloMail</p>' . vm_cell_view( $us, true ) . '</div>'
		. '<div><p class="vm:mb-1.5 vm:text-[0.7rem] vm:font-semibold vm:uppercase vm:tracking-wide vm:text-ink-mute vm:sm:hidden">' . esc_html( $c['name'] ) . '</p>' . vm_cell_view( $them ) . '</div></div>';
};
$group = function ( $name, $inner ) {
	return '<div><p class="vm:eyebrow vm:border-b vm:border-line vm:bg-paper-deep/60 vm:px-6 vm:py-2.5 vm:text-ink-mute">' . esc_html( $name ) . '</p><div class="vm:divide-y vm:divide-line/70">' . $inner . '</div></div>';
};
$out = '<div class="vm:card vm:overflow-hidden"><div class="vm:hidden vm:grid-cols-[1.25fr_1fr_1fr] vm:gap-6 vm:border-b vm:border-line vm:px-6 vm:py-4 vm:sm:grid"><span class="vm:eyebrow vm:text-ink-mute">Feature</span><span class="vm:text-sm vm:font-bold">VuloMail</span><span class="vm:text-sm vm:font-bold">' . esc_html( $c['name'] ) . '</span></div>';
if ( ! empty( $c['extraRows'] ) ) {
	$inner = '';
	foreach ( $c['extraRows'] as $r ) {
		$inner .= $row( $r['label'], $r['vulomail'], $r['them'] );
	}
	$out .= $group( $c['name'] . ' specifics', $inner );
}
foreach ( $cmp['featureGroups'] as $g ) {
	$inner = '';
	foreach ( $g['rows'] as $r ) {
		if ( in_array( $r['key'], $omit, true ) ) {
			continue;
		}
		$inner .= $row( $r['label'], $cmp['vulomail']['features'][ $r['key'] ], isset( $c['features'][ $r['key'] ] ) ? $c['features'][ $r['key'] ] : null );
	}
	if ( $inner ) {
		$out .= $group( $g['name'], $inner );
	}
}
echo $out . '</div>'; // phpcs:ignore
