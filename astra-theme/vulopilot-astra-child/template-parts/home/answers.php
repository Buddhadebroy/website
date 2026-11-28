<?php
/**
 * Home: "Every problem deserves a next step" – five question cards.
 *
 * @package VuloPilot
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = 'Meet VuloPilot';
$title   = 'Every problem deserves <span class="text-sage">a next step.</span>';
$intro   = 'One WordPress plugin that understands your entire website, tells you what matters, and helps you act – automatically.';
$card    = array(
	'kicker' => 'Your question',
	'title'  => 'becomes a clear next step.',
	'flow'   => array( 'Question', 'Answer', 'Action' ),
);
$labels  = array( 'does' => 'What VuloPilot does', 'where' => 'Where it checks' );

// question, icon, what it does, "where" icon, where, note, colour
$items = array(
	array( 'Is my website okay?', 'sparkles', 'Checks your whole site and tells you what is fine and what needs attention.', 'shield-check', 'Security & health', 'Old software, risky settings and broken links.', 'var(--rose)' ),
	array( 'Why did my visitors drop?', 'rotate-ccw-clock', 'Finds the likely cause and shows you the exact pages.', 'search-check', 'Search · Content · Performance', 'Spot critical issues and make your site safer.', 'var(--sage)' ),
	array( 'What do I fix first?', 'link-2', 'Puts the important things first, so you know where to start.', 'list-ordered', 'All six areas, ranked', 'By what matters most.', 'var(--lilac)' ),
	array( 'Did my fix work?', 'layout-grid', 'Checks again and tells you if the problem is gone.', 'repeat', 'Automation', 'Checks that run on their own, so nothing is forgotten.', 'var(--rose)' ),
	array( 'Does AI know my business?', 'bot', 'Shows how clearly tools like ChatGPT and Gemini understand what you do.', 'radar', 'AI Visibility', 'And how to improve it.', 'var(--lilac)' ),
);
?>
<section class="px-5 py-16">
	<div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[0.8fr_1.2fr]">
		<div class="lg:sticky lg:top-32 lg:self-start">
			<?php vp_eyebrow( $eyebrow ); ?>
			<h2 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-5xl"><?php echo wp_kses_post( $title ); ?></h2>
			<p class="mt-5 text-lg text-muted"><?php echo esc_html( $intro ); ?></p>
			<div class="relative mt-8 hidden overflow-hidden rounded-sm bg-ink p-6 text-bg lg:block">
				<div class="vp-drift absolute -right-8 -top-8 h-36 w-36 rounded-full bg-sage blur-2xl" style="--dx:30px;--dd:8s"></div>
				<div class="relative">
					<div class="text-xs font-semibold uppercase tracking-widest text-white/50"><?php echo esc_html( $card['kicker'] ); ?></div>
					<div class="mt-2 text-xl font-semibold"><?php echo esc_html( $card['title'] ); ?></div>
					<div class="mt-4 flex items-center gap-2 text-sm text-white/70">
						<span class="rounded-sm bg-white/10 px-3 py-1"><?php echo esc_html( $card['flow'][0] ); ?></span>
						<?php vp_i( 'arrow-right', 14 ); ?>
						<span class="rounded-sm bg-white/10 px-3 py-1"><?php echo esc_html( $card['flow'][1] ); ?></span>
						<?php vp_i( 'arrow-right', 14 ); ?>
						<span class="rounded-sm bg-flare px-3 py-1 font-semibold text-white"><?php echo esc_html( $card['flow'][2] ); ?></span>
					</div>
				</div>
			</div>
		</div>

		<div class="space-y-4">
			<?php foreach ( $items as $i => $it ) : list( $q, $icon, $does, $wicon, $where, $note, $c ) = $it; ?>
				<div <?php vp_m( 'o:0 y:28 d:.05 t:.6' ); ?>>
					<article class="vp-hover group relative overflow-hidden rounded-sm border border-line bg-surface p-6 shadow-[0_20px_50px_-32px_rgba(0,41,145,.3)] hover:border-sage/40" style="--hy:-4px">
						<span class="absolute inset-y-0 left-0 w-1 origin-top scale-y-0 transition-transform duration-500 group-hover:scale-y-100" style="background:<?php echo esc_attr( $c ); ?>"></span>
						<div class="flex items-center gap-4">
							<span class="grid h-12 w-12 shrink-0 place-items-center rounded-sm transition-transform duration-300 group-hover:rotate-[-6deg] group-hover:scale-110" style="<?php echo esc_attr( vp_tint_style( $c, 12 ) ); ?>"><?php vp_i( $icon, 22 ); ?></span>
							<h3 class="text-2xl font-semibold tracking-tight"><?php echo esc_html( $q ); ?></h3>
							<span class="ml-auto font-mono text-sm text-line">0<?php echo (int) $i + 1; ?></span>
						</div>
						<div class="mt-5 grid gap-4 sm:grid-cols-2">
							<div class="rounded-sm bg-bg/70 p-4">
								<div class="text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $labels['does'] ); ?></div>
								<p class="mt-1.5 text-[15px]"><?php echo esc_html( $does ); ?></p>
							</div>
							<div class="rounded-sm p-4" style="background:<?php echo esc_attr( vp_tint( $c, 7 ) ); ?>">
								<div class="text-[11px] font-semibold uppercase tracking-widest text-muted"><?php echo esc_html( $labels['where'] ); ?></div>
								<div class="mt-1.5 flex items-center gap-2 font-semibold" style="color:<?php echo esc_attr( $c ); ?>"><?php vp_i( $wicon, 16 ); ?><?php echo esc_html( $where ); ?></div>
								<p class="mt-0.5 text-sm text-muted"><?php echo esc_html( $note ); ?></p>
							</div>
						</div>
					</article>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
