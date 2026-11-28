<?php
/**
 * Graphic: More comparisons (comparison page)
 *
 * Props: slug (the current one is left out).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$slug = $a['slug'] ?? '';
$all  = vm_competitors();
?>
<div class="vm:flex vm:flex-wrap vm:items-end vm:justify-between vm:gap-4">
	<h2 class="vm:display vm:text-4xl">More comparisons</h2>
	<a href="<?php echo esc_url( vm_url( '/compare' ) ); ?>" class="vm:inline-flex vm:items-center vm:gap-1.5 vm:text-sm vm:font-medium vm:text-blue">All <?php echo count( $all ); ?> comparisons <?php echo vm_icon( 'arrow-right', 'vm:h-4 vm:w-4' ); // phpcs:ignore ?></a>
</div>
<?php echo vm_part( 'compare-cards', array( 'exclude' => $slug, 'limit' => 3, 'class' => 'vm:mt-8 vm:grid vm:gap-5 vm:md:grid-cols-3' ) ); // phpcs:ignore
