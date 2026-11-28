<?php
/**
 * Content shared by several sections: plans and prices, product screenshots, site facts.
 * Edit the values here and every section that uses them updates (Home pricing, Pricing page,
 * FAQ answers and the schema markup).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/**
 * Paid plans. Prices in USD. Every plan links straight to checkout (no popup) —
 * 'checkout_monthly' is used while the Monthly tab is active, 'checkout_yearly'
 * while Yearly is active; the button's href swaps automatically when the person
 * flips the toggle.
 */
function vp_plans() {
	return apply_filters(
		'vp_plans',
		array(
			array(
				'id'               => 'go',
				'name'             => 'Go',
				'icon'             => 'zap',
				'monthly'          => 19,
				'yearly'           => 149,
				'sites'            => 1,
				'credits'          => 1000,
				'checkout_monthly' => 'https://store.vulolabs.com/checkout?org=b3235308-51a9-48db-ba2b-be76b93663c8&offering=49070a0c-b891-40b6-b673-5876cc54c234',
				'checkout_yearly'  => 'https://store.vulolabs.com/checkout?org=b3235308-51a9-48db-ba2b-be76b93663c8&offering=1751309e-5176-49f7-9f13-af7a6bf7be21',
			),
			array(
				'id'               => 'plus',
				'name'             => 'Plus',
				'icon'             => 'sparkles',
				'monthly'          => 49,
				'yearly'           => 399,
				'sites'            => 5,
				'credits'          => 5000,
				'popular'          => true,
				'checkout_monthly' => 'https://store.vulolabs.com/checkout?org=b3235308-51a9-48db-ba2b-be76b93663c8&offering=8d4d822c-c8b9-42e8-97c3-c00fb6425e4d',
				'checkout_yearly'  => 'https://store.vulolabs.com/checkout?org=b3235308-51a9-48db-ba2b-be76b93663c8&offering=51d918d3-527b-416d-8b46-e23a7f804636',
			),
			array(
				'id'               => 'pro',
				'name'             => 'Pro',
				'icon'             => 'crown',
				'monthly'          => 99,
				'yearly'           => 799,
				'sites'            => 25,
				'credits'          => 10000,
				'checkout_monthly' => 'https://store.vulolabs.com/checkout?org=b3235308-51a9-48db-ba2b-be76b93663c8&offering=b6772e29-9a62-4ada-baa5-6e1fb41b9254',
				'checkout_yearly'  => 'https://store.vulolabs.com/checkout?org=b3235308-51a9-48db-ba2b-be76b93663c8&offering=a60015b7-828b-42c4-afd4-abf93aebec35',
			),
		)
	);
}

/** What every paid plan card lists (the Home section shows the first four). */
function vp_plan_features() {
	return array( 'AI-powered fixes', 'Custom workflow automation', 'Advanced AI search optimization', 'AI content creation and refresh', 'Security monitoring', 'Performance monitoring' );
}

/** Savings for a plan when billed yearly. */
function vp_plan_saving( $p ) {
	$full = $p['monthly'] * 12;
	return array(
		'amount'    => $full - $p['yearly'],
		'pct'       => (int) round( ( 1 - $p['yearly'] / $full ) * 100 ),
		'per_month' => preg_replace( '/\.00$/', '', number_format( $p['yearly'] / 12, 2, '.', '' ) ),
	);
}

/** "1k" style credits. */
function vp_credits_short( $n ) {
	return $n >= 1000 ? ( $n / 1000 ) . 'k' : (string) $n;
}

/** Facts reused in several places. */
function vp_site_facts() {
	return array(
		'refund_days'  => 15,
		'refund_url'   => 'https://vulopilot.com/refund-policy/',
		'payments'     => 'Stripe, PayPal and credit cards',
		'summary'      => 'VuloPilot is a WordPress plugin that audits your whole website for SEO, AI search visibility (GEO and AEO), speed, security, accessibility, content and WooCommerce issues. It scores each area from 0 to 100, ranks what to fix first, proposes fixes you approve before anything changes, and re-scans to prove the fix worked.',
		'site_url'     => 'https://vulopilot.com',
		'brand'        => 'VuloPilot',
		'maker'        => 'Vulo Labs',
	);
}

