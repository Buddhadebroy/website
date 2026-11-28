<?php
/**
 * Pieces reused by several graphic parts: pills, the live delivery log, the phone, the postmark, code cards.
 * Markup and classes match the original React components; behaviour is in assets/js (data-vm-* attributes).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

/** <Pill> */
function vm_pill( $inner, $tone = 'ink' ) {
	$t = array(
		'ink'   => 'vm:bg-paper-deep vm:text-ink-soft',
		'red'   => 'vm:bg-red-soft vm:text-red',
		'blue'  => 'vm:bg-blue-soft vm:text-blue',
		'green' => 'vm:bg-green-soft vm:text-green',
		'amber' => 'vm:bg-amber-soft vm:text-amber-ink',
	);
	return '<span class="vm:inline-flex vm:items-center vm:gap-1.5 vm:rounded-full vm:px-2.5 vm:py-1 vm:text-xs vm:font-medium ' . $t[ $tone ] . '">' . $inner . '</span>';
}

/** <StatusPill> */
function vm_status_pill( $s ) {
	if ( 'sent' === $s ) {
		return vm_pill( vm_icon( 'check', 'vm:h-3 vm:w-3' ) . ' Sent', 'green' );
	}
	if ( 'backup' === $s ) {
		return vm_pill( vm_icon( 'rotate-cw', 'vm:h-3 vm:w-3' ) . ' Sent via backup', 'blue' );
	}
	return vm_pill( vm_icon( 'x', 'vm:h-3 vm:w-3' ) . ' Failed', 'red' );
}

/** The demo feed shown in every live log. */
function vm_log_feed() {
	return array(
		array( 'subject' => 'Password reset', 'provider' => 'Mailgun', 'status' => 'sent', 'source' => 'core', 'channel' => 'email' ),
		array( 'subject' => 'Order #1042 received', 'provider' => 'SMTP', 'status' => 'backup', 'source' => 'woocommerce', 'channel' => 'email' ),
		array( 'subject' => 'Text message', 'provider' => 'Twilio', 'status' => 'sent', 'source' => 'vulomail-alert-wc_new_order', 'channel' => 'sms' ),
		array( 'subject' => 'New comment on “Spring planting”', 'provider' => 'Mailgun', 'status' => 'sent', 'source' => 'core', 'channel' => 'email' ),
		array( 'subject' => 'Your receipt', 'provider' => 'Mailgun', 'status' => 'sent', 'source' => 'woocommerce', 'channel' => 'email' ),
		array( 'subject' => 'Contact form enquiry', 'provider' => 'SMTP', 'status' => 'backup', 'source' => 'my-forms', 'channel' => 'email' ),
		array( 'subject' => 'Text message', 'provider' => 'Vonage', 'status' => 'sent', 'source' => 'vulomail-alert-wc_customer_completed', 'channel' => 'sms' ),
	);
}

function vm_log_row( $r ) {
	$icon = 'email' === $r['channel'] ? vm_icon( 'mail', 'vm:h-4 vm:w-4' ) : vm_icon( 'message-square-text', 'vm:h-4 vm:w-4' );
	return '<li class="vm:flex vm:items-center vm:gap-3 vm:border-b vm:border-line/70 vm:px-5 vm:py-3 vm:last:border-0">'
		. '<span class="vm:grid vm:h-8 vm:w-8 vm:shrink-0 vm:place-items-center vm:rounded-lg ' . ( 'email' === $r['channel'] ? 'vm:bg-paper-deep' : 'vm:bg-amber-soft' ) . '">' . $icon . '</span>'
		. '<span class="vm:min-w-0 vm:flex-1"><span class="vm:block vm:truncate vm:text-[0.83rem] vm:font-medium">' . esc_html( $r['subject'] ) . '</span>'
		. '<span class="vm:block vm:truncate vm:font-mono vm:text-[0.68rem] vm:text-ink-mute">' . esc_html( $r['provider'] . ' · ' . $r['source'] ) . '</span></span>'
		. vm_status_pill( $r['status'] ) . '</li>';
}

