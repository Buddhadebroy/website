<?php
/**
 * Appearance → Notifima: one-click setup + cheat sheet.
 *
 *   Synced patterns   create (or put back) every section, page, header and footer pattern
 *   Pages             Home, Features, Pricing, Blog, Release Notes, About Us, each built from page-content/ as Kadence blocks
 *                     (shared sections as synced patterns) on the Canvas template; Home becomes the front page
 *   Menu              the "Notifima header" menu (with the Resources mega menu)
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	function () {
		add_theme_page( 'Notifima', 'Notifima', 'edit_theme_options', 'notifima', 'nf_admin_page' );
	}
);

/** Create a page once (looked up by path). Returns its ID. */
function nf_make_page( $title, $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return $page->ID;
	}
	return (int) wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug ) );
}

/** The pages to build: data/pages.php, else the page-content/*.php files themselves. */
function nf_page_list() {
	$pages = nf_data( 'pages' );
	if ( $pages ) {
		return $pages;
	}
	foreach ( (array) glob( NF_DIR . '/page-content/*.php' ) as $file ) {
		$name    = basename( (string) $file, '.php' );
		$pages[] = array( 'slug' => $name, 'title' => ucwords( str_replace( '-', ' ', $name ) ), 'front' => 'home' === $name );
	}
	return $pages;
}

/**
 * Every page from data/pages.php gets its Kadence blocks from page-content/ (template "Notifima Canvas").
 * A page with content of its own is left alone unless $replace. Returns [ filled, skipped ].
 */
function nf_setup_pages( $replace = false, $real_blog = false, $auto = false, $only = array() ) {
	$done    = array( 0, 0 );
	$offered = (array) get_option( 'nf_pages_offered', array( 'home', 'features', 'pricing', 'blog', 'release-notes', 'about-us' ) );
	foreach ( nf_page_list() as $p ) {
		if ( $only && ! in_array( $p['slug'], $only, true ) ) {
			continue;
		}
		if ( $auto && ! get_page_by_path( $p['slug'] ) && in_array( $p['slug'], $offered, true ) ) {
			continue; // an update only refreshes pages that exist; a page new in this version is made once
		}
		$offered[] = $p['slug'];
		$id  = nf_make_page( $p['title'], $p['slug'] );
		$ref = nf_page_markup( $p['slug'] );
		if ( ! empty( $p['front'] ) && $id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $id );
		}
		if ( 'blog' === $p['slug'] && $id ) {
			update_option( 'page_for_posts', $real_blog ? $id : 0 );
		}
		if ( $auto && $id && ! nf_is_untouched( $id ) ) {
			$done[1]++;
			continue;
		}
		if ( ! $id || ! $ref || ( ! $replace && ! $auto && '' !== trim( get_post_field( 'post_content', $id ) ) ) ) {
			$done[1]++;
			continue;
		}
		wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( $ref ) ) );
		nf_mark_built( $id );
		update_post_meta( $id, '_wp_page_template', 'release-notes' === $p['slug'] ? 'page-templates/template-release-notes.php' : ( 'release-notes-pro' === $p['slug'] ? 'page-templates/template-release-notes-pro.php' : 'page-templates/template-canvas.php' ) );
		$done[0]++;
	}
	update_option( 'nf_pages_offered', array_values( array_unique( $offered ) ), false );
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	flush_rewrite_rules();
	return $done;
}

