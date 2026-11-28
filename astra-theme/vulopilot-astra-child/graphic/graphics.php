<?php
/**
 * Graphic names.
 *
 *   1. Aliases     old or alternative names => the graphic that is shown
 *   2. PHP parts   which file draws which graphic (filter vp_graphic_parts)
 *
 * Every file in graphic/parts/ is a graphic named after the file, e.g. graphic/parts/seo-audit-1.php
 * is [vp_graphic name="seo-audit-1"]. Add a file there and it can be used straight away.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * 1. Aliases
 * ---------------------------------------------------------------------- */

/** Old or alternative graphic names => the graphic that is shown. */
function vp_graphic_aliases() {
	return apply_filters(
		'vp_graphic_aliases',
		array(
			// Names that pages already used but that never existed.
			'content-demo'       => 'content-hero-demo',
			'slow-page-demo'     => 'slow-pages-demo',
			'alert-demo'         => 'alarm-demo',
			'alerts-demo'        => 'alarm-demo',
			'snippet-click-demo' => 'click-demo',
			'scan-list'          => 'feature-scan',
			'features-scan'      => 'feature-scan',
			'problem-graphic'    => 'noise-signal',
			'chat-rings'         => 'ai-answer',

			// Numbered feature-page graphics: features-<page>-<n>, same order as the page.
			'features-search-visibility-1' => 'snippet-demo',
			'features-search-visibility-2' => 'problems-demo',
			'features-search-visibility-3' => 'seo-signals-demo',
			'features-search-visibility-4' => 'click-demo',
			'features-search-visibility-5' => 'redirect-demo',
			'features-performance-1'       => 'speed-demo',
			'features-performance-2'       => 'vitals-demo',
			'features-performance-3'       => 'slow-pages-demo',
			'features-performance-4'       => 'quick-fix-demo',
			'features-ai-search-1'         => 'ask-demo',
			'features-ai-search-2'         => 'crawler-demo',
			'features-ai-search-3'         => 'geo-demo',
			'features-ai-search-4'         => 'aeo-demo',
			'features-website-health-1'    => 'checkup-demo',
			'features-website-health-2'    => 'health-score-demo',
			'features-website-health-3'    => 'backup-demo',
			'features-website-health-4'    => 'alt-text-demo',
			'features-security-1'          => 'security-score-demo',
			'features-security-2'          => 'login-demo',
			'features-security-3'          => 'exposure-demo',
			'features-security-4'          => 'alarm-demo',
			'features-content-1'           => 'content-hero-demo',
			'features-content-2'           => 'backlog-demo',
			'features-content-3'           => 'draft-demo',
			'features-content-4'           => 'readability-demo',
			'features-woocommerce-1'       => 'store-issues-demo',
			'features-woocommerce-2'       => 'store-leaks-demo',
			'features-woocommerce-3'       => 'product-schema-demo',
			'features-woocommerce-4'       => 'carts-demo',
			'features-automation-1'        => 'schedule-demo',
			'features-automation-2'        => 'report-demo',
			'features-automation-3'        => 'history-demo',
			'features-automation-4'        => 'workflow-demo',
			'features-overview-1'          => 'feature-scan',
		)
	);
}

/** Resolve an alias to the real graphic name. */
function vp_graphic_alias( $name ) {
	$map = vp_graphic_aliases();
	return isset( $map[ $name ] ) ? $map[ $name ] : $name;
}

/* -------------------------------------------------------------------------
 * 2. PHP parts
 *
 * A value is 'graphics/<file>' (a file in graphic/parts/), 'home/<file>' / 'shared/<file>'
 * (a file in template-parts/), or array( '<path>', $args ).
 * ---------------------------------------------------------------------- */

/** Every graphic file in a folder of graphic/parts/, as name => 'graphics/<sub><name>'. */
function vp_graphic_files( $sub = '' ) {
	$out = array();
	foreach ( (array) glob( VP_GRAPHIC_DIR . '/parts/' . $sub . '*.php' ) as $file ) {
		$name         = basename( $file, '.php' );
		$out[ $name ] = 'graphics/' . $sub . $name;
	}
	return $out;
}

// Feature-page demos, about/feature-hub visuals and the 4 graphics of every solution,
// problem, use-case and "for" page (graphic/parts/*.php).
add_filter(
	'vp_graphic_parts',
	function ( $parts ) {
		return array_merge(
			$parts,
			vp_graphic_files(),
			array(
				// Home sections without their heading, to place anywhere.
				'noise-signal'   => array( 'home/problem', array( 'graphic_only' => true ) ),
				'dashboard-demo' => array( 'home/dashboard', array( 'graphic_only' => true ) ),
				'ai-answer'      => array( 'home/ai', array( 'graphic_only' => true ) ),
				'how-stepper'    => array( 'home/how', array( 'graphic_only' => true ) ),
				'feature-tabs'   => array( 'home/features', array( 'graphic_only' => true ) ),
			)
		);
	}
);

// The 2.5 versions stay reachable as <name>-classic (graphic/parts/classic/*.php).
add_filter(
	'vp_graphic_parts',
	function ( $parts ) {
		foreach ( vp_graphic_files( 'classic/' ) as $name => $path ) {
			$parts[ $name . '-classic' ] = $path;
		}
		return $parts;
	},
	20
);
