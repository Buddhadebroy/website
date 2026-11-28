<?php
/**
 * Graphic: WordPress vs VuloMail table (home)
 *
 * Props: rows ([[label, wordpress, vulomail], …] with true / false / text).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$rows = isset( $a['rows'] ) ? $a['rows'] : array(
	array( 'Sends through a real email service', false, true ),
	array( 'Backup provider when the first fails', false, true ),
	array( 'A log of every message and why it failed', false, true ),
	array( 'Resend a message from the log', false, 'When content is stored' ),
	array( 'SMS through your own gateway', false, true ),
	array( 'Checks your SPF and DMARC records', false, true ),
	array( 'Costs anything', 'Free', 'Free' ),
);
$cell = function ( $v ) {
	if ( true === $v ) {
		return '<span class="vm:mx-auto vm:grid vm:h-7 vm:w-7 vm:place-items-center vm:rounded-full vm:bg-green-soft vm:text-green">' . vm_icon( 'check', 'vm:h-4 vm:w-4' ) . '</span>';
	}
	if ( false === $v ) {
		return '<span class="vm:mx-auto vm:grid vm:h-7 vm:w-7 vm:place-items-center vm:rounded-full vm:bg-paper-deep vm:text-ink-mute">' . vm_icon( 'minus', 'vm:h-4 vm:w-4' ) . '</span>';
	}
	return '<span class="vm:text-sm vm:text-ink-soft">' . esc_html( $v ) . '</span>';
};
?>
<div class="vm:card vm:mt-14 vm:overflow-hidden" data-vm-reveal data-delay="0.1">
	<div class="vm:grid vm:grid-cols-[1.6fr_1fr_1fr] vm:border-b vm:border-line vm:bg-paper/60 vm:px-5 vm:py-4 vm:text-sm vm:font-medium vm:sm:px-8">
		<span></span><span class="vm:text-center vm:text-ink-soft">WordPress default</span><span class="vm:text-center">VuloMail</span>
	</div>
	<?php foreach ( $rows as $i => $r ) : ?>
		<div data-vm-reveal data-delay="<?php echo esc_attr( (string) ( $i * 0.04 ) ); ?>" data-y="10">
			<div class="vm:grid vm:grid-cols-[1.6fr_1fr_1fr] vm:items-center vm:border-b vm:border-line vm:px-5 vm:py-4 vm:text-center vm:last:border-0 vm:sm:px-8">
				<span class="vm:text-left vm:text-sm vm:sm:text-base"><?php echo esc_html( $r[0] ); ?></span>
				<?php echo $cell( $r[1] ) . $cell( $r[2] ); // phpcs:ignore ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
