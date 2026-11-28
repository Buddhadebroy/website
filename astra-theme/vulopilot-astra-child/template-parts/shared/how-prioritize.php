<?php
/**
 * How it works – step 3 graphic: "Prioritize" (findings sort themselves by impact after 1.1 s).
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$url    = 'VuloPilot · Needs your attention';
$before = array( 'Unsorted findings', 'Where to begin?' );
$after  = array( 'Ranked by impact', 'Start at #1' );
$items  = array( // text, severity, impact (0-100, sorts the list), colour, icon
	array( 'Repair 12 broken links', 'Medium', 45, 'var(--lilac)', 'link-2' ),
	array( 'Add alt text to 48 images', 'Low', 22, 'var(--sage)', 'file-text' ),
	array( 'Update 2 vulnerable plugins', 'Critical', 96, 'var(--rose)', 'shield-alert' ),
	array( 'Fix the slow homepage hero', 'High', 72, 'var(--flare)', 'gauge' ),
);
$rank = array_keys( $items );
usort( $rank, function ( $a, $b ) use ( $items ) { return $items[ $b ][2] - $items[ $a ][2]; } );
$rank = array_flip( $rank ); // item index => position after sorting.
vp_frame_open( $url, 'sm' );
?>
<div class="p-5" data-fx="sort" data-after="1.1">
	<div class="mb-3 flex items-center justify-between text-[11px] font-semibold uppercase tracking-widest text-muted">
		<span><span data-when="before"><?php echo esc_html( $before[0] ); ?></span><span data-when="after" hidden><?php echo esc_html( $after[0] ); ?></span></span>
		<span class="text-rose" data-when="before"><?php echo esc_html( $before[1] ); ?></span>
		<span class="text-sage" data-when="after" hidden <?php vp_m( 'o:0 t:.3' ); ?>><?php echo esc_html( $after[1] ); ?></span>
	</div>
	<ul class="space-y-2">
		<?php foreach ( $items as $i => $it ) : list( $t, $sev, $w, $c, $icon ) = $it; ?>
			<li class="flex items-center gap-3 rounded-sm border border-line bg-surface px-3.5 py-3" data-rank="<?php echo (int) $rank[ $i ]; ?>" data-color="<?php echo esc_attr( $c ); ?>">
				<span class="grid h-6 w-6 shrink-0 place-items-center rounded-full font-mono text-[11px] font-semibold" style="background:var(--line);color:var(--muted)" data-rank-badge>·</span>
				<span class="grid h-8 w-8 shrink-0 place-items-center rounded-sm" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>"><?php vp_i( $icon, 15 ); ?></span>
				<span class="min-w-0 flex-1">
					<span class="block truncate text-sm font-semibold"><?php echo esc_html( $t ); ?></span>
					<span class="mt-1.5 block h-1.5 overflow-hidden rounded-full bg-line"><span class="block h-full rounded-full" <?php vp_m( 'W:' . (int) $w . '% t:.9 d:.2 e:ease', 'background:' . $c ); ?>></span></span>
				</span>
				<span class="rounded-sm px-2.5 py-0.5 text-[11px] font-semibold" style="<?php echo esc_attr( vp_tint_style( $c ) ); ?>"><?php echo esc_html( $sev ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
</div>
