<?php
/**
 * Appearance → VuloForm: one-click setup + a cheat sheet of blocks, graphics, shortcodes and effect classes.
 *
 *   1. Create pages     Home (front page), Blog (posts page), Compare + one page per plugin (/compare/wpforms/ …)
 *   2. Import posts     the 10 articles from data/posts.json as normal WordPress posts (skips ones that exist)
 *   3. Kadence pages    fills Home, Compare and the 7 plugin pages with the Kadence patterns (template "VuloForm Canvas")
 *   Synced patterns     create / reset every pattern as a synced pattern (Appearance → Design → Patterns); edit header / footer
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	function () {
		add_theme_page( 'VuloForm', 'VuloForm', 'edit_theme_options', 'vuloform', 'vf_admin_page' );
	}
);

/** Create a page once (looked up by path). Returns its ID. */
function vf_make_page( $title, $slug, $template, $parent = 0 ) {
	$path = $parent ? get_page_uri( $parent ) . '/' . $slug : $slug;
	$page = get_page_by_path( $path );
	if ( $page ) {
		return $page->ID;
	}
	$id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_parent' => $parent,
		)
	);
	if ( $id && $template ) {
		update_post_meta( $id, '_wp_page_template', $template );
	}
	return $id;
}

/** 1. Pages. */
function vf_setup_pages( $blog_links ) {
	$home = vf_make_page( 'Home', 'home', 'page-templates/template-home.php' );
	$blog = vf_make_page( 'Blog', 'blog', '' );
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home );
	update_option( 'page_for_posts', $blog );

	$compare = vf_make_page( 'Compare', 'compare', 'page-templates/template-compare.php' );
	foreach ( vf_compare()['profiles'] as $p ) {
		vf_make_page( 'VuloForm vs ' . $p['name'], $p['slug'], 'page-templates/template-compare-plugin.php', $compare );
	}
	if ( $blog_links ) {
		update_option( 'permalink_structure', '/blog/%postname%/' );
	}
	flush_rewrite_rules();
	return 3 + count( vf_compare()['profiles'] );
}

/**
 * 3. Kadence pages: put the full-page patterns into the pages and switch them to the Canvas template.
 * A page that already has content of its own is left alone. Returns [ filled, skipped ].
 */
function vf_setup_kadence_pages( $replace = false ) {
	$compare = vf_make_page( 'Compare', 'compare', '' );
	$pages   = array( 'page-home' => vf_make_page( 'Home', 'home', '' ), 'page-compare' => $compare );
	foreach ( vf_compare()['profiles'] as $p ) {
		$pages[ 'vs-' . $p['slug'] ] = vf_make_page( 'VuloForm vs ' . $p['name'], $p['slug'], '', $compare );
	}
	$done = array( 0, 0 );
	foreach ( $pages as $pattern => $id ) {
		$ref = vf_sync_pattern( $pattern );
		if ( ! $id || ! $ref || ( ! $replace && '' !== trim( get_post_field( 'post_content', $id ) ) ) ) {
			$done[1]++;
			continue;
		}
		// The page shows the synced pattern: edit it in Appearance → Design → Patterns and the page follows.
		wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( vf_pattern_ref( $ref ) ) ) );
		update_post_meta( $id, '_wp_page_template', 'page-templates/template-canvas.php' );
		$done[0]++;
	}
	if ( ! get_option( 'page_on_front' ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pages['page-home'] );
	}
	return $done;
}

/** Blog post (data/posts.json item) → block markup. */
function vf_post_blocks( $p ) {
	$para = function ( $t ) { return "<!-- wp:paragraph -->\n<p>" . esc_html( $t ) . "</p>\n<!-- /wp:paragraph -->\n\n"; };
	$out  = implode( '', array_map( $para, $p['intro'] ) );
	foreach ( $p['sections'] as $s ) {
		$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( $s['h'] ) . "</h2>\n<!-- /wp:heading -->\n\n";
		$out .= implode( '', array_map( $para, $s['p'] ) );
		if ( ! empty( $s['list'] ) ) {
			$items = '';
			foreach ( $s['list'] as $li ) {
				$items .= '<!-- wp:list-item --><li>' . esc_html( $li ) . '</li><!-- /wp:list-item -->';
			}
			$out .= "<!-- wp:list -->\n<ul class=\"wp-block-list\">" . $items . "</ul>\n<!-- /wp:list -->\n\n";
		}
	}
	$outro = rtrim( implode( '', array_map( $para, $p['outro'] ) ) );
	$out  .= "<!-- wp:group {\"className\":\"vf-outro\",\"layout\":{\"type\":\"default\"}} -->\n<div class=\"wp-block-group vf-outro\">" . $outro . "</div>\n<!-- /wp:group -->";
	return $out;
}