function nf_admin_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$msg = '';
	if ( isset( $_POST['nf_action'] ) && check_admin_referer( 'nf_setup' ) ) {
		$action = sanitize_key( $_POST['nf_action'] );
		if ( 'patterns' === $action ) {
			$reset = ! empty( $_POST['nf_reset'] );
			$n     = nf_sync_all( $reset );
			$msg   = sprintf( $reset ? '%d synced patterns are back to the original design (the old versions are kept as revisions).' : '%d synced patterns are ready in Appearance → Design → Patterns → Notifima.', $n );
		} elseif ( 'pages' === $action ) {
			if ( ! nf_page_list() ) {
				$msg = 'No page content was found in the theme folder (page-content/*.php). Upload the theme zip again, then try once more.';
			} else {
			$only = array_map( 'sanitize_title', (array) wp_unslash( $_POST['nf_only'] ?? array() ) );
			$n    = nf_setup_pages( ! empty( $_POST['nf_replace'] ), ! empty( $_POST['nf_real_blog'] ) || ( $only && ! in_array( 'blog', $only, true ) && get_option( 'page_for_posts' ) ), false, $only );
			$msg = sprintf( '%d pages are built from Kadence blocks (template "Notifima Canvas"). %d skipped because they already have content (tick "Replace what is already on these pages" to fill them anyway).', $n[0], $n[1] );
			}
		} elseif ( 'releases' === $action ) {
			$r   = nf_rel_refresh();
			$msg = $r ? sprintf( 'Release notes refreshed: %d releases, latest %s.', count( $r ), $r[0]['version'] ) : 'Could not read the release notes. Check the plugin slug / GitHub repository in Customize → Notifima → General.';
		} elseif ( 'sample' === $action ) {
			$id  = nf_make_sample_post();
			$msg = $id ? sprintf( 'The sample post is ready: <a href="%s">view it</a> or <a href="%s">edit it</a>. Copy it (or any part of it) for your own posts.', esc_url( get_permalink( $id ) ), esc_url( get_edit_post_link( $id ) ) ) : 'The sample post file (data/sample-post.php) is missing. Upload the theme zip again.';
		} elseif ( 'menu' === $action ) {
			nf_make_menu();
			$msg = 'The "Notifima header" menu is ready (Appearance → Menus).';
		}
	}
	$code  = 'style="display:inline-block;margin:2px 4px 2px 0;padding:2px 6px;background:#f0f0f1;border-radius:4px"';
	$files = nf_pattern_files();
	?>
	<div class="wrap">
		<h1>Notifima theme</h1>
		<?php if ( $msg ) : ?><div class="notice notice-success"><p><?php echo wp_kses( $msg, array( 'a' => array( 'href' => array() ) ) ); ?></p></div><?php endif; ?>
		<?php if ( ! defined( 'KADENCE_BLOCKS_VERSION' ) ) : ?><div class="notice notice-error"><p>The patterns are built with Kadence blocks. <a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=kadence+blocks&tab=search&type=term' ) ); ?>">Install and activate Kadence Blocks</a> first.</p></div><?php endif; ?>

		<h2>1. Synced patterns, header and footer</h2>
		<p>Every section of every page, each whole page, the header and the footer is a <b>synced pattern</b>: edit it in <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wp_block' ) ); ?>">Appearance → Design → Patterns</a> → <b>Notifima</b> with Kadence, and every page that shows it changes too. <?php echo (int) count( array_filter( array_map( 'nf_synced_post', array_keys( $files ) ) ) ); ?> of <?php echo (int) count( $files ); ?> exist now.</p>
		<p>
			<?php foreach ( array( 'header' => 'Edit header', 'footer' => 'Edit footer' ) as $part => $label ) : ?>
				<?php if ( nf_hf_edit_url( $part ) ) : ?>
					<a class="button button-primary" href="<?php echo esc_url( nf_hf_edit_url( $part ) ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php else : ?>
					<span class="button disabled"><?php echo esc_html( $label ); ?> (not created yet)</span>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if ( nf_pattern_edit_url( 'mega-resources' ) ) : ?>
				<a class="button button-primary" href="<?php echo esc_url( nf_pattern_edit_url( 'mega-resources' ) ); ?>">Edit mega menu (Kadence)</a>
			<?php endif; ?>
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=wp_block' ) ); ?>">All patterns</a>
		</p>
		<form method="post">
			<?php wp_nonce_field( 'nf_setup' ); ?>
			<input type="hidden" name="nf_action" value="patterns">
			<p><label><input type="checkbox" name="nf_reset" value="1"> Put the original design back into all existing synced patterns (your edits are replaced; WordPress keeps a revision)</label></p>
			<?php submit_button( 'Create synced patterns', 'secondary', 'submit', false ); ?>
		</form>

		<h2>2. Pages</h2>
		<form method="post">
			<?php wp_nonce_field( 'nf_setup' ); ?>
			<input type="hidden" name="nf_action" value="pages">
			<p><label><input type="checkbox" name="nf_replace" value="1"> Replace what is already on these pages (WordPress keeps a revision to undo)</label></p>
			<p><label><input type="checkbox" name="nf_real_blog" value="1"<?php checked( (bool) get_option( 'page_for_posts' ) ); ?>> Show your real WordPress posts on the Blog page (Blog = Posts page; the hero, newsletter and FAQ stay)</label></p>
			<p>Only these pages (none ticked = all):
				<?php foreach ( nf_page_list() as $p ) : ?>
					<label style="margin-right:12px"><input type="checkbox" name="nf_only[]" value="<?php echo esc_attr( $p['slug'] ); ?>"> <?php echo esc_html( $p['title'] ); ?></label>
				<?php endforeach; ?>
			</p>
			<?php submit_button( 'Build the pages', 'primary', 'submit', false ); ?>
		</form>

		<h2>Release notes</h2>
		<form method="post">
			<?php wp_nonce_field( 'nf_setup' ); ?>
			<input type="hidden" name="nf_action" value="releases">
			<p>The Release Notes page reads the changelog from WordPress.org<?php echo nf_opt( 'nf_rel_github' ) ? ' and the GitHub Releases of <code>' . esc_html( nf_opt( 'nf_rel_github' ) ) . '</code>' : ''; ?> (<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=notifima' ) ); ?>">change the sources</a>). It updates itself within an hour of a new release; last read: <?php echo get_option( 'nf_releases_checked' ) ? esc_html( human_time_diff( (int) get_option( 'nf_releases_checked' ) ) . ' ago' ) : 'not yet'; ?>.</p>
			<?php submit_button( 'Refresh release notes now', 'secondary', 'submit', false ); ?>
			<p class="description">Instant updates from GitHub: repository → Settings → Webhooks → Add webhook. Payload URL <code><?php echo esc_html( rest_url( 'notifima/v1/releases/refresh' ) ); ?></code>, Content type <code>application/json</code>, Secret <code><?php echo esc_html( nf_rel_secret() ); ?></code>, events "Releases" and "Pushes".</p>
		</form>

		<h2>Blog post design</h2>
		<form method="post">
			<?php wp_nonce_field( 'nf_setup' ); ?>
			<input type="hidden" name="nf_action" value="sample">
			<p>Every post uses the Notifima single post design (title, author, date, reading time, "On this page" from the H2 headings, "Keep reading"). The sample post shows every component (compare cards, chips, stats, tip and warning boxes, steps, table, takeaways, FAQ) as Kadence blocks you can copy. New posts can also be written in Markdown and turned into blocks with <code>src/build-blog.py</code>.</p>
			<?php submit_button( 'Create the sample blog post', 'secondary', 'submit', false ); ?>
		</form>

		<h2>3. Menu, logo and design</h2>
		<form method="post" style="display:inline">
			<?php wp_nonce_field( 'nf_setup' ); ?>
			<input type="hidden" name="nf_action" value="menu">
			<?php submit_button( 'Create the "Notifima header" menu', 'secondary', 'submit', false ); ?>
		</form>
		<p>
			Menu: <a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">Appearance → Menus</a> → "Notifima header". The desktop <b>mega menu</b> (design and content) is the synced pattern "Site · Mega menu · Resources": edit it with Kadence via the <b>Edit mega menu</b> button above (icon of a link = Advanced → Additional CSS class <code>nfi-book</code>, <code>nfi-help</code> …). The phone menu uses the sub items of this menu.<br>
			Logo and favicon: <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=title_tagline' ) ); ?>">Customize → Site Identity</a> (without a logo the site title is shown as the wordmark).<br>
			Links (contact, demo, docs …): <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=notifima' ) ); ?>">Customize → Notifima → General</a>. Colours, fonts, radii and widths: <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=notifima_tokens' ) ); ?>">Design tokens</a>.
		</p>

		<h2>Patterns</h2>
		<p><?php foreach ( $files as $slug => $f ) { echo '<code ' . $code . ' title="' . esc_attr( $f['description'] ) . '">' . esc_html( $f['title'] ) . '</code>'; } // phpcs:ignore ?></p>

		<h2>Graphics</h2>
		<p>Block <b>Animated Graphic (Notifima)</b> or <code>[nf_graphic name="…"]</code>:</p>
		<p><?php foreach ( nf_graphics() as $name => $label ) { echo '<code ' . $code . ' title="' . esc_attr( $label ) . '">' . esc_html( $name ) . '</code>'; } // phpcs:ignore ?></p>

		<h2>Classes</h2>
		<p>The look comes from class names (block sidebar → Advanced → Additional CSS class), the same names as the original site: rows <code>nf-k sec</code> (+ <code>tint</code>, <code>hero</code>, <code>phero</code>, <code>cta</code>), columns <code>wrap</code>, <code>center</code>, <code>sixgrid</code>, <code>six</code>, <code>fz</code>, <code>fz rev</code>, text <code>kicker</code>, <code>title</code>, <code>intro</code>, <code>lead</code>, buttons <code>btn btn-p</code>, <code>btn btn-g</code>, <code>btn-sm</code>. Reveal on scroll: <code>rv</code>. Full list: <code>assets/css/nf-site.css</code>.</p>
	</div>
	<?php
}

