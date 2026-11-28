<?php
/**
 * Graphic: Searchable FAQ with categories
 *
 * Questions from data/site.json → faqs.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$faqs = vm_faqs();
$cats = array_merge( array( 'All' ), array_values( array_unique( wp_list_pluck( $faqs, 'cat' ) ) ) );
?>
<div data-vm-faqx>
	<div class="vm:flex vm:flex-col vm:gap-4 vm:sm:flex-row vm:sm:items-center vm:sm:justify-between">
		<div class="vm:flex vm:flex-wrap vm:gap-1">
			<?php foreach ( $cats as $k => $c ) : ?>
				<button type="button" data-vm-cat="<?php echo esc_attr( $c ); ?>" class="vm:relative vm:rounded-full vm:px-4 vm:py-2 vm:text-sm vm:transition-colors <?php echo 0 === $k ? 'vm:text-paper' : 'vm:text-ink-soft vm:hover:text-ink'; ?>"><?php echo 0 === $k ? '<span class="vm-seg-bg vm:absolute vm:inset-0 vm:rounded-full vm:bg-ink"></span>' : ''; ?><span class="vm:relative"><?php echo esc_html( $c ); ?></span></button>
			<?php endforeach; ?>
		</div>
		<label class="vm:flex vm:h-11 vm:items-center vm:gap-2 vm:rounded-full vm:border vm:border-line vm:bg-surface vm:px-4 vm:text-sm vm:focus-within:border-ink vm:sm:w-72"><?php echo vm_icon( 'search', 'vm:h-4 vm:w-4 vm:text-ink-mute' ); // phpcs:ignore ?><input data-vm-q placeholder="Search questions" class="vm:w-full vm:bg-transparent vm:outline-none vm:placeholder:text-ink-mute"></label>
	</div>
	<ul class="vm:mt-10 vm:divide-y vm:divide-line vm:border-y vm:border-line">
		<?php foreach ( $faqs as $k => $f ) : ?>
			<li data-cat="<?php echo esc_attr( $f['cat'] ); ?>" data-text="<?php echo esc_attr( strtolower( $f['q'] . $f['a'] ) ); ?>">
				<button type="button" aria-expanded="<?php echo 0 === $k ? 'true' : 'false'; ?>" class="vm:group vm:flex vm:w-full vm:items-start vm:justify-between vm:gap-6 vm:py-6 vm:text-left">
					<span><span class="vm:eyebrow vm:text-ink-mute"><?php echo esc_html( $f['cat'] ); ?></span><span class="vm:mt-1 vm:block vm:text-lg vm:font-medium vm:transition-colors vm:group-hover:text-red vm:sm:text-xl"><?php echo esc_html( $f['q'] ); ?></span></span>
					<span class="vm-faq-plus vm:mt-4 vm:grid vm:h-8 vm:w-8 vm:shrink-0 vm:place-items-center vm:rounded-full vm:border vm:border-line-strong" style="<?php echo 0 === $k ? 'transform:rotate(45deg);background-color:var(--ink);color:var(--paper)' : 'background-color:var(--surface);color:var(--ink)'; ?>"><?php echo vm_icon( 'plus', 'vm:h-4 vm:w-4' ); // phpcs:ignore ?></span>
				</button>
				<div class="vm:overflow-hidden"<?php echo 0 === $k ? '' : ' hidden'; ?>><p class="vm:max-w-3xl vm:pb-7 vm:leading-relaxed vm:text-ink-soft"><?php echo esc_html( $f['a'] ); ?></p></div>
			</li>
		<?php endforeach; ?>
		</ul>
	<template data-vm-none-tpl><li class="vm:py-12 vm:text-center vm:text-ink-mute" data-vm-none>No questions match “<span></span>”.</li></template>
</div>
