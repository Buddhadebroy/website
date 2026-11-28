<?php
/**
 * Website Health › hero: what VuloPilot finds on this page's first scan.
 * Graphic name: website-health-1  ·  Shortcode: [vp_website_health_1]  ·  /solutions/website-health/
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'label'    => 'Scan result',
		'page'     => '/solutions/website-health/',
		'headline' => 'Know your website is actually healthy.',
		'intro'    => 'VuloPilot checks WordPress itself, keeps restorable backups, and rolls everything into a Health Score.',
		'found'    => 'Updates pile up and cron jobs quietly stop.',
		'does'     => 'A checkup covers WordPress updates, the database, scheduled tasks and server setup.',
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
