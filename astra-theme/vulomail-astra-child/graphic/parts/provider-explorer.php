<?php
/**
 * Graphic: Provider explorer (filter, search, expandable cards)
 *
 * Every provider in data/site.json.
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$ps    = vm_providers();
$kinds = array( 'api' => 'Email API', 'preset' => 'SMTP preset', 'smtp' => 'Any SMTP', 'sms' => 'SMS' );
$cnt   = function ( $k ) use ( $ps ) {
	return count( array_filter( $ps, function ( $p ) use ( $k ) { return 'smtp' === $k ? in_array( $p['kind'], array( 'smtp', 'preset' ), true ) : $p['kind'] === $k; } ) );
};
$opts  = array( 'all' => 'All · ' . count( $ps ), 'api' => 'Email APIs · ' . $cnt( 'api' ), 'smtp' => 'SMTP · ' . $cnt( 'smtp' ), 'sms' => 'SMS · ' . $cnt( 'sms' ) );
?>
<div data-vm-providers>
	<div class="vm:flex vm:flex-wrap vm:items-center vm:justify-between vm:gap-4">
		<div class="vm:max-w-full vm:overflow-x-auto">
			<div class="vm:inline-flex vm:rounded-full vm:border vm:border-line vm:bg-surface vm:p-1" data-vm-seg>
				<?php foreach ( $opts as $k => $l ) : ?>
					<button type="button" data-vm-filter="<?php echo esc_attr( $k ); ?>" class="vm:relative vm:rounded-full vm:px-4 vm:py-2 vm:text-sm vm:transition-colors <?php echo 'all' === $k ? 'vm:text-paper' : 'vm:text-ink-soft vm:hover:text-ink'; ?>"><?php echo 'all' === $k ? '<span class="vm-seg-bg vm:absolute vm:inset-0 vm:rounded-full vm:bg-ink"></span>' : ''; ?><span class="vm:relative"><?php echo esc_html( $l ); ?></span></button>
				<?php endforeach; ?>
			</div>
		</div>
		<label class="vm:relative vm:w-full vm:sm:w-64">
			<?php echo vm_icon( 'search', 'vm:pointer-events-none vm:absolute vm:left-4 vm:top-1/2 vm:h-4 vm:w-4 vm:-translate-y-1/2 vm:text-ink-mute' ); // phpcs:ignore ?>
			<span class="vm:sr-only">Search providers</span>
			<input type="search" data-vm-search placeholder="Search providers" class="vm:h-11 vm:w-full vm:rounded-full vm:border vm:border-line vm:bg-surface vm:pl-10 vm:pr-4 vm:text-sm vm:outline-none vm:transition-colors vm:placeholder:text-ink-mute vm:focus:border-ink">
		</label>
	</div>
	<div class="vm:mt-8 vm:grid vm:gap-4 vm:sm:grid-cols-2 vm:lg:grid-cols-3 vm:xl:grid-cols-4" data-vm-cards>
		<?php foreach ( $ps as $p ) : ?>
			<button type="button" aria-expanded="false" data-kind="<?php echo esc_attr( $p['kind'] ); ?>" data-name="<?php echo esc_attr( strtolower( $p['name'] ) ); ?>" class="vm:card vm:group vm:relative vm:h-fit vm:overflow-hidden vm:p-5 vm:text-left">
				<div class="vm:flex vm:items-start vm:justify-between">
					<span class="vm:grid vm:h-10 vm:w-10 vm:place-items-center vm:rounded-2xl <?php echo 'email' === $p['channel'] ? 'vm:bg-blue-soft vm:text-blue' : 'vm:bg-amber-soft vm:text-amber-ink'; ?>"><?php echo vm_icon( 'email' === $p['channel'] ? 'mail' : 'message-square-text', 'vm:h-5 vm:w-5' ); // phpcs:ignore ?></span>
					<span class="vm:eyebrow vm:text-ink-mute"><?php echo esc_html( $kinds[ $p['kind'] ] ); ?></span>
				</div>
				<h3 class="vm:display vm:mt-5 vm:text-2xl"><?php echo esc_html( $p['name'] ); ?></h3>
				<p class="vm:mt-2 vm:text-sm vm:leading-relaxed vm:text-ink-soft"><?php echo esc_html( $p['blurb'] ); ?></p>
				<div class="vm-prov-more vm:overflow-hidden" hidden>
					<div class="vm:mt-5 vm:border-t vm:border-dashed vm:border-line-strong vm:pt-5">
						<p class="vm:eyebrow vm:text-ink-mute">What you need</p>
						<ul class="vm:mt-3 vm:flex vm:flex-wrap vm:gap-2"><?php foreach ( $p['needs'] as $n ) : ?><li class="vm:rounded-full vm:bg-paper-deep vm:px-3 vm:py-1 vm:font-mono vm:text-xs"><?php echo esc_html( $n ); ?></li><?php endforeach; ?></ul>
						<?php if ( ! empty( $p['where'] ) ) : ?><p class="vm:mt-4 vm:text-xs vm:text-ink-mute">Find it: <?php echo esc_html( $p['where'] ); ?></p><?php endif; ?>
						<?php if ( ! empty( $p['note'] ) ) : ?><p class="vm:mt-3 vm:rounded-xl vm:bg-paper vm:p-3 vm:text-xs vm:leading-relaxed vm:text-ink-soft"><?php echo esc_html( $p['note'] ); ?></p><?php endif; ?>
					</div>
				</div>
				<span class="vm:mt-5 vm:inline-flex vm:items-center vm:gap-1.5 vm:text-xs vm:font-medium vm:text-ink-soft"><?php echo vm_icon( 'plus', 'vm:h-3.5 vm:w-3.5 vm:transition-transform vm:duration-300' ); // phpcs:ignore ?><span class="vm-prov-label">Details</span></span>
			</button>
		<?php endforeach; ?>
	</div>
	<p class="vm:mt-8 vm:rounded-3xl vm:border vm:border-dashed vm:border-line-strong vm:p-8 vm:text-center vm:text-sm vm:text-ink-soft" data-vm-empty hidden>No provider called “<span></span>”. Any service with SMTP access works through the SMTP connection.</p>
</div>
