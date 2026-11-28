<?php
/**
 * Graphic: How the email plugins split on each feature (Compare)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$cmp     = vm_compare();
$plugins = array_values( array_filter( $cmp['competitors'], function ( $c ) { return 'wsms' !== $c['slug']; } ) );
$rows    = array( array( 'log', 'Log of every email sent' ), array( 'backup', 'Backup connection' ), array( 'failAlerts', 'Alert when an email fails' ), array( 'ses', 'Amazon SES' ), array( 'smsAlerts', 'Text-message (SMS) alerts' ), array( 'wooCustomer', 'Order-update texts to customers' ) );
$seg     = array( 'yes' => array( 'vm:bg-green', 'free' ), 'paid' => array( 'vm:bg-paid', 'paid plan' ), 'no' => array( 'vm:bg-ink/25', 'not offered' ), 'na' => array( 'vm:bg-ink/10', 'not mentioned' ) );
$n       = count( $plugins );
?>
<div class="vm:card vm:divide-y vm:divide-line/70">
	<div class="vm:hidden vm:px-6 vm:py-4 vm:md:grid vm:md:grid-cols-[1fr_2fr_auto] vm:md:items-center vm:md:gap-8">
		<span class="vm:eyebrow vm:text-ink-mute">Feature</span>
		<span class="vm:flex vm:flex-wrap vm:items-center vm:gap-x-4 vm:gap-y-1 vm:text-xs vm:text-ink-mute"><span class="vm:font-semibold vm:text-ink">The <?php echo (int) $n; ?> plugins</span>
			<?php foreach ( $seg as $v ) : ?><span class="vm:inline-flex vm:items-center vm:gap-1.5"><span class="vm:h-2.5 vm:w-2.5 vm:rounded-full <?php echo esc_attr( $v[0] ); ?>"></span><?php echo esc_html( $v[1] ); ?></span><?php endforeach; ?>
		</span>
		<span class="vm:text-sm vm:font-bold">VuloMail</span>
	</div>
	<?php
	foreach ( $rows as $r ) :
		$by   = array();
		foreach ( array_keys( $seg ) as $v ) {
			$by[ $v ] = array_values( array_filter( $plugins, function ( $p ) use ( $r, $v ) { return ( isset( $p['features'][ $r[0] ] ) ? $p['features'][ $r[0] ]['v'] : 'na' ) === $v; } ) );
		}
		$aria = implode( ', ', array_map( function ( $v ) use ( $by, $seg ) { return count( $by[ $v ] ) . ' ' . $seg[ $v ][1]; }, array_keys( $seg ) ) );
		?>
		<div class="vm:grid vm:gap-3 vm:px-6 vm:py-5 vm:md:grid-cols-[1fr_2fr_auto] vm:md:items-center vm:md:gap-8">
			<p class="vm:font-medium"><?php echo esc_html( $r[1] ); ?></p>
			<div>
				<div class="vm:flex vm:h-3 vm:overflow-hidden vm:rounded-full vm:bg-paper-deep" role="img" aria-label="<?php echo esc_attr( $aria ); ?>">
					<?php foreach ( $seg as $v => $sv ) : if ( ! count( $by[ $v ] ) ) { continue; } ?>
						<span title="<?php echo esc_attr( $sv[1] . ': ' . implode( ', ', wp_list_pluck( $by[ $v ], 'name' ) ) ); ?>" class="<?php echo esc_attr( $sv[0] ); ?> vm:border-r-2 vm:border-surface vm:last:border-r-0" style="width:<?php echo esc_attr( (string) ( count( $by[ $v ] ) / $n * 100 ) ); ?>%"></span>
					<?php endforeach; ?>
				</div>
				<p class="vm:mt-2 vm:text-xs vm:leading-relaxed vm:text-ink-mute"><span class="vm:font-semibold vm:text-green"><?php echo count( $by['yes'] ); ?> free</span> · <span class="vm:font-semibold vm:text-amber-ink"><?php echo count( $by['paid'] ); ?> paid</span><?php echo count( $by['paid'] ) ? ' (' . esc_html( implode( ', ', wp_list_pluck( $by['paid'], 'name' ) ) ) . ')' : ''; ?> · <?php echo count( $by['no'] ) + count( $by['na'] ); ?> don&#039;t offer or mention it</p>
			</div>
			<span class="vm:justify-self-start vm:md:justify-self-end"><?php echo vm_avail_badge( $cmp['vulomail']['features'][ $r[0] ]['v'], true ); // phpcs:ignore ?></span>
		</div>
	<?php endforeach; ?>
</div>
