<?php
/**
 * "Feature by feature" comparison table (data/compare.json → plugins, rows).
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

echo vf_feature_table( empty( $args['ids'] ) ? null : $args['ids'] ); // phpcs:ignore
