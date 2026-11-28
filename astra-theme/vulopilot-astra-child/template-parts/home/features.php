<?php
/**
 * Home: "Eleven areas. One plugin." – tabs with Free and Pro lists per area.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = 'Everything inside';
$title   = 'Eleven areas. <span class="text-sage">One plugin.</span>';
$intro   = 'Free gives you the insight. Pro adds the action. Pick an area to see exactly what you get in each.';
$start   = 2; // Area open on load (0 = first).
$labels  = array( 'free' => 'Free', 'pro' => 'Pro', 'all_free' => 'Everything here is included in Free.' );

// icon, title, subtitle, free list, pro list, screenshot key ('' for none)
$areas = array(
	array( 'layout-dashboard', 'Dashboard', 'See everything at a glance', array( 'Six scores: Visibility, Health, Commerce, Performance, Content and Brand', 'Health timeline showing whether scores are rising', 'Needs-your-attention list: open issues, quick fixes, pending approvals', 'Recent activity and recent changes, with undo', 'AI crawler traffic, brand breakdown and knowledge graph cards', 'Drag, hide and reset cards to arrange it your way' ), array(), 'overview' ),
	array( 'bot', 'AI Copilot', 'Ask your site anything', array( 'Copilot Q&A: questions answered from your real scan results', 'Blog drafting, saved as drafts and never published for you', 'Approve, reject or undo every change' ), array( 'Full interactive chat with your site', 'MCP server so AI tools like Claude Desktop can read findings and propose changes (you still approve)' ), 'copilot' ),
	array( 'search', 'SEO', 'Get found in search', array( 'SEO score and a ranked problem list', 'Page titles and descriptions', 'Sitemap and instant indexing (IndexNow)', 'Redirects, 404 tracking and robots.txt', 'Per-page SEO panel in the post editor', 'Table of Contents and FAQ blocks', 'Site verification and Google Search Console connection' ), array( 'Technical SEO: schema, sitemap, duplicate descriptions, multiple H1s, focus keywords', 'Keyword rank tracking, opportunities and groups', 'One-click fixes, single or in bulk' ), 'seo' ),
	array( 'radar', 'AI Visibility', 'Be understood by ChatGPT, Claude, Gemini', array( 'GEO: AI summary, question coverage, evidence, structure, entity clarity, freshness', 'AEO: answer-readiness checks and engine testing', 'Brand visibility: Trust and Authority scores', 'Crawler traffic from GPTBot, ClaudeBot and PerplexityBot', 'Editable llms.txt', 'Business profile, knowledge graph and schema coverage' ), array( 'Answer engine citation coverage and stale-content flags', 'AI crawler tracking with alerts when a bot stops or is blocked', 'Deeper brand and knowledge graph findings' ), 'geo' ),
	array( 'pen-line', 'Content', 'Write and keep content healthy', array( 'AI Writer and Blog Generator', 'Before and after preview, approve or reject', 'Duplicate title fixer', 'Content audit and readability checks', 'Content stats' ), array( 'Content gaps against competitors', 'Readability and structure suggestions', 'AI drafts for titles, descriptions and summaries' ), 'content' ),
	array( 'gauge', 'Performance', 'Know how slow, really', array( 'Overall speed score, mobile vs desktop', 'Core Web Vitals', 'Biggest speed opportunity and slow-page finder', 'AI speed assistant in plain language', 'Real-time monitoring and speed history', 'Quick fixes: clear caches, minify, optimize images, database cleanup' ), array(), 'performance' ),
	array( 'heart-pulse', 'Site Health & Backups', 'Recover from any mistake', array( 'WordPress health checkup: updates, database, scheduled tasks, server', 'Manual and automatic backups', 'Download, restore or delete backups' ), array( 'Copy backups to Amazon S3 or Google Drive, credentials stored encrypted' ), 'health' ),
	array( 'accessibility', 'Accessibility', 'Usable by everyone', array( 'WCAG-based scan of your pages', 'Page structure, images, links and forms, keyboard and readability checks', 'Manual testing checklist' ), array( 'Scheduled audits (hourly to weekly) with score history' ), 'accessibility' ),
	array( 'shield-check', 'Security', 'Spot weak points early', array( 'Security score and ranked problems', 'Login and account checks', 'Password-guessing protection and attack filter', 'Public exposure and browser protection checks', 'Alerts when something looks wrong' ), array( 'Known vulnerability feed for core, plugins and themes', 'File change monitoring', 'Exposed files, debug mode, headers and XML-RPC checks', 'Incident log with alerts' ), 'security' ),
	array( 'shopping-bag', 'Commerce', 'For WooCommerce stores', array( 'Product, checkout and store issue list', 'AI sales assistant with review-first suggestions', 'Product schema checks' ), array( 'Missing images, duplicate SKUs, pricing gaps, low stock, failed payments', 'Revenue, top products and unsold products', 'Abandoned carts and revenue at risk', 'Daily store trends', 'Bulk AI product writing, up to 50 at a time' ), '' ),
	array( 'calendar-clock', 'Automations & Reports', 'Keep it running by itself', array( 'Scheduled full-site scan: daily, weekly or monthly', 'Emailed visibility report', 'History timeline with search, export and undo', 'PDF or CSV reports' ), array( 'Workflow builder with conditions, retries and run logs', 'Custom and health reports, scheduled and exported as PDF', 'AI credit analytics' ), 'automations' ),
);

// Optional overrides (the "Feature Tabs" block and [vp_feature_tabs] pass these).
// An area may also carry: [6] image URL (used instead of the screenshot), [7] image alt text, [8] dot on/off.
$args         = isset( $args ) && is_array( $args ) ? $args : array();
$only         = ! empty( $args['graphic_only'] );
$show_heading = isset( $args['show_heading'] ) ? (bool) $args['show_heading'] : ! $only;
$eyebrow      = isset( $args['eyebrow'] ) ? $args['eyebrow'] : $eyebrow;
$title        = isset( $args['title'] ) ? $args['title'] : $title;
$intro        = isset( $args['intro'] ) ? $args['intro'] : $intro;
$labels       = array_merge( $labels, (array) ( $args['labels'] ?? array() ) );
$areas        = ! empty( $args['areas'] ) ? array_values( $args['areas'] ) : $areas;
$start        = isset( $args['start'] ) ? max( 0, min( count( $areas ) - 1, (int) $args['start'] ) ) : $start;
$section_id   = isset( $args['id'] ) ? $args['id'] : 'features';
?>
<?php if ( ! $only ) : ?>
<section<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?> class="px-5 py-16">
	<div class="mx-auto max-w-6xl">
<?php endif; ?>
		<?php if ( $show_heading ) : ?>
		<div class="mx-auto max-w-3xl text-center" <?php vp_reveal(); ?>>
			<?php if ( '' !== (string) $eyebrow ) { vp_eyebrow( $eyebrow ); } ?>
			<h2 class="mt-5 font-display text-4xl leading-tight sm:text-6xl"><?php echo wp_kses_post( $title ); ?></h2>
			<?php if ( '' !== (string) $intro ) : ?><p class="mt-4 text-lg text-muted"><?php echo esc_html( $intro ); ?></p><?php endif; ?>
		</div>
		<?php endif; ?>

		<div class="<?php echo $show_heading ? 'mt-9 ' : ''; ?>grid gap-6 lg:grid-cols-[260px_1fr]" data-vp="tabs">
			<div class="relative flex gap-2 overflow-x-auto pb-2 lg:flex-col lg:overflow-visible" data-pill-group>
				<span class="vp-pill rounded-sm bg-ink"></span>
				<?php foreach ( $areas as $i => $a ) : $on = $i === $start; ?>
					<button type="button" class="relative flex shrink-0 items-center gap-3 rounded-sm px-4 py-3 text-left text-sm font-medium transition <?php echo $on ? 'text-bg' : 'text-muted hover:bg-surface'; ?>" data-tab="<?php echo (int) $i; ?>" aria-pressed="<?php echo $on ? 'true' : 'false'; ?>" data-on="text-bg" data-off="text-muted hover:bg-surface">
						<?php vp_i( $a[0], 18, 'relative' ); ?>
						<span class="relative whitespace-nowrap"><?php echo esc_html( $a[1] ); ?></span>
						<?php if ( isset( $a[8] ) ? $a[8] : $a[4] ) : ?><span class="relative ml-auto hidden h-1.5 w-1.5 rounded-full lg:block <?php echo $on ? 'bg-apricot' : 'bg-apricot/70'; ?>"></span><?php endif; ?>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="card min-h-[420px] p-6 sm:p-9">
				<?php foreach ( $areas as $i => $a ) : list( $icon, $t, $sub, $free, $pro, $shot ) = array_pad( $a, 6, '' ); $img = $a[6] ?? ''; $free = (array) $free; $pro = array_filter( (array) $pro ); ?>
					<div data-panel="<?php echo (int) $i; ?>"<?php echo $i === $start ? '' : ' hidden'; ?> <?php vp_m( 'o:0 y:16 t:.3' ); ?>>
						<div class="flex items-center gap-3">
							<span class="grid h-12 w-12 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( $icon, 22 ); ?></span>
							<div><h3 class="font-display text-3xl"><?php echo esc_html( $t ); ?></h3><p class="text-muted"><?php echo esc_html( $sub ); ?></p></div>
						</div>
						<div class="mt-8 grid gap-6 <?php echo $pro ? 'md:grid-cols-2' : ''; ?>">
							<div>
								<div class="mb-3 inline-flex rounded-sm bg-sage-soft px-3 py-1 font-mono text-[11px] uppercase tracking-widest text-sage"><?php echo esc_html( $labels['free'] ); ?></div>
								<ul class="space-y-2.5">
									<?php foreach ( $free as $k => $f ) : ?>
										<li class="flex gap-3 text-[15px]" <?php vp_m( 'o:0 x:-10 d:' . ( $k * 0.05 ) ); ?>><?php vp_i( 'check', 17, 'mt-0.5 shrink-0 text-sage' ); ?><?php echo esc_html( $f ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
							<?php if ( $pro ) : ?>
								<div class="rounded-sm bg-apricot-soft/60 p-5">
									<div class="mb-3 inline-flex items-center gap-1.5 rounded-sm bg-apricot px-3 py-1 font-mono text-[11px] uppercase tracking-widest text-white"><?php vp_i( 'sparkles', 12 ); ?><?php echo esc_html( $labels['pro'] ); ?></div>
									<ul class="space-y-2.5">
										<?php foreach ( $pro as $k => $f ) : ?>
											<li class="flex gap-3 text-[15px]" <?php vp_m( 'o:0 x:10 d:' . ( 0.15 + $k * 0.05 ) ); ?>><?php vp_i( 'sparkles', 16, 'mt-0.5 shrink-0 text-apricot' ); ?><?php echo esc_html( $f ); ?></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( $img ) : ?>
							<figure class="mt-8"><?php vp_frame_open( $a[9] ?? ( 'wp-admin / VuloPilot / ' . $t ) ); ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $a[7] ?? $t ); ?>" loading="lazy" decoding="async" class="block h-auto w-full" /></div></figure>
						<?php elseif ( $shot ) : ?>
							<div class="mt-8 <?php echo 'geo' === $shot ? 'mx-auto max-w-xl' : ''; ?>"><?php echo vp_shot( $shot, true ); // phpcs:ignore ?></div>
						<?php endif; ?>
						<?php if ( ! $pro && '' !== (string) $labels['all_free'] ) : ?>
							<p class="mt-8 rounded-sm bg-sage-soft/60 px-5 py-3 text-sm text-sage"><?php echo esc_html( $labels['all_free'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
<?php if ( ! $only ) : ?>
	</div>
</section>
<?php endif; ?>
