<?php
/**
 * Graphic: Header navigation (desktop, from the "VuloMail header menu")
 *
 * Top-level items and dropdown cards, built from Appearance → Menus → VuloMail header menu.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$tree = vm_nav_tree();
echo '<nav class="vm:hidden vm:items-center vm:gap-1 vm:lg:flex" data-vm-nav aria-label="Main">';
foreach ( $tree as $n ) {
	if ( isset( $n['items'] ) ) {
		$active = false;
		foreach ( $n['items'] as $i ) {
			$active = $active || vm_is_current( $i['href'] );
		}
		echo '<div class="vm:relative" data-vm-dropdown>';
		echo '<button type="button" aria-expanded="false" class="vm:relative vm:flex vm:items-center vm:gap-1 vm:rounded-full vm:px-4 vm:py-2 vm:text-[0.92rem] vm:text-ink-soft vm:transition-colors vm:hover:text-ink">';
		echo $active ? '<span class="vm-nav-active vm:absolute vm:inset-0 vm:rounded-full vm:bg-paper-deep"></span>' : ''; // phpcs:ignore
		echo '<span class="vm:relative">' . esc_html( $n['label'] ) . '</span>' . vm_icon( 'chevron-down', 'vm:relative vm:h-3.5 vm:w-3.5 vm:transition-transform vm:duration-300' ) . '</button>'; // phpcs:ignore
		echo '<div class="vm-dropdown vm:absolute vm:left-1/2 vm:top-full vm:w-[480px] vm:-translate-x-1/2 vm:pt-3" hidden><div class="vm:card vm:grid vm:grid-cols-2 vm:gap-1 vm:p-2">';
		foreach ( $n['items'] as $i ) {
			$ext = ! empty( $i['external'] );
			echo '<a href="' . esc_url( $i['href'] ) . '"' . ( $ext ? ' target="_blank" rel="noopener noreferrer"' : '' ) . ' class="vm:group vm:flex vm:gap-3 vm:rounded-2xl vm:p-3 vm:transition-colors vm:hover:bg-paper">';
			echo '<span class="vm:grid vm:h-9 vm:w-9 vm:shrink-0 vm:place-items-center vm:rounded-xl vm:bg-paper-deep vm:text-ink vm:transition-all vm:duration-300 vm:group-hover:-rotate-6 vm:group-hover:bg-ink vm:group-hover:text-paper">' . vm_icon( $i['icon'] ? $i['icon'] : 'sparkles', 'vm:h-4 vm:w-4' ) . '</span>'; // phpcs:ignore
			echo '<span><span class="vm:flex vm:items-center vm:gap-1 vm:text-sm vm:font-medium">' . esc_html( $i['label'] ) . ( $ext ? vm_icon( 'arrow-up-right', 'vm:h-3.5 vm:w-3.5 vm:text-ink-mute' ) : '' ) . '</span>'; // phpcs:ignore
			echo '<span class="vm:block vm:text-xs vm:leading-snug vm:text-ink-mute">' . esc_html( $i['desc'] ) . '</span></span></a>';
		}
		echo '</div></div></div>';
	} else {
		$active = vm_is_current( $n['href'] );
		echo '<a href="' . esc_url( $n['href'] ) . '" data-vm-plain class="vm:relative vm:rounded-full vm:px-4 vm:py-2 vm:text-[0.92rem] vm:text-ink-soft vm:transition-colors vm:hover:text-ink">';
		echo $active ? '<span class="vm-nav-active vm:absolute vm:inset-0 vm:rounded-full vm:bg-paper-deep"></span>' : ''; // phpcs:ignore
		echo '<span class="vm:relative">' . esc_html( $n['label'] ) . '</span></a>';
	}
}
echo '</nav>';
