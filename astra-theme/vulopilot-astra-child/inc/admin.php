<?php
/**
 * Appearance → VuloPilot: one-click setup + a cheat-sheet of every shortcode and class.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	function () {
		add_theme_page( 'VuloPilot', 'VuloPilot', 'edit_theme_options', 'vulopilot', 'vp_admin_page' );
	}
);

add_action(
	'admin_post_vp_setup',
	function () {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'vulopilot' ) );
		}
		check_admin_referer( 'vp_setup' );
		$do  = sanitize_key( $_POST['do'] ?? '' );
		$msg = '';
		if ( 'menus' === $do ) {
			vp_create_default_menus();
			$msg = 'menus';
		}
		if ( 'pages' === $do ) {
			$ids = array();
			foreach ( array( 'home' => array( 'Home', 'page-templates/template-home.php' ) ) as $slug => $p ) {
				$existing = get_page_by_path( $slug );
				$id       = $existing ? $existing->ID : wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $p[0], 'post_name' => $slug, 'post_content' => '' ) );
				update_post_meta( $id, '_wp_page_template', $p[1] );
				$ids[ $slug ] = $id;
			}
			if ( ! empty( $_POST['front'] ) ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $ids['home'] );
			}
			$msg = 'pages';
		}
		wp_safe_redirect( admin_url( 'themes.php?page=vulopilot&done=' . $msg ) );
		exit;
	}
);

/** Admin screen. */
function vp_admin_page() {
	$reg   = vp_registry();
	$done  = sanitize_key( $_GET['done'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification
	$group = array();
	foreach ( $reg as $name => $e ) {
		$group[ $e['group'] ][ $name ] = $e;
	}
	?>
	<div class="wrap">
		<h1>VuloPilot</h1>
		<?php if ( 'menus' === $done ) : ?><div class="notice notice-success"><p><?php esc_html_e( 'Menus created and assigned. Edit them in Appearance → Menus.', 'vulopilot' ); ?></p></div><?php endif; ?>
		<?php if ( 'pages' === $done ) : ?><div class="notice notice-success"><p><?php esc_html_e( 'Home page is ready.', 'vulopilot' ); ?></p></div><?php endif; ?>

		<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;max-width:1100px">
			<div class="card" style="max-width:none">
				<h2><?php esc_html_e( '1. Home page', 'vulopilot' ); ?></h2>
				<p><?php esc_html_e( 'Creates (or updates) the page with the slug “home” and assigns the VuloPilot Home template. You can also pick the template yourself in Page → Template.', 'vulopilot' ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'vp_setup' ); ?><input type="hidden" name="action" value="vp_setup" /><input type="hidden" name="do" value="pages" />
					<p><label><input type="checkbox" name="front" value="1" checked /> <?php esc_html_e( 'Use “Home” as the front page', 'vulopilot' ); ?></label></p>
					<?php submit_button( __( 'Create page', 'vulopilot' ), 'primary', 'submit', false ); ?>
				</form>
			</div>
			<div class="card" style="max-width:none">
				<h2><?php esc_html_e( '2. Editable mega menu & footer', 'vulopilot' ); ?></h2>
				<p><?php esc_html_e( 'Until you do this, the header and footer show the original site links. This copies them into Appearance → Menus (“VuloPilot Header” and “VuloPilot Footer”) so you can edit them. Running it again rebuilds both menus from scratch.', 'vulopilot' ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'vp_setup' ); ?><input type="hidden" name="action" value="vp_setup" /><input type="hidden" name="do" value="menus" />
					<?php submit_button( __( 'Create menus', 'vulopilot' ), 'secondary', 'submit', false ); ?>
				</form>
				<p class="description"><?php esc_html_e( 'Turn on “Description” and “CSS Classes” under Screen Options on the Menus screen. Level 1 = top items, level 2 = categories (Description = hint, Title Attribute = “All …” text), level 3 = links (Description = grey line). Class vp-promo = promo card (Title Attribute = kicker, Description = “text || button text”), vp-foot = bottom link, vp-icon-NAME = icon.', 'vulopilot' ); ?></p>
			</div>
		</div>

		<h2 style="margin-top:32px"><?php esc_html_e( 'Scroll animation classes (Block → Advanced → Additional CSS class)', 'vulopilot' ); ?></h2>
		<table class="widefat striped" style="max-width:1100px">
			<tbody>
				<tr><td><code>vp-reveal</code></td><td><?php esc_html_e( 'Fade up when scrolled into view (same as the original site). Put it on a section / row / column / any block.', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-stagger</code></td><td><?php esc_html_e( 'Reveals the children one after another (cards in a row, columns, list items). Wrapper divs are skipped automatically. Add vp-reveal-item to specific children to choose them yourself.', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-reveal-left</code> <code>vp-reveal-right</code> <code>vp-reveal-zoom</code> <code>vp-reveal-fade</code> <code>vp-reveal-down</code></td><td><?php esc_html_e( 'Other directions.', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-delay-1</code> … <code>vp-delay-8</code></td><td><?php esc_html_e( 'Wait 0.1s … 0.8s before animating.', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-reveal-slow</code> <code>vp-reveal-fast</code> <code>vp-stagger-fast</code> <code>vp-stagger-slow</code></td><td><?php esc_html_e( 'Speed.', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-reveal-repeat</code></td><td><?php esc_html_e( 'Animate again every time it scrolls into view.', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-lift</code> <code>vp-float</code> <code>vp-count</code></td><td><?php esc_html_e( 'Hover lift for cards · gentle floating · count a number up from 0 when visible (e.g. a heading “96%”).', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-zoom</code></td><td><?php esc_html_e( 'On an Image block: magnifier on hover, click opens a full-screen lightbox (Esc closes).', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-frame</code></td><td><?php esc_html_e( 'On an Image block: browser-window frame with three dots. The address text is the image Title attribute (default “wp-admin / VuloPilot”). Combine: “vp-frame vp-zoom”.', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-shadow</code> <code>vp-card</code> <code>vp-accent</code></td><td><?php esc_html_e( 'Screenshot shadow · white card with border and shadow · blue accent text.', 'vulopilot' ); ?></td></tr>
				<tr><td><code>vp-open-consult</code></td><td><?php esc_html_e( 'On a button: opens the “Got a question?” popup. A link to #consult does the same.', 'vulopilot' ); ?></td></tr>
			</tbody>
		</table>

		<h2 style="margin-top:32px"><?php esc_html_e( 'Shortcodes', 'vulopilot' ); ?></h2>
		<p><?php esc_html_e( 'Every shortcode also works as the “Animated Graphic (VuloPilot)” block. Optional attributes: max_width="768" class="…".', 'vulopilot' ); ?></p>
		<?php foreach ( $group as $g => $items ) : if ( 'Template' === $g ) { continue; } ?>
		<h3><?php echo esc_html( $g ); ?></h3>
		<table class="widefat striped" style="max-width:1100px"><tbody>
			<?php foreach ( $items as $name => $e ) : ?>
			<tr>
				<td style="width:42%"><code><?php
				if ( 0 === strpos( $name, 'shot-' ) ) {
					$k = preg_replace( '/^shot-|-caption$/', '', $name );
					echo esc_html( '[vp_shot name="' . $k . '"' . ( substr( $name, -8 ) === '-caption' ? ' caption="yes"' : '' ) . ']' );
				} elseif ( 'Widget' === $g ) {
					echo esc_html( preg_replace( '/^.*?(\[.*\]).*$/', '$1', $e['label'] ) );
				} else {
					echo esc_html( '[vp_' . str_replace( '-', '_', $name ) . ']' );
				}
				?></code></td>
				<td><?php echo esc_html( preg_replace( '/\s*\[.*\]/', '', $e['label'] ) ); ?><?php echo ! empty( $e['page'] ) && 'all' !== $e['page'] ? ' <span style="color:#757575">— ' . esc_html( $e['page'] ) . '</span>' : ''; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody></table>
		<?php endforeach; ?>
		<p><code>[vp_consult_button text="…"]</code> · <code>[vp_mega_menu menu="" fixed="no" cta="yes"]</code></p>
	</div>
	<?php
}