/** <LiveLog rows> : a new entry slides in every 2.4 s. */
function vm_live_log( $rows = 4 ) {
	$feed = vm_log_feed();
	$html = '<div class="vm:card vm:overflow-hidden" data-vm-livelog="' . (int) $rows . '">'
		. '<div class="vm:flex vm:items-center vm:justify-between vm:border-b vm:border-line vm:px-5 vm:py-3.5"><div class="vm:flex vm:items-center vm:gap-2">'
		. '<span class="vm:relative vm:flex vm:h-2 vm:w-2"><span class="vm:absolute vm:inline-flex vm:h-full vm:w-full vm:animate-ping vm:rounded-full vm:bg-green vm:opacity-60"></span><span class="vm:relative vm:inline-flex vm:h-2 vm:w-2 vm:rounded-full vm:bg-green"></span></span>'
		. '<span class="vm:text-sm vm:font-medium">Delivery log</span></div><span class="vm:font-mono vm:text-[0.7rem] vm:text-ink-mute">VuloMail → Logs</span></div>'
		. '<ul class="vm:relative">';
	for ( $k = 0; $k < $rows; $k++ ) {
		$idx   = ( 0 - $k + count( $feed ) * 100 ) % count( $feed );
		$html .= vm_log_row( $feed[ $idx ] );
	}
	$html .= '</ul><template>';
	foreach ( $feed as $r ) {
		$html .= vm_log_row( $r );
	}
	return $html . '</template></div>';
}

/** <Postmark> rotating circular stamp. */
function vm_postmark( $size = 180, $label = 'Delivered', $class = '' ) {
	static $n = 0;
	$n++;
	$id   = 'vm-pm-' . $n;
	$text = strtoupper( $label . ' · VuloMail · free forever · ' );
	return '<div class="vm:relative ' . esc_attr( $class ) . '" style="width:' . (int) $size . 'px;height:' . (int) $size . 'px" aria-hidden="true">'
		. '<svg viewBox="0 0 200 200" class="vm:h-full vm:w-full vm:animate-spin-slow vm:text-ink/70"><defs><path id="' . $id . '" d="M100,100 m-78,0 a78,78 0 1,1 156,0 a78,78 0 1,1 -156,0"></path></defs>'
		. '<circle cx="100" cy="100" r="96" fill="none" stroke="currentColor" stroke-width="1.5"></circle>'
		. '<circle cx="100" cy="100" r="62" fill="none" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 5"></circle>'
		. '<text font-size="13" letter-spacing="3.5" fill="currentColor" font-family="var(--font-geist-mono)"><textPath href="#' . $id . '">' . esc_html( $text . $text ) . '</textPath></text></svg>'
		. '<div class="vm:absolute vm:inset-0 vm:grid vm:place-items-center"><svg viewBox="0 0 40 40" class="vm:h-[26%] vm:w-[26%]"><rect x="2" y="7" width="36" height="26" rx="6" fill="var(--red)"></rect>'
		. '<path d="M6 12 L20 23 L34 12" fill="none" stroke="var(--paper)" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"></path></svg></div></div>';
}

