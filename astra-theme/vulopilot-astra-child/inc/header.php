<?php
/**
 * Mega-menu header (port of components/Nav.tsx). Behaviour lives in assets/js/vp-site.js.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

/** Replace Astra's header with ours. */
add_action(
	'wp',
	function () {
		if ( ! vp_opt( 'vp_use_header' ) ) {
			return;
		}
		remove_all_actions( 'astra_header' );
		add_action( 'astra_header', function () { echo vp_render_header(); } ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
);

/** One link row inside a dropdown. */
function vp_header_row( $it, $i ) {
	$icon = $it['icon'] ?? vp_icon_for( $it['href'] );
	ob_start();
	?>
	<li class="vp-row-li relative" style="--i:<?php echo (int) $i; ?>">
		<a href="<?php echo esc_url( vp_url( $it['href'] ) ); ?>" class="vp-row relative z-10 flex items-center gap-2.5 rounded-sm px-2 py-1.5 text-[13px]">
			<span class="vp-row-ic grid h-7 w-7 shrink-0 place-items-center rounded-sm border border-line bg-bg text-sage transition-colors"><?php echo vp_icon( $icon, 14 ); // phpcs:ignore ?></span>
			<span class="min-w-0 flex-1"><span class="vp-row-label block font-medium leading-tight text-ink"><?php echo esc_html( $it['label'] ); ?></span><?php if ( ! empty( $it['desc'] ) ) : ?><span class="block text-[11px] leading-tight text-muted"><?php echo esc_html( $it['desc'] ); ?></span><?php endif; ?></span>
		</a>
	</li>
	<?php
	return ob_get_clean();
}

/** A dropdown panel. */
function vp_header_popover( $m ) {
	$rail = count( $m['cats'] ) > 1;
	ob_start();
	?>
	<div class="vp-pop" data-vp-pop="<?php echo esc_attr( $m['id'] ); ?>" role="region" aria-label="<?php echo esc_attr( $m['label'] ); ?>">
		<div class="overflow-hidden rounded-sm border border-line bg-surface/95 shadow-[0_30px_70px_-28px_rgba(0,41,145,.4)] backdrop-blur-2xl">
			<div class="grid <?php echo $rail ? 'grid-cols-[230px_1fr_260px]' : 'grid-cols-[1fr_260px]'; ?>">
				<?php if ( $rail ) : ?>
				<ul class="vp-rail relative space-y-1 border-r border-line bg-bg/60 p-2.5">
					<span class="vp-rail-pill absolute rounded-sm bg-surface shadow-sm ring-1 ring-line" aria-hidden="true"></span>
					<?php foreach ( $m['cats'] as $k => $c ) : ?>
					<li>
						<button type="button" data-vp-cat="<?php echo esc_attr( $c['id'] ); ?>" class="vp-rail-btn<?php echo 0 === $k ? ' is-on' : ''; ?> relative flex w-full items-center gap-3 rounded-sm px-3 py-2.5 text-left">
							<span class="vp-rail-ic relative grid h-8 w-8 shrink-0 place-items-center rounded-sm bg-sage-soft text-sage transition-colors"><?php echo vp_icon( $c['icon'], 15 ); // phpcs:ignore ?></span>
							<span class="relative min-w-0 flex-1"><span class="block text-sm font-semibold leading-tight"><?php echo esc_html( $c['title'] ); ?></span><span class="block truncate text-[11px] text-muted"><?php echo esc_html( $c['hint'] ); ?></span></span>
							<span class="vp-rail-count relative rounded-sm px-1.5 text-[10px] font-semibold text-muted"><?php echo count( $c['items'] ); ?></span>
						</button>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
				<div class="vp-cats relative flex min-h-[300px] flex-col p-3">
					<?php foreach ( $m['cats'] as $k => $c ) : ?>
					<div class="vp-cat<?php echo 0 === $k ? ' is-active' : ''; ?> flex flex-1 flex-col" data-vp-panel="<?php echo esc_attr( $c['id'] ); ?>">
						<ul class="vp-rows relative grid flex-1 content-start gap-x-2 <?php echo ( count( $c['items'] ) > 6 || ! $rail ) ? 'grid-cols-3' : 'grid-cols-2'; ?>">
							<span class="vp-row-hl absolute rounded-sm bg-sage-soft/70" aria-hidden="true"></span>
							<?php
							foreach ( $c['items'] as $i => $it ) {
								echo vp_header_row( $it, $i ); // phpcs:ignore
							}
							?>
						</ul>
						<div class="mt-2 flex items-center justify-between border-t border-line px-2 pt-2.5 text-xs">
							<a href="<?php echo esc_url( vp_url( $c['all']['href'] ) ); ?>" class="group inline-flex items-center gap-1 font-semibold text-sage"><?php echo esc_html( $c['all']['label'] ); ?><?php echo vp_icon( 'arrow-right', 13, 'transition group-hover:translate-x-0.5' ); // phpcs:ignore ?></a>
							<?php if ( ! empty( $m['foot'] ) ) : ?><a href="<?php echo esc_url( vp_url( $m['foot']['href'] ) ); ?>" class="text-muted transition hover:text-ink"><?php echo esc_html( $m['foot']['label'] ); ?></a><?php endif; ?>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
				<?php if ( ! empty( $m['promo'] ) ) : $p = $m['promo']; ?>
				<div class="relative m-3 ml-0 flex flex-col justify-between overflow-hidden rounded-sm border border-line bg-gradient-to-br from-sage-soft via-surface to-apricot-soft/60 p-5 text-ink">
					<div class="vp-blob-a absolute -right-8 -top-8 h-36 w-36 rounded-full bg-sage/15 blur-2xl"></div>
					<div class="vp-blob-b absolute -bottom-10 -left-6 h-32 w-32 rounded-full bg-flare/20 blur-3xl"></div>
					<div class="relative">
						<span class="vp-bob grid h-10 w-10 place-items-center rounded-sm bg-sage text-white shadow-sm"><?php echo vp_icon( $p['icon'], 18 ); // phpcs:ignore ?></span>
						<div class="mt-4 text-[11px] font-semibold uppercase tracking-widest text-sage"><?php echo esc_html( $p['kicker'] ); ?></div>
						<div class="mt-1 text-lg font-semibold leading-tight tracking-tight"><?php echo esc_html( $p['title'] ); ?></div>
						<p class="mt-1.5 text-xs text-muted"><?php echo esc_html( $p['body'] ); ?></p>
					</div>
					<a href="<?php echo esc_url( vp_url( $p['href'] ) ); ?>" class="group relative mt-4 inline-flex w-fit items-center gap-1.5 rounded-sm bg-sage px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-ink"><?php echo esc_html( $p['cta'] ); ?><?php echo vp_icon( 'arrow-right', 13, 'transition group-hover:translate-x-0.5' ); // phpcs:ignore ?></a>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Full header markup.
 *
 * @param array $args menu (id/slug to force a menu), fixed (bool), cta (bool).
 */
function vp_render_header( $args = array() ) {
	$args  = wp_parse_args( $args, array( 'menu' => null, 'fixed' => true, 'cta' => true ) );
	$items = vp_header_menu( $args['menu'] );
	$megas = array_values( array_filter( $items, function ( $i ) { return 'mega' === $i['type']; } ) );
	$plain = array_values( array_filter( $items, function ( $i ) { return 'mega' !== $i['type']; } ) );
	$link  = 'vp-nav-link relative z-[1] rounded-sm px-3 py-1.5 transition-colors hover:text-ink';
	ob_start();
	?>
	<div class="vp-scope vp-header-wrap">
	<header class="vp-header <?php echo $args['fixed'] ? 'fixed inset-x-0 top-4 z-50' : 'relative z-40'; ?> flex justify-center px-4" data-vp-header>
		<div class="relative w-full max-w-6xl">
			<nav class="relative flex items-center justify-between rounded-sm border border-line bg-surface/80 px-5 py-2.5 shadow-[0_10px_40px_-20px_rgba(0,41,145,.35)] backdrop-blur-xl" aria-label="<?php esc_attr_e( 'Main', 'vulopilot' ); ?>">
				<?php echo vp_logo_html(); // phpcs:ignore ?>
				<div class="vp-nav-links relative hidden items-center gap-0.5 whitespace-nowrap text-sm font-medium text-muted lg:flex">
					<span class="vp-nav-pill absolute rounded-sm bg-sage-soft" aria-hidden="true"></span>
					<?php foreach ( $items as $it ) : ?>
						<?php if ( 'mega' === $it['type'] ) : ?>
						<button type="button" data-vp-open="<?php echo esc_attr( $it['id'] ); ?>" aria-expanded="false" class="<?php echo esc_attr( $link ); ?> flex items-center gap-1"><?php echo esc_html( $it['label'] ); ?><?php echo vp_icon( 'chevron-down', 14, 'vp-chev transition-transform duration-300' ); // phpcs:ignore ?></button>
						<?php else : ?>
						<a href="<?php echo esc_url( vp_url( $it['href'] ) ); ?>" class="<?php echo esc_attr( $link ); ?>"<?php echo ! empty( $it['target'] ) ? ' target="' . esc_attr( $it['target'] ) . '"' : ''; ?>><?php echo esc_html( $it['label'] ); ?></a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<div class="flex items-center gap-2">
					<?php if ( $args['cta'] && vp_opt( 'vp_cta_text' ) ) : ?>
					<a href="<?php echo esc_url( vp_url( vp_opt( 'vp_cta_url' ) ) ); ?>" class="group relative overflow-hidden whitespace-nowrap rounded-sm bg-sage px-4 py-2 text-sm font-semibold text-white">
						<span class="relative z-10 flex items-center gap-1.5"><?php echo vp_icon( 'zap', 14 ); // phpcs:ignore ?><?php echo esc_html( vp_opt( 'vp_cta_text' ) ); ?><span class="hidden xl:inline"><?php echo esc_html( vp_opt( 'vp_cta_text_long' ) ); ?></span></span>
						<span class="absolute inset-0 -translate-x-full bg-flare transition-transform duration-500 group-hover:translate-x-0"></span>
					</a>
					<?php endif; ?>
					<button type="button" aria-label="<?php esc_attr_e( 'Menu', 'vulopilot' ); ?>" aria-expanded="false" data-vp-burger class="grid h-9 w-9 place-items-center rounded-full border border-line lg:hidden"><span class="vp-burger-open"><?php echo vp_icon( 'menu', 16 ); // phpcs:ignore ?></span><span class="vp-burger-close"><?php echo vp_icon( 'x', 16 ); // phpcs:ignore ?></span></button>
				</div>
				<div class="pointer-events-none absolute inset-0 overflow-hidden rounded-sm"><div class="vp-progress absolute bottom-0 left-0 h-[2px] w-full origin-left bg-flare"></div></div>
			</nav>

			<div class="vp-pops absolute inset-x-0 top-full hidden pt-3 lg:block">
				<?php
				foreach ( $megas as $m ) {
					echo vp_header_popover( $m ); // phpcs:ignore
				}
				?>
			</div>

			<div class="vp-mobile absolute left-0 right-0 top-full mt-3 max-h-[75vh] overflow-auto rounded-sm border border-line bg-surface p-4 shadow-2xl lg:hidden" data-vp-mobile>
				<?php foreach ( $plain as $it ) : ?>
				<a href="<?php echo esc_url( vp_url( $it['href'] ) ); ?>" class="block rounded-sm px-3 py-2.5 font-semibold"><?php echo esc_html( $it['label'] ); ?></a>
				<?php endforeach; ?>
				<?php foreach ( $megas as $m ) : ?>
				<details class="group rounded-sm px-3 py-2">
					<summary class="flex cursor-pointer list-none items-center justify-between font-semibold"><?php echo esc_html( $m['label'] ); ?><?php echo vp_icon( 'chevron-down', 15, 'transition group-open:rotate-180' ); // phpcs:ignore ?></summary>
					<div class="mt-2 space-y-3">
						<?php foreach ( $m['cats'] as $c ) : ?>
						<div>
							<a href="<?php echo esc_url( vp_url( $c['all']['href'] ) ); ?>" class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-sage"><?php echo vp_icon( $c['icon'], 13 ); // phpcs:ignore ?><?php echo esc_html( $c['title'] ); ?></a>
							<?php foreach ( $c['items'] as $it ) : ?>
							<a href="<?php echo esc_url( vp_url( $it['href'] ) ); ?>" class="flex items-center gap-2.5 py-1.5 text-sm text-muted"><?php echo vp_icon( $it['icon'] ?? vp_icon_for( $it['href'] ), 15, 'text-sage' ); // phpcs:ignore ?><?php echo esc_html( $it['label'] ); ?></a>
							<?php endforeach; ?>
						</div>
						<?php endforeach; ?>
					</div>
				</details>
				<?php endforeach; ?>
			</div>
		</div>
	</header>
	</div>
	<?php
	return ob_get_clean();
}
