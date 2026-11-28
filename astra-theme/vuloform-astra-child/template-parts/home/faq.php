<?php
/**
 * Home › FAQ (port of components/Faq.tsx). Accordion: .vf-accordion (one open at a time, first open).
 * The answers are also printed as FAQPage schema.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$faqs = apply_filters(
	'vf_home_faqs',
	array(
		array( 'Is there a limit on forms or submissions?', 'No. Build as many forms as you like and collect as many submissions as you get.' ),
		array( 'Do I need another plugin?', 'No. VuloForm works by itself. VuloMail is optional — it makes email delivery more reliable and is required for SMS notifications.' ),
		array( "Can I put a form on a site that isn't WordPress?", "Yes. Copy the snippet under “On another website” on the form's Share tab and paste it into that page's HTML. Your WordPress site must stay online, and the form needs JavaScript on the other site." ),
		array( 'Is there a CAPTCHA?', 'No. Forms are protected by a hidden trap field, a minimum fill time and a rate limit. Developers can connect another anti-spam service with the vuloform_spam_check filter.' ),
		array( 'Can conditional logic be bypassed?', "No. The rules are checked again on your server. A field hidden by its rules is never stored, and a required field can't be skipped by turning off JavaScript." ),
		array( 'Are uploaded files public?', "They're stored under long random names in a folder that blocks direct access on Apache and LiteSpeed. Administrators download them through WordPress. On nginx, ask your host to block wp-content/uploads/vuloform/." ),
		array( 'Does “Handed off” mean the email was delivered?', "It means your site's mail system accepted it. VuloForm can't see whether it reached the inbox — if mail goes missing, check spam and consider an SMTP plugin such as VuloMail." ),
		array( 'What is removed when I delete the plugin?', 'Nothing, by default, so a reinstall picks up where you left off. To remove everything, choose “Delete everything” under VuloForm › Settings first.' ),
	)
);

vf_jsonld(
	array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => array_map(
			function ( $f ) {
				return array( '@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ) );
			},
			$faqs
		),
	)
);
?>
<section id="faq" class="py-28 sm:py-36">
	<div class="mx-auto grid max-w-6xl gap-12 px-4 sm:px-6 lg:grid-cols-[0.8fr_1.2fr]">
		<div class="vf-reveal">
			<?php echo vf_eyebrow( 'FAQ' ); // phpcs:ignore ?>
			<h2 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl">Good questions.</h2>
			<p class="mt-5 text-lg text-ink-soft">Straight answers, no small print.</p>
		</div>
		<div class="vf-accordion divide-y divide-line border-y border-line">
			<?php foreach ( $faqs as $i => $f ) : ?>
			<div class="vf-acc-item<?php echo 0 === $i ? ' is-open' : ''; ?>">
				<button type="button" class="vf-acc-q flex w-full items-center justify-between gap-6 py-6 text-left text-lg font-semibold text-ink" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>">
					<?php echo esc_html( $f[0] ); ?>
					<span class="vf-acc-icon grid h-9 w-9 shrink-0 place-items-center rounded-full text-xl">+</span>
				</button>
				<div class="vf-acc-a"><div><p class="max-w-xl pb-6 leading-relaxed text-ink-soft"><?php echo esc_html( $f[1] ); ?></p></div></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
