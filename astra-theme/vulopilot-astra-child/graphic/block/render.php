<?php
/**
 * Animated graphic block (same output as the [vp_graphic] shortcode).
 *
 * @var array $attributes
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$a = $attributes;
$w = (int) $a['maxWidth'];
?>
<div <?php echo vp_block_wrapper( $a, 'vp-graphic-block' ); // phpcs:ignore ?><?php echo $w ? '' : ''; ?>>
	<?php echo vp_mount( sanitize_key( $a['name'] ), array(), array( 'style' => $w ? 'max-width:' . $w . 'px;margin-left:auto;margin-right:auto;' : '' ) ); // phpcs:ignore ?>
</div>
