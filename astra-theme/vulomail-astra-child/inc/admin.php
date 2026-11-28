<?php
/**
 * Appearance → VuloMail: build the pages from the patterns, header/footer, blog import, logo, cheat sheet.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	function () {
		add_theme_page( __( 'VuloMail', 'vulomail' ), __( 'VuloMail', 'vulomail' ), 'edit_theme_options', 'vulomail', 'vm_admin_page' );
	}
);

/** Page slug => [ title, parent slug ]. Built from data/pages.json (written by src/build-patterns.py). */
function vm_admin_pages() {
	$pages = array();
	foreach ( vm_data( 'pages' ) as $slug => $p ) {
		$slug = (string) $slug; // PHP turns the key "404" into the integer 404.
		if ( in_array( $slug, array( 'blog', '404' ), true ) ) {
			continue;
		}
		if ( 0 === strpos( $slug, 'compare-' ) ) {
			$pages[ $slug ] = array( $p['title'], 'compare', substr( $slug, 8 ) );
		} else {
			$pages[ $slug ] = array( $p['title'], '', $slug );
		}
	}
	return $pages;
}

/** Creates or (with $replace) updates the pages. Returns a list of messages. */
function vm_build_pages( $selected, $replace ) {
	vm_sync_patterns();
	$msgs = array();
	$all  = vm_admin_pages();
	if ( isset( $all['compare'] ) ) {
		$all = array( 'compare' => $all['compare'] ) + $all; // parent before its children
	}
	$any_compare = (bool) array_filter( $selected, function ( $s ) { return 0 === strpos( $s, 'compare-' ); } );
	foreach ( $all as $slug => $info ) {
		if ( ! in_array( $slug, $selected, true ) && ! ( 'compare' === $slug && $any_compare ) ) {
			continue;
		}
		$parent_id = 0;
		if ( $info[1] ) {
			$pp        = get_page_by_path( $info[1] );
			$parent_id = $pp ? $pp->ID : 0;
		}
		$name     = 'home' === $slug ? 'home' : $info[2];
		$path     = ( $info[1] ? $info[1] . '/' : '' ) . $name;
		$existing = get_page_by_path( $path );
		if ( $existing && ! $replace ) {
			$msgs[] = sprintf( '%s: already exists, kept (tick "Replace" to rebuild it).', $info[0] );
			continue;
		}
		$args = array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $info[0],
			'post_name'    => $name,
			'post_parent'  => $parent_id,
			'post_content' => wp_slash( vm_page_pattern_content( $slug ) ),
		);
		if ( $existing ) {
			$args['ID'] = $existing->ID;
			$id         = wp_update_post( $args ); // WordPress keeps the previous version as a revision.
		} else {
			$id = wp_insert_post( $args );
		}
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_vm_canvas', 1 );
			update_post_meta( $id, '_wp_page_template', 'default' );
			// Astra's own page settings: full width, no sidebar, no title.
			update_post_meta( $id, 'site-content-layout', 'page-builder' );
			update_post_meta( $id, 'ast-site-content-layout', 'full-width-container' );
			update_post_meta( $id, 'site-content-style', 'unboxed' );
			update_post_meta( $id, 'site-sidebar-layout', 'no-sidebar' );
			update_post_meta( $id, 'site-post-title', 'disabled' );
			update_post_meta( $id, 'ast-title-bar-display', 'disabled' );
			$msgs[] = sprintf( '%s: %s.', $info[0], $existing ? 'rebuilt' : 'created' );
			if ( 'home' === $slug ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $id );
			}
		}
	}
	if ( array_intersect( array( 'about', 'changelog', 'contact' ), $selected ) && vm_menu_add_company() ) {
		$msgs[] = 'Header menu: added Company (About, Changelog, Contact).';
	}
	if ( in_array( 'blog', $selected, true ) ) {
		$blog = get_page_by_path( 'blog' );
		$id   = $blog ? $blog->ID : wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Blog', 'post_name' => 'blog' ) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_option( 'page_for_posts', $id );
			$msgs[] = 'Blog: set as the posts page.';
		}
	}
	return $msgs;
}

