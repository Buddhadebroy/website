<?php
/**
 * Not Showing On Google › hero: what VuloPilot finds on this page's first scan.
 * Graphic name: not-showing-on-google-1  ·  Shortcode: [vp_not_showing_on_google_1]  ·  /problems/not-showing-on-google/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'label'    => 'Scan result',
		'page'     => '/problems/not-showing-on-google/',
		'headline' => 'Your site is not showing on Google.',
		'intro'    => 'Usually a blocked crawler, a missing sitemap or thin pages. VuloPilot checks the usual suspects in one scan.',
		'found'    => 'robots.txt blocks crawling.',
		'does'     => 'Check SEO problems, sitemap and robots.txt.',
		'found_tag' => 'Found',
		'fix_tag'   => 'What VuloPilot does',
	),
	(array) ( $args['text'] ?? array() )
);
?>
<div class="card overflow-hidden" data-vpg="static">
	<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
		<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'scan-search', 15 ); ?></span><?php echo esc_html( $t['label'] ); ?></div>
		<span class="font-mono text-[11px] text-muted"><?php echo esc_html( $t['page'] ); ?></span>
	</div>
	<div class="p-5">
		<div class="text-base font-semibold leading-snug"><?php echo esc_html( $t['headline'] ); ?></div>
		<p class="mt-2 text-sm leading-relaxed text-muted"><?php echo esc_html( $t['intro'] ); ?></p>
		<div class="mt-4 flex flex-wrap items-start gap-2">
			<span class="vpg-chip is-bad shrink-0"><?php echo esc_html( $t['found_tag'] ); ?></span>
			<span class="min-w-0 flex-1 text-xs text-muted"><?php echo esc_html( $t['found'] ); ?></span>
		</div>
		<div class="mt-2 flex flex-wrap items-start gap-2">
			<span class="vpg-chip is-good shrink-0"><?php vp_i( 'check', 11 ); ?><?php echo esc_html( $t['fix_tag'] ); ?></span>
			<span class="min-w-0 flex-1 text-xs text-muted"><?php echo esc_html( $t['does'] ); ?></span>
		</div>
	</div>
</div>
