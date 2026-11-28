<?php
/**
 * Graphic: Code examples with tabs and copy button (Developers)
 *
 * Tabs from data/developers.json → tabs.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$tabs = vm_data( 'developers' )['tabs'];
?>
<div class="vm:overflow-hidden vm:rounded-3xl vm:border vm:border-code-line vm:bg-code-bg vm:text-code-text vm:shadow-[0_40px_80px_-40px_rgba(19,20,27,0.7)]" data-vm-codetabs>
	<div class="vm:code-scroll vm:flex vm:gap-1 vm:overflow-x-auto vm:border-b vm:border-white/5 vm:p-2">
		<?php foreach ( $tabs as $k => $t ) : ?>
			<button type="button" data-vm-tab="<?php echo (int) $k; ?>" data-file="<?php echo esc_attr( $t['file'] ); ?>" class="vm:relative vm:shrink-0 vm:rounded-xl vm:px-4 vm:py-2 vm:text-sm vm:transition-colors <?php echo 0 === $k ? 'vm:text-white' : 'vm:text-white/45 vm:hover:text-white/80'; ?>"><?php echo 0 === $k ? '<span class="vm-seg-bg vm:absolute vm:inset-0 vm:rounded-xl vm:bg-white/10"></span>' : ''; ?><span class="vm:relative"><?php echo esc_html( $t['label'] ); ?></span></button>
		<?php endforeach; ?>
	</div>
	<div class="vm:flex vm:items-center vm:justify-between vm:px-5 vm:pt-4">
		<span class="vm:font-mono vm:text-xs vm:text-white/40" data-vm-file><?php echo esc_html( $tabs[0]['file'] ); ?></span>
		<button type="button" data-vm-copy class="vm:flex vm:items-center vm:gap-1 vm:rounded-lg vm:px-2 vm:py-1 vm:font-mono vm:text-xs vm:text-white/50 vm:transition-colors vm:hover:bg-white/10 vm:hover:text-white"><span class="vm:flex vm:items-center vm:gap-1">Copy</span></button>
	</div>
	<?php foreach ( $tabs as $k => $t ) : ?>
		<div data-vm-panel="<?php echo (int) $k; ?>"<?php echo $k ? ' hidden' : ''; ?>>
			<pre class="vm:code-scroll vm:overflow-x-auto vm:px-5 vm:pb-5 vm:pt-3 vm:font-mono vm:text-[0.8rem] vm:leading-relaxed"><code><?php echo esc_html( $t['code'] ); ?></code></pre>
			<p class="vm:border-t vm:border-white/5 vm:px-5 vm:py-4 vm:text-sm vm:text-white/55"><?php echo esc_html( $t['note'] ); ?></p>
		</div>
	<?php endforeach; ?>
	<template data-vm-check><?php echo vm_icon( 'check', 'vm:h-3 vm:w-3' ); // phpcs:ignore ?></template>
</div>