/** The sample blog post from data/sample-post.php (a draft is updated, never duplicated). Returns its ID. */
function nf_make_sample_post() {
	$file = NF_DIR . '/data/sample-post.php';
	if ( ! is_readable( $file ) ) {
		return 0;
	}
	$p    = include $file;
	$cat  = term_exists( $p['category'], 'category' );
	$cat  = $cat ? (int) $cat['term_id'] : (int) wp_insert_term( $p['category'], 'category' )['term_id'];
	$old  = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'meta_key' => '_nf_sample', 'meta_value' => '1', 'numberposts' => 1, 'fields' => 'ids' ) ); // phpcs:ignore
	$data = array(
		'post_title'    => $p['title'],
		'post_excerpt'  => $p['excerpt'],
		'post_content'  => wp_slash( $p['content'] ),
		'post_status'   => 'publish',
		'post_type'     => 'post',
		'post_category' => array( $cat ),
	);
	if ( $old ) {
		$data['ID'] = $old[0];
		return wp_update_post( $data ) ? $old[0] : 0;
	}
	$id = wp_insert_post( $data );
	if ( $id ) {
		update_post_meta( $id, '_nf_sample', '1' );
	}
	return $id;
}

/** Remember what the theme wrote into a page / pattern, to know later whether you changed it. */
function nf_mark_built( $id ) {
	update_post_meta( $id, '_nf_built_hash', md5( (string) get_post_field( 'post_content', $id ) ) );
}

