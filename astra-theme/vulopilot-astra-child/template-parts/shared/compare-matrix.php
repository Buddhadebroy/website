<?php
/**
 * "Every feature, side by side" – Free vs Paid matrix (the [vp_compare_matrix] graphic).
 * Uses the Plan Comparison block's markup (blocks/compare-matrix/render.php) with the content below.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$plans = vp_plans();

// One group per area. 'open' = expanded on load. One line per feature.
$groups = array(
	array(
		'title'    => 'AI Copilot',
		'icon'     => 'bot',
		'open'     => true,
		'linkText' => '',
		'linkUrl'  => '',
		'free'     => array(
			'Copilot Q&A: questions answered from your real scan results',
			'Blog drafting, saved as drafts and never published for you'
		),
		'paid'     => array(
			'Full interactive chat with your site',
			'MCP server for Claude Desktop and other AI tools'
		),
	),
	array(
		'title'    => 'Search Visibility',
		'icon'     => 'search',
		'open'     => true,
		'linkText' => 'About Search Visibility →',
		'linkUrl'  => '/features/search-visibility/',
		'free'     => array(
			'SEO score and a ranked problem list',
			'Page titles and descriptions',
			'Sitemap and instant indexing (IndexNow)',
			'Redirects, 404 tracking and robots.txt',
			'Per-page SEO panel in the post editor',
			'Table of Contents and FAQ blocks',
			'Site verification and Google Search Console connection'
		),
		'paid'     => array(
			'Technical SEO: schema, sitemap, duplicate descriptions, multiple H1s, focus keywords',
			'Keyword rank tracking, opportunities and groups',
			'One-click fixes, single or in bulk'
		),
	),
	array(
		'title'    => 'AI Search',
		'icon'     => 'radar',
		'open'     => false,
		'linkText' => 'About AI Search →',
		'linkUrl'  => '/features/ai-search/',
		'free'     => array(
			'GEO: AI summary, question coverage, evidence, structure, entity clarity, freshness',
			'AEO: answer-readiness checks and engine testing',
			'Brand visibility: Trust and Authority scores',
			'Crawler traffic from GPTBot, ClaudeBot and PerplexityBot',
			'Editable llms.txt',
			'Business profile, knowledge graph and schema coverage'
		),
		'paid'     => array(
			'Answer engine citation coverage and stale-content flags',
			'AI crawler tracking with alerts when a bot stops or is blocked',
			'Deeper brand and knowledge graph findings'
		),
	),
	array(
		'title'    => 'Website Health',
		'icon'     => 'heart-pulse',
		'open'     => false,
		'linkText' => 'About Website Health →',
		'linkUrl'  => '/features/website-health/',
		'free'     => array(
			'WordPress health checkup: updates, database, scheduled tasks, server',
			'Manual and automatic backups',
			'Download, restore or delete backups',
			'WCAG-based accessibility scan',
			'Manual accessibility checklist'
		),
		'paid'     => array(
			'Copy backups to Amazon S3 or Google Drive, credentials stored encrypted',
			'Scheduled accessibility audits (hourly to weekly) with score history'
		),
	),
	array(
		'title'    => 'Performance',
		'icon'     => 'gauge',
		'open'     => false,
		'linkText' => 'About Performance →',
		'linkUrl'  => '/features/performance/',
		'free'     => array(
			'Overall speed score, mobile vs desktop',
			'Core Web Vitals',
			'Biggest speed opportunity and slow-page finder',
			'AI speed assistant in plain language',
			'Real-time monitoring and speed history',
			'Quick fixes: clear caches, minify, optimize images, database cleanup'
		),
		'paid'     => array(),
	),
	array(
		'title'    => 'Security',
		'icon'     => 'shield-check',
		'open'     => false,
		'linkText' => 'About Security →',
		'linkUrl'  => '/features/security/',
		'free'     => array(
			'Security score and ranked problems',
			'Login and account checks',
			'Password-guessing protection and attack filter',
			'Public exposure and browser protection checks',
			'Alerts when something looks wrong'
		),
		'paid'     => array(
			'Known vulnerability feed for WordPress core, plugins and themes',
			'File change monitoring',
			'Exposed files, debug mode, security headers and XML-RPC checks',
			'Incident log with alerts'
		),
	),
	array(
		'title'    => 'Content',
		'icon'     => 'pen-line',
		'open'     => false,
		'linkText' => 'About Content →',
		'linkUrl'  => '/features/content/',
		'free'     => array(
			'AI Writer and Blog Generator',
			'Before and after preview, approve or reject',
			'Duplicate title fixer',
			'Content audit and readability checks',
			'Content stats'
		),
		'paid'     => array(
			'Content gaps against competitors',
			'Readability and structure suggestions',
			'AI drafts for titles, descriptions and summaries'
		),
	),
	array(
		'title'    => 'WooCommerce',
		'icon'     => 'shopping-bag',
		'open'     => false,
		'linkText' => 'About WooCommerce →',
		'linkUrl'  => '/features/woocommerce/',
		'free'     => array(
			'Product, checkout and store issue list',
			'AI sales assistant with review-first suggestions',
			'Product schema checks'
		),
		'paid'     => array(
			'Missing images, duplicate SKUs, pricing gaps, low stock, failed payments',
			'Revenue, top products and unsold products',
			'Abandoned carts and revenue at risk',
			'Daily store trends',
			'Bulk AI product writing, up to 50 at a time'
		),
	),
	array(
		'title'    => 'Automation',
		'icon'     => 'calendar-clock',
		'open'     => false,
		'linkText' => 'About Automation →',
		'linkUrl'  => '/features/automation/',
		'free'     => array(
			'Scheduled full-site scan: daily, weekly or monthly',
			'Emailed visibility report',
			'History timeline with search, export and undo',
			'PDF or CSV reports'
		),
		'paid'     => array(
			'Workflow builder with conditions, retries and run logs',
			'Custom and health reports, scheduled and exported as PDF',
			'AI credit analytics'
		),
	),
);

$attrs = array(
	'showHeading'    => true,
	'eyebrow'        => 'Compare plans',
	'title'          => 'Every feature,',
	'titleAccent'    => 'side by side.',
	'subtitle'       => '{free} features are free. Paid plans add {paid} more.', // {free} and {paid} are counted for you.
	'freeLabel'      => 'Free',
	'freePrice'      => '$0',
	'paidLabel'      => 'Paid',
	'paidPrice'      => 'from $' . min( wp_list_pluck( $plans, 'monthly' ) ) . '/mo',
	'toggleLabel'    => 'Paid extras only',
	'expandLabel'    => 'Expand all',
	'collapseLabel'  => 'Collapse all',
	'paidTag'        => 'Paid',
	'freeCountLabel' => 'free',
	'paidCountLabel' => 'paid',
	'limits'         => array(
		array( 'icon' => 'server', 'label' => 'Sites', 'free' => 'Free 1 ·', 'paid' => implode( ' / ', wp_list_pluck( $plans, 'sites' ) ) ),
		array( 'icon' => 'coins', 'label' => 'AI credits / mo', 'free' => 'Free 100 ·', 'paid' => implode( ' / ', array_map( 'vp_credits_short', wp_list_pluck( $plans, 'credits' ) ) ) ),
	),
	'maxWidth'       => 896,
	'radius'         => 4,
);

// Groups are stored one feature per line, like the block editor does.
$attrs['groups'] = array_map(
	function ( $g ) {
		$g['free'] = implode( "\n", $g['free'] );
		$g['paid'] = implode( "\n", $g['paid'] );
		return $g;
	},
	$groups
);

echo render_block( array( 'blockName' => 'vulopilot/compare-matrix', 'attrs' => $attrs, 'innerBlocks' => array(), 'innerHTML' => '', 'innerContent' => array() ) ); // phpcs:ignore WordPress.Security.EscapeOutput
