<?php
/**
 * Graphic: Table (blog posts)
 *
 * Props: head (list), rows (list of lists). Cells accept [links](/path), **bold** and `code`.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="vm:mt-7 vm:overflow-x-auto vm:rounded-2xl vm:border vm:border-line vm:bg-surface">
	<table class="vm:w-full vm:min-w-[34rem] vm:border-collapse vm:text-left vm:text-sm">
		<thead><tr class="vm:border-b vm:border-line vm:bg-paper-deep/60"><?php foreach ( (array) ( $a['head'] ?? array() ) as $h ) : ?><th scope="col" class="vm:px-4 vm:py-3 vm:font-semibold vm:text-ink"><?php echo vm_rich( $h ); // phpcs:ignore ?></th><?php endforeach; ?></tr></thead>
		<tbody>
			<?php foreach ( (array) ( $a['rows'] ?? array() ) as $r ) : ?>
				<tr class="vm:border-b vm:border-line vm:last:border-0"><?php foreach ( $r as $j => $c ) : ?><td class="vm:px-4 vm:py-3 vm:align-top vm:leading-relaxed <?php echo 0 === $j ? 'vm:font-medium vm:text-ink' : 'vm:text-ink-soft'; ?>"><?php echo vm_rich( $c ); // phpcs:ignore ?></td><?php endforeach; ?></tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
