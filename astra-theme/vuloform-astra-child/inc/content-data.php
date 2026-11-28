<?php
/**
 * Content shared by several templates: menu links, footer columns, products, comparison data, blog posts.
 * Section-only text (headlines, demo labels) sits at the top of each file in template-parts/home/.
 *
 * Every list goes through a filter (vf_nav_links, vf_footer_groups …) so a plugin can change it too.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** Header links (used when no menu is assigned to "VuloForm header" in Appearance → Menus). */
function vf_nav_links() {
	return apply_filters(
		'vf_nav_links',
		array(
			array( '/#builder', 'Builder' ),
			array( '/#logic', 'Logic' ),
			array( '/#features', 'Features' ),
			array( '/compare/', 'Compare' ),
			array( '/blog/', 'Blog' ),
			array( '/#faq', 'FAQ' ),
		)
	);
}

/** Footer link columns: title => [ [href, label, external?] ]. */
function vf_footer_groups() {
	$compare = array( array( '/compare/', 'All comparisons' ) );
	foreach ( array_slice( vf_compare()['profiles'], 0, 4 ) as $p ) {
		$compare[] = array( '/compare/' . $p['slug'] . '/', 'vs ' . $p['name'] );
	}
	return apply_filters(
		'vf_footer_groups',
		array(
			'Product'   => array(
				array( '/#builder', 'Builder' ),
				array( '/#logic', 'Logic' ),
				array( '/#features', 'Features' ),
				array( '/#privacy', 'Privacy' ),
				array( '/#faq', 'FAQ' ),
			),
			'Resources' => array(
				array( '/blog/', 'Blog' ),
				array( vf_docs_url( '/user' ), 'User guide', true ),
				array( vf_docs_url( '/developer' ), 'Developers', true ),
				array( vf_download_url(), 'Source code', true ),
			),
			'Compare'   => $compare,
		)
	);
}

/** "More from VuloLabs" cards in the footer. */
function vf_products() {
	return apply_filters(
		'vf_products',
		array(
			array( 'VuloLabs', 'https://www.vulolabs.com', 'Product engineering for modern business' ),
			array( 'VuloPilot', 'https://vulopilot.com', 'The WordPress growth operating system' ),
			array( 'VuloForm', 'https://vuloform.com', 'Build and review forms inside WordPress' ),
		)
	);
}

/**
 * Comparison facts (data/compare.json): checked_on, plugins, rows, costs, sources, profiles.
 * Edit the JSON file to update a price or a cell; Home, /compare and every head-to-head page follow.
 */
function vf_compare() {
	return apply_filters( 'vf_compare', vf_data( 'compare' ) );
}

/** One head-to-head profile by slug ("wpforms"), or null. */
function vf_profile( $slug ) {
	foreach ( vf_compare()['profiles'] as $p ) {
		if ( $p['slug'] === $slug ) {
			return $p;
		}
	}
	return null;
}

/** Every source link, flattened: [ [label, url] ]. */
function vf_all_sources() {
	return array_merge( ...array_values( vf_compare()['sources'] ) );
}

/**
 * Default text of the sections that are also blocks (Ready-made forms, Builder, Compare, Privacy).
 * The Home template uses these; a block uses them for every field you leave as it is.
 * Lists are one item per line; "Title | text" splits a line into a title and its text.
 */