/** 2. Posts. Returns the number created. */
function vf_import_posts() {
	$made = 0;
	foreach ( vf_data( 'posts' ) as $p ) {
		if ( get_page_by_path( $p['slug'], OBJECT, 'post' ) ) {
			continue;
		}
		$cat = term_exists( $p['category'], 'category' );
		if ( ! $cat ) {
			$cat = wp_insert_term( $p['category'], 'category' );
		}
		$id = wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $p['title'],
				'post_name'     => $p['slug'],
				'post_excerpt'  => $p['description'],
				'post_date'     => $p['date'] . ' 09:00:00',
				'post_content'  => wp_slash( vf_post_blocks( $p ) ),
				'post_category' => is_array( $cat ) ? array( (int) $cat['term_id'] ) : array(),
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			if ( ! empty( $p['compare'] ) ) {
				update_post_meta( $id, 'vf_compare', implode( ',', $p['compare'] ) );
			}
			$made++;
		}
	}
	return $made;
}

function vf_admin_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$msg = '';
	if ( isset( $_POST['vf_action'] ) && check_admin_referer( 'vf_setup' ) ) {
		if ( 'pages' === $_POST['vf_action'] ) {
			$msg = sprintf( 'Pages are ready (%d). Home is the front page, Blog is the posts page.', vf_setup_pages( ! empty( $_POST['vf_blog_links'] ) ) );
		} elseif ( 'posts' === $_POST['vf_action'] ) {
			$msg = sprintf( '%d blog posts imported.', vf_import_posts() );
		} elseif ( 'hf' === $_POST['vf_action'] ) {
			$reset = ! empty( $_POST['vf_hf_reset'] );
			$n     = vf_sync_all( $reset );
			$msg   = sprintf( $reset ? '%d synced patterns are back to the original design (the old versions are kept as revisions).' : '%d synced patterns are ready in Appearance → Design → Patterns → VuloForm.', $n );
			if ( ! empty( $_POST['vf_identity'] ) ) {
				vf_import_identity();
				$msg .= ' Logo and site icon set where they were empty.';
			}
		} elseif ( 'kadence' === $_POST['vf_action'] ) {
			$n   = vf_setup_kadence_pages( ! empty( $_POST['vf_replace'] ) );
			$msg = sprintf( '%d pages now use the Kadence patterns (template "VuloForm Canvas"). %d skipped because they already have content.', $n[0], $n[1] );
		}
	}
	$code = 'style="display:inline-block;margin:2px 4px 2px 0;padding:2px 6px;background:#f0f0f1;border-radius:4px"';
	?>
	<div class="wrap">
		<h1>VuloForm theme</h1>
		<?php if ( $msg ) : ?><div class="notice notice-success"><p><?php echo esc_html( $msg ); ?></p></div><?php endif; ?>

		<h2>Synced patterns, header and footer</h2>
		<p>Every VuloForm pattern (each section, each whole page, the header and the footer) is a <b>synced pattern</b> in WordPress: edit it in <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wp_block' ) ); ?>">Appearance → Design → Patterns</a> → <b>VuloForm</b>, with Kadence, and every page that uses it changes too. <?php echo (int) count( array_filter( array_map( 'vf_synced_post', array_keys( vf_pattern_files() ) ) ) ); ?> of <?php echo (int) count( vf_pattern_files() ); ?> exist now.</p>
		<p>The header and footer are the synced patterns <b>Site · Header</b> and <b>Site · Footer</b>. They are shown on every page in place of Astra's header and footer (turn that off in <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=vuloform' ) ); ?>">Customize → VuloForm → General</a>).</p>
		<p>
			<?php foreach ( array( 'header' => 'Edit header', 'footer' => 'Edit footer' ) as $part => $label ) : ?>
				<?php if ( vf_hf_edit_url( $part ) ) : ?>
					<a class="button button-primary" href="<?php echo esc_url( vf_hf_edit_url( $part ) ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php else : ?>
					<span class="button disabled"><?php echo esc_html( $label ); ?> (not created yet)</span>
				<?php endif; ?>
			<?php endforeach; ?>
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=wp_block' ) ); ?>">All patterns</a>
		</p>
		<form method="post">
			<?php wp_nonce_field( 'vf_setup' ); ?>
			<input type="hidden" name="vf_action" value="hf">
			<p><label><input type="checkbox" name="vf_hf_reset" value="1"> Put the original design back into all existing synced patterns (your edits are replaced; WordPress keeps a revision)</label></p>
			<p><label><input type="checkbox" name="vf_identity" value="1" checked> Set the VuloForm logo and site icon in Site Identity if they are empty</label></p>
			<?php submit_button( 'Create synced patterns', 'secondary', 'submit', false ); ?>
		</form>
		<p class="description">
			Logo: <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=title_tagline' ) ); ?>">Customize → Site Identity</a> (Logo, Astra's Retina logo, Site Icon = favicon). The header shows it with the block <b>Site Logo (VuloForm)</b>.
			Links: <a href="<?php echo esc_url( admin_url( 'nav-menus.php?action=locations' ) ); ?>">Appearance → Menus</a> → location "VuloForm header" (block <b>Site Menu (VuloForm)</b>).
			Colours, font sizes and content width: <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=vuloform_tokens' ) ); ?>">Customize → VuloForm → Design tokens</a>.
		</p>

		<h2>1. Set up the site</h2>
		<form method="post" style="margin:1em 0">
			<?php wp_nonce_field( 'vf_setup' ); ?>
			<input type="hidden" name="vf_action" value="pages">
			<p><label><input type="checkbox" name="vf_blog_links" value="1"> Use <code>/blog/post-name/</code> links for posts, like vuloform.com (changes Settings → Permalinks)</label></p>
			<?php submit_button( 'Create pages (Home, Blog, Compare + 7 plugin pages)', 'primary', 'submit', false ); ?>
		</form>
		<form method="post">
			<?php wp_nonce_field( 'vf_setup' ); ?>
			<input type="hidden" name="vf_action" value="posts">
			<?php submit_button( 'Import the 10 blog posts', 'secondary', 'submit', false ); ?>
		</form>
		<form method="post" style="margin:1em 0 0">
			<?php wp_nonce_field( 'vf_setup' ); ?>
			<input type="hidden" name="vf_action" value="kadence">
			<p><label><input type="checkbox" name="vf_replace" value="1"> Replace what is already on these pages (old sections are removed; WordPress keeps a revision to undo)</label></p>
			<?php submit_button( 'Build Home, Compare + plugin pages from the synced patterns', 'secondary', 'submit', false ); ?>
			<?php if ( ! defined( 'KADENCE_BLOCKS_VERSION' ) ) : ?><span style="color:#b32d2e;margin-left:8px">Install and activate the Kadence Blocks plugin first.</span><?php endif; ?>
		</form>
		<p class="description">Without the tick only empty pages are filled. Without this, the pages use the PHP templates, which look the same.</p>
		<p>Options: <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=vuloform' ) ); ?>">Customize → VuloForm</a> (header, footer, download link, font, smooth scroll) and <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=vuloform_tokens' ) ); ?>">Design tokens</a> (every colour, size and width).</p>

		<h2>2. Kadence patterns</h2>
		<p>In the editor: <b>+</b> → <b>Patterns</b> → <b>VuloForm</b> (synced: edits in one place show everywhere; ⋮ → Detach to make a free copy). Every section of every page is a pattern built from Kadence blocks (Row Layout, Section, Advanced Text, Buttons); change text, colours and spacing in Kadence's own panels. For full-width pages pick the page template <b>VuloForm Canvas (Kadence)</b>.</p>
		<ul style="list-style:disc;margin-left:2em">
			<li><b>Home ·</b> Hero, Ready-made forms marquee, Three panes sticky steps, Smart forms, Everything included, Comparison table with tabs, Publish anywhere, Privacy with giant scrolling words, FAQ accordion, Final call to action</li>
			<li><b>Compare ·</b> Page header, Feature table + cost chart, Pick a head-to-head · <b>Blog ·</b> Page header, Post list · <b>Try VuloForm free banner</b></li>
			<li><b>Site ·</b> Header, Footer (the originals of the synced patterns above)</li>
			<li><b>Page ·</b> Home (all sections), Compare (all plugins), Blog, and one full page per plugin (VuloForm vs WPForms …)</li>
		</ul>
		<p>Their look comes from class names in <code>assets/css/vf-kadence.css</code> (Advanced → Additional CSS class), e.g. <code <?php echo $code; // phpcs:ignore ?>>vf-t-h2</code> <code <?php echo $code; // phpcs:ignore ?>>vf-eyebrow</code> <code <?php echo $code; // phpcs:ignore ?>>vf-card vf-bg-mint</code> <code <?php echo $code; // phpcs:ignore ?>>vf-btn vf-btn-primary</code>. Pictures and demos are the block <b>Animated Graphic (VuloForm)</b>. No Gutenberg core blocks are used.</p>

		<h2>3. Blocks</h2>
		<p>In the editor: <b>+</b> → category <b>VuloForm</b> (or search “VuloForm”). Edit the text in the block sidebar.</p>
		<ul style="list-style:disc;margin-left:2em">
			<li><b>Ready-made Forms Marquee (VuloForm)</b> – two endless rows of chips</li>
			<li><b>Builder Sticky Steps (VuloForm)</b> – “Three panes. Zero guesswork.” with the builder app</li>
			<li><b>Comparison Table with Tabs (VuloForm)</b> – Feature by feature / What it costs</li>
			<li><b>Privacy Scrolling Words (VuloForm)</b> – giant words sliding while you scroll</li>
			<li><b>Animated Graphic (VuloForm)</b> – any graphic below, or any Home section</li>
			<li><b>Site Logo (VuloForm)</b> – the Site Identity logo + site title · <b>Site Menu (VuloForm)</b> – header links, phone menu and burger button</li>
		</ul>

		<h2>Class cheat sheet: header and footer</h2>
		<p><?php foreach ( array( 'vf-site-header', 'vf-nav-bar', 'vf-nav-actions', 'vf-nav-cta', 'vf-btn-sm', 'vf-btn-flat', 'vf-site-footer', 'vf-ft-card', 'vf-ft-top', 'vf-ft-brand', 'vf-ft-about', 'vf-ft-groups', 'vf-ft-group', 'vf-ft-h', 'vf-ft-links', 'vf-ft-link', 'vf-ft-more', 'vf-ft-products', 'vf-ft-product', 'vf-ft-product-name', 'vf-ft-product-text', 'vf-ft-bottom', 'vf-ft-dot', 'vf-btn-ft' ) as $c ) { echo '<code ' . $code . '>' . esc_html( $c ) . '</code>'; } // phpcs:ignore ?></p>

		<h2>4. Graphics</h2>
		<p>Block <b>Animated Graphic (VuloForm)</b>, or the shortcode <code>[vf_graphic name="…"]</code>:</p>
		<p><?php foreach ( vf_graphics() as $name => $label ) { echo '<code ' . $code . ' title="' . esc_attr( $label ) . '">' . esc_html( $name ) . '</code>'; } // phpcs:ignore ?></p>
		<p>Also: <code>[vf_section name="privacy"]</code> <code>[vf_compare_table plugins="vulo,wpf"]</code> <code>[vf_cost_chart]</code> <code>[vf_get_it title="…"]</code></p>

		<h2>5. Effects – just add a class</h2>
		<p>Block sidebar → <b>Advanced</b> → <b>Additional CSS class(es)</b>. Full list with notes: <code>assets/css/vf-effects.css</code>.</p>
		<p>
			<?php
			$fx = array( 'vf-reveal', 'vf-reveal-left', 'vf-reveal-right', 'vf-reveal-zoom', 'vf-reveal-pop', 'vf-reveal-fade', 'vf-reveal-blur', 'vf-reveal-tilt', 'vf-stagger', 'vf-words', 'vf-count', 'vf-delay-1…10', 'vf-fast', 'vf-slow', 'vf-repeat', 'vf-parallax', 'vf-parallax-lg', 'vf-parallax-xl', 'vf-parallax-reverse', 'vf-drift-left', 'vf-drift-right', 'vf-exit-up', 'vf-exit-down', 'vf-exit-fade', 'vf-scroll-zoom', 'vf-enter-up', 'vf-scroll-rotate', 'vf-fx-root', 'vf-sticky-steps', 'vf-page-progress', 'vf-marquee', 'vf-marquee-reverse', 'vf-fade-edges', 'vf-float', 'vf-wiggle', 'vf-pulse', 'vf-tilt', 'vf-follow', 'vf-lift', 'vf-shine' );
			foreach ( $fx as $c ) {
				echo '<code ' . $code . '>' . esc_html( $c ) . '</code>'; // phpcs:ignore
			}
			?>
		</p>
	</div>
	<?php
}
