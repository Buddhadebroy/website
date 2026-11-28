<?php
/**
 * Ready-made Forms Marquee (VuloForm) – renders template-parts/home/templates.php with the text from the block sidebar
 * (empty fields keep the default text from vf_section_defaults( 'templates' )).
 *
 * @var array $attributes
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

echo '<div ' . get_block_wrapper_attributes() . '>' . vf_graphic( 'section-templates', array( 'args' => $attributes ) ) . '</div>'; // phpcs:ignore
