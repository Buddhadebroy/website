<?php
/**
 * Graphic: Where each one wins (comparison page)
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
$list = function ( $title, $items, $tone ) {
	$dot = 'blue' === $tone ? 'vm:bg-blue' : 'vm:bg-ink';
	$h   = '<div class="vm:card vm:h-full vm:p-8' . ( 'blue' === $tone ? ' vm:border-blue/30' : '' ) . '">'
		. '<span class="vm:eyebrow vm:inline-flex vm:items-center vm:gap-2 vm:rounded-full vm:border vm:border-line vm:bg-surface/80 vm:px-3 vm:py-1.5 vm:text-ink-soft vm:backdrop-blur"><span class="vm:relative vm:h-1.5 vm:w-1.5 vm:rounded-full ' . $dot . '"><span class="vm:absolute vm:inset-0 vm:animate-ping vm:rounded-full ' . $dot . ' vm:opacity-60"></span></span>' . esc_html( $title ) . '</span><ul class="vm:mt-6 vm:space-y-4">';
	foreach ( $items as $i ) {
		$h .= '<li class="vm:flex vm:gap-3 vm:leading-relaxed vm:text-ink-soft"><span class="vm:mt-2 vm:h-1.5 vm:w-1.5 vm:shrink-0 vm:rounded-full ' . $dot . '"></span>' . esc_html( $i ) . '</li>';
	}
	return $h . '</ul></div>';
};
echo '<div class="vm:mt-12 vm:grid vm:gap-6 vm:lg:grid-cols-2"><div data-vm-reveal>' . $list( 'Where ' . $c['name'] . ' is stronger', $c['theyWin'], 'ink' ) . '</div><div data-vm-reveal data-delay="0.1">' . $list( 'Where VuloMail is stronger', $c['weWin'], 'blue' ) . '</div></div>'; // phpcs:ignore
