<?php
/**
 * Scene: Google result preview, before and after the fix, with title and description checks.
 * Data comes from the page graphic file that calls it (vpx_scene). Edit the text there.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;
$d = isset( $args['d'] ) ? (array) $args['d'] : array();
$d['_loop'] = $d['_loop'] ?? 11000;
$checks = (array) ( $d['checks'] ?? array() );
vpx_open( $d, 'search' );
?>
<div class="vpx-search"><?php vpx_i( 'search', 14 ); ?><span class="vpx-trunc"><?php echo esc_html( $d['query'] ?? '' ); ?></span></div>
<div data-fx="swap" data-after="2.6" class="vpx-grow" style="display:flex;flex-direction:column;gap:12px">
	<?php foreach ( array( 'before', 'after' ) as $w ) : $b = 'before' === $w; ?>
	<div data-when="<?php echo esc_attr( $w ); ?>" <?php echo $b ? '' : 'hidden'; ?> class="<?php echo $b ? 'vpx-serp-bad' : ''; ?>">
		<div class="vpx-line" <?php vp_m( 'o:0 y:8 t:.45' ); ?>>
			<div class="vpx-serp-site"><span class="vpx-fav"><?php echo esc_html( strtoupper( substr( (string) ( $d['site'] ?? 'V' ), 0, 1 ) ) ); ?></span><span><b style="color:var(--ink);font-weight:600"><?php echo esc_html( $d['site'] ?? '' ); ?></b><br><?php echo esc_html( $d['crumb'] ?? '' ); ?></span></div>
			<div class="vpx-serp-title"><?php echo esc_html( $b ? ( $d['bad_title'] ?? '' ) : ( $d['good_title'] ?? '' ) ); ?></div>
			<div class="vpx-serp-desc"><?php echo esc_html( $b ? ( $d['bad_desc'] ?? '' ) : ( $d['good_desc'] ?? '' ) ); ?></div>
		</div>
		<ul class="vpx-list" style="margin-top:12px">
			<?php foreach ( $checks as $i => $c ) : $st = $b ? ( $c[1] ?? 'bad' ) : ( $c[2] ?? 'good' ); ?>
			<li class="vpx-row" <?php vp_m( 'o:0 x:-8 t:.35 d:' . ( 0.2 + $i * 0.12 ) ); ?>>
				<span class="<?php echo 'good' === $st ? 'vpg-ok' : 'vpg-no'; ?>"><?php vpx_i( 'good' === $st ? 'check' : 'x', 11 ); ?></span>
				<span class="vpx-grow vpx-muted"><?php echo esc_html( $c[0] ?? '' ); ?></span>
				<span class="vpx-tag" style="<?php echo esc_attr( vp_tint_style( vpx_col( $st ) ) ); ?>"><?php echo esc_html( $b ? ( $d['before_tag'] ?? 'Before' ) : ( $d['after_tag'] ?? 'Fixed' ) ); ?></span>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endforeach; ?>
</div>
<?php vpx_close( $d );
