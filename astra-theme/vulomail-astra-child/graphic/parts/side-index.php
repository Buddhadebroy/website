<?php
/**
 * Graphic: "On this page" sticky index
 *
 * Highlights the section in view (red bar slides between items). Props: items ([[id, label], …]).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$items = isset( $a['items'] ) ? $a['items'] : array();
?>
<nav aria-label="On this page" class="vm:sticky vm:top-28 vm:hidden vm:lg:block vm-sticky" data-vm-spy>
	<p class="vm:eyebrow vm:mb-4 vm:text-ink-mute">On this page</p>
	<ul class="vm:relative vm:space-y-1 vm:border-l vm:border-line">
		<span class="vm-spy-bar vm:absolute vm:-left-px vm:top-0 vm:w-0.5 vm:bg-red" style="height:0"></span>
		<?php foreach ( $items as $k => $i ) : ?>
			<li class="vm:relative"><a href="#<?php echo esc_attr( $i[0] ); ?>" data-vm-spy-link="<?php echo esc_attr( $i[0] ); ?>" class="vm:block vm:py-1.5 vm:pl-4 vm:text-sm vm:transition-colors <?php echo 0 === $k ? 'vm:font-medium vm:text-ink' : 'vm:text-ink-mute vm:hover:text-ink'; ?>"><?php echo esc_html( $i[1] ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</nav>
