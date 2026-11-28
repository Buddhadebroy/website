<?php
/**
 * Privacy Scrolling Words (VuloForm) – renders template-parts/home/privacy.php with the text from the block sidebar
 * (empty fields keep the default text from vf_section_defaults( 'privacy' )).
 *
 * @var array $attributes
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

echo '<div ' . get_block_wrapper_attributes() . '>' . vf_graphic( 'section-privacy', array( 'args' => $attributes ) ) . '</div>'; // phpcs:ignore
