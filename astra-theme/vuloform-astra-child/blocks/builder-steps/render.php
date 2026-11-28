<?php
/**
 * Builder Sticky Steps (VuloForm) – renders template-parts/home/builder.php with the text from the block sidebar
 * (empty fields keep the default text from vf_section_defaults( 'builder' )).
 *
 * @var array $attributes
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

echo '<div ' . get_block_wrapper_attributes() . '>' . vf_graphic( 'section-builder', array( 'args' => $attributes ) ) . '</div>'; // phpcs:ignore
