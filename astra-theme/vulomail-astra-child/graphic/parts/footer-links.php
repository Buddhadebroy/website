<?php
/**
 * Graphic: Footer link columns (from the menus)
 *
 * Same groups as the header menu, so the two never drift apart, plus "More from VuloLabs"
 * (Appearance → Menus → VuloMail footer: More from VuloLabs).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$tree    = vm_nav_tree();
$groups  = array_values( array_filter( $tree, function ( $n ) { return isset( $n['items'] ); } ) );
$singles = array_values( array_filter( $tree, function ( $n ) { return ! isset( $n['items'] ); } ) );
$cols    = array();
if ( isset( $groups[0] ) ) {
	$cols[] = array( 'title' => $groups[0]['label'], 'links' => $groups[0]['items'] );
}
$cols[] = array( 'title' => 'Choose', 'links' => $singles );
if ( isset( $groups[1] ) ) {
	$cols[] = array( 'title' => $groups[1]['label'], 'links' => array_merge( $groups[1]['items'], array( array( 'href' => vm_opt( 'repo_url' ), 'label' => 'Plugin on GitHub', 'external' => true ) ) ) );
}
foreach ( array_slice( $groups, 2 ) as $g ) {
	$cols[] = array( 'title' => $g['label'], 'links' => $g['items'] );
}
$cols[] = array( 'title' => 'More from VuloLabs', 'links' => vm_more_links(), 'desc' => true );

foreach ( $cols as $c ) {
	echo '<div><p class="vm:eyebrow vm:text-ink-mute">' . esc_html( $c['title'] ) . '</p><ul class="vm:mt-5 vm:space-y-3">';
	foreach ( $c['links'] as $l ) {
		$ext = ! empty( $l['external'] );
		echo '<li><a href="' . esc_url( $l['href'] ) . '"' . ( $ext ? ' target="_blank" rel="noreferrer"' : '' ) . ' class="vm:link-draw vm:text-sm vm:text-ink-soft vm:hover:text-ink">' . esc_html( $l['label'] ) . ( $ext ? ' ↗' : '' ) . '</a>';
		if ( ! empty( $c['desc'] ) && ! empty( $l['desc'] ) ) {
			echo '<p class="vm:mt-0.5 vm:text-xs vm:text-ink-mute">' . esc_html( $l['desc'] ) . '</p>';
		}
		echo '</li>';
	}
	echo '</ul></div>';
}
