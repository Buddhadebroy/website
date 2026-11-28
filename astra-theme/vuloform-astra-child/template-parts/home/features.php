<?php
/**
 * Home › Everything included: bento cards (port of components/Features.tsx).
 * Effects: vf-parallax on the two blobs, vf-stagger on the grid, vf-lift on each card.
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

/** Card shell: $cls = background + column span. */
$card = function ( $cls, $title, $body, $inner = '', $delay = 0 ) {
	return '<div class="vf-reveal group relative overflow-hidden rounded-[28px] p-7 ' . esc_attr( $cls ) . '" style="--vf-delay:' . esc_attr( $delay ) . 's"><div class="vf-lift h-full">'
		. '<h3 class="text-xl font-bold">' . esc_html( $title ) . '</h3><p class="mt-2 max-w-md leading-relaxed text-ink-soft">' . esc_html( $body ) . '</p>' . $inner . '</div></div>';
};

// The small visuals inside the cards are graphics (graphic/parts/feature-*.php), so Kadence patterns can reuse them.
$chips = '';
foreach ( array( 'Email', 'Phone', 'Date', 'Time', 'Upload', 'Address', 'Consent', 'Calc', 'HTML', '…' ) as $f ) {
	$chips .= '<span class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium">' . esc_html( $f ) . '</span>';
}
?>
<section id="features" class="relative isolate py-28 sm:py-36">
	<div class="vf-parallax absolute top-40 -left-20 -z-10 h-72 w-72 rounded-full bg-mint-soft blur-3xl" style="--vf-move:60px"></div>
	<div class="vf-parallax absolute right-0 bottom-20 -z-10 h-80 w-80 rounded-full bg-peach-soft blur-3xl" style="--vf-move:140px"></div>

	<div class="mx-auto max-w-6xl px-4 sm:px-6">
		<div class="vf-reveal max-w-2xl">
			<?php echo vf_eyebrow( 'Everything included', 'navy' ); // phpcs:ignore ?>
			<h2 class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl">Built-in, not bolted on.</h2>
			<p class="mt-5 text-lg text-ink-soft">No add-ons to buy, no tiers to unlock. Every feature ships in the free plugin.</p>
		</div>

		<div class="mt-14 grid gap-5 md:grid-cols-6">
			<?php
			// phpcs:disable WordPress.Security.EscapeOutput
			echo $card( 'bg-white md:col-span-4', 'Submissions, right in WordPress', 'Search, filter by status and date, mark as read, new or spam. Export to CSV. Optional automatic deletion after a set number of days.', vf_graphic( 'feature-submissions' ) );
			echo $card( 'bg-navy text-white md:col-span-2 [&_p]:text-white/70', '21 field types', 'From short text to file uploads, addresses, hidden fields and page breaks.', '<div class="mt-6 flex flex-wrap gap-1.5">' . $chips . '</div>', 0.1 );
			echo $card( 'bg-butter-soft md:col-span-2', 'Notifications', 'Email your team with placeholders for any answer, send visitors a confirmation, or text yourself via VuloMail.', vf_graphic( 'feature-notify' ) );
			echo $card( 'bg-white md:col-span-2', 'Signed webhooks', 'Send each submission as JSON over HTTPS. Background delivery, two retries, optional secret — every attempt logged.', vf_graphic( 'feature-webhooks' ), 0.08 );
			echo $card( 'bg-mint-soft md:col-span-2', 'Spam? Handled.', 'A hidden trap field, a minimum fill time and a per-visitor rate limit. No CAPTCHA, no puzzles, no third parties.', '<div class="mt-6 flex gap-2 text-xs font-semibold"><span class="rounded-full bg-white px-3 py-1.5">🍯 Honeypot</span><span class="rounded-full bg-white px-3 py-1.5">⏱️ Fill time</span><span class="rounded-full bg-white px-3 py-1.5">🚦 Rate limit</span></div>', 0.16 );
			echo $card( 'bg-sky-soft md:col-span-3', 'Safe file uploads', 'Only a fixed list of safe types. Content checked against the extension. Stored under random names in a protected folder.', vf_graphic( 'feature-upload' ) );
			echo $card( 'bg-lilac-soft md:col-span-3', 'Your theme, your style', 'Forms follow your theme. Per form, set label position, spacing, accent and text colour, size, corner roundness and button alignment.', vf_graphic( 'feature-colors' ), 0.08 );
			echo $card( 'bg-white md:col-span-6', 'Accessible by default — and featherweight', 'Plain HTML with labels on every control, fieldsets for groups, errors linked to fields and announced to screen readers, full keyboard use. The public form ships no React and no jQuery, and works without JavaScript on your own site.', '<div class="mt-6 flex flex-wrap gap-2 text-sm font-medium"><span class="rounded-full border border-line px-4 py-2">♿ Screen-reader friendly</span><span class="rounded-full border border-line px-4 py-2">⌨️ Keyboard first</span><span class="rounded-full border border-line px-4 py-2">🪶 No React / jQuery on the front end</span><span class="rounded-full border border-line px-4 py-2">🔌 Works without JS</span></div>' );
			// phpcs:enable
			?>
		</div>
	</div>
</section>