/** <Phone> with messages that pop in when it scrolls into view. */
function vm_phone( $messages, $title = 'Messages' ) {
	$html = '<div class="vm:relative vm:mx-auto vm:w-[290px] vm:rounded-[3rem] vm:border-[10px] vm:border-ink vm:bg-ink vm:shadow-[0_50px_80px_-40px_rgba(19,20,27,0.7)]" data-vm-phone>'
		. '<div class="vm:absolute vm:left-1/2 vm:top-2 vm:z-10 vm:h-6 vm:w-24 vm:-translate-x-1/2 vm:rounded-full vm:bg-ink"></div>'
		. '<div class="vm:h-[560px] vm:overflow-hidden vm:rounded-[2.3rem] vm:bg-paper">'
		. '<div class="vm:border-b vm:border-line vm:bg-surface/80 vm:px-5 vm:pb-3 vm:pt-10 vm:text-center vm:text-sm vm:font-semibold">' . esc_html( $title ) . '</div>'
		. '<div class="vm:space-y-3 vm:p-4">';
	foreach ( $messages as $i => $m ) {
		$html .= '<div class="vm:max-w-[88%] vm:origin-bottom-left" data-vm-msg="' . (int) $i . '" style="opacity:0;transform:translateY(20px) scale(0.9)">'
			. '<p class="vm:mb-1 vm:pl-1 vm:text-[0.65rem] vm:text-ink-mute">' . esc_html( $m[0] ) . '</p>'
			. '<p class="vm:rounded-2xl vm:rounded-bl-md vm:bg-surface vm:px-3.5 vm:py-2.5 vm:text-[0.82rem] vm:leading-snug vm:shadow-sm">' . esc_html( $m[1] ) . '</p></div>';
	}
	$html .= '<div class="vm:flex vm:w-14 vm:gap-1 vm:rounded-2xl vm:bg-surface vm:px-3 vm:py-3 vm:shadow-sm" data-vm-typing="' . count( $messages ) . '" style="opacity:0">'
		. '<span class="vm-typing-dot vm:h-1.5 vm:w-1.5 vm:rounded-full vm:bg-ink-mute"></span><span class="vm-typing-dot vm:h-1.5 vm:w-1.5 vm:rounded-full vm:bg-ink-mute"></span><span class="vm-typing-dot vm:h-1.5 vm:w-1.5 vm:rounded-full vm:bg-ink-mute"></span></div>';
	return $html . '</div></div></div>';
}

/** <Code> card with traffic-light dots. */
function vm_code( $code, $title = '' ) {
	return '<div class="vm:overflow-hidden vm:rounded-2xl vm:border vm:border-code-line vm:bg-code-bg vm:text-code-text vm:shadow-[0_30px_60px_-30px_rgba(19,20,27,0.6)]">'
		. '<div class="vm:flex vm:items-center vm:gap-2 vm:border-b vm:border-white/5 vm:px-4 vm:py-3"><span class="vm:h-2.5 vm:w-2.5 vm:rounded-full vm:bg-[var(--traffic-red)]"></span><span class="vm:h-2.5 vm:w-2.5 vm:rounded-full vm:bg-[var(--traffic-amber)]"></span><span class="vm:h-2.5 vm:w-2.5 vm:rounded-full vm:bg-[var(--traffic-green)]"></span>'
		. ( $title ? '<span class="vm:ml-3 vm:font-mono vm:text-xs vm:text-white/40">' . esc_html( $title ) . '</span>' : '' ) . '</div>'
		. '<pre class="vm:code-scroll vm:overflow-x-auto vm:p-5 vm:font-mono vm:text-[0.8rem] vm:leading-relaxed"><code>' . esc_html( $code ) . '</code></pre></div>';
}

/** <Switch> toggle (bg-ink when on). */
function vm_toggle( $on, $label, $on_class = 'vm:bg-ink', $knob = 'vm:bg-paper', $extra = '' ) {
	return '<button type="button" aria-label="' . esc_attr( $label ) . '" aria-pressed="' . ( $on ? 'true' : 'false' ) . '" data-vm-toggle data-on="' . esc_attr( $on_class ) . '" class="vm:relative vm:h-6 vm:w-11 vm:shrink-0 vm:rounded-full vm:transition-colors vm:duration-300 ' . ( $on ? $on_class : 'vm:bg-line-strong' ) . ' ' . $extra . '">'
		. '<span class="vm-knob vm:absolute vm:top-1 vm:h-4 vm:w-4 vm:rounded-full ' . $knob . '" style="left:' . ( $on ? '24px' : '4px' ) . '"></span></button>';
}

/* ---------------------------------------------------------------- comparison helpers (src/components/compare.tsx) */