function vf_section_defaults( $section ) {
	$d = array(
		'templates' => array(
			'kicker' => 'Start from ten ready-made forms — or a blank page',
			'row1'   => "💬 Contact\n🧭 General inquiry\n🎯 Lead generation\n📰 Newsletter signup\n⭐ Feedback",
			'row2'   => "📊 Survey\n🎟️ Event registration\n💼 Job application\n🛟 Support request\n🧾 Order or quote inquiry",
		),
		'builder'   => array(
			'eyebrow'  => 'The builder',
			'title'    => 'Three panes.',
			'subtitle' => 'Zero guesswork.',
			'steps'    => "Drag fields in | Field types on the left, your form in the middle. Drag to add, drag to reorder — or use the buttons and keyboard instead.\nTune every detail | Select a field to change its label, make it required, or add conditional logic in the inspector on the right.\nPreview the real thing | Try the form exactly as visitors will see it. Nothing you enter in the preview is sent or saved.\nPublish & share | Changes save as a draft by themselves. Hit Publish, then add it with the block, a shortcode, or an embed snippet.",
		),
		'compare'   => array(
			'eyebrow'     => 'How it compares',
			'title'       => 'The features others charge for,',
			'highlight'   => 'free.',
			'intro'       => "We compared VuloForm with seven widely used WordPress form plugins, using each one's own pricing page and WordPress.org listing. Here's what you get without paying — and what it costs to get the rest.",
			'tab1'        => 'Feature by feature',
			'tab2'        => 'What it costs',
			'showHeading' => true,
			'showCards'   => true,
			'showLinks'   => true,
			'showSources' => true,
		),
		'privacy'   => array(
			'band1'      => 'your data · your site · your data · your site',
			'band2'      => 'no strings · no trackers · no strings · no trackers',
			'eyebrow'    => 'Privacy & ownership',
			'title'      => 'Everything stays on',
			'highlight'  => 'your',
			'titleAfter' => 'site.',
			'intro'      => "VuloForm doesn't contact any external service on its own. Data only leaves your server when you set up a webhook or notification.",
			'promises'   => "No account | You never sign up with VuloLabs. Install, activate, build.\nNo subscription | Free and GPL-licensed. Nothing to renew, nothing locked.\nNo limits | Unlimited forms. Unlimited submissions. Forever.\nNo telemetry | No connection to VuloLabs, no visitor data sent anywhere.",
			'extras'     => "IP addresses off by default | Visitor IPs aren't stored unless you switch it on.\nWordPress privacy tools | Works with Export Personal Data and Erase Personal Data.\nPolicy text, suggested | Adds suggested wording to the WordPress privacy policy guide.",
		),
	);
	return apply_filters( 'vf_section_defaults', $d[ $section ] ?? array(), $section );
}

/** Section defaults merged with what a block sent (empty fields keep the default). */
function vf_section_args( $section, $args ) {
	$args = array_filter( (array) $args, function ( $v ) { return '' !== $v && null !== $v; } );
	return array_merge( vf_section_defaults( $section ), $args );
}

/** Text area → lines. With $split, "Title | text" → [ 'Title', 'text' ]. */
function vf_lines( $text, $split = false ) {
	$out = array();
	foreach ( preg_split( '/\r?\n/', (string) $text ) as $l ) {
		$l = trim( $l );
		if ( '' === $l ) {
			continue;
		}
		$out[] = $split ? array_map( 'trim', array_pad( explode( '|', $l, 2 ), 2, '' ) ) : $l;
	}
	return $out;
}

/** "Publish anywhere" tabs: [ label, help text, code ]. Used by the Home section and the graphic "embed-code". */
function vf_embed_tabs() {
	return apply_filters(
		'vf_embed_tabs',
		array(
			array( 'Block editor', 'Add the VuloForm block to any page or post and pick a published form from the list.', '<!-- wp:vuloform/form {"formId":12} /-->' ),
			array( 'Shortcode', "Paste it into a page, a text widget, or any page builder's shortcode element.", '[vuloform id="12"]' ),
			array( 'PHP', 'Render a form straight from your theme or plugin.', '<?php echo vuloform_render( 12 ); ?>' ),
			array( 'Another website', "Landing page, shop or static site — not an iframe, so it inherits the host page's fonts and width.", "<div data-vuloform=\"12\"></div>\n<script src=\"https://example.com/wp-content/plugins/vuloform/\n  assets/js/public/vuloform-embed.min.js\n  ?site=https%3A%2F%2Fexample.com%2Fwp-json\" async></script>" ),
		)
	);
}