/**
 * Menus created by 1.1 have no About, Changelog or Contact: adds them under "Company" in the header menu
 * (the footer shows the same group). Returns true when it added them.
 */
function vm_menu_add_company() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['vm_primary'] ) ) {
		return false;
	}
	$menu  = (int) $locations['vm_primary'];
	$items = (array) wp_get_nav_menu_items( $menu );
	foreach ( $items as $it ) {
		if ( preg_match( '#/(about|changelog)/?$#', untrailingslashit( (string) wp_parse_url( $it->url, PHP_URL_PATH ) ) . '/' ) ) {
			return false;
		}
	}
	$group = null;
	foreach ( (array) vm_data( 'site' )['nav'] as $n ) {
		if ( isset( $n['items'] ) && 'Company' === $n['label'] ) {
			$group = $n;
		}
	}
	if ( ! $group ) {
		return false;
	}
	$pos    = count( $items );
	$parent = wp_update_nav_menu_item( $menu, 0, array( 'menu-item-title' => $group['label'], 'menu-item-url' => '#', 'menu-item-status' => 'publish', 'menu-item-position' => ++$pos ) );
	if ( is_wp_error( $parent ) ) {
		return false;
	}
	foreach ( $group['items'] as $i ) {
		wp_update_nav_menu_item(
			$menu,
			0,
			array(
				'menu-item-title'       => $i['label'],
				'menu-item-url'         => vm_url( $i['href'] ),
				'menu-item-description' => isset( $i['desc'] ) ? $i['desc'] : '',
				'menu-item-classes'     => 'vm-icon-' . vm_nav_icon_for( $i['href'] ),
				'menu-item-parent-id'   => $parent,
				'menu-item-status'      => 'publish',
				'menu-item-position'    => ++$pos,
			)
		);
	}
	return true;
}

/** Imports the 10 original posts: data/blog.json (title, dates, category …) + data/blog/<slug>.html (Kadence blocks). */
function vm_import_posts( $replace ) {
	$msgs = array();
	foreach ( (array) vm_data( 'blog' ) as $p ) {
		$slug = sanitize_title( $p['slug'] );
		$file = VM_DIR . 'data/blog/' . $slug . '.html';
		if ( empty( $p['title'] ) || ! is_readable( $file ) ) {
			$msgs[] = $slug . ': skipped (missing data).';
			continue;
		}
		$html     = (string) file_get_contents( $file ); // phpcs:ignore
		$existing = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $existing && ! $replace ) {
			$msgs[] = $p['title'] . ': already imported.';
			continue;
		}
		$cat  = term_exists( $p['category'], 'category' );
		$cat  = $cat ? $cat : wp_insert_term( $p['category'], 'category' );
		$args = array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'post_title'    => wp_slash( $p['title'] ),
			'post_name'     => $slug,
			'post_excerpt'  => wp_slash( $p['answer'] ),
			'post_content'  => wp_slash( $html ),
			'post_date'     => $p['published'] . ' 09:00:00',
			'post_category' => is_array( $cat ) ? array( (int) $cat['term_id'] ) : array(),
			'tags_input'    => (array) $p['keywords'],
		);
		if ( $existing ) {
			$args['ID'] = $existing->ID;
			$id         = wp_update_post( $args );
		} else {
			$id = wp_insert_post( $args );
		}
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_vm_description', $p['description'] );
			update_post_meta( $id, '_vm_related', implode( ',', (array) $p['related'] ) );
			global $wpdb;
			$wpdb->update( $wpdb->posts, array( 'post_modified' => $p['updated'] . ' 09:00:00', 'post_modified_gmt' => $p['updated'] . ' 09:00:00' ), array( 'ID' => $id ) ); // phpcs:ignore
			clean_post_cache( $id );
			$msgs[] = $p['title'] . ': ' . ( $existing ? 'updated' : 'imported' ) . '.';
		} else {
			$msgs[] = $p['title'] . ': could not be saved.';
		}
	}
	return $msgs;
}