function vm_avail_badge( $v, $us = false ) {
	$s = array(
		'yes'  => array( 'Free', 'check', 'vm:bg-green-soft vm:text-green' ),
		'paid' => array( 'Paid plan', 'lock', 'vm:bg-amber-soft vm:text-amber-ink' ),
		'no'   => array( 'No', 'minus', 'vm:bg-paper-deep vm:text-ink-mute' ),
		'na'   => array( 'Not listed', 'circle-help', 'vm:bg-paper-deep vm:text-ink-mute' ),
	)[ $v ];
	return '<span class="vm:inline-flex vm:items-center vm:gap-1.5 vm:whitespace-nowrap vm:rounded-full vm:px-2.5 vm:py-1 vm:text-xs vm:font-medium ' . $s[2] . '">' . vm_icon( $s[1], 'vm:h-3.5 vm:w-3.5' ) . esc_html( $us && 'yes' === $v ? 'Included' : $s[0] ) . '</span>';
}

function vm_cell_view( $cell, $us = false ) {
	if ( ! $cell ) {
		return vm_avail_badge( 'na' );
	}
	return '<div>' . vm_avail_badge( $cell['v'], $us ) . ( ! empty( $cell['note'] ) ? '<p class="vm:mt-1.5 vm:text-[0.8rem] vm:leading-snug vm:text-ink-mute">' . esc_html( $cell['note'] ) . '</p>' : '' ) . '</div>';
}

function vm_stars( $rating, $reviews ) {
	if ( ! $reviews ) {
		return '<span class="vm:text-ink-mute">No reviews yet</span>';
	}
	return '<span class="vm:inline-flex vm:items-center vm:gap-1.5">' . vm_icon( 'star', 'vm:h-4 vm:w-4 vm:fill-star vm:text-star' ) . '<span class="vm:font-semibold">' . esc_html( vm_star_rating( $rating ) ) . '</span><span class="vm:text-ink-mute">/ 5 · ' . esc_html( number_format( $reviews ) ) . ' reviews</span></span>';
}

function vm_compare_card( $c ) {
	return '<a href="' . esc_url( vm_url( '/compare/' . $c['slug'] ) ) . '" class="vm:card vm:group vm:flex vm:h-full vm:flex-col vm:p-6 vm:transition-colors vm:hover:border-ink/40">'
		. '<p class="vm:eyebrow vm:text-ink-mute">' . esc_html( $c['model'] ) . '</p>'
		. '<h3 class="vm:display vm:mt-3 vm:text-2xl">VuloMail vs ' . esc_html( $c['name'] ) . '</h3>'
		. '<p class="vm:mt-3 vm:flex-1 vm:text-sm vm:leading-relaxed vm:text-ink-soft">' . esc_html( $c['oneLiner'] ) . '</p>'
		. '<div class="vm:mt-5 vm:flex vm:items-center vm:justify-between vm:text-sm"><span class="vm:text-ink-mute">' . esc_html( vm_format_installs( $c['stats']['activeInstalls'] ) ) . ' installs</span>'
		. '<span class="vm:inline-flex vm:items-center vm:gap-1 vm:font-medium vm:text-blue">Compare ' . vm_icon( 'arrow-up-right', 'vm:h-4 vm:w-4 vm:transition-transform vm:group-hover:-translate-y-0.5 vm:group-hover:translate-x-0.5' ) . '</span></div></a>';
}

function vm_sources( $list, $date ) {
	$h = '<div><p class="vm:text-sm vm:text-ink-soft">Checked on <strong class="vm:font-semibold vm:text-ink">' . esc_html( $date ) . '</strong>. Plugin numbers come from the WordPress.org plugin directory; features and prices come from each vendor&#039;s own listing and pricing pages. Products change, so check the vendor&#039;s current page before you decide.</p><ul class="vm:mt-5 vm:grid vm:gap-2 vm:sm:grid-cols-2">';
	foreach ( $list as $s ) {
		$h .= '<li><a href="' . esc_url( $s['url'] ) . '" target="_blank" rel="noopener noreferrer" class="vm:group vm:inline-flex vm:items-center vm:gap-1.5 vm:text-sm vm:text-blue vm:underline-offset-4 vm:hover:underline">' . esc_html( $s['label'] ) . vm_icon( 'arrow-up-right', 'vm:h-3.5 vm:w-3.5 vm:transition-transform vm:group-hover:-translate-y-0.5 vm:group-hover:translate-x-0.5' ) . '</a></li>';
	}
	return $h . '</ul></div>';
}
