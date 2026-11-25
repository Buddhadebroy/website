<?php
/**
 * Notifima Astra Child functions.
 *
 * @package Notifima_Astra_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NF_CHILD_VERSION', '{{VER}}' );
define( 'NF_CHILD_DIR', get_stylesheet_directory() );
define( 'NF_CHILD_URI', get_stylesheet_directory_uri() );
define( 'NF_TEMPLATE', 'page-templates/notifima.php' );

/* ---------------------------------------------------------------
 * Assets
 * ------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'notifima', NF_CHILD_URI . '/assets/css/notifima.css', array(), NF_CHILD_VERSION );
	wp_enqueue_style( 'notifima-child', get_stylesheet_uri(), array( 'notifima' ), NF_CHILD_VERSION );
	wp_enqueue_script( 'notifima', NF_CHILD_URI . '/assets/js/notifima.js', array( 'jquery' ), NF_CHILD_VERSION, true );
}, 20 );

/* Astra's own stylesheet is not needed on Notifima pages and only fights the design. */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_page_template( NF_TEMPLATE ) ) {
		foreach ( array( 'astra-theme-css', 'astra-google-fonts', 'astra-menu-animation' ) as $h ) {
			wp_dequeue_style( $h );
		}
	}
}, 99 );

add_action( 'wp_head', function () {
	echo "<script>document.documentElement.className+=' js'</script>\n";
	echo '<link rel="preload" href="' . esc_url( NF_CHILD_URI . '/assets/fonts/outfit-latin-wght-normal.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	if ( is_singular( 'page' ) ) {
		$desc = get_post_meta( get_the_ID(), '_nf_desc', true );
		if ( $desc && ! defined( 'WPSEO_VERSION' ) && ! defined( 'RANK_MATH_VERSION' ) ) {
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		}
	}
}, 1 );

/* Icon sprite, used by the header, footer and page blocks. */
add_action( 'wp_body_open', function () {
	echo file_get_contents( NF_CHILD_DIR . '/inc/icons.svg' ); // phpcs:ignore WordPress.Security.EscapeOutput
} );

/* ---------------------------------------------------------------
 * Header and footer (replace Astra's everywhere)
 * ------------------------------------------------------------- */
function nf_active_nav() {
	if ( is_front_page() ) {
		return 'home';
	}
	if ( is_page() ) {
		$nav = get_post_meta( get_the_ID(), '_nf_nav', true );
		if ( $nav ) {
			return $nav;
		}
	}
	if ( is_home() || is_singular( 'post' ) || is_archive() ) {
		return 'res';
	}
	return '';
}

function nf_fill( $html ) {
	$html   = str_replace( '{{home}}', untrailingslashit( home_url() ), $html );
	$active = nf_active_nav();
	return preg_replace_callback( '/\{\{on_(\w+)\}\}/', function ( $m ) use ( $active ) {
		return $m[1] === $active ? 'on' : '';
	}, $html );
}

