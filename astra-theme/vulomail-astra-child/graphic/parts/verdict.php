<?php
/**
 * Graphic: Choose them if / Choose VuloMail if (comparison page)
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
$check = function ( $items ) {
	$h = '<ul class="vm:mt-5 vm:space-y-3">';
	foreach ( $items as $i ) {
		$h .= '<li class="vm:flex vm:gap-3 vm:leading-relaxed"><span class="vm:mt-1 vm:grid vm:h-5 vm:w-5 vm:shrink-0 vm:place-items-center vm:rounded-full vm:bg-green vm:text-paper">' . vm_icon( 'check', 'vm:h-3 vm:w-3' ) . '</span>' . esc_html( $i ) . '</li>';
	}
	return $h . '</ul>';
};
$cta = '<a href="' . esc_url( vm_url( (string) vm_opt( 'cta_url' ) ) ) . '" data-vm-magnetic="0.25" class="vm:group vm:relative vm:inline-flex vm:h-12 vm:items-center vm:gap-2 vm:overflow-hidden vm:rounded-full vm:bg-blue vm:px-6 vm:text-[0.95rem] vm:font-medium vm:text-white vm:shadow-[0_10px_30px_-12px_rgba(0,59,205,0.6)] vm:transition-colors vm:duration-300 vm:hover:bg-blue-light"><span class="vm:relative">Get started free</span><span class="vm:relative vm:grid vm:h-5 vm:w-5 vm:place-items-center vm:overflow-hidden">' . vm_icon( 'arrow-right', 'vm:h-4 vm:w-4 vm:transition-transform vm:duration-500 vm:ease-[var(--ease-out)] vm:group-hover:translate-x-5' ) . vm_icon( 'arrow-right', 'vm:absolute vm:h-4 vm:w-4 vm:-translate-x-5 vm:transition-transform vm:duration-500 vm:ease-[var(--ease-out)] vm:group-hover:translate-x-0' ) . '</span></a>';
echo '<div class="vm:mt-12 vm:grid vm:gap-6 vm:md:grid-cols-2" data-vm-stagger>'
	. '<div data-vm-st class="vm:card vm:p-8"><p class="vm:eyebrow vm:text-ink-mute">Choose ' . esc_html( $c['name'] ) . ' if</p>' . $check( $c['chooseThem'] ) . '</div>'
	. '<div data-vm-st class="vm:card vm:border-blue/30 vm:p-8"><p class="vm:eyebrow vm:text-blue">Choose VuloMail if</p>' . $check( $c['chooseUs'] ) . '<div class="vm:mt-8"><div class="vm:inline-block">' . $cta . '</div></div></div></div>'; // phpcs:ignore
