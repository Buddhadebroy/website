<?php
/**
 * Footer (port of components/Footer.tsx).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp',
	function () {
		if ( ! vp_opt( 'vp_use_footer' ) ) {
			return;
		}
		remove_all_actions( 'astra_footer' );
		add_action( 'astra_footer', function () { echo vp_render_footer(); } ); // phpcs:ignore
	}
);

/** One footer column. */
function vp_footer_col( $col ) {
	ob_start();
	?>
	<div>
		<div class="mb-3 font-mono text-[11px] uppercase tracking-widest text-sage"><?php echo esc_html( $col['title'] ); ?></div>
		<ul class="space-y-1.5 text-sm text-muted">
			<?php foreach ( $col['items'] as $i ) : ?>
			<li><a href="<?php echo esc_url( vp_url( $i['href'] ) ); ?>" class="transition hover:text-ink"><?php echo esc_html( $i['label'] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
	return ob_get_clean();
}

/** Footer markup. */
function vp_render_footer() {
	$cols = vp_footer_menu();
	$row1 = array_filter( $cols, function ( $c ) { return 1 === $c['row']; } );
	$row2 = array_filter( $cols, function ( $c ) { return 2 === $c['row']; } );
	ob_start();
	?>
	<div class="vp-scope vp-footer-wrap">
	<footer class="border-t border-line px-5 py-10">
		<div class="mx-auto max-w-6xl">
			<div class="grid gap-10 lg:grid-cols-[1.2fr_repeat(5,1fr)]">
				<div>
					<?php echo vp_logo_html(); // phpcs:ignore ?>
					<p class="mt-4 max-w-xs text-sm text-muted"><?php echo esc_html( vp_opt( 'vp_footer_about' ) ); ?></p>
					<?php if ( vp_opt( 'vp_footer_consult' ) && vp_opt( 'vp_consult_enable' ) ) : ?>
					<button type="button" class="vp-open-consult mt-5 rounded-sm border border-line bg-surface px-4 py-2 text-sm font-semibold text-sage transition hover:border-sage"><?php echo esc_html( vp_opt( 'vp_footer_consult' ) ); ?></button>
					<?php endif; ?>
				</div>
				<?php
				foreach ( $row1 as $c ) {
					echo vp_footer_col( $c ); // phpcs:ignore
				}
				?>
			</div>
			<?php if ( $row2 ) : ?>
			<div class="mt-8 grid gap-8 border-t border-line pt-8 sm:grid-cols-2 lg:grid-cols-5">
				<?php
				foreach ( $row2 as $c ) {
					echo vp_footer_col( $c ); // phpcs:ignore
				}
				?>
			</div>
			<?php endif; ?>
			<div class="mt-8 flex flex-col justify-between gap-3 border-t border-line pt-6 text-sm text-muted sm:flex-row">
				<span><?php echo esc_html( vp_opt( 'vp_footer_left' ) ); ?></span>
				<span><?php echo esc_html( vp_opt( 'vp_footer_right' ) ); ?></span>
			</div>
		</div>
	</footer>
	</div>
	<?php
	return ob_get_clean();
}
