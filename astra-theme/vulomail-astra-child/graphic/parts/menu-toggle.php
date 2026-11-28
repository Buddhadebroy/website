<?php
/**
 * Graphic: Mobile menu button + full-screen menu
 *
 * Round button (below 1024px) that opens the full-screen menu with a circular reveal.
 * Links come from the "VuloMail header menu": groups keep their names, top-level links share "Choose".
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$tree     = vm_nav_tree();
$groups   = array_values( array_filter( $tree, function ( $n ) { return isset( $n['items'] ); } ) );
$singles  = array_values( array_filter( $tree, function ( $n ) { return ! isset( $n['items'] ); } ) );
$sections = array( array( 'label' => 'Menu', 'items' => array( array( 'href' => home_url( '/' ), 'label' => 'Home', 'external' => false ) ) ) );
if ( isset( $groups[0] ) ) {
	$sections[] = $groups[0];
}
$sections[] = array( 'label' => 'Choose', 'items' => $singles );
foreach ( array_slice( $groups, 1 ) as $g ) {
	$sections[] = $g;
}
?>
<button type="button" data-vm-menu-toggle aria-label="Open menu" aria-expanded="false" class="vm:grid vm:h-11 vm:w-11 vm:place-items-center vm:rounded-full vm:border vm:border-line vm:bg-surface vm:lg:hidden">
	<span class="vm-menu-icon vm-menu-open"><?php echo vm_icon( 'menu', 'vm:h-5 vm:w-5' ); // phpcs:ignore ?></span>
	<span class="vm-menu-icon vm-menu-close" hidden><?php echo vm_icon( 'x', 'vm:h-5 vm:w-5' ); // phpcs:ignore ?></span>
</button>
<div class="vm-mobile-menu vm:fixed vm:inset-0 vm:z-40 vm:overflow-y-auto vm:bg-paper vm:px-6 vm:pb-10 vm:pt-28 vm:lg:hidden" data-vm-mobile-menu hidden>
	<div class="vm:airmail vm:absolute vm:inset-x-0 vm:top-0 vm:h-2"></div>
	<div class="vm:space-y-8">
		<?php foreach ( $sections as $sec ) : ?>
			<div>
				<?php if ( 'Menu' !== $sec['label'] ) : ?>
					<p class="vm:eyebrow vm:mb-1 vm:text-ink-mute" data-vm-mm="fade"><?php echo esc_html( $sec['label'] ); ?></p>
				<?php endif; ?>
				<ul>
					<?php
					foreach ( $sec['items'] as $item ) :
						$ext = ! empty( $item['external'] );
						$cur = vm_is_current( $item['href'] ) && ( untrailingslashit( wp_parse_url( $item['href'], PHP_URL_PATH ) ?: '' ) === untrailingslashit( wp_parse_url( home_url( add_query_arg( array() ) ), PHP_URL_PATH ) ?: '' ) );
						?>
						<li data-vm-mm="rise">
							<a href="<?php echo esc_url( $item['href'] ); ?>"<?php echo $ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?> class="vm:display vm:flex vm:items-center vm:justify-between vm:border-b vm:border-line vm:py-3 vm:text-3xl<?php echo $cur ? ' vm:text-blue' : ''; ?>">
								<?php echo esc_html( $item['label'] ); ?>
								<?php echo $ext ? vm_icon( 'arrow-up-right', 'vm:h-5 vm:w-5 vm:text-ink-mute' ) : vm_icon( 'arrow-right', 'vm:h-5 vm:w-5 vm:text-ink-mute' ); // phpcs:ignore ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</div>
</div>
