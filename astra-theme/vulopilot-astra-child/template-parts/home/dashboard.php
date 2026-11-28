<?php
/**
 * Home: interactive dashboard demo (tabs, run scan, approve a fix).
 * The numbers for every tab are in $tabs below; the script reads them from data-vp-data.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = 'Inside VuloPilot';
$title   = 'Finally, see what’s <span class="text-sage">really happening.</span>';
$intro   = 'Six scores, a health timeline, a needs-your-attention list and recent changes you can undo. Try it: switch tabs, run a scan, approve a fix.';
$url     = 'yourstore.com / wp-admin / vulopilot';

// Per tab: overall score, 9-point history line (20-100), six tile scores (same order as $tiles).
$tabs = array(
	'SEO'             => array( 'overall' => 82, 'line' => array( 48, 52, 50, 58, 63, 61, 70, 76, 82 ), 'tiles' => array( 84, 71, 92, 97, 76, 68 ) ),
	'GEO'             => array( 'overall' => 74, 'line' => array( 30, 36, 41, 40, 52, 58, 63, 70, 74 ), 'tiles' => array( 78, 66, 88, 94, 72, 71 ) ),
	'AEO'             => array( 'overall' => 69, 'line' => array( 22, 28, 35, 37, 44, 52, 58, 64, 69 ), 'tiles' => array( 73, 64, 90, 95, 70, 66 ) ),
	'Brand'           => array( 'overall' => 77, 'line' => array( 40, 46, 45, 52, 58, 66, 69, 74, 77 ), 'tiles' => array( 80, 69, 91, 96, 74, 77 ) ),
	'Knowledge Graph' => array( 'overall' => 71, 'line' => array( 26, 33, 38, 43, 49, 55, 61, 67, 71 ), 'tiles' => array( 75, 67, 89, 95, 71, 70 ) ),
);
$tiles = array( // icon, name, question, colour
	array( 'search', 'Visibility', 'Can people and AI find you?', 'var(--sage)' ),
	array( 'file-text', 'Content', 'Are you answering them?', 'var(--lilac)' ),
	array( 'gauge', 'Performance', 'How slow, really?', 'var(--flare)' ),
	array( 'heart-pulse', 'Health', 'How are WordPress and your server?', 'var(--sage)' ),
	array( 'shopping-bag', 'Commerce', 'Is my store doing okay?', 'var(--rose)' ),
	array( 'megaphone', 'Brand', 'Does your brand look trustworthy?', 'var(--lilac)' ),
);
$tasks = array( // task, area, colour (each approval adds 3 points)
	array( 'Update 2 outdated plugins', 'Security', 'var(--rose)' ),
	array( 'Compress the homepage hero image', 'Speed', 'var(--flare)' ),
	array( 'Add FAQ schema to /services', 'AI visibility', 'var(--lilac)' ),
);
$sidebar = array( 'layout-dashboard', 'bot', 'search', 'pen-line', 'gauge', 'heart-pulse', 'shield-check', 'shopping-bag', 'calendar-clock' );
$t = array(
	'timeline' => 'Health timeline',
	'sub'      => '%s score, last 9 scans',
	'good'     => 'Good',
	'needs'    => 'Needs work',
	'attn'     => 'Needs your attention',
	'open'     => 'open',
	'fix'      => 'Fix with AI',
	'approved' => 'Approved',
	'note'     => 'Nothing changes until you approve. Approved changes can be undone.',
	'scan'     => 'Run scan',
	'scanning' => 'Scanning…',
	'score'    => 'Score',
);

// Optional overrides. 'graphic_only' prints just the dashboard window ([vp_dashboard_demo]).
$args  = isset( $args ) && is_array( $args ) ? $args : array();
$only  = ! empty( $args['graphic_only'] );
$url   = isset( $args['url'] ) ? $args['url'] : $url;
$tasks = ! empty( $args['tasks'] ) ? $args['tasks'] : $tasks;

$first = array_key_first( $tabs );
$d     = $tabs[ $first ];
if ( ! $only ) :
	?>
<section class="px-5 py-16">
	<div class="mx-auto max-w-6xl">
		<div class="mx-auto max-w-3xl text-center" <?php vp_reveal(); ?>>
			<?php vp_eyebrow( $eyebrow ); ?>
			<h2 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-6xl"><?php echo wp_kses_post( $title ); ?></h2>
			<p class="mt-4 text-lg text-muted"><?php echo esc_html( $intro ); ?></p>
		</div>

		<div class="mt-10" <?php vp_reveal(); ?>>
<?php endif; // ! $only ?>
			<div class="relative" data-vp="dash" data-vp-data="<?php echo esc_attr( wp_json_encode( array( 'tabs' => $tabs, 'text' => $t ) ) ); ?>">
				<div class="absolute -inset-4 -z-10 rounded-sm bg-gradient-to-br from-sage-soft via-lilac-soft to-apricot-soft opacity-70 blur-2xl"></div>
				<div class="overflow-hidden rounded-sm border border-line bg-surface shadow-[0_40px_90px_-40px_rgba(0,41,145,.45)]">
					<div class="flex items-center gap-2 border-b border-line bg-bg/60 px-5 py-3">
						<span class="h-2.5 w-2.5 rounded-full bg-rose/70"></span><span class="h-2.5 w-2.5 rounded-full bg-flare/60"></span><span class="h-2.5 w-2.5 rounded-full bg-sage/70"></span>
						<span class="ml-3 rounded-sm bg-surface px-4 py-1 font-mono text-[11px] text-muted ring-1 ring-line"><?php echo esc_html( $url ); ?></span>
					</div>
					<div class="grid lg:grid-cols-[64px_1fr]">
						<div class="hidden flex-col items-center gap-2 border-r border-line bg-bg/40 py-5 lg:flex">
							<?php foreach ( $sidebar as $i => $ic ) : ?>
								<span class="vp-hover grid h-10 w-10 place-items-center rounded-sm <?php echo 0 === $i ? 'bg-sage text-white' : 'text-muted hover:bg-sage-soft hover:text-sage'; ?>" style="--hy:0px;--hs:1.12"><?php vp_i( $ic, 18 ); ?></span>
							<?php endforeach; ?>
						</div>
						<div class="p-5 sm:p-7">
							<div class="flex flex-wrap items-center justify-between gap-3">
								<div class="relative flex flex-wrap gap-1.5" data-pill-group>
									<span class="vp-pill rounded-sm bg-ink"></span>
									<?php foreach ( $tabs as $name => $x ) : ?>
										<button type="button" class="relative rounded-sm px-4 py-1.5 text-sm font-medium" data-tab="<?php echo esc_attr( $name ); ?>" aria-pressed="<?php echo $name === $first ? 'true' : 'false'; ?>"><span class="relative <?php echo $name === $first ? 'text-bg' : 'text-muted hover:text-ink'; ?>"><?php echo esc_html( $name ); ?></span></button>
									<?php endforeach; ?>
								</div>
								<button type="button" class="inline-flex items-center gap-2 rounded-sm bg-sage px-4 py-2 text-sm font-semibold text-white transition hover:bg-ink" data-scan>
									<span data-when="before"><?php vp_i( 'play', 14 ); ?></span><span data-when="after" hidden><?php vp_i( 'loader-circle', 15, 'animate-spin' ); ?></span>
									<span data-scan-label><?php echo esc_html( $t['scan'] ); ?></span>
								</button>
							</div>

							<div class="relative mt-6 grid gap-5 lg:grid-cols-[1.35fr_1fr]" data-scan-layer>
								<div class="rounded-sm border border-line p-5">
									<div class="flex items-center justify-between">
										<div>
											<div class="text-xs font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $t['timeline'] ); ?></div>
											<div class="mt-0.5 text-sm text-muted" data-sub><?php echo esc_html( sprintf( $t['sub'], $first ) ); ?></div>
										</div>
										<div class="flex items-center gap-3">
											<div class="text-right text-[11px] font-semibold uppercase text-sage" data-good><?php echo esc_html( $d['overall'] >= 80 ? $t['good'] : $t['needs'] ); ?></div>
											<?php echo vp_ring( $d['overall'], 76 ); // phpcs:ignore ?>
										</div>
									</div>
									<div class="mt-2" data-chart><?php echo vp_dash_chart( $d['line'], 'SEO' ); // phpcs:ignore ?></div>
								</div>
								<div class="rounded-sm border border-line p-5">
									<div class="flex items-center justify-between text-xs font-semibold uppercase tracking-widest text-muted">
										<span><?php echo esc_html( $t['attn'] ); ?></span>
										<span class="rounded-sm bg-rose/10 px-2 py-0.5 text-rose"><span data-open><?php echo count( $tasks ); ?></span> <?php echo esc_html( $t['open'] ); ?></span>
									</div>
									<ul class="mt-3 space-y-2">
										<?php foreach ( $tasks as $i => $k ) : ?>
											<li class="flex items-center gap-3 rounded-sm bg-bg/70 px-3 py-2.5 text-sm" data-task>
												<span class="h-2 w-2 shrink-0 rounded-full" style="background:<?php echo esc_attr( $k[2] ); ?>" data-dot data-color="<?php echo esc_attr( $k[2] ); ?>"></span>
												<span class="min-w-0 flex-1" data-task-text><?php echo esc_html( $k[0] ); ?><span class="block text-[11px] text-muted"><?php echo esc_html( $k[1] ); ?></span></span>
												<button type="button" class="vp-press inline-flex shrink-0 items-center gap-1 rounded-sm px-3 py-1 text-xs font-semibold transition bg-ink text-bg hover:bg-sage" data-approve>
													<span data-when="before" class="inline-flex items-center gap-1"><?php vp_i( 'sparkles', 12 ); ?><?php echo esc_html( $t['fix'] ); ?></span>
													<span data-when="after" class="inline-flex items-center gap-1" hidden><?php vp_i( 'check', 12 ); ?><?php echo esc_html( $t['approved'] ); ?></span>
												</button>
											</li>
										<?php endforeach; ?>
									</ul>
									<p class="mt-3 text-[11px] text-muted"><?php echo esc_html( $t['note'] ); ?></p>
								</div>
							</div>

							<div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
								<?php foreach ( $tiles as $i => $tile ) : ?>
									<div class="vp-hover rounded-sm border border-line bg-bg/40 p-4" style="--hy:-3px">
										<div class="flex items-center justify-between">
											<span class="flex items-center gap-2 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( $tile[3] ) ); ?>"><?php vp_i( $tile[0], 14 ); ?></span><?php echo esc_html( $tile[1] . ' ' . $t['score'] ); ?></span>
											<span class="text-2xl font-semibold tabular-nums" data-tile-val <?php vp_m( 'o:0 y:6 t:.3' ); ?>><?php echo (int) $d['tiles'][ $i ]; ?></span>
										</div>
										<p class="mt-1 text-xs text-muted"><?php echo esc_html( $tile[2] ); ?></p>
										<div class="mt-3 h-1.5 overflow-hidden rounded-full bg-line"><div class="h-full rounded-full" data-tile-bar <?php vp_m( 'W:' . (int) $d['tiles'][ $i ] . '% t:.9 e:ease', 'background:' . $tile[3] ); ?>></div></div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php if ( $only ) { return; } ?>
		</div>
	</div>
</section>