/** True when the theme built this post and nobody changed it since (or it was built before hashes existed). */
function nf_is_untouched( $id ) {
	$h = get_post_meta( $id, '_nf_built_hash', true );
	return ! $h || md5( (string) get_post_field( 'post_content', $id ) ) === $h;
}

/*
 * After a theme update: put the new block markup into the pages and synced patterns the theme made, so they
 * match the new Kadence settings without any clicks. Anything you edited yourself is left alone (and listed).
 * Runs once per theme version, as an admin (so nothing is stripped from the blocks). WordPress keeps revisions.
 */
add_action(
	'admin_init',
	function () {
		if ( get_option( 'nf_built_version' ) === NF_VERSION || ! current_user_can( 'unfiltered_html' ) || wp_doing_ajax() ) {
			return;
		}
		update_option( 'nf_built_version', NF_VERSION, false );
		$kept = array();
		foreach ( array_keys( nf_pattern_files() ) as $slug ) {
			$post = nf_synced_post( $slug );
			if ( $post && ! nf_is_untouched( $post->ID ) ) {
				$kept[] = $post->post_title;
				continue;
			}
			$id = nf_sync_pattern( $slug, true );
			if ( $id ) {
				nf_mark_built( $id );
			}
		}
		if ( nf_page_list() && get_page_by_path( 'home' ) ) {
			$n = nf_setup_pages( false, (bool) get_option( 'page_for_posts' ), true );
			if ( $n[1] ) {
				$kept[] = sprintf( '%d page(s)', $n[1] );
			}
		}
		set_transient( 'nf_upgrade_notice', $kept ? $kept : array( '-' ), DAY_IN_SECONDS );
	}
);
add_action(
	'admin_notices',
	function () {
		$k = get_transient( 'nf_upgrade_notice' );
		if ( ! $k ) {
			return;
		}
		delete_transient( 'nf_upgrade_notice' );
		echo '<div class="notice notice-success is-dismissible"><p><b>Notifima ' . esc_html( NF_VERSION ) . ':</b> the pages and synced patterns made by the theme were updated to the new design.';
		if ( array( '-' ) !== $k ) {
			echo ' Left as they are because you changed them: ' . esc_html( implode( ', ', $k ) ) . '. To update them too, use Appearance → Notifima ("Put the original design back" / "Replace what is already on these pages").';
		}
		echo '</p></div>';
	}
);
