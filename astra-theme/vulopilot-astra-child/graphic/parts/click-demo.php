<?php
/**
 * Search Visibility › "Win the click": the same Google result before and after the fixes
 * (title, description, rich result), plus an automatic redirect and the 404 log. Plays on a loop.
 * Graphic name: click-demo  ·  Shortcode: [vp_click_demo]
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();
$t    = array_merge(
	array(
		'label'    => 'Google preview',
		'page'     => '/services/web-design/',
		'before'   => 'Before',
		'after'    => 'After',
		'site'     => 'Studio North',
		'crumb'    => 'studionorth.com › services › web-design',
		'title_b'  => 'Services – Studio North',
		'desc_b'   => 'Welcome to our website. We do many things for many clients. Click here to learn more about what we…',
		'title_a'  => 'Web Design for Growing Brands | Studio North',
		'desc_a'   => 'Fast, accessible WordPress websites for small businesses in Austin. Fixed prices from $2,900. Book a free call.',
		'rating'   => '4.9 · 38 reviews',
		'faq'      => array( 'How long does a new website take?', 'Do you build on WordPress?' ),
		'redirect' => 'Automatic redirect',
		'from'     => '/web-design-services/',
		'to'       => '/services/web-design/',
		'r404'     => '404 log',
		'broken'   => '/pricng/',
		'visits'   => '12 visits this week',
		'fix'      => 'Redirect',
	),
	(array) ( $args['text'] ?? array() )
);
// Check, before (text, ok), after (text, ok).
$checks = $args['checks'] ?? array(
	array( 'Title', array( '23 / 60, too vague', false ), array( '45 / 60 characters', true ) ),
	array( 'Description', array( 'Generic text', false ), array( '112 / 160 characters', true ) ),
	array( 'Rich result', array( 'None', false ), array( 'Reviews + FAQ', true ) ),
);
?>
<div class="card overflow-hidden" data-vpg="loop" data-every="9000">
	<div data-fx="swap" data-after="2.6">
		<div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5">
			<div class="flex items-center gap-2.5 text-sm font-semibold"><span class="grid h-7 w-7 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'search', 15 ); ?></span><?php echo esc_html( $t['label'] ); ?> <span class="vpg-sm-inline font-mono text-[11px] font-normal text-muted"><?php echo esc_html( $t['page'] ); ?></span></div>
			<div class="vpg-seg"><span class="is-b"><?php echo esc_html( $t['before'] ); ?></span><span class="is-a"><?php echo esc_html( $t['after'] ); ?></span></div>
		</div>

		<div class="p-5">
			<div class="vpg-serp rounded-sm border border-line bg-surface p-4">
				<div class="flex items-center gap-2.5">
					<span class="grid h-7 w-7 place-items-center rounded-full bg-bg text-[11px] font-bold text-sage">SN</span>
					<span class="min-w-0"><span class="block text-[13px] font-medium leading-tight"><?php echo esc_html( $t['site'] ); ?></span><span class="block truncate text-[11px] text-muted"><?php echo esc_html( $t['crumb'] ); ?></span></span>
				</div>
				<div data-when="before">
					<div class="vpg-serp-title mt-2"><?php echo esc_html( $t['title_b'] ); ?></div>
					<p class="mt-1 text-[13px] leading-relaxed text-muted"><?php echo esc_html( $t['desc_b'] ); ?></p>
				</div>
				<div data-when="after" hidden>
					<div class="vpg-serp-title mt-2" <?php vp_m( 'o:0 y:6 t:.4' ); ?>><?php echo esc_html( $t['title_a'] ); ?></div>
					<div class="mt-1 flex items-center gap-1.5 text-[12px] text-muted" <?php vp_m( 'o:0 t:.4 d:.15' ); ?>><span class="vpg-stars">★★★★★</span><?php echo esc_html( $t['rating'] ); ?></div>
					<p class="mt-1 text-[13px] leading-relaxed text-muted" <?php vp_m( 'o:0 t:.4 d:.25' ); ?>><?php echo esc_html( $t['desc_a'] ); ?></p>
					<div class="mt-2 divide-y divide-line border-t border-line" <?php vp_m( 'o:0 y:6 t:.4 d:.4' ); ?>>
						<?php foreach ( (array) $t['faq'] as $q ) : ?>
							<div class="flex items-center justify-between py-1.5 text-[13px]"><?php echo esc_html( $q ); ?><?php vp_i( 'chevron-down', 14, 'text-muted' ); ?></div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="mt-3 grid gap-2 sm:grid-cols-3">
				<?php foreach ( $checks as $c ) : ?>
					<div class="rounded-sm border border-line bg-bg/40 px-3 py-2">
						<div class="text-[10px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $c[0] ); ?></div>
						<div class="mt-0.5 flex items-center gap-1.5 text-xs font-semibold" style="color:var(--rose)" data-when="before"><?php vp_i( 'x', 12, '', 3 ); ?><?php echo esc_html( $c[1][0] ); ?></div>
						<div class="mt-0.5 flex items-center gap-1.5 text-xs font-semibold text-sage" data-when="after" hidden <?php vp_m( 'o:0 s:.9 t:.3' ); ?>><?php vp_i( 'check', 12, '', 3 ); ?><?php echo esc_html( $c[2][0] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="mt-4 space-y-2">
				<div class="flex flex-wrap items-center gap-2 rounded-sm border border-line px-3.5 py-2.5 text-xs" <?php vp_m( 'o:0 y:8 t:.4 d:.2' ); ?>>
					<span class="grid h-6 w-6 place-items-center rounded-sm bg-sage-soft text-sage"><?php vp_i( 'signpost', 13 ); ?></span>
					<span class="font-semibold"><?php echo esc_html( $t['redirect'] ); ?></span>
					<span class="font-mono text-muted"><?php echo esc_html( $t['from'] ); ?></span><?php vp_i( 'arrow-right', 12, 'text-muted' ); ?><span class="font-mono"><?php echo esc_html( $t['to'] ); ?></span>
					<span class="vpg-chip is-good ml-auto">301</span>
				</div>
				<div class="flex flex-wrap items-center gap-2 rounded-sm border border-line px-3.5 py-2.5 text-xs" <?php vp_m( 'o:0 y:8 t:.4 d:.3' ); ?>>
					<span class="grid h-6 w-6 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( 'var(--rose)' ) ); ?>"><?php vp_i( 'link-2', 13 ); ?></span>
					<span class="font-semibold"><?php echo esc_html( $t['r404'] ); ?></span>
					<span class="font-mono" style="color:var(--rose)"><?php echo esc_html( $t['broken'] ); ?></span>
					<span class="text-muted"><?php echo esc_html( $t['visits'] ); ?></span>
					<span class="vpg-chip is-ink ml-auto" data-when="before"><?php echo esc_html( $t['fix'] ); ?></span>
					<span class="vpg-chip is-good ml-auto" data-when="after" hidden><?php vp_i( 'check', 11, '', 3 ); ?>/pricing/</span>
				</div>
			</div>
		</div>
	</div>
</div>
