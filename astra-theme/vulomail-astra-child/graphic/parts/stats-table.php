<?php
/**
 * Graphic: WordPress.org numbers, side by side (comparison page)
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
$us   = vm_compare()['vulomail']['stats'];
$rows = array(
	array( 'Active installs', function ( $s ) { return esc_html( $s['activeInstalls'] ? vm_format_installs( $s['activeInstalls'] ) : 'Under 10 (new)' ); } ),
	array( 'Rating', function ( $s ) { return vm_stars( $s['rating'], $s['reviews'] ); } ),
	array( 'Current version', function ( $s ) { return esc_html( $s['version'] ); } ),
	array( 'Last updated', function ( $s ) { return esc_html( $s['lastUpdated'] ); } ),
	array( 'On WordPress.org since', function ( $s ) { return esc_html( $s['listed'] ); } ),
	array( 'Requires', function ( $s ) { return esc_html( 'WordPress ' . $s['requiresWp'] . '+ · PHP ' . ( '—' === $s['requiresPhp'] ? 'not stated' : $s['requiresPhp'] . '+' ) ); } ),
	array( 'Tested up to', function ( $s ) { return esc_html( 'WordPress ' . $s['testedUpTo'] ); } ),
);
?>
<div class="vm:card vm:overflow-hidden">
	<div class="vm:grid vm:grid-cols-[1fr_1fr] vm:gap-4 vm:border-b vm:border-line vm:bg-paper-deep/60 vm:px-6 vm:py-3 vm:sm:grid-cols-[1fr_1.2fr_1.2fr]"><span class="vm:eyebrow vm:hidden vm:text-ink-mute vm:sm:block">WordPress.org</span><span class="vm:text-sm vm:font-bold">VuloMail</span><span class="vm:text-sm vm:font-bold"><?php echo esc_html( $c['name'] ); ?></span></div>
	<div class="vm:divide-y vm:divide-line/70">
		<?php foreach ( $rows as $r ) : ?>
			<div class="vm:grid vm:grid-cols-2 vm:gap-x-4 vm:gap-y-1 vm:px-6 vm:py-3.5 vm:text-sm vm:sm:grid-cols-[1fr_1.2fr_1.2fr]"><p class="vm:col-span-2 vm:text-ink-mute vm:sm:col-span-1"><?php echo esc_html( $r[0] ); ?></p><p><?php echo $r[1]( $us ); // phpcs:ignore ?></p><p><?php echo $r[1]( $c['stats'] ); // phpcs:ignore ?></p></div>
		<?php endforeach; ?>
	</div>
</div>
