<?php
/**
 * Content data, read from data/*.json (exported from the original site's src/lib).
 * Edit the JSON files to change providers, alerts, FAQs, comparisons or the demo copy.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

function vm_site() {
	$d = vm_data( 'site' );
	return isset( $d['site'] ) ? $d['site'] : array();
}

function vm_providers() {
	$d = vm_data( 'site' );
	return isset( $d['providers'] ) ? $d['providers'] : array();
}

/** Same as the original providerCount(kind). */
function vm_provider_count( $kind ) {
	return count(
		array_filter(
			vm_providers(),
			function ( $p ) use ( $kind ) {
				return $p['kind'] === $kind;
			}
		)
	);
}

function vm_alert_groups() {
	$d = vm_data( 'site' );
	return isset( $d['alertGroups'] ) ? $d['alertGroups'] : array();
}

function vm_principles() {
	$d = vm_data( 'site' );
	return isset( $d['principles'] ) ? $d['principles'] : array();
}

function vm_faqs() {
	$d = vm_data( 'site' );
	return isset( $d['faqs'] ) ? $d['faqs'] : array();
}

function vm_compare() {
	return vm_data( 'compare' );
}

function vm_competitors() {
	$c = vm_compare();
	return isset( $c['competitors'] ) ? $c['competitors'] : array();
}

function vm_competitor( $slug ) {
	foreach ( vm_competitors() as $c ) {
		if ( $c['slug'] === $slug ) {
			return $c;
		}
	}
	return null;
}

/** Original blog posts (newest first), used by the importer in Appearance → VuloMail. */
function vm_seed_posts() {
	return vm_data( 'blog' );
}

/** Replaces {tokens} in pattern copy with live counts so the copy stays in sync with data/site.json. */
function vm_tokens( $text ) {
	return strtr(
		(string) $text,
		array(
			'{providers}'   => count( vm_providers() ),
			'{api}'         => vm_provider_count( 'api' ),
			'{preset}'      => vm_provider_count( 'preset' ),
			'{sms}'         => vm_provider_count( 'sms' ),
			'{competitors}' => count( vm_competitors() ),
		)
	);
}
