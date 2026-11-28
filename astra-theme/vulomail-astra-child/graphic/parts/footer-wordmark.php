<?php
/**
 * Graphic: Footer wordmark (scroll-linked)
 *
 * The giant site title that slides in (x −18% → 0, opacity 0 → 0.4 → 1) as the footer scrolls into view.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$title  = get_bloginfo( 'name' );
$accent = (string) vm_opt( 'title_accent' );
$name   = esc_html( $title );
if ( $accent && false !== strpos( $title, $accent ) ) {
	$pos  = strrpos( $title, $accent );
	$name = esc_html( substr( $title, 0, $pos ) ) . '<em class="vm:text-blue/[0.12]">' . esc_html( $accent ) . '</em>' . esc_html( substr( $title, $pos + strlen( $accent ) ) );
}
echo '<p aria-hidden="true" data-vm-wordmark style="opacity:0;transform:translateX(-18%)" class="vm:display vm:pointer-events-none vm:select-none vm:whitespace-nowrap vm:px-4 vm:text-[24vw] vm:leading-[0.8] vm:text-ink/[0.07] vm:sm:px-8">' . $name . '</p>'; // phpcs:ignore
