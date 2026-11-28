<?php
/**
 * Builder mock app (three panes). Follows data-step 0-3 from a parent .vf-sticky-steps section;
 * on its own it plays the four steps by itself (data-vf="builder-app").
 * vf-on-N = only shown on step N. State styles: assets/css/vf-site.css → "Builder app".
 *
 * @package VuloForm
 */

defined( 'ABSPATH' ) || exit;

$palette = array( 'Short text', 'Long text', 'Email', 'Phone', 'Number', 'Website', 'Dropdown', 'Single choice', 'Multiple choice', 'Consent', 'Date', 'Time' );
$fields  = array( array( 'Name', 'Name' ), array( 'Email address', 'Email' ), array( 'Topic', 'Dropdown' ), array( 'Your message', 'Long text' ) );
?>
<div class="vf-app relative hidden rounded-[28px] border border-line bg-white p-2 shadow-[0_50px_100px_-50px_rgba(13,26,69,0.5)] sm:block" data-vf="builder-app">
	<div class="flex items-center justify-between rounded-t-[22px] border-b border-line px-4 py-3">
		<div class="flex items-center gap-2">
			<span class="h-3 w-3 rounded-full bg-[#ff8f7a]"></span><span class="h-3 w-3 rounded-full bg-[#ffd36b]"></span><span class="h-3 w-3 rounded-full bg-[#5fd39c]"></span>
			<span class="ml-3 text-sm font-semibold">Contact us</span>
			<span class="vf-app-badge rounded-md px-2 py-0.5 text-[10px] font-bold uppercase transition-colors"><span class="vf-on-0 vf-on-1 vf-on-2">Draft · saved</span><span class="vf-on-3">Published</span></span>
		</div>
		<div class="flex gap-2 text-xs font-semibold">
			<span class="vf-app-preview rounded-lg px-3 py-1.5 transition-colors">Preview</span>
			<span class="vf-app-publish rounded-lg px-3 py-1.5 text-white transition-colors">Publish</span>
		</div>
	</div>

	<div class="grid min-h-[430px] grid-cols-[150px_1fr_170px] gap-2 p-2">
		<!-- palette -->
		<div class="vf-app-side overflow-hidden rounded-2xl bg-cream p-3 transition-opacity duration-500">
			<p class="mb-2 text-[10px] font-bold tracking-wider text-ink-soft uppercase">Fields</p>
			<div class="grid gap-1.5">
				<?php foreach ( $palette as $p ) : ?>
				<span class="<?php echo 'Long text' === $p ? 'vf-app-pick ' : ''; ?>rounded-lg border border-line bg-white px-2.5 py-1.5 text-[11px] font-medium transition-all"><?php echo esc_html( $p ); ?></span>
				<?php endforeach; ?>
				<span class="text-[10px] text-ink-soft">+ 9 more</span>
			</div>
		</div>

		<!-- canvas -->
		<div class="dotted relative rounded-2xl bg-cream/50 p-4">
			<span class="vf-on-0 vf-app-ghost absolute top-0 left-0 z-10 rounded-lg border border-navy bg-white px-2.5 py-1.5 text-[11px] font-medium text-navy shadow-xl">Long text ✋</span>
			<div class="space-y-3">
				<?php foreach ( $fields as $i => $f ) : ?>
				<div class="vf-app-field <?php echo 3 === $i ? 'vf-on-1 vf-on-2 vf-on-3' : ''; ?> <?php echo 2 === $i ? 'vf-app-topic' : ''; ?> rounded-xl border border-line bg-white p-3 transition-all duration-500">
					<p class="mb-1.5 flex items-center justify-between text-xs font-semibold"><?php echo esc_html( $f[0] ); ?><span class="vf-on-0 vf-on-1 text-[9px] font-medium text-ink-soft"><?php echo esc_html( $f[1] ); ?></span></p>
					<div class="rounded-lg bg-cream <?php echo 3 === $i ? 'h-16' : 'h-7'; ?>"></div>
				</div>
				<?php endforeach; ?>
				<div class="vf-on-2 vf-on-3 rounded-xl bg-navy py-2.5 text-center text-xs font-semibold text-white">Send message</div>
			</div>
			<div class="vf-on-3 absolute inset-x-4 bottom-4 rounded-xl border border-mint/40 bg-white p-3 font-mono text-[11px] shadow-xl"><p class="mb-1 font-sans text-[10px] font-bold text-mint uppercase">Share</p>[vuloform id="12"]</div>
		</div>

		<!-- inspector -->
		<div class="vf-app-side rounded-2xl bg-cream p-3 transition-opacity duration-500">
			<p class="mb-2 text-[10px] font-bold tracking-wider text-ink-soft uppercase"><span class="vf-on-1">Topic · Dropdown</span><span class="vf-on-0 vf-on-2 vf-on-3">Form</span></p>
			<div class="vf-on-1 space-y-2 text-[11px]">
				<div class="rounded-lg bg-white p-2">Label: Topic</div>
				<div class="flex items-center justify-between rounded-lg bg-white p-2">Required <span class="h-4 w-7 rounded-full bg-mint p-0.5"><span class="ml-auto block h-3 w-3 rounded-full bg-white"></span></span></div>
				<div class="rounded-lg bg-white p-2">Choices<p class="mt-1 text-ink-soft">Sales · Support · Order</p></div>
				<div class="rounded-lg border border-[#6b45d6]/30 bg-lilac-soft p-2 text-[#6b45d6]">Conditional logic: on</div>
			</div>
			<div class="vf-on-0 vf-on-2 vf-on-3 space-y-2 text-[11px] text-ink-soft">
				<div class="rounded-lg bg-white p-2">Label position: top</div>
				<div class="flex items-center gap-2 rounded-lg bg-white p-2">Accent <span class="h-3.5 w-3.5 rounded-full bg-navy"></span></div>
				<div class="rounded-lg bg-white p-2">Corners: rounded</div>
				<div class="rounded-lg bg-white p-2">Undo · Redo</div>
			</div>
		</div>
	</div>
</div>
