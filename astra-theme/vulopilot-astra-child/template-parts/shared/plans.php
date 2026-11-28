<?php
/**
 * Plan cards with a Monthly / Yearly switch. Prices come from vp_plans() in inc/content-data.php.
 *
 * Args: compact (bool) – the Home version shows four features per plan.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$compact  = ! empty( $args['compact'] );
$plans    = vp_plans();
$features = vp_plan_features();
if ( $compact ) {
	$features = array_slice( $features, 0, 4 );
}
$best = max( array_map( function ( $p ) { return vp_plan_saving( $p )['pct']; }, $plans ) );
$t    = array(
	'monthly'  => 'Monthly',
	'yearly'   => 'Yearly',
	'free'     => 'Not ready to pay? <a href="' . esc_url( vp_url( '/docs/' ) ) . '" class="font-semibold text-sage hover:underline">Start with the free plugin</a>: core scans and 100 AI credits, no card needed.',
	'popular'  => 'Most Popular',
	'month'    => 'month',
	'year'     => 'year',
	'save'     => 'Save $%s',
	'per_mo'   => 'about $%s/mo',
	'billed'   => 'or $%s billed yearly',
	'site'     => 'site',
	'sites'    => 'sites',
	'credits'  => 'credits',
	'included' => 'Everything in the free plan',
	'get'      => 'Get %s',
	'talk'     => 'Talk to us',
);
?>
<div data-vp="billing">
	<div class="flex flex-col items-center gap-3">
		<div class="relative inline-flex rounded-sm border border-line bg-surface p-1 shadow-sm" role="group" aria-label="Billing period" data-pill-group>
			<span class="vp-pill rounded-sm bg-ink"></span>
			<?php foreach ( array( false, true ) as $yearly ) : ?>
				<button type="button" aria-pressed="<?php echo $yearly ? 'false' : 'true'; ?>" class="relative rounded-sm px-6 py-2 text-sm font-semibold" data-tab="<?php echo $yearly ? 'yearly' : 'monthly'; ?>">
					<span class="relative flex items-center gap-2 <?php echo $yearly ? 'text-muted' : 'text-bg'; ?>"><?php echo esc_html( $yearly ? $t['yearly'] : $t['monthly'] ); ?><?php if ( $yearly ) : ?><span class="rounded-sm bg-flare px-2 py-0.5 text-[10px] font-bold text-white">-<?php echo (int) $best; ?>%</span><?php endif; ?></span>
				</button>
			<?php endforeach; ?>
		</div>
		<p class="text-sm text-muted"><?php echo wp_kses_post( $t['free'] ); ?></p>
	</div>

	<div class="mx-auto mt-9 grid max-w-xl items-stretch gap-5 lg:max-w-5xl lg:grid-cols-3">
		<?php foreach ( $plans as $p ) :
			$s     = vp_plan_saving( $p );
			$pop   = ! empty( $p['popular'] );
			$muted = $pop ? 'text-white/60' : 'text-muted';
			$btn   = 'group mt-8 flex items-center justify-center gap-2 rounded-sm py-3 text-sm font-semibold transition ' . ( $pop ? 'bg-bg text-ink hover:bg-flare hover:text-white' : 'bg-sage text-white hover:bg-ink' );
			?>
			<div class="vp-hover relative flex flex-col overflow-hidden rounded-sm p-7 <?php echo $pop ? 'bg-gradient-to-b from-ink to-[#14246b] text-bg shadow-[0_40px_80px_-30px_rgba(0,41,145,.65)] ring-1 ring-sage/40 lg:-my-3' : 'card'; ?>">
				<?php if ( $pop ) : ?>
					<div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 rounded-full bg-sage blur-3xl"></div>
					<div class="pointer-events-none absolute -bottom-20 -left-10 h-44 w-44 rounded-full bg-flare/30 blur-3xl"></div>
				<?php endif; ?>
				<div class="relative flex flex-1 flex-col">
					<div class="flex items-start justify-between">
						<span class="grid h-10 w-10 place-items-center rounded-sm <?php echo $pop ? 'bg-white/10 text-flare' : 'bg-sage-soft text-sage'; ?>"><?php vp_i( $p['icon'], 18 ); ?></span>
						<?php if ( $pop ) : ?><span class="rounded-sm bg-flare px-3 py-1 text-[11px] font-bold text-white"><?php echo esc_html( $t['popular'] ); ?></span><?php endif; ?>
					</div>
					<div class="mt-4 text-sm font-semibold <?php echo esc_attr( $muted ); ?>"><?php echo esc_html( $p['name'] ); ?></div>
					<div class="mt-1 flex items-baseline gap-1.5">
						<span class="text-5xl font-semibold tracking-tight"><span class="tabular-nums">$<span data-price data-monthly="<?php echo (int) $p['monthly']; ?>" data-yearly="<?php echo (int) $p['yearly']; ?>"><?php echo (int) $p['monthly']; ?></span></span></span>
						<span class="text-sm <?php echo esc_attr( $muted ); ?>">/<span data-when="before"><?php echo esc_html( $t['month'] ); ?></span><span data-when="after" hidden><?php echo esc_html( $t['year'] ); ?></span></span>
						<span class="text-sm line-through <?php echo $pop ? 'text-white/40' : 'text-muted/70'; ?>" data-when="after" hidden>$<?php echo (int) ( $p['monthly'] * 12 ); ?></span>
					</div>
					<div class="mt-2 min-h-[2.5rem] text-xs">
						<span class="<?php echo esc_attr( $muted ); ?>" data-when="before"><?php echo esc_html( sprintf( $t['billed'], $p['yearly'] ) ); ?></span>
						<span class="inline-flex flex-wrap items-center gap-x-2 gap-y-1" data-when="after" hidden>
							<span class="whitespace-nowrap rounded-sm bg-flare/15 px-2.5 py-0.5 font-bold text-flare"><?php echo esc_html( sprintf( $t['save'], $s['amount'] ) ); ?></span>
							<span class="whitespace-nowrap <?php echo esc_attr( $muted ); ?>"><?php echo esc_html( sprintf( $t['per_mo'], $s['per_month'] ) ); ?></span>
						</span>
					</div>
					<div class="mt-4 grid grid-cols-2 gap-2">
						<div class="flex items-center gap-2 rounded-sm px-3 py-2 text-sm <?php echo $pop ? 'bg-white/10' : 'bg-bg'; ?>"><?php vp_i( 'server', 14, $pop ? 'text-flare' : 'text-sage' ); ?><b><?php echo (int) $p['sites']; ?></b><span class="<?php echo esc_attr( $muted ); ?>"><?php echo esc_html( 1 === (int) $p['sites'] ? $t['site'] : $t['sites'] ); ?></span></div>
						<div class="flex items-center gap-2 rounded-sm px-3 py-2 text-sm <?php echo $pop ? 'bg-white/10' : 'bg-bg'; ?>"><?php vp_i( 'coins', 14, $pop ? 'text-flare' : 'text-sage' ); ?><b><?php echo esc_html( vp_credits_short( $p['credits'] ) ); ?></b><span class="<?php echo esc_attr( $muted ); ?>"><?php echo esc_html( $t['credits'] ); ?></span></div>
					</div>
					<ul class="mt-6 flex-1 space-y-3 text-sm">
						<li class="flex gap-3 font-semibold"><span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full <?php echo $pop ? 'bg-white/15 text-flare' : 'bg-sage text-white'; ?>"><?php vp_i( 'sparkles', 11 ); ?></span><?php echo esc_html( $t['included'] ); ?></li>
						<?php foreach ( $features as $f ) : ?>
							<li class="flex gap-3"><span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full <?php echo $pop ? 'bg-white/15 text-flare' : 'bg-sage-soft text-sage'; ?>"><?php vp_i( 'check', 11, '', 3 ); ?></span><?php echo esc_html( $f ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo esc_url( $p['checkout_monthly'] ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $btn ); ?>" data-plan-cta data-href-monthly="<?php echo esc_url( $p['checkout_monthly'] ); ?>" data-href-yearly="<?php echo esc_url( $p['checkout_yearly'] ); ?>"><?php echo esc_html( sprintf( $t['get'], $p['name'] ) ); ?><?php vp_i( 'arrow-right', 15, 'transition group-hover:translate-x-1' ); ?></a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
