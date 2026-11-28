<?php
/**
 * Graphic: Five rules the code keeps (horizontal scroll)
 *
 * A 300vh section whose cards slide left (0 → −62%) while it is pinned. Copy: data/site.json → principles.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$colors = array( 'vm:bg-red', 'vm:bg-blue', 'vm:bg-green', 'vm:bg-amber', 'vm:bg-ink' );
?>
<div class="vm:relative vm:h-[300vh]" data-vm-track>
	<div class="vm:sticky vm:top-0 vm:flex vm:h-screen vm:flex-col vm:justify-center vm:overflow-hidden">
		<div class="vm:mx-auto vm:w-full vm:max-w-7xl vm:px-4 vm:sm:px-8">
			<p class="vm:eyebrow vm:text-ink-mute">Five rules the code keeps</p>
			<h2 class="vm:display vm:mt-4 vm:max-w-3xl vm:text-5xl vm:sm:text-7xl">Built to be <em class="vm:text-ink-soft">boring</em> in all the right ways.</h2>
		</div>
		<div data-vm-track-x class="vm:mt-12 vm:flex vm:w-max vm:gap-5 vm:pl-4 vm:sm:pl-[max(2rem,calc((100vw-80rem)/2+2rem))]">
			<?php foreach ( vm_principles() as $i => $p ) : ?>
				<article class="vm:card vm:relative vm:flex vm:h-[300px] vm:w-[300px] vm:shrink-0 vm:flex-col vm:justify-between vm:overflow-hidden vm:p-7 vm:sm:h-[340px] vm:sm:w-[400px]">
					<span class="vm:absolute vm:right-0 vm:top-0 vm:h-24 vm:w-24 vm:-translate-y-1/2 vm:translate-x-1/2 vm:rounded-full <?php echo esc_attr( $colors[ $i % 5 ] ); ?> vm:opacity-15"></span>
					<span class="vm:display vm:text-6xl vm:text-ink/20">0<?php echo (int) $i + 1; ?></span>
					<div>
						<h3 class="vm:display vm:text-3xl vm:sm:text-4xl"><?php echo esc_html( $p['title'] ); ?></h3>
						<p class="vm:mt-3 vm:leading-relaxed vm:text-ink-soft"><?php echo esc_html( $p['body'] ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
			<div class="vm:w-[20vw] vm:shrink-0"></div>
		</div>
	</div>
</div>
