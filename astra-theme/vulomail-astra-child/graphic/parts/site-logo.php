<?php
/**
 * Graphic: Site logo (Customize → Site Identity)
 *
 * The logo set in Customize → Site Identity (Astra's logo), followed by the site title.
 * Without a logo, only the site title is shown. Props: link (bool, default true).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$link   = ! isset( $a['link'] ) || $a['link'];
$logo   = '';
$logoid = (int) get_theme_mod( 'custom_logo' );
if ( $logoid ) {
	$logo = wp_get_attachment_image( $logoid, 'full', false, array( 'class' => 'vm-logo-img vm:h-[1.95rem] vm:w-auto', 'alt' => '', 'loading' => 'eager', 'aria-hidden' => 'true' ) );
}
$title  = get_bloginfo( 'name' );
$accent = (string) vm_opt( 'title_accent' );
$name   = esc_html( $title );
if ( $accent && false !== strpos( $title, $accent ) ) {
	$pos  = strrpos( $title, $accent );
	$name = esc_html( substr( $title, 0, $pos ) ) . '<span class="vm:text-blue">' . esc_html( $accent ) . '</span>' . esc_html( substr( $title, $pos + strlen( $accent ) ) );
}
$inner = '<span class="vm:inline-flex vm:items-center vm:gap-[0.2rem]">' . $logo
	. ( vm_opt( 'show_title' ) || ! $logo ? '<span class="vm:-translate-y-[0.14rem] vm:text-[1.3rem] vm:leading-none vm:font-bold vm:tracking-tight">' . $name . '</span>' : '' )
	. '</span>';
if ( $link ) {
	echo '<a href="' . esc_url( home_url( '/' ) ) . '" aria-label="' . esc_attr( $title . ' home' ) . '" class="vm:inline-block vm:transition-transform vm:duration-300 vm:hover:-rotate-2">' . $inner . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
} else {
	echo $inner; // phpcs:ignore WordPress.Security.EscapeOutput
}