/** Puts the bundled logo and icon into the Media Library and sets them in Site Identity. */
function vm_install_identity() {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$out = array();
	foreach ( array( 'custom_logo' => 'brand-icon.svg', 'site_icon' => 'site-icon.png' ) as $key => $file ) {
		$up   = wp_upload_dir();
		$dest = trailingslashit( $up['path'] ) . 'vulomail-' . $file;
		copy( VM_DIR . 'assets/img/' . $file, $dest );
		$type = 'svg' === pathinfo( $file, PATHINFO_EXTENSION ) ? 'image/svg+xml' : 'image/png';
		$id   = wp_insert_attachment( array( 'post_mime_type' => $type, 'post_title' => 'VuloMail ' . ( 'custom_logo' === $key ? 'logo' : 'icon' ), 'post_status' => 'inherit' ), $dest );
		if ( 'image/png' === $type ) {
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $dest ) );
		} else {
			update_post_meta( $id, '_wp_attachment_metadata', array( 'width' => 1402, 'height' => 940, 'file' => _wp_relative_upload_path( $dest ) ) );
		}
		if ( 'custom_logo' === $key ) {
			set_theme_mod( 'custom_logo', $id );
		} else {
			update_option( 'site_icon', $id );
		}
		$out[] = ( 'custom_logo' === $key ? 'Logo' : 'Site icon' ) . ' set (Customize → Site Identity).';
	}
	return $out;
}

