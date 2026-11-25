<?php
/**
 * Template Name: Notifima Full Page
 * Template Post Type: page
 *
 * Full-width canvas: Notifima header, the page's Kadence blocks, Notifima footer.
 *
 * @package Notifima_Astra_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'nf-page' ); ?>>
<?php wp_body_open(); ?>
<?php nf_render_header(); ?>
<main id="main">
<?php
while ( have_posts() ) {
	the_post();
	the_content();
}
?>
</main>
<?php nf_render_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>
