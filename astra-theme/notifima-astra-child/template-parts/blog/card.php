<?php
/**
 * One blog card (the original .post card). Picture: the featured image, else one of the five Notifima graphics.
 *
 * @var array $args [ 'i' => index ]
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

$i    = (int) ( $args['i'] ?? 0 );
$arts = array( array( 'pa1', 'bell' ), array( 'pa2', 'shield' ), array( 'pa3', 'chart' ), array( 'pa4', 'mail' ), array( 'pa5', 'package' ) );
$art  = $arts[ $i % 5 ];
$cat  = get_the_category();
$name = get_the_author();
$ini  = strtoupper( implode( '', array_map( function ( $w ) { return mb_substr( $w, 0, 1 ); }, array_slice( preg_split( '/\s+/', trim( $name ) ), 0, 2 ) ) ) );
?>
<a class="post rv" href="<?php the_permalink(); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-art" style="background:var(--tint)"><?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%;height:100%;object-fit:cover;position:absolute;inset:0' ) ); ?></div>
	<?php else : ?>
		<div class="post-art <?php echo esc_attr( $art[0] ); ?>" aria-hidden="true"><?php echo str_replace( 'class="i"', 'class="i pa-big"', nf_icon( $art[1] ) ); // phpcs:ignore ?></div>
	<?php endif; ?>
	<div class="post-body">
		<?php if ( $cat ) : ?><span class="cat"><?php echo esc_html( $cat[0]->name ); ?></span><?php endif; ?>
		<h3><?php the_title(); ?></h3>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
		<div class="meta"><span class="av"><?php echo esc_html( $ini ); ?></span><?php echo esc_html( $name ); ?> · <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?> · <?php echo esc_html( nf_read_time() ); ?></div>
	</div>
</a>