function nf_render_header() {
	echo nf_fill( file_get_contents( NF_CHILD_DIR . '/inc/header.html' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
}

function nf_render_footer() {
	echo nf_fill( file_get_contents( NF_CHILD_DIR . '/inc/footer.html' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
}

add_action( 'wp', function () {
	remove_all_actions( 'astra_header' );
	remove_all_actions( 'astra_footer' );
	add_action( 'astra_header', 'nf_render_header' );
	add_action( 'astra_footer', 'nf_render_footer' );
}, 20 );

/* Use the Notifima colours in Astra (posts, WooCommerce, etc.). */
add_filter( 'astra_global_color_palette', function ( $palette ) {
	if ( isset( $palette['palette'] ) && is_array( $palette['palette'] ) ) {
		$palette['palette'] = array( '#852aff', '#852aff', '#1e293b', '#334155', '#FFFFFF', '#f0f4fe', '#111111', '#D1D5DB', '#111111' );
	}
	return $palette;
} );

/* ---------------------------------------------------------------
 * Page import
 * ------------------------------------------------------------- */
function nf_import_pages( $overwrite = false ) {
	$list = json_decode( file_get_contents( NF_CHILD_DIR . '/content/pages.json' ), true );
	$home = untrailingslashit( home_url() );
	$done = array();

	// Keep SVG icons and data attributes intact while saving.
	kses_remove_filters();

	foreach ( $list as $p ) {
		$content  = str_replace( '{{home}}', $home, file_get_contents( NF_CHILD_DIR . '/content/' . $p['slug'] . '.html' ) );
		$existing = get_page_by_path( $p['slug'], OBJECT, 'page' );

		if ( $existing && ! $overwrite ) {
			$id = $existing->ID;
		} else {
			$args = array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $p['title'],
				'post_name'    => $p['slug'],
				'post_content' => wp_slash( $content ),
			);
			if ( $existing ) {
				$args['ID'] = $existing->ID;
				$id         = wp_update_post( $args );
			} else {
				$id = wp_insert_post( $args );
			}
		}
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		update_post_meta( $id, '_wp_page_template', NF_TEMPLATE );
		update_post_meta( $id, '_nf_nav', $p['nav'] );
		update_post_meta( $id, '_nf_desc', $p['desc'] );
		// Astra layout settings, in case the page is switched back to the default template.
		update_post_meta( $id, 'site-sidebar-layout', 'no-sidebar' );
		update_post_meta( $id, 'site-content-layout', 'page-builder' );
		update_post_meta( $id, 'ast-site-content-layout', 'full-width-container' );
		update_post_meta( $id, 'site-content-style', 'unboxed' );
		update_post_meta( $id, 'site-post-title', 'disabled' );
		update_post_meta( $id, 'ast-title-bar-display', 'disabled' );
		// Kadence Blocks: let it build CSS for these pages.
		delete_post_meta( $id, '_kad_blocks_custom_css' );

		if ( ! empty( $p['front'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $id );
		}
		$done[] = $p['title'];
	}

	kses_init_filters();
	return $done;
}

add_action( 'after_switch_theme', function () {
	if ( ! get_option( 'nf_pages_imported' ) ) {
		nf_import_pages( false );
		update_option( 'nf_pages_imported', time() );
		set_transient( 'nf_import_notice', 1, 60 );
	}
} );

/* Appearance > Notifima Setup: re-import pages, check plugins. */
add_action( 'admin_menu', function () {
	add_theme_page( 'Notifima Setup', 'Notifima Setup', 'manage_options', 'notifima-setup', 'nf_setup_page' );
} );

function nf_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$msg = '';
	if ( isset( $_POST['nf_import'] ) && check_admin_referer( 'nf_import' ) ) {
		$overwrite = ! empty( $_POST['nf_overwrite'] );
		$done      = nf_import_pages( $overwrite );
		$msg       = sprintf( '%s: %s', $overwrite ? 'Pages rebuilt' : 'Missing pages created', esc_html( implode( ', ', $done ) ) );
	}
	$kadence = defined( 'KADENCE_BLOCKS_VERSION' );
	?>
	<div class="wrap">
		<h1>Notifima Setup</h1>
		<?php if ( $msg ) : ?><div class="notice notice-success"><p><?php echo esc_html( $msg ); ?></p></div><?php endif; ?>
		<p>Kadence Blocks: <strong><?php echo $kadence ? 'active' : 'not active'; ?></strong>
		<?php if ( ! $kadence ) : ?> &middot; <a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=kadence+blocks&tab=search&type=term' ) ); ?>">Install Kadence Blocks</a><?php endif; ?></p>
		<p>Pages: Home, Features, Pricing, Blog, Release Notes, About Us. Each uses the <em>Notifima Full Page</em> template.</p>
		<form method="post">
			<?php wp_nonce_field( 'nf_import' ); ?>
			<p><label><input type="checkbox" name="nf_overwrite" value="1"> Overwrite existing pages with the original design (your edits to them will be lost)</label></p>
			<p><button class="button button-primary" name="nf_import" value="1">Import pages</button></p>
		</form>
	</div>
	<?php
}

add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_transient( 'nf_import_notice' ) ) {
		delete_transient( 'nf_import_notice' );
		echo '<div class="notice notice-success is-dismissible"><p>Notifima pages were created and Home is set as your front page. <a href="' . esc_url( admin_url( 'themes.php?page=notifima-setup' ) ) . '">Notifima Setup</a></p></div>';
	}
	if ( ! defined( 'KADENCE_BLOCKS_VERSION' ) ) {
		echo '<div class="notice notice-warning"><p>The Notifima pages are built with Kadence Blocks. <a href="' . esc_url( admin_url( 'plugin-install.php?s=kadence+blocks&tab=search&type=term' ) ) . '">Install and activate Kadence Blocks</a>.</p></div>';
	}
} );
