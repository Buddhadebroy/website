<?php
/**
 * Graphic: SMS phone with "Low stock" stamp (home)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="vm:relative">
	<div class="vm:absolute vm:-left-6 vm:top-10 vm:hidden vm:md:block" data-vm-parallax="-0.25" data-vm-rotate="-8">
		<div class="vm:stamp vm:w-40 vm:bg-red vm:p-4 vm:text-paper">
			<p class="vm:eyebrow vm:text-paper/70">Alert</p>
			<p class="vm:display vm:mt-2 vm:text-2xl">Low stock</p>
		</div>
	</div>
	<div data-vm-parallax="0.15">
		<?php
		echo vm_phone( // phpcs:ignore
			array(
				array( 'VuloMail · New order', 'New order #1042 from Ava: $86.00.' ),
				array( 'VuloMail · Low stock', 'Low stock: Ceramic planter (CP-12) has 3 left.' ),
				array( 'VuloMail · Email failed', 'Juniper & Co.: an email failed. “Your receipt”: HTTP 401' ),
				array( 'VuloMail · Admin login', 'maya logged in to Juniper & Co. from 203.0.113.24.' ),
			),
			'Juniper & Co.'
		);
		?>
	</div>
</div>