/** SoftwareApplication schema (printed on Home and Pricing). */
function vp_software_schema() {
	$f      = vp_site_facts();
	$offers = array( array( '@type' => 'Offer', 'name' => 'Free', 'price' => '0', 'priceCurrency' => 'USD' ) );
	foreach ( vp_plans() as $p ) {
		$offers[] = array(
			'@type'         => 'Offer',
			'name'          => $p['name'],
			'price'         => (string) $p['monthly'],
			'priceCurrency' => 'USD',
			'description'   => sprintf( '%d site%s, %s AI credits a month. $%d billed yearly.', $p['sites'], $p['sites'] > 1 ? 's' : '', number_format( $p['credits'] ), $p['yearly'] ),
			'url'           => $f['site_url'] . '/pricing/',
		);
	}
	return array(
		'@type'                  => 'SoftwareApplication',
		'@id'                    => $f['site_url'] . '/#app',
		'name'                   => $f['brand'],
		'url'                    => $f['site_url'],
		'description'            => $f['summary'],
		'applicationCategory'    => 'BusinessApplication',
		'applicationSubCategory' => 'WordPress plugin',
		'operatingSystem'        => 'WordPress 6.7+, PHP 8.1+',
		'publisher'              => array( '@id' => $f['site_url'] . '/#org' ),
		'screenshot'             => vp_screens()['overview']['src'],
		'offers'                 => $offers,
	);
}

/** Screenshot list: key => [width, height, title, where in wp-admin, alt text]. */
function vp_screen_rows() {
	return array(
		// key => [width, height, title, where in wp-admin, alt text]
		'overview'      => array( 1760, 727, 'Visibility Overview', 'SEO & Visibility → Overview', 'VuloPilot visibility overview with an overall score of 96 and a 30-day trend chart' ),
		'seo'           => array( 1750, 670, 'SEO Health', 'SEO & Visibility → SEO', 'VuloPilot SEO Health score with titles, content structure, images and linking breakdown' ),
		'geo'           => array( 862, 686, 'GEO Score', 'SEO & Visibility → GEO', 'VuloPilot GEO score covering AI summary, question coverage, evidence and entity clarity' ),
		'aeo'           => array( 1745, 718, 'AEO Score', 'SEO & Visibility → AEO', 'VuloPilot AEO score for questions and answers, direct answers and schema markup' ),
		'brand'         => array( 1760, 727, 'Brand Visibility', 'SEO & Visibility → Brand Visibility', 'VuloPilot brand, trust and authority scores with knowledge panel optimization' ),
		'crawl'         => array( 1700, 696, 'Crawl & URLs', 'SEO & Visibility → Crawl & URLs', 'VuloPilot crawl health and AI crawler requests over time' ),
		'schema'        => array( 1713, 717, 'Business Identity & Schema', 'SEO & Visibility → Business Identity & Schema', 'VuloPilot business identity completeness and knowledge graph' ),
		'copilot'       => array( 1302, 780, 'AI Copilot', 'AI Copilot', 'VuloPilot AI Copilot chat with suggested prompts and recommended actions' ),
		'content'       => array( 1730, 872, 'Content tools', 'Content', 'VuloPilot content page with AI chat, content stats and writing tools' ),
		'performance'   => array( 1720, 826, 'Performance', 'Performance → Overview', 'VuloPilot performance overview with speed score, Core Web Vitals and quick actions' ),
		'health'        => array( 1721, 853, 'Site Health', 'Site Health', 'VuloPilot site health score with WordPress, updates, database and server checks' ),
		'accessibility' => array( 1703, 861, 'Accessibility', 'Accessibility', 'VuloPilot accessibility score with page structure, images, links and readability checks' ),
		'security'      => array( 1708, 871, 'Security', 'Security', 'VuloPilot security score with malware, firewall, login protection and file change checks' ),
		'automations'   => array( 1718, 876, 'Automations', 'Automations', 'VuloPilot automations for scheduled scans and email visibility reports' ),
	);
}

/** Placeholder shown until a screenshot is picked in Customize → VuloPilot screenshots. */
function vp_screen_placeholder( $w, $h, $title ) {
	$svg = sprintf( '<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%2$d" viewBox="0 0 %1$d %2$d"><rect width="100%%" height="100%%" fill="#eef1f8"/><text x="50%%" y="50%%" fill="#5d6784" font-family="sans-serif" font-size="%3$d" text-anchor="middle" dominant-baseline="middle">%4$s</text></svg>', $w, $h, max( 18, (int) ( $w / 40 ) ), htmlspecialchars( $title, ENT_QUOTES ) );
	return 'data:image/svg+xml;charset=utf-8,' . rawurlencode( $svg );
}

/** Product screenshots: Media Library links from the Customizer (filterable with vp_screen_src). */
function vp_screens() {
	static $s = null;
	if ( null !== $s ) {
		return $s;
	}
	$rows = vp_screen_rows();
	$s = array();
	foreach ( $rows as $k => $r ) {
		$s[ $k ] = array(
			'key'   => $k,
			'src'   => (string) apply_filters( 'vp_screen_src', get_theme_mod( 'vp_screen_' . $k, '' ) ? get_theme_mod( 'vp_screen_' . $k ) : vp_screen_placeholder( $r[0], $r[1], $r[2] ), $k ),
			'w'     => $r[0],
			'h'     => $r[1],
			'title' => $r[2],
			'where' => $r[3],
			'alt'   => $r[4],
		);
	}
	return $s;
}
