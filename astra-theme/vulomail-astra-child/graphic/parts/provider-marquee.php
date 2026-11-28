<?php
/**
 * Graphic: Provider marquee
 *
 * Every provider name from data/site.json, twice, scrolling at 2 s per name; pauses on hover.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$names = wp_list_pluck( vm_providers(), 'name' );
echo '<div class="vm:relative vm:mt-6 vm:overflow-hidden vm:[mask-image:linear-gradient(90deg,transparent,black_12%,black_88%,transparent)]">';
echo '<div class="vm:flex vm:w-max vm:animate-marquee vm:gap-14 vm:pr-14 vm:hover:[animation-play-state:paused]" style="animation-duration:' . ( count( $names ) * 2 ) . 's">';
foreach ( array_merge( $names, $names ) as $n ) {
	echo '<span class="vm:display vm:flex vm:items-center vm:gap-14 vm:text-4xl vm:text-ink/70 vm:transition-colors vm:hover:text-ink">' . esc_html( $n ) . '<span class="vm:h-2 vm:w-2 vm:rotate-45 vm:bg-red"></span></span>';
}
echo '</div></div>';
