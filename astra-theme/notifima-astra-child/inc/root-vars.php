<?php
/**
 * Design tokens: Appearance → Customize → Notifima → Design tokens.
 *
 * One field for every :root variable of the theme (colours, font, font sizes, line heights, letter spacing,
 * radii, content width, spacing, easing …). The list and the original values are data/tokens.json
 * (made by src/build-tokens.mjs from the theme CSS). All theme CSS uses these variables, so a change here
 * changes the header, footer, every pattern and the blog.
 * The theme prints them as one inline  html:root { … }  block after its CSS (front end and editor).
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/** All tokens: '--ink' => [ 'value' => '#0d1a45', 'label' => …, 'group' => 'colors' ]. */
function nf_tokens() {
	static $t = null;
	if ( null === $t ) {
		$t = (array) apply_filters( 'nf_tokens', nf_data( 'tokens' ) );
	}
	return $t;
}

/** Theme mod key of a token: '--ink-soft' → 'nf_tok_ink_soft'. */
function nf_token_key( $name ) {
	return 'nf_tok_' . str_replace( '-', '_', ltrim( $name, '-' ) );
}

/** Groups, in the order they are shown. */
function nf_token_groups() {
	return array(
		'colors' => __( 'Colours', 'notifima' ),
		'type'   => __( 'Font, sizes and spacing of text', 'notifima' ),
		'layout' => __( 'Width and spacing', 'notifima' ),
		'radius' => __( 'Corner radius', 'notifima' ),
		'motion' => __( 'Motion and blur', 'notifima' ),
		'other'  => __( 'Other', 'notifima' ),
	);
}

/** Keep a value safe inside a CSS declaration. */
function nf_token_clean( $v ) {
	$v = trim( wp_strip_all_tags( (string) $v ) );
	return preg_replace( '/[;{}<>]/', '', $v );
}

/** The inline :root block with every token (saved value or original). */
function nf_tokens_css() {
	$out = '';
	foreach ( nf_tokens() as $name => $t ) {
		$v = nf_token_clean( get_theme_mod( nf_token_key( $name ), $t['value'] ) );
		if ( '--nf-content-width' === $name ) {
			$v = '' !== $v ? $v : nf_content_width() . 'px'; // empty = Astra's Container Width
		}
		if ( '' !== $v ) {
			$out .= $name . ':' . $v . ';';
		}
	}
	return 'html:root{' . $out . '}';
}

/** Content width when the token is empty: Astra's Customize → Global → Container → Container Width. */
function nf_content_width() {
	$w = function_exists( 'astra_get_option' ) ? (int) astra_get_option( 'site-content-width', 1200 ) : 1200;
	return max( 600, $w );
}

// Print after the theme CSS (nf-kadence is the last theme file), on the front end and in the editor.
add_action( 'wp_enqueue_scripts', function () { wp_add_inline_style( 'nf-kadence', nf_tokens_css() ); }, 30 );
add_action( 'enqueue_block_assets', function () { if ( is_admin() ) { wp_add_inline_style( 'nf-kadence', nf_tokens_css() ); } }, 30 );

add_action(
	'customize_register',
	function ( $wp ) {
		$wp->add_section(
			'notifima_tokens',
			array(
				'title'       => __( 'Design tokens', 'notifima' ),
				'panel'       => 'notifima',
				'priority'    => 20,
				'description' => __( 'Every CSS variable of the theme. Each field starts with the original Notifima value. Changing one changes the whole site: header, footer, every pattern and the blog.', 'notifima' ),
			)
		);

		/** "Reset to original" button: puts every field back to its default (Publish to save). */
		if ( ! class_exists( 'NF_Reset_Tokens_Control' ) ) {
			class NF_Reset_Tokens_Control extends WP_Customize_Control { // phpcs:ignore Generic.Files.OneObjectStructurePerFile
				public $type = 'nf_reset_tokens';
				public function render_content() {
					echo '<button type="button" class="button nf-reset-tokens">' . esc_html__( 'Reset to original', 'notifima' ) . '</button>';
					echo '<p class="description">' . esc_html__( 'Puts every design token back to the original Notifima value. Click Publish to keep it.', 'notifima' ) . '</p>';
				}
			}
		}
		$wp->add_setting( 'nf_tokens_reset', array( 'default' => '', 'sanitize_callback' => '__return_empty_string', 'transport' => 'postMessage' ) );
		$wp->add_control( new NF_Reset_Tokens_Control( $wp, 'nf_tokens_reset', array( 'section' => 'notifima_tokens', 'priority' => 1 ) ) );

		$groups = nf_token_groups();
		$order  = array_flip( array_keys( $groups ) );
		$list   = nf_tokens();
		uksort(
			$list,
			function ( $a, $b ) use ( $list, $order ) {
				return ( $order[ $list[ $a ]['group'] ] ?? 9 ) <=> ( $order[ $list[ $b ]['group'] ] ?? 9 );
			}
		);
		$prio = 10;
		$last = '';
		foreach ( $list as $name => $t ) {
			$key   = nf_token_key( $name );
			$color = 'colors' === $t['group'];
			$wp->add_setting(
				$key,
				array(
					'default'           => $t['value'],
					'sanitize_callback' => $color ? 'sanitize_hex_color' : 'nf_token_clean',
				)
			);
			$args = array(
				'section'     => 'notifima_tokens',
				'priority'    => $prio++,
				'label'       => $name,
				'description' => ( $last !== $t['group'] ? '— ' . ( $groups[ $t['group'] ] ?? $t['group'] ) . ' — ' : '' ) . $t['label'] . ( $color ? '' : ' · ' . __( 'original:', 'notifima' ) . ' ' . ( '' === $t['value'] ? __( '(empty)', 'notifima' ) : $t['value'] ) ),
			);
			$last = $t['group'];
			if ( $color ) {
				$wp->add_control( new WP_Customize_Color_Control( $wp, $key, $args ) );
			} else {
				$wp->add_control( $key, $args + array( 'type' => 'text' ) );
			}
		}
	},
	20
);

/** The reset button's script: every token field back to its default. */
add_action(
	'customize_controls_print_footer_scripts',
	function () {
		$defaults = array();
		foreach ( nf_tokens() as $name => $t ) {
			$defaults[ nf_token_key( $name ) ] = $t['value'];
		}
		?>
		<script>
		( function ( api, d ) {
			document.addEventListener( 'click', function ( e ) {
				if ( ! e.target.classList.contains( 'nf-reset-tokens' ) ) return;
				Object.keys( d ).forEach( function ( k ) { if ( api( k ) ) api( k ).set( d[ k ] ); } );
			} );
		} )( wp.customize, <?php echo wp_json_encode( $defaults ); ?> );
		</script>
		<?php
	}
);
