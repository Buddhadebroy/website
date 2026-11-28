<?php
/**
 * Blog index (port of app/blog/page.tsx). Used for the Posts page (Settings → Reading).
 * The newest post is the big gradient card, the rest are cards in two columns.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="vf-main" class="vf-template-main">
	<div class="vf-scope vf-page pt-28 sm:pt-36">
		<div class="mx-auto max-w-6xl px-4 sm:px-6">
			<div class="vf-reveal max-w-3xl">
				<?php echo vf_eyebrow( 'Blog' ); // phpcs:ignore ?>
				<h1 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl">WordPress forms, <span class="text-navy">explained.</span></h1>
				<p class="mt-5 text-lg text-ink-soft">Practical guides and honest comparisons for anyone building forms in WordPress.</p>
			</div>

			<?php echo vf_graphic( 'blog-posts' ); // phpcs:ignore -- graphic/parts/blog-posts.php ?>
		</div>
		<?php echo vf_get_it_banner(); // phpcs:ignore ?>
	</div>
</main>
<?php
get_footer();
