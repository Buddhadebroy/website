<?php
/**
 * Home: getting started – three steps and four promises.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = 'Getting started';
$title    = 'Live in minutes. <span class="text-sage">In control always.</span>';
$steps    = array( // icon, title, text
	array( 'download', 'Install', 'Add VuloPilot from the WordPress plugin directory, or upload the zip. Needs WordPress 6.7+ and PHP 8.1+.' ),
	array( 'scan-line', 'Run your first scan', 'Open the Dashboard and click Run scan. Every scanner runs and all six scores refresh.' ),
	array( 'thumbs-up', 'Approve what helps', 'AI proposes, you preview before and after, then approve or reject.' ),
);
$promises = array( // icon, text
	array( 'thumbs-up', 'Nothing changes until you approve' ),
	array( 'undo-2', 'Undo any applied change' ),
	array( 'cpu', 'Core scans work without any AI service' ),
	array( 'lock', 'AI crawler logs store bot name and page, never visitor IPs' ),
);
?>
<section class="px-5 py-16">
	<div class="mx-auto max-w-6xl">
		<div class="mx-auto max-w-2xl text-center" <?php vp_reveal(); ?>>
			<?php vp_eyebrow( $eyebrow ); ?>
			<h2 class="mt-5 font-display text-4xl leading-tight sm:text-5xl"><?php echo wp_kses_post( $title ); ?></h2>
		</div>
		<div class="mt-9 grid gap-5 md:grid-cols-3">
			<?php foreach ( $steps as $i => $s ) : ?>
				<div <?php vp_reveal( $i * 0.1 ); ?>>
					<div class="vp-hover card relative h-full p-7">
						<span class="absolute right-6 top-5 font-display text-6xl text-line"><?php echo (int) $i + 1; ?></span>
						<span class="grid h-12 w-12 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( $s[0], 22 ); ?></span>
						<h3 class="mt-5 font-display text-2xl"><?php echo esc_html( $s[1] ); ?></h3>
						<p class="mt-2 text-muted"><?php echo esc_html( $s[2] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="mt-8" <?php vp_reveal(); ?>>
			<div class="grid gap-3 rounded-sm border border-line bg-surface/70 p-4 sm:grid-cols-2 lg:grid-cols-4">
				<?php foreach ( $promises as $p ) : ?>
					<div class="flex items-center gap-3 rounded-sm p-3 text-sm"><?php vp_i( $p[0], 18, 'shrink-0 text-apricot' ); ?><?php echo esc_html( $p[1] ); ?></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
