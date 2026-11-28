<?php
/**
 * Graphic: Also looked at + How we compared (Compare)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$cmp = vm_compare();
?>
<div class="vm:grid vm:gap-6 vm:lg:grid-cols-2">
	<div data-vm-reveal class="vm:card vm:p-8">
		<p class="vm:eyebrow vm:text-ink-mute">Also looked at</p>
		<h2 class="vm:display vm:mt-3 vm:text-3xl">Not a like-for-like match</h2>
		<p class="vm:mt-3 vm:text-sm vm:leading-relaxed vm:text-ink-soft">These came up in the same searches but do a narrower job, so they don&#039;t get a full comparison.</p>
		<ul class="vm:mt-6 vm:divide-y vm:divide-line/70">
			<?php foreach ( $cmp['alsoConsidered'] as $x ) : ?>
				<li class="vm:flex vm:flex-col vm:gap-1 vm:py-3.5 vm:sm:flex-row vm:sm:items-baseline vm:sm:justify-between vm:sm:gap-6">
					<a href="https://wordpress.org/plugins/<?php echo esc_attr( $x['slug'] ); ?>/" target="_blank" rel="noopener noreferrer" class="vm:inline-flex vm:shrink-0 vm:items-center vm:gap-1 vm:font-medium vm:text-blue vm:underline-offset-4 vm:hover:underline"><?php echo esc_html( $x['name'] ); ?> <?php echo vm_icon( 'arrow-up-right', 'vm:h-3.5 vm:w-3.5' ); // phpcs:ignore ?></a>
					<span class="vm:text-sm vm:text-ink-soft vm:sm:text-right"><?php echo esc_html( vm_format_installs( $x['installs'] ) . ' installs · ' . $x['why'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div data-vm-reveal data-delay="0.1" class="vm:card vm:p-8">
		<p class="vm:eyebrow vm:text-ink-mute">Method</p>
		<h2 class="vm:display vm:mt-3 vm:text-3xl">How we compared</h2>
		<ul class="vm:mt-5 vm:space-y-3 vm:text-sm vm:leading-relaxed vm:text-ink-soft">
			<li>We searched the WordPress.org directory for SMTP, email delivery, email logging and SMS plugins, and kept the most-installed ones that deliver WordPress email or send SMS alerts.</li>
			<li>Installs, ratings, versions and dates come from the WordPress.org plugin API.</li>
			<li>Free and paid features come from each plugin&#039;s WordPress.org description and its vendor&#039;s pricing page. Where they disagreed, we read the free plugin&#039;s code: that&#039;s how we confirmed Post SMTP&#039;s fallback is free and GoSMTP&#039;s backup setting needs Pro.</li>
			<li>“Not listed” means the vendor doesn&#039;t mention the feature. It may still exist, so we don&#039;t count it either way.</li>
			<li>VuloMail is ours. It was listed on WordPress.org on <?php echo esc_html( $cmp['vulomail']['stats']['listed'] ); ?>, so it has no installs or reviews to compare yet.</li>
		</ul>
	</div>
</div>
