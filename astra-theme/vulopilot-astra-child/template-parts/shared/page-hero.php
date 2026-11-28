<?php
/**
 * Inner-page hero (eyebrow, title with accent, intro, optional buttons) + breadcrumb schema.
 *
 * Args: eyebrow, title, accent, intro, crumbs (array of [name, path]), ctas (bool).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$a = wp_parse_args(
	$args,
	array(
		'eyebrow' => '',
		'title'   => '',
		'accent'  => '',
		'intro'   => '',
		'crumbs'  => array(),
		'ctas'    => true,
	)
);
$site  = vp_site_facts()['site_url'];
$trail = array_merge( array( array( 'Home', '/' ) ), $a['crumbs'] );
$items = array();
foreach ( $trail as $i => $c ) {
	$item = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0] );
	if ( ! empty( $c[1] ) ) {
		$item['item'] = $site . $c[1];
	}
	$items[] = $item;
}
?>
<section class="relative overflow-hidden px-5 pb-12 pt-28">
	<div class="pointer-events-none absolute inset-0 -z-10">
		<div class="vp-drift absolute -left-24 top-10 h-96 w-96 rounded-full bg-sage-soft blur-3xl" style="--dx:30px;--dy:20px;--dd:14s"></div>
		<div class="vp-drift absolute -right-20 top-24 h-80 w-80 rounded-full bg-apricot-soft blur-3xl" style="--dx:-30px;--dy:24px;--dd:16s"></div>
		<div class="absolute inset-0 opacity-[.35] [background-image:radial-gradient(var(--line)_1px,transparent_1px)] [background-size:26px_26px] [mask-image:radial-gradient(ellipse_at_top,black,transparent_70%)]"></div>
	</div>
	<div class="mx-auto max-w-4xl">
		<?php vp_jsonld( array( '@type' => 'BreadcrumbList', 'itemListElement' => $items ) ); ?>
		<div <?php vp_m( 'o:0 y:20 t:.8 on:load' ); ?>>
			<?php vp_eyebrow( $a['eyebrow'] ); ?>
			<h1 class="mt-5 font-display text-4xl leading-[1.06] tracking-tight sm:text-6xl"><?php echo wp_kses_post( $a['title'] ); ?><?php if ( $a['accent'] ) : ?> <span class="text-sage"><?php echo wp_kses_post( $a['accent'] ); ?></span><?php endif; ?></h1>
			<p class="mt-6 max-w-2xl text-lg text-muted"><?php echo wp_kses_post( $a['intro'] ); ?></p>
			<?php if ( $a['ctas'] ) : ?>
				<div class="mt-8 flex flex-wrap gap-3">
					<a href="<?php echo esc_url( vp_url( '/#pricing' ) ); ?>" class="group inline-flex items-center gap-2 rounded-sm bg-ink px-6 py-3 font-medium text-bg transition hover:bg-sage">SuperPower your Website <?php vp_i( 'arrow-right', 16, 'transition group-hover:translate-x-1' ); ?></a>
					<a href="<?php echo esc_url( vp_url( '/pricing/' ) ); ?>" class="rounded-sm border border-line bg-surface/70 px-6 py-3 font-medium transition hover:border-ink">See Free &amp; Pro</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
