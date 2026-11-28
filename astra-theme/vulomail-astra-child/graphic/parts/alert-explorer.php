<?php
/**
 * Graphic: SMS alert explorer (switch alerts on, edit the template, live preview and segment count)
 *
 * Alerts and sample values from data/site.json.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$d      = vm_data( 'site' );
$groups = $d['alertGroups'];
$icons  = array( 'site' => 'globe', 'store' => 'store', 'customer' => 'user' );
$on     = array( 'wc_new_order' => true, 'email_failed' => true );
$data   = array( 'groups' => $groups, 'samples' => $d['sampleValues'], 'enabled' => $on );
$g0     = $groups[0];
$a0     = $g0['alerts'][0];
?>
<div class="vm:grid vm:gap-6 vm:lg:grid-cols-[1.1fr_1fr]" data-vm-alerts='<?php echo esc_attr( wp_json_encode( $data ) ); ?>'>
	<div class="vm:card vm:p-3 vm:sm:p-4">
		<div class="vm:flex vm:flex-wrap vm:gap-2 vm:p-2">
			<?php foreach ( $groups as $k => $x ) : ?>
				<button type="button" data-vm-group="<?php echo esc_attr( $x['id'] ); ?>" class="vm:relative vm:flex vm:items-center vm:gap-2 vm:rounded-full vm:px-4 vm:py-2 vm:text-sm vm:transition-colors <?php echo 0 === $k ? 'vm:text-paper' : 'vm:text-ink-soft vm:hover:text-ink'; ?>"><?php echo 0 === $k ? '<span class="vm-seg-bg vm:absolute vm:inset-0 vm:rounded-full vm:bg-ink"></span>' : ''; ?><?php echo vm_icon( $icons[ $x['id'] ], 'vm:relative vm:h-4 vm:w-4' ); // phpcs:ignore ?><span class="vm:relative"><?php echo esc_html( $x['name'] . ' · ' . count( $x['alerts'] ) ); ?></span></button>
			<?php endforeach; ?>
		</div>
		<p class="vm:px-3 vm:pb-2 vm:text-xs vm:text-ink-mute" data-vm-to>Sent to: <b class="vm:font-medium vm:text-ink-soft"><?php echo esc_html( $g0['to'] ); ?></b></p>
		<ul class="vm:space-y-1" data-vm-alert-list></ul>
		<template data-vm-lock><span class="vm:ml-2 vm:inline-flex vm:items-center vm:gap-1"><?php echo vm_icon( 'lock', 'vm:h-3 vm:w-3' ); // phpcs:ignore ?> Requires </span></template>
	</div>
	<div class="vm:card vm:relative vm:flex vm:flex-col vm:overflow-hidden vm:p-6">
		<div class="vm:airmail vm:absolute vm:inset-x-0 vm:top-0 vm:h-1"></div>
		<div class="vm:flex vm:items-center vm:justify-between"><p class="vm:eyebrow vm:text-ink-mute">Message template</p><span data-vm-state class="vm:rounded-full vm:px-2.5 vm:py-1 vm:text-xs vm:font-medium vm:bg-paper-deep vm:text-ink-mute">Off by default</span></div>
		<h3 class="vm:display vm:mt-3 vm:text-3xl" data-vm-title><?php echo esc_html( $a0['label'] ); ?></h3>
		<textarea rows="3" data-vm-tpl class="vm:mt-4 vm:w-full vm:resize-none vm:rounded-xl vm:border vm:border-line vm:bg-paper vm:p-3 vm:font-mono vm:text-sm vm:outline-none vm:transition-shadow vm:focus:border-ink vm:focus:shadow-[0_0_0_4px_rgba(19,20,27,0.06)]"><?php echo esc_html( $a0['template'] ); ?></textarea>
		<div class="vm:mt-3 vm:flex vm:flex-wrap vm:gap-1.5" data-vm-ph></div>
		<p class="vm:eyebrow vm:mt-6 vm:text-ink-mute">Preview</p>
		<p data-vm-preview class="vm:mt-3 vm:max-w-[90%] vm:rounded-2xl vm:rounded-bl-md vm:bg-ink vm:px-4 vm:py-3 vm:text-sm vm:leading-snug vm:text-paper"></p>
		<div class="vm:mt-auto vm:flex vm:items-center vm:justify-between vm:pt-6 vm:font-mono vm:text-xs vm:text-ink-mute"><span data-vm-chars></span><span data-vm-segs></span></div>
	</div>
</div>
