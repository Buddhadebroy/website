<?php
/**
 * Home: "What is VuloPilot?" quick facts + FAQ (with FAQPage schema).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$f     = vp_site_facts();
$plans = vp_plans();
$min   = min( wp_list_pluck( $plans, 'monthly' ) );

$facts = array(
	array( 'Product type', 'WordPress plugin (SEO, AI search, performance, security and site health)' ),
	array( 'Made by', $f['maker'] ),
	array( 'Works with', 'WordPress 6.7+ and PHP 8.1+, WooCommerce optional' ),
	array( 'Scores', 'Visibility, Health, Commerce, Performance, Content and Brand, each 0 to 100' ),
	array( 'AI engines checked', 'ChatGPT, Claude, Gemini and Perplexity' ),
	array( 'Free plan', 'Yes. Core scans and reports work without any AI service, plus 100 AI credits' ),
	array( 'Paid plans', implode( ', ', array_map( function ( $p ) { return sprintf( '%s $%d/mo (%d site%s)', $p['name'], $p['monthly'], $p['sites'], $p['sites'] > 1 ? 's' : '' ); }, $plans ) ) ),
	array( 'Changes', 'Nothing changes without your approval, and applied changes can be undone' ),
);
$links = 'See <a href="' . esc_url( vp_url( '/how-it-works/' ) ) . '" class="underline">how it works</a>, <a href="' . esc_url( vp_url( '/features/' ) ) . '" class="underline">every feature</a> or <a href="' . esc_url( vp_url( '/compare/' ) ) . '" class="underline">how it compares</a>.';

$faq = array(
	array( 'What does VuloPilot do?', $f['summary'] ),
	array( 'Is VuloPilot free?', 'Yes. The free plugin includes SEO, AI search, performance, security, health and content scans with scores and reports, plus 100 AI credits. Paid plans start at $' . $min . ' a month.' ),
	array( 'Does VuloPilot help my site appear in ChatGPT and other AI answers?', 'It improves the signals AI assistants use. VuloPilot checks Generative Engine Optimization (GEO) and Answer Engine Optimization (AEO) signals, AI crawler access for GPTBot, ClaudeBot and PerplexityBot, schema, brand trust and llms.txt, then ranks the fixes. No tool can guarantee an AI citation.' ),
	array( 'Will VuloPilot change my website without asking?', 'No. Every proposed change shows a before and after preview, and nothing goes live until you approve it. Applied changes can be undone from the History page.' ),
	array( 'Does it replace Yoast SEO or Rank Math?', 'It can, since VuloPilot includes titles, descriptions, sitemaps, redirects and robots.txt. If you keep another SEO plugin, let only one of them output meta tags and sitemaps to avoid duplicates.' ),
	array( 'What do I need to run VuloPilot?', 'WordPress 6.7 or newer, PHP 8.1 or newer and an administrator account. AI features, Google Search Console and WooCommerce are optional.' ),
);

vp_titled_section(
	'In one paragraph',
	'What is VuloPilot?',
	function () use ( $f, $facts, $links ) {
		echo '<div' . vp_m_attr( 'o:0 y:24 t:.8' ) . '><p class="max-w-3xl text-xl leading-relaxed text-ink/80">' . esc_html( $f['summary'] ) . '</p></div>';
		echo '<div class="mt-8"><dl class="overflow-hidden rounded-sm border border-line bg-surface">';
		foreach ( $facts as $row ) {
			echo '<div class="grid gap-1 border-t border-line px-6 py-4 first:border-t-0 sm:grid-cols-[200px_1fr] sm:gap-6"><dt class="font-medium">' . esc_html( $row[0] ) . '</dt><dd class="text-muted">' . esc_html( $row[1] ) . '</dd></div>';
		}
		echo '</dl></div><p class="mt-4 text-sm text-muted">' . wp_kses_post( $links ) . '</p>';
	},
	true
);

vp_titled_section(
	'Questions',
	'VuloPilot: frequently asked questions',
	function () use ( $faq ) {
		vp_faq( $faq );
	},
	true
);