function vm_admin_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$msgs = array();
	if ( isset( $_POST['vm_action'] ) && check_admin_referer( 'vm_admin' ) ) {
		$action  = sanitize_key( wp_unslash( $_POST['vm_action'] ) );
		$replace = ! empty( $_POST['vm_replace'] );
		if ( 'pages' === $action ) {
			$sel  = isset( $_POST['vm_pages'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['vm_pages'] ) ) : array();
			$msgs = vm_build_pages( $sel, $replace );
		} elseif ( 'patterns' === $action ) {
			$reset          = isset( $_POST['vm_reset'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['vm_reset'] ) ) : array();
			list( $c, $r )  = vm_sync_patterns( $reset );
			vm_create_menus();
			$msgs[]         = sprintf( '%d patterns created, %d put back to the original design (the edited version is kept as a revision).', $c, $r );
		} elseif ( 'posts' === $action ) {
			$msgs = vm_import_posts( $replace );
		} elseif ( 'identity' === $action ) {
			$msgs = vm_install_identity();
		} elseif ( 'migrate' === $action ) {
			$msgs = vm_migrate_from_11();
		}
	}
	$pages = vm_admin_pages();
	$hid   = vm_layout_block_id( 'header' );
	$fid   = vm_layout_block_id( 'footer' );
	?>
	<div class="wrap">
		<h1>VuloMail</h1>
		<?php foreach ( $msgs as $m ) : ?><div class="notice notice-success"><p><?php echo esc_html( $m ); ?></p></div><?php endforeach; ?>

		<?php if ( vm_needs_update() ) : ?>
		<div class="notice notice-warning inline"><p><b>Update pages built with VuloMail 1.1 or 1.2.0.</b> Each page gets its own copy of its sections (with your edits), so editing one page no longer changes another. The four identical call to action bands and the ten compare ones become two shared patterns, old class names get the new <code>vm:</code> prefix, and narrow rows use Kadence's Content max width instead of <code>vm-w-5xl</code>. The old section patterns go to the trash, and WordPress keeps the previous version of every page as a revision.</p>
		<form method="post"><?php wp_nonce_field( 'vm_admin' ); ?><input type="hidden" name="vm_action" value="migrate"><?php submit_button( 'Update my pages', 'primary', '', false ); ?></form><br></div>
		<?php endif; ?>

		<h2>1. Synced patterns (header, footer and shared sections)</h2>
		<p>Only parts that look the same everywhere are synced: the header, the footer, the call to action band, the compare call to action, the blog header and banner, and the 404 section. Edit one and every page that shows it changes. Every other section is a plain pattern (Patterns in the block inserter → <b>VuloMail</b>, whole pages under <b>VuloMail pages</b>): inserting it copies its blocks, so editing one page never changes another. Nothing is locked.</p>
		<p>
			<?php if ( $hid ) : ?><a class="button" href="<?php echo esc_url( admin_url( 'post.php?post=' . $hid . '&action=edit' ) ); ?>">Edit header</a><?php endif; ?>
			<?php if ( $fid ) : ?><a class="button" href="<?php echo esc_url( admin_url( 'post.php?post=' . $fid . '&action=edit' ) ); ?>">Edit footer</a><?php endif; ?>
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=wp_block' ) ); ?>">All patterns</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">Menus</a>
		</p>
		<form method="post"><?php wp_nonce_field( 'vm_admin' ); ?><input type="hidden" name="vm_action" value="patterns">
			<details><summary><b>Put the original design back</b> (tick the patterns to restore)</summary>
				<p style="columns:3;max-width:1100px">
					<?php foreach ( vm_pattern_list() as $slug => $p ) : if ( ! vm_pattern_is_synced( $slug ) ) { continue; } ?>
						<label style="display:block;break-inside:avoid"><input type="checkbox" name="vm_reset[]" value="<?php echo esc_attr( $slug ); ?>"> <?php echo esc_html( $p['title'] ); ?><?php echo vm_pattern_id( $slug ) ? '' : ' <em>(not created yet)</em>'; ?></label>
					<?php endforeach; ?>
				</p>
			</details><br>
			<?php submit_button( 'Create missing patterns / restore the ticked ones', 'secondary', '', false ); ?>
		</form>

		<h2>2. Logo and favicon</h2>
		<p>Both come from Customize → Site Identity. This puts the VuloMail logo and icon there for you.</p>
		<form method="post"><?php wp_nonce_field( 'vm_admin' ); ?><input type="hidden" name="vm_action" value="identity"><?php submit_button( 'Use the VuloMail logo and icon', 'secondary', '', false ); ?></form>

		<h2>3. Build pages from the patterns</h2>
		<p>Each page gets its own copy of its sections, plus references to the shared ones, and uses Astra's page settings for a full-width page without title (no page template). Home becomes the front page; Blog becomes the posts page. Building About, Changelog or Contact adds a Company group to the header menu if it doesn't have one.</p>
		<form method="post"><?php wp_nonce_field( 'vm_admin' ); ?><input type="hidden" name="vm_action" value="pages">
			<p><?php foreach ( $pages as $slug => $info ) : ?><label style="display:inline-block;min-width:240px;margin:2px 0"><input type="checkbox" name="vm_pages[]" value="<?php echo esc_attr( $slug ); ?>" checked> <?php echo esc_html( $info[0] ); ?></label><?php endforeach; ?>
			<label style="display:inline-block;min-width:240px"><input type="checkbox" name="vm_pages[]" value="blog" checked> Blog (posts page)</label></p>
			<label><input type="checkbox" name="vm_replace" value="1"> <b>Replace what is already on these pages</b> (WordPress keeps a revision)</label><br><br>
			<?php submit_button( 'Build pages', 'primary', '', false ); ?>
		</form>

		<h2>4. Blog posts</h2>
		<p>Imports the 10 original articles as posts (from data/blog.json + data/blog/*.html) (Kadence blocks, categories, excerpt = "The short answer"). New posts: write Markdown and convert it with <code>python3 src/build-blog.py post.md out.html</code>, then paste into the Code editor.</p>
		<form method="post"><?php wp_nonce_field( 'vm_admin' ); ?><input type="hidden" name="vm_action" value="posts"><label><input type="checkbox" name="vm_replace" value="1"> Update posts that already exist</label><br><br><?php submit_button( 'Import blog posts', 'secondary', '', false ); ?></form>

		<h2>Cheat sheet: classes (Kadence → Advanced → Additional CSS class)</h2>
		<table class="widefat striped" style="max-width:980px"><tbody>
			<tr><td><code>vm-row</code></td><td>Row Layout: content width from the <code>--vm-container-7xl</code> token (or Astra's container width)</td></tr>
			<tr><td><code>vm-band</code> · <code>vm-bg-ink</code> · <code>vm-airmail-top</code> · <code>vm-grain</code> · <code>vm-dots-top</code></td><td>Row backgrounds: paper band with borders, dark band, red/blue airmail stripe, paper grain, dot grid</td></tr>
			<tr><td><code>vm-pill vm-tone-red|blue|green|ink</code></td><td>Advanced Text: eyebrow pill with the pinging dot</td></tr>
			<tr><td><code>vm-h1</code> · <code>vm-h2</code> · <code>vm-lead</code> · <code>vm-mw-3xl</code> · <code>vm-center</code></td><td>Headline sizes, lead paragraph, width, centring. Italic text (<em>em</em>) in a headline is coloured</td></tr>
			<tr><td><code>vm-btn vm-btn-primary|ghost|light|outline-light</code></td><td>Kadence Button: the pill buttons with the sliding arrow (magnetic hover)</td></tr>
			<tr><td><code>vm-card</code> + <code>vm-card-p7</code> · <code>vm-tilt</code></td><td>Section (column): white card; 3D tilt toward the pointer</td></tr>
			<tr><td><code>vm-list</code> + <code>vm-checks</code> · <code>vm-checks-soft</code> · <code>vm-checks-dark</code></td><td>Row Layout holding Advanced Text items with class <code>vm-li</code>: green check, soft green circle, ink circle</td></tr>
			<tr><td><code>vm-faq</code> + <code>vm-faq-item</code> · <code>vm-faq-q</code> · <code>vm-faq-a</code></td><td>FAQ: one Section per question, with the question and answer as Advanced Text (opens like the original)</td></tr>
			<tr><td><code>vm-rv</code> · <code>vm-rv-d12</code> · <code>vm-rv-now</code> · <code>vm-rv-y10</code></td><td>Reveal on scroll (0.9s, rise 28px, blur 8px); delay in hundredths; on load; distance</td></tr>
			<tr><td><code>vm-stagger</code> (row) + <code>vm-st</code> (columns)</td><td>Columns rise in one after another, 0.08s apart</td></tr>
			<tr><td><code>vm-split</code> · <code>vm-split-25</code></td><td>Headline words rise in (0.06s apart); delay of the italic part in hundredths</td></tr>
			<tr><td><code>vm-b-*</code></td><td>Blog body styles written by src/build-blog.py</td></tr>
		</tbody></table>
		<p>Design tokens (every colour, font, size, radius and the content width): Customize → VuloMail → Design tokens.</p>
	</div>
	<?php
}

/** After updating from 1.1: points to the one-click page update until it has run. */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $screen && 'appearance_page_vulomail' === $screen->id ) {
			return;
		}
		if ( ! vm_needs_update() ) {
			return;
		}
		echo '<div class="notice notice-warning"><p><b>VuloMail 1.2.1:</b> pages built with 1.1 or 1.2.0 need a one-click update so they keep their look and stop sharing section edits. <a href="' . esc_url( admin_url( 'themes.php?page=vulomail' ) ) . '">Update my pages</a></p></div>';
	}
);
