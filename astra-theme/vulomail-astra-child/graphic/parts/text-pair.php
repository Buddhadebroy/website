<?php
/**
 * Graphic: Heading + text pair (Security · no tracking)
 *
 * Props: h2, p.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;
?>
<div><h2 class="vm:display vm:text-4xl"><?php echo esc_html( $a['h2'] ?? '' ); ?></h2><p class="vm:mt-3 vm:text-paper/70"><?php echo esc_html( $a['p'] ?? '' ); ?></p></div>
