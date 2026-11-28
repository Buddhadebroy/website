<?php
/**
 * Home: automation cards + automations screenshot.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = 'Automation';
$title   = 'Set up VuloPilot. <span class="text-sage">It keeps working.</span>';
$intro   = 'Free covers scheduled scans, reports and backups. Pro adds rules, security watch and scheduled audits.';
$result  = 'Result:';
$shot    = 'automations'; // Screenshot under the cards ('' to hide).

// plan, icon, when, title, description, flow steps, result, colour
$cards = array(
	array( 'Free', 'calendar-check', 'daily, weekly or monthly', 'Run Full Site Scan', 'Every scanner runs on a schedule and every score refreshes.', array( 'Scan', 'Score', 'Compare' ), 'The dashboard stays honest without you.', 'var(--sage)' ),
	array( 'Free', 'file-plus-corner', 'on your schedule', 'Send Visibility Report', 'A summary of visibility, issues and opportunities, emailed to you.', array( 'Check', 'Summarize', 'Email you' ), 'Start the week knowing what needs you.', 'var(--lilac)' ),
	array( 'Free', 'hourglass', 'automatic backups', 'Backups that run themselves', 'Save a copy of your site, then download, restore or delete it.', array( 'Back up', 'Store', 'Restore if needed' ), 'Recover from any mistake or hack.', 'var(--apricot)' ),
	array( 'Pro', 'timer', 'if / then rules', 'Workflow automation', 'Run a rule only when conditions are true, such as the score dropping below 70.', array( 'Condition', 'Step', 'Retry', 'Log' ), 'Every run is logged, so you see why it did or didn’t fire.', 'var(--apricot)' ),
	array( 'Pro', 'shield-alert', 'always on', 'Security watch', 'Vulnerabilities, file changes, exposed files and debug mode.', array( 'Notice', 'Check the risk', 'Alert you' ), 'You hear about it before it becomes a problem.', 'var(--rose)' ),
	array( 'Pro', 'pen-line', 'scheduled', 'Audits, rankings & reports', 'Hourly-to-weekly accessibility audits, daily keyword sync and scheduled PDF reports.', array( 'Audit', 'Sync', 'Report' ), 'History shows whether you’re improving.', 'var(--lilac)' ),
);
?>
<section id="automation" class="px-5 py-16">
	<div class="mx-auto max-w-6xl">
		<div class="mx-auto max-w-3xl text-center" <?php vp_reveal(); ?>>
			<?php vp_eyebrow( $eyebrow ); ?>
			<h2 class="mt-5 font-display text-4xl leading-tight sm:text-6xl"><?php echo wp_kses_post( $title ); ?></h2>
			<p class="mt-4 text-lg text-muted"><?php echo esc_html( $intro ); ?></p>
		</div>

		<div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $cards as $i => $c ) : list( $plan, $icon, $when, $t, $d, $flow, $r, $col ) = $c; ?>
				<div <?php vp_reveal( ( $i % 3 ) * 0.08 ); ?>>
					<div class="vp-hover card group h-full p-6">
						<div class="flex items-center justify-between">
							<span class="flex items-center gap-2">
								<span class="grid h-11 w-11 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( $col, 16 ) ); ?>"><?php vp_i( $icon, 20 ); ?></span>
								<span class="rounded-sm px-2.5 py-0.5 font-mono text-[10px] uppercase tracking-widest <?php echo 'Pro' === $plan ? 'bg-apricot text-white' : 'bg-sage-soft text-sage'; ?>"><?php echo esc_html( $plan ); ?></span>
							</span>
							<span class="flex items-center gap-1.5 font-mono text-[11px] uppercase tracking-wider text-muted"><span class="h-1.5 w-1.5 animate-pulse rounded-full" style="background:<?php echo esc_attr( $col ); ?>"></span><?php echo esc_html( $when ); ?></span>
						</div>
						<h3 class="mt-5 font-display text-2xl"><?php echo esc_html( $t ); ?></h3>
						<p class="mt-1 text-muted"><?php echo esc_html( $d ); ?></p>
						<div class="mt-5 flex flex-wrap items-center gap-1.5">
							<?php foreach ( $flow as $o => $step ) : ?>
								<span class="flex items-center gap-1.5" <?php vp_m( 'o:0 x:-8 d:' . ( 0.3 + $o * 0.18 ) ); ?>>
									<span class="rounded-sm border border-line bg-bg px-3 py-1 text-xs"><?php echo esc_html( $step ); ?></span>
									<?php if ( $o < count( $flow ) - 1 ) { vp_i( 'chevron-right', 12, 'text-muted' ); } ?>
								</span>
							<?php endforeach; ?>
						</div>
						<p class="mt-5 border-t border-line pt-4 text-sm"><span class="font-medium" style="color:<?php echo esc_attr( $col ); ?>"><?php echo esc_html( $result ); ?></span> <?php echo esc_html( $r ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $shot ) : ?>
			<div class="mt-10" <?php vp_reveal(); ?>><?php echo vp_shot( $shot, true ); // phpcs:ignore ?></div>
		<?php endif; ?>
	</div>
</section>
