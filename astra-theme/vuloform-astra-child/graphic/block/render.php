<?php
/**
 * Animated Graphic (VuloForm) block – same output as [vf_graphic].
 *
 * @var array $attributes
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$w   = (int) ( $attributes['maxWidth'] ?? 0 );
$ids = array_filter( array_map( 'sanitize_key', explode( ',', (string) ( $attributes['plugins'] ?? '' ) ) ) ); // compare-table: "vulo,wpf" = only these columns
echo '<div ' . get_block_wrapper_attributes() . '>' . vf_graphic( $attributes['name'] ?? 'builder-app', array( 'style' => $w ? "max-width:{$w}px;margin-inline:auto" : '', 'args' => array( 'ids' => $ids ) ) ) . '</div>'; // phpcs:ignore
