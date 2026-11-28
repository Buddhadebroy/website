<?php
/**
 * Graphic: Free / paid / not offered / not mentioned legend
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="vm:flex vm:flex-wrap vm:items-center vm:gap-x-4 vm:gap-y-2 vm:text-xs vm:text-ink-mute">
	<span class="vm:flex vm:items-center vm:gap-2"><?php echo vm_avail_badge( 'yes' ); // phpcs:ignore ?> in the free plugin</span>
	<span class="vm:flex vm:items-center vm:gap-2"><?php echo vm_avail_badge( 'paid' ); // phpcs:ignore ?> needs a paid plan</span>
	<span class="vm:flex vm:items-center vm:gap-2"><?php echo vm_avail_badge( 'no' ); // phpcs:ignore ?> not offered</span>
	<span class="vm:flex vm:items-center vm:gap-2"><?php echo vm_avail_badge( 'na' ); // phpcs:ignore ?> the vendor doesn&#039;t mention it</span>
</div>
