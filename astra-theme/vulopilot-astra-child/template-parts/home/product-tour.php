<?php
/**
 * Home: product tour – real screenshots with numbered hotspots.
 * Screens are listed in vp_screens() (inc/content-data.php); groups and hotspots are below.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args     = isset( $args ) && is_array( $args ) ? $args : array();
$eyebrow  = $args['eyebrow'] ?? 'See the real thing';
$title    = $args['title'] ?? 'Inside your <span class="text-sage">WordPress dashboard.</span>';
$intro    = $args['intro'] ?? 'Real screens from the plugin, not mock-ups. Pick a screen, follow the numbered markers, or click the screenshot to enlarge it.';
$interval = (int) ( $args['interval'] ?? 4200 ); // ms between hotspots.
$heading  = $args['show_heading'] ?? true;
$sec_id   = $args['id'] ?? 'tour';
$only     = ! empty( $args['graphic_only'] );

// Groups in the left column: id, icon, title, hint, screens.
$groups = array(
	array( 'search', 'search', 'Search & AI visibility', 'SEO, GEO, AEO, brand, crawl and schema', array( 'overview', 'seo', 'geo', 'aeo', 'brand', 'crawl', 'schema' ) ),
	array( 'ai', 'bot', 'Copilot & content', 'Chat with your site, write with AI', array( 'copilot', 'content' ) ),
	array( 'speed', 'gauge', 'Performance', 'Speed score and Core Web Vitals', array( 'performance' ) ),
	array( 'care', 'heart-pulse', 'Site care', 'Health, accessibility and security', array( 'health', 'accessibility', 'security' ) ),
	array( 'auto', 'calendar-clock', 'Automation', 'Scheduled scans and reports', array( 'automations' ) ),
);

// Hotspots per screen: left %, top %, title, text.
$hotspots = array(
	'overview'      => array( array( 19, 37, 'Combined visibility score', 'Brand, SEO, GEO and Crawl scores rolled into one number.' ), array( 74, 44, '30-day trend', 'See whether your visibility is rising or falling.' ), array( 28, 84, 'Traffic by source', 'Connect Google Analytics to see sessions here.' ) ),
	'seo'           => array( array( 19, 39, 'SEO Health score', 'One score for the whole site, with open issue counts.' ), array( 38, 57, 'Breakdown by area', 'Titles, structure, images, linking, indexability and social tags.' ), array( 75, 49, 'Progress over time', 'Track issues fixed and new issues over 7, 30 or 90 days.' ) ),
	'geo'           => array( array( 33, 38, 'GEO score', 'How easy your content is for AI assistants to use.' ), array( 72, 56, 'Six checks', 'AI summary, question coverage, evidence, structure, entity clarity and freshness.' ) ),
	'aeo'           => array( array( 12.5, 39, 'AEO score', 'How ready your content is to be quoted by answer engines.' ), array( 26, 40, 'Questions and schema', 'Questions and answers, direct answers and schema markup.' ), array( 74, 56, 'Engine coverage', 'Test whether AI recognizes your pages. Needs a VuloCloud connection.' ) ),
	'brand'         => array( array( 17, 32, 'Brand, Trust and Authority', 'Three scores for how trustworthy your brand looks.' ), array( 72, 58, 'Knowledge panel', 'Add Organization and author schema where it is missing.' ) ),
	'crawl'         => array( array( 18, 52, 'Crawl health', 'robots.txt, sitemap and AI-bot access checks in one score.' ), array( 76, 63, 'AI crawler traffic', 'Requests from AI crawlers over time.' ) ),
	'schema'        => array( array( 26, 36, 'Business information', 'How complete your business details are.' ), array( 70, 57, 'Key information found', 'Name, type, services, locations, people and contact details.' ), array( 21, 86, 'Knowledge graph', 'How the things on your site connect.' ) ),
	'copilot'       => array( array( 34, 49, 'Suggested prompts', 'One click to common questions and tasks.' ), array( 83, 29, 'Site overview', 'Overall health and category scores beside the chat.' ), array( 34, 86, 'Recommended actions', 'The highest-impact fixes, ready to investigate with AI.' ) ),
	'content'       => array( array( 34, 44, 'Chat for content', 'Write blogs, FAQs, meta titles and calls to action.' ), array( 83, 19, 'Content stats', 'Content created, words generated and time saved.' ), array( 34, 92, 'Content tools', 'AI Writer, Blog Generator and landing pages.' ) ),
	'performance'   => array( array( 18, 28, 'Speed score', 'Overall speed, from Google PageSpeed data.' ), array( 76, 36, 'Core Web Vitals', 'Real-user vitals over time.' ), array( 76, 77, 'Quick actions', 'Clear caches, minify code, optimize images, clean the database.' ) ),
	'health'        => array( array( 22, 11, 'Health score', 'WordPress, updates, tasks, database and server in one score.' ), array( 66, 23, 'Checks by area', 'Open findings and severity for each area.' ), array( 23, 63, 'Issues list', 'Filter by priority and open the details.' ) ),
	'accessibility' => array( array( 21, 19, 'Accessibility score', 'How usable your site is for everyone.' ), array( 44, 35, 'Problem groups', 'Page structure, images, links and forms, keyboard use and readability.' ), array( 67, 87, 'Manual checks', 'Keyboard, screen reader and zoom checks a tool cannot judge.' ) ),
	'security'      => array( array( 21, 25, 'Security score', 'Rated from your open security findings.' ), array( 53, 44, 'What it checks', 'Malware, firewall, login protection, vulnerabilities, file changes and SSL.' ), array( 78, 34, 'Security trend', 'Your daily score over 30 days.' ) ),
	'automations'   => array( array( 20, 19, 'Automation library', 'Ready-made automations to start from.' ), array( 51, 38, 'Running agents', 'Pro agents for monitoring, content, security and reporting. Sample data shown.' ), array( 26, 73, 'Scheduled scan and report', 'Switch on the automatic scan and the emailed visibility report.' ) ),
);

$screens = vp_screens();

// Block / shortcode content: groups => screens (own image, title, where, hotspots) replaces the defaults.
if ( ! empty( $args['groups'] ) && is_array( $args['groups'] ) ) {
	$groups   = array();
	$screens  = array();
	$hotspots = array();
	foreach ( $args['groups'] as $gi => $g ) {
		$keys = array();
		foreach ( (array) ( $g['screens'] ?? array() ) as $si => $sc ) {
			$k              = 's' . $gi . '-' . $si;
			$keys[]         = $k;
			$screens[ $k ]  = array(
				'title' => (string) ( $sc['title'] ?? '' ),
				'where' => (string) ( $sc['where'] ?? '' ),
				'src'   => (string) ( $sc['src'] ?? '' ),
				'w'     => max( 1, (int) ( $sc['w'] ?? 1600 ) ),
				'h'     => max( 1, (int) ( $sc['h'] ?? 900 ) ),
				'alt'   => (string) ( $sc['alt'] ?? '' ),
			);
			$hotspots[ $k ] = (array) ( $sc['hotspots'] ?? array() );
		}
		if ( $keys ) {
			$groups[] = array( 'g' . $gi, $g['icon'] ?? 'sparkles', (string) ( $g['title'] ?? '' ), (string) ( $g['hint'] ?? '' ), $keys );
		}
	}
}

$order = array();
foreach ( $groups as $g ) {
	$order = array_merge( $order, $g[4] );
}
if ( ! $order ) {
	return;
}
$first = $order[0];
?>
<?php if ( ! $only ) : ?>
<section<?php echo $sec_id ? ' id="' . esc_attr( $sec_id ) . '"' : ''; ?> class="relative overflow-hidden px-5 py-16">
	<div class="pointer-events-none absolute inset-x-0 top-1/3 -z-10 h-[70%] bg-[radial-gradient(ellipse_at_center,var(--sage-soft),transparent_70%)] opacity-70"></div>
	<div class="mx-auto max-w-6xl">
		<?php if ( $heading ) : ?>
		<div class="mx-auto max-w-3xl text-center" <?php vp_reveal(); ?>>
			<?php vp_eyebrow( $eyebrow ); ?>
			<h2 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-6xl"><?php echo wp_kses_post( $title ); ?></h2>
			<p class="mt-4 text-lg text-muted"><?php echo esc_html( $intro ); ?></p>
		</div>
		<?php endif; ?>
<?php endif; ?>

		<div class="<?php echo $only || ! $heading ? '' : 'mt-10'; ?>" <?php vp_reveal(); ?>>
			<div class="grid gap-6 lg:grid-cols-[280px_1fr]" data-vp="tour" data-interval="<?php echo (int) $interval; ?>" data-order="<?php echo esc_attr( implode( ',', $order ) ); ?>">
				<div class="flex gap-2 overflow-x-auto pb-2 lg:flex-col lg:overflow-visible lg:pb-0">
					<?php foreach ( $groups as $gi => $g ) : $on = 0 === $gi; ?>
						<div class="shrink-0 rounded-sm border transition-colors lg:shrink <?php echo $on ? 'is-active border-sage/30 bg-surface shadow-[0_18px_40px_-28px_rgba(0,41,145,.5)]' : 'border-transparent hover:bg-surface/70'; ?>" data-group="<?php echo esc_attr( implode( ',', $g[4] ) ); ?>">
							<button type="button" class="flex w-full items-center gap-3 px-3.5 py-3 text-left" data-go="<?php echo esc_attr( $g[4][0] ); ?>">
								<span class="grid h-10 w-10 shrink-0 place-items-center rounded-sm transition-colors <?php echo $on ? 'bg-sage text-white' : 'bg-sage-soft text-sage'; ?>" data-group-ic><?php vp_i( $g[1], 18 ); ?></span>
								<span class="min-w-0 flex-1">
									<span class="block whitespace-nowrap text-sm font-semibold lg:whitespace-normal"><?php echo esc_html( $g[2] ); ?></span>
									<span class="hidden text-xs text-muted lg:block"><?php echo esc_html( $g[3] ); ?></span>
								</span>
								<?php vp_i( 'chevron-right', 15, 'hidden shrink-0 text-muted transition-transform lg:block' . ( $on ? ' rotate-90 text-sage' : '' ) ); ?>
							</button>
							<?php if ( count( $g[4] ) > 1 ) : ?>
								<div class="vp-sub">
									<ul class="px-3 pb-3">
										<?php foreach ( $g[4] as $k ) : $sel = $k === $first; ?>
											<li>
												<button type="button" class="relative flex w-full items-center gap-2.5 rounded-sm px-3 py-1.5 text-left text-[13px]" data-go="<?php echo esc_attr( $k ); ?>" data-sub="<?php echo esc_attr( $k ); ?>">
													<span class="absolute inset-0 rounded-sm bg-sage-soft transition <?php echo $sel ? '' : 'opacity-0'; ?>" data-sub-bg></span>
													<span class="relative h-1.5 w-1.5 rounded-full <?php echo $sel ? 'bg-sage' : 'bg-line'; ?>" data-sub-dot></span>
													<span class="relative <?php echo $sel ? 'font-semibold text-sage' : 'text-muted'; ?>" data-sub-label><?php echo esc_html( $screens[ $k ]['title'] ); ?></span>
												</button>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
					<?php foreach ( $groups as $gi => $g ) : if ( count( $g[4] ) < 2 ) { continue; } ?>
						<div class="flex shrink-0 gap-1.5 lg:hidden" data-mobile-group="<?php echo esc_attr( implode( ',', $g[4] ) ); ?>"<?php echo 0 === $gi ? '' : ' hidden'; ?>>
							<?php foreach ( $g[4] as $k ) : ?>
								<button type="button" class="whitespace-nowrap rounded-sm px-3 py-2 text-xs font-semibold <?php echo $k === $first ? 'bg-ink text-bg' : 'border border-line text-muted'; ?>" data-go="<?php echo esc_attr( $k ); ?>" data-chip="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $screens[ $k ]['title'] ); ?></button>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<div data-vp="tilt" data-tilt-local>
					<div class="relative rounded-sm bg-ink p-4 sm:p-6">
						<div class="pointer-events-none absolute inset-0 overflow-hidden rounded-sm">
							<div class="vp-drift absolute -left-10 -top-16 h-72 w-72 rounded-full bg-sage blur-3xl" style="--dx:40px;--dy:-20px;--dd:10s"></div>
							<div class="vp-drift absolute -bottom-20 -right-10 h-72 w-72 rounded-full bg-flare/40 blur-3xl" style="--dx:-30px;--dy:24px;--dd:12s"></div>
							<div class="absolute inset-0 opacity-20 [background-image:radial-gradient(white_1px,transparent_1px)] [background-size:24px_24px]"></div>
						</div>
						<div class="relative" data-tilt="rx:3:-3 ry:-4:4" style="transform:perspective(1400px)">
							<?php foreach ( $order as $k ) : $s = $screens[ $k ]; $hs = $hotspots[ $k ] ?? array(); ?>
								<div class="relative <?php echo $s['w'] < 1000 ? 'mx-auto max-w-[560px]' : ''; ?>" data-panel data-screen="<?php echo esc_attr( $k ); ?>"<?php echo $k === $first ? '' : ' hidden'; ?> <?php vp_m( 'o:0 y:24 s:.97 t:.35' ); ?>>
									<?php vp_frame_open( 'wp-admin / VuloPilot / ' . $s['where'] ); ?>
										<button type="button" class="group relative block w-full cursor-zoom-in" data-vp-lightbox aria-label="<?php echo esc_attr( 'Enlarge screenshot: ' . $s['title'] ); ?>">
											<img src="<?php echo esc_url( $s['src'] ); ?>" width="<?php echo (int) $s['w']; ?>" height="<?php echo (int) $s['h']; ?>" alt="<?php echo esc_attr( $s['alt'] ); ?>" class="block h-auto w-full" loading="<?php echo $k === $first ? 'eager' : 'lazy'; ?>" decoding="async" />
											<span class="absolute right-3 top-3 grid h-8 w-8 place-items-center rounded-full bg-ink/80 text-bg opacity-0 transition group-hover:opacity-100"><?php vp_i( 'maximize-2', 14 ); ?></span>
										</button>
									</div>
									<div class="pointer-events-none absolute inset-x-0 bottom-0 top-10 hidden sm:block">
										<?php foreach ( $hs as $hi => $h ) : $right = $h[0] > 55; $below = $h[1] > 60; $on = 0 === $hi; ?>
											<div class="absolute" style="left:<?php echo (float) $h[0]; ?>%;top:<?php echo (float) $h[1]; ?>%" data-hs-wrap>
												<button type="button" aria-label="<?php echo esc_attr( $h[2] ); ?>" class="pointer-events-auto relative -ml-3.5 -mt-3.5 grid h-7 w-7 place-items-center" data-hs="<?php echo (int) $hi; ?>">
													<span class="vp-ping-out absolute inset-0 rounded-sm bg-flare" data-hs-ping<?php echo $on ? '' : ' hidden'; ?>></span>
													<span class="relative grid h-7 w-7 place-items-center rounded-full text-[11px] font-bold text-white shadow-lg ring-2 ring-white transition-colors <?php echo $on ? 'bg-flare' : 'bg-sage'; ?>" data-hs-dot><?php echo (int) $hi + 1; ?></span>
												</button>
												<div class="absolute z-20 w-60 rounded-sm bg-surface p-3.5 text-left shadow-[0_20px_50px_-12px_rgba(0,0,0,.45)] ring-1 ring-line <?php echo $right ? 'right-5' : 'left-5'; ?> <?php echo $below ? 'bottom-5' : 'top-5'; ?>" data-hs-tip<?php echo $on ? '' : ' hidden'; ?> <?php vp_m( 'o:0 s:.9 e:spring y:' . ( $below ? 6 : -6 ) ); ?>>
													<div class="text-sm font-semibold leading-tight"><?php echo esc_html( $h[2] ); ?></div>
													<p class="mt-1 text-xs leading-snug text-muted"><?php echo esc_html( $h[3] ); ?></p>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="relative mt-5 flex flex-wrap items-center justify-between gap-3 text-bg">
							<div>
								<?php foreach ( $order as $k ) : ?>
									<div data-screen-title="<?php echo esc_attr( $k ); ?>"<?php echo $k === $first ? '' : ' hidden'; ?>>
										<div class="text-lg font-semibold leading-tight"><?php echo esc_html( $screens[ $k ]['title'] ); ?></div>
										<div class="text-sm text-white/60"><?php echo esc_html( $screens[ $k ]['where'] ); ?></div>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="flex items-center gap-2">
								<span class="mr-1 font-mono text-xs text-white/50"><span data-pos>1</span> / <?php echo count( $order ); ?></span>
								<button type="button" aria-label="Previous screen" class="grid h-10 w-10 place-items-center rounded-full border border-white/20 transition hover:bg-white/10" data-prev><?php vp_i( 'arrow-left', 16 ); ?></button>
								<button type="button" aria-label="Next screen" class="grid h-10 w-10 place-items-center rounded-full bg-bg text-ink transition hover:bg-flare hover:text-white" data-next-btn><?php vp_i( 'arrow-right', 16 ); ?></button>
							</div>
						</div>
					</div>
					<?php foreach ( $order as $k ) : $hs = $hotspots[ $k ] ?? array(); ?>
						<ol class="mt-5 grid gap-3 sm:grid-cols-3" data-screen-list="<?php echo esc_attr( $k ); ?>"<?php echo $k === $first ? '' : ' hidden'; ?>>
							<?php foreach ( $hs as $hi => $h ) : $on = 0 === $hi; ?>
								<li>
									<button type="button" class="flex h-full w-full gap-3 rounded-sm border p-4 text-left transition <?php echo $on ? 'border-sage/40 bg-surface shadow-[0_16px_36px_-26px_rgba(0,41,145,.6)]' : 'border-line bg-surface/50 hover:bg-surface'; ?>" data-hs="<?php echo (int) $hi; ?>" data-hs-card>
										<span class="grid h-6 w-6 shrink-0 place-items-center rounded-full text-[11px] font-bold text-white <?php echo $on ? 'bg-flare' : 'bg-sage'; ?>" data-hs-dot><?php echo (int) $hi + 1; ?></span>
										<span><span class="block text-sm font-semibold leading-tight"><?php echo esc_html( $h[2] ); ?></span><span class="mt-1 block text-xs leading-snug text-muted"><?php echo esc_html( $h[3] ); ?></span></span>
									</button>
								</li>
							<?php endforeach; ?>
						</ol>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
<?php if ( ! $only ) : ?>
	</div>
</section>
<?php endif; ?>
