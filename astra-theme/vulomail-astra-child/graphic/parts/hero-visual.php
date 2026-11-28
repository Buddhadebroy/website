<?php
/**
 * Graphic: Home hero visual (live log, routing stamp, SMS card)
 *
 * Four layers that follow the pointer and drift with the scroll (depth 0.3 / 0.6 / 1.1 / 1.5), each with
 * its own entrance. Shown from 1024px up, like the original.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="vm:relative vm:mx-auto vm:hidden vm:h-[min(560px,calc(100svh-10rem))] vm:min-h-[440px] vm:w-full vm:max-w-[560px] vm:lg:block" data-vm-hero-visual>
	<div class="vm:absolute vm:right-0 vm:top-0 vm:hidden vm:sm:block" data-vm-layer="0.3">
		<?php echo vm_postmark( 170, 'Delivered' ); // phpcs:ignore ?>
	</div>
	<div class="vm:absolute vm:left-0 vm:right-4 vm:top-16 vm:sm:right-10" data-vm-layer="0.6">
		<div data-vm-enter='{"from":{"opacity":0,"y":40,"rotate":-2},"to":{"opacity":1,"y":0,"rotate":-1.5},"duration":1.1,"delay":0.3}' style="opacity:0;transform:translateY(40px) rotate(-2deg)">
			<?php echo vm_live_log( 4 ); // phpcs:ignore ?>
		</div>
	</div>
	<div class="vm:absolute vm:bottom-0 vm:left-0 vm:w-[230px] vm:sm:-left-10 vm:sm:bottom-16 vm:sm:w-[250px]" data-vm-layer="1.1">
		<div data-vm-enter='{"from":{"opacity":0,"y":40,"rotate":4},"to":{"opacity":1,"y":0,"rotate":3},"duration":1.1,"delay":0.55}' style="opacity:0;transform:translateY(40px) rotate(4deg)">
			<div class="vm:stamp vm:bg-surface vm:p-5 vm:shadow-[0_24px_50px_-24px_rgba(19,20,27,0.45)]">
				<p class="vm:eyebrow vm:text-ink-mute">Email routing</p>
				<ul class="vm:mt-3 vm:space-y-2 vm:text-sm">
					<li class="vm:flex vm:items-center vm:justify-between"><span class="vm:flex vm:items-center vm:gap-2"><span class="vm:h-2 vm:w-2 vm:rounded-full vm:bg-green"></span> Mailgun</span><?php echo vm_pill( 'Primary', 'green' ); // phpcs:ignore ?></li>
					<li class="vm:flex vm:items-center vm:justify-between"><span class="vm:flex vm:items-center vm:gap-2"><span class="vm:h-2 vm:w-2 vm:rounded-full vm:bg-blue"></span> SMTP</span><?php echo vm_pill( 'Backup', 'blue' ); // phpcs:ignore ?></li>
					<li class="vm:flex vm:items-center vm:justify-between vm:text-ink-mute"><span class="vm:flex vm:items-center vm:gap-2"><span class="vm:h-2 vm:w-2 vm:rounded-full vm:bg-line-strong"></span> WordPress</span><span class="vm:text-xs">Last resort</span></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="vm:absolute vm:-right-6 vm:bottom-0 vm:hidden vm:w-[240px] vm:sm:block" data-vm-layer="1.5">
		<div data-vm-enter='{"from":{"opacity":0,"y":50,"scale":0.9},"to":{"opacity":1,"y":0,"scale":1},"duration":1.1,"delay":0.8}' style="opacity:0;transform:translateY(50px) scale(0.9)">
			<div class="vm:animate-float vm:rounded-[1.4rem] vm:bg-ink vm:p-4 vm:text-paper vm:shadow-[0_30px_60px_-25px_rgba(19,20,27,0.7)]">
				<div class="vm:flex vm:items-center vm:gap-2 vm:text-[0.7rem] vm:text-paper/50"><?php echo vm_icon( 'message-square-text', 'vm:h-3.5 vm:w-3.5' ); // phpcs:ignore ?> SMS · now</div>
				<p class="vm:mt-2 vm:text-sm vm:leading-snug"><span class="vm:text-paper/60">Juniper &amp; Co.</span> New order #1042 from Ava: $86.00.</p>
			</div>
		</div>
	</div>
</div>
