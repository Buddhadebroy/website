<?php
/**
 * Comparison Table with Tabs (VuloForm) – renders template-parts/home/compare.php with the text from the block sidebar
 * (empty fields keep the default text from vf_section_defaults( 'compare' )).
 *
 * @var array $attributes
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

echo '<div ' . get_block_wrapper_attributes() . '>' . vf_graphic( 'section-compare', array( 'args' => $attributes ) ) . '</div>'; // phpcs:ignore
