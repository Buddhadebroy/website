<?php
/**
 * Shortcodes (all output is wrapped in .vf-scope, so it looks right inside any page or post).
 *
 *   [vf_graphic name="builder-app"]              any graphic (see graphic/graphic.php for the list)
 *   [vf_section name="privacy"]                  any Home section: hero, templates, builder, logic, features, compare, embed, privacy, faq, cta
 *   [vf_compare_table plugins="vulo,wpf"]        the feature table (all plugins when empty)
 *   [vf_cost_chart]                              the yearly cost bar chart
 *   [vf_get_it title="Try VuloForm free"]        the download band
 *   [vf_download_url] [vf_docs_url]              the links from Customize → VuloForm
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		add_shortcode( 'vf_section', function ( $a ) { return vf_graphic( 'section-' . sanitize_key( $a['name'] ?? '' ) ); } );
		add_shortcode(
			'vf_compare_table',
			function ( $a ) {
				$ids = array_filter( array_map( 'sanitize_key', explode( ',', $a['plugins'] ?? '' ) ) );
				return vf_graphic( 'compare-table', array( 'args' => array( 'ids' => $ids ) ) );
			}
		);
		add_shortcode( 'vf_cost_chart', function () { return vf_graphic( 'cost-chart' ); } );
		add_shortcode( 'vf_get_it', function ( $a ) { return vf_graphic( 'get-it-banner', array( 'args' => array( 'title' => $a['title'] ?? 'Try VuloForm free' ) ) ); } );
		add_shortcode( 'vf_download_url', function () { return esc_url( vf_download_url() ); } );
		add_shortcode( 'vf_docs_url', function () { return esc_url( vf_docs_url() ); } );
	}
);
