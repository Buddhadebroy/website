<?php
/**
 * Improve Ai Visibility › graphic 2: an AI answer and the sources it cites.
 * Graphic name: improve-ai-visibility-2  ·  Shortcode: [vp_improve_ai_visibility_2]  ·  /use-cases/improve-ai-visibility/
 * Scene: graphic/parts/scenes/ai-chat.php
 *
 * Edit the words and numbers below. Lists are rows of short arrays; keep their order.
 * The 2.5 version of this graphic is still available as [vp_graphic name="improve-ai-visibility-2-classic"].
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$d = vpx_data(
	array(
		'label' => 'Before: AI gets it wrong',
		'path' => 'Perplexity-style answer',
		'engine' => 'AI answer',
		'question' => 'What does Northfield Garden Design do?',
		'answer' => 'Northfield Garden Design appears to be a landscaping supply shop. Opening hours and services are not clearly listed online.',
		'cites' => array( array( 'old-directory.com', false ), array( 'facebook.com', false ) ),
		'verdict' => 'No source from your own site, so AI guesses from old listings.',
		'verdict_state' => 'bad',
	),
	isset( $args ) ? $args : array(),
	false
);
vpx_scene( 'ai-chat', $d );
