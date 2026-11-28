<?php
/**
 * Release Notes (Notifima): the live list from inc/releases.php.
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

echo '<div ' . get_block_wrapper_attributes( array( 'class' => 'nf-release-notes' ) ) . '>' . nf_release_notes_html() . '</div>'; // phpcs:ignore
