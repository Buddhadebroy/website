<?php
/**
 * "Sources & method" fold-out under comparison tables.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$data = vf_compare();
?>
<details class="group mt-6 rounded-2xl border border-line bg-white/70 px-5 py-4 text-sm">
	<summary class="cursor-pointer list-none font-semibold text-ink-soft group-open:text-ink">Sources &amp; method <span class="text-xs font-normal">(checked <?php echo esc_html( $data['checked_on'] ); ?>)</span></summary>
	<p class="mt-3 text-ink-soft">
		<?php if ( ! empty( $args['intro'] ) ) : ?>Each column describes the plugin's free version as stated by its maker; where a feature is paid we name the cheapest plan the pricing page lists it under. <?php endif; ?>
		Plans and prices change — check the vendor's site before you buy. All product names are trademarks of their owners; this comparison isn't affiliated with or endorsed by them.
	</p>
	<ul class="mt-3 grid gap-1.5 sm:grid-cols-2">
		<?php foreach ( vf_all_sources() as $s ) : ?>
		<li><a href="<?php echo esc_url( $s[1] ); ?>" target="_blank" rel="noopener noreferrer" class="text-navy underline decoration-navy/30 underline-offset-2 hover:decoration-navy"><?php echo esc_html( $s[0] ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</details>
