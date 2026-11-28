<?php
/**
 * Design tokens: Appearance → Customize → VuloForm → Design tokens.
 *
 * One field for every :root variable of the theme (colours, font, font sizes, line heights, letter spacing,
 * radii, content width, spacing, easing …). The list and the original values are data/tokens.json
 * (made by src/build-tokens.mjs from the theme CSS). All theme CSS uses these variables, so a change here
 * changes the header, footer, every pattern and the blog.
 * The theme prints them as one inline  html:root { … }  block after its CSS (front end and editor).
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** All tokens: '--ink' => [ 'value' => '#0d1a45', 'label' => …, 'group' => 'colors' ]. */
function vf_tokens() {
	static $t = null;
	if ( null === $t ) {
		$t = (array) apply_filters( 'vf_tokens', vf_data( 'tokens' ) );
	}
	return $t;
}

/** Theme mod key of a token: '--ink-soft' → 'vf_tok_ink_soft'. */
function vf_token_key( $name ) {
	return 'vf_tok_' . str_replace( '-', '_', ltrim( $name, '-' ) );
}

/** Groups, in the order they are shown. */
function vf_token_groups() {
	return array(
		'colors' => __( 'Colours', 'vuloform' ),
		'type'   => __( 'Font, sizes and spacing of text', 'vuloform' ),
		'layout' => __( 'Width and spacing', 'vuloform' ),
		'radius' => __( 'Corner radius', 'vuloform' ),
		'motion' => __( 'Motion and blur', 'vuloform' ),
		'other'  => __( 'Other', 'vuloform' ),
	);
}

/** Keep a value safe inside a CSS declaration. */
function vf_token_clean( $v ) {
	$v = trim( wp_strip_all_tags( (string) $v ) );
	return preg_replace( '/[;{}<>]/', '', $v );
}

/** The inline :root block with every token (saved value or original). */
function vf_tokens_css() {
	$out = '';
	foreach ( vf_tokens() as $name => $t ) {
		$v = vf_token_clean( get_theme_mod( vf_token_key( $name ), $t['value'] ) );
		if ( '--vf-content-width' === $name ) {
			$v = '' !== $v ? $v : vf_content_width() . 'px'; // empty = Astra's Container Width
		}
		if ( '' !== $v ) {
			$out .= $name . ':' . $v . ';';
		}
	}
	return 'html:root{' . $out . '}';
}

/** Content width when the token is empty: Astra's Customize → Global → Container → Container Width. */
function vf_content_width() {
	$w = function_exists( 'astra_get_option' ) ? (int) astra_get_option( 'site-content-width', 1200 ) : 1200;
	return max( 600, $w );
}

// Print after the theme CSS (vf-kadence is the last theme file), on the front end and in the editor.
add_action( 'wp_enqueue_scripts', function () { wp_add_inline_style( 'vf-kadence', vf_tokens_css() ); }, 30 );
add_action( 'enqueue_block_assets', function () { if ( is_admin() ) { wp_add_inline_style( 'vf-kadence', vf_tokens_css() ); } }, 30 );

add_action(
	'customize_register',
	function ( $wp ) {
		$wp->add_section(
			'vuloform_tokens',
			array(
				'title'       => __( 'Design tokens', 'vuloform' ),
				'panel'       => 'vuloform',
				'priority'    => 20,
				'description' => __( 'Every CSS variable of the theme. Each field starts with the original VuloForm value. Changing one changes the whole site: header, footer, every pattern and the blog.', 'vuloform' ),
			)
		);

		/** "Reset to original" button: puts every field back to its default (Publish to save). */
		if ( ! class_exists( 'VF_Reset_Tokens_Control' ) ) {
			class VF_Reset_Tokens_Control extends WP_Customize_Control { // phpcs:ignore Generic.Files.OneObjectStructurePerFile
				public $type = 'vf_reset_tokens';
				public function render_content() {
					echo '<button type="button" class="button vf-reset-tokens">' . esc_html__( 'Reset to original', 'vuloform' ) . '</button>';
					echo '<p class="description">' . esc_html__( 'Puts every design token back to the original VuloForm value. Click Publish to keep it.', 'vuloform' ) . '</p>';
				}
			}
		}
		$wp->add_setting( 'vf_tokens_reset', array( 'default' => '', 'sanitize_callback' => '__return_empty_string', 'transport' => 'postMessage' ) );
		$wp->add_control( new VF_Reset_Tokens_Control( $wp, 'vf_tokens_reset', array( 'section' => 'vuloform_tokens', 'priority' => 1 ) ) );

		$groups = vf_token_groups();
		$order  = array_flip( array_keys( $groups ) );
		$list   = vf_tokens();
		uksort(
			$list,
			function ( $a, $b ) use ( $list, $order ) {
				return ( $order[ $list[ $a ]['group'] ] ?? 9 ) <=> ( $order[ $list[ $b ]['group'] ] ?? 9 );
			}
		);
		$prio = 10;
		$last = '';
		foreach ( $list as $name => $t ) {
			$key   = vf_token_key( $name );
			$color = 'colors' === $t['group'];
			$wp->add_setting(
				$key,
				array(
					'default'           => $t['value'],
					'sanitize_callback' => $color ? 'sanitize_hex_color' : 'vf_token_clean',
				)
			);
			$args = array(
				'section'     => 'vuloform_tokens',
				'priority'    => $prio++,
				'label'       => $name,
				'description' => ( $last !== $t['group'] ? '— ' . ( $groups[ $t['group'] ] ?? $t['group'] ) . ' — ' : '' ) . $t['label'] . ( $color ? '' : ' · ' . __( 'original:', 'vuloform' ) . ' ' . ( '' === $t['value'] ? __( '(empty)', 'vuloform' ) : $t['value'] ) ),
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
		foreach ( vf_tokens() as $name => $t ) {
			$defaults[ vf_token_key( $name ) ] = $t['value'];
		}
		?>
		<script>
		( function ( api, d ) {
			document.addEventListener( 'click', function ( e ) {
				if ( ! e.target.classList.contains( 'vf-reset-tokens' ) ) return;
				Object.keys( d ).forEach( function ( k ) { if ( api( k ) ) api( k ).set( d[ k ] ); } );
			} );
		} )( wp.customize, <?php echo wp_json_encode( $defaults ); ?> );
		</script>
		<?php
	}
);
