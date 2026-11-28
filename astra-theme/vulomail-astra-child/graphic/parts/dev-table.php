<?php
/**
 * Graphic: API reference table (Developers)
 *
 * Props: kind = functions | actions | filters (data/developers.json).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$kind = isset( $a['kind'] ) ? $a['kind'] : 'functions';
$rows = vm_data( 'developers' )[ $kind ];
$cols = array( 'functions' => array( 'Function', 'Returns', 'Notes' ), 'actions' => array( 'Action', 'Arguments' ), 'filters' => array( 'Filter', 'Purpose' ) )[ $kind ];
$grid = 3 === count( $cols ) ? 'vm:sm:grid-cols-[1.4fr_0.6fr_1.6fr]' : 'vm:sm:grid-cols-[1.2fr_1.6fr]';
?>
<div class="vm:card vm:overflow-hidden">
	<div class="vm:hidden vm:gap-4 vm:border-b vm:border-line vm:bg-paper/60 vm:px-6 vm:py-3 vm:text-ink-mute vm:sm:grid <?php echo esc_attr( $grid ); ?>">
		<?php foreach ( $cols as $c ) : ?><span class="vm:eyebrow"><?php echo esc_html( $c ); ?></span><?php endforeach; ?>
	</div>
	<div class="vm:divide-y vm:divide-line">
		<?php foreach ( $rows as $r ) : ?>
			<div class="vm:grid vm:gap-1 vm:px-6 vm:py-4 vm:transition-colors vm:hover:bg-paper vm:sm:items-center vm:sm:gap-4 <?php echo esc_attr( $grid ); ?>">
				<code class="vm:break-all vm:font-mono vm:text-[0.82rem]"><?php echo esc_html( $r[0] ); ?></code>
				<?php if ( 3 === count( $r ) ) : ?><span class="vm:font-mono vm:text-xs vm:text-blue"><?php echo esc_html( $r[1] ); ?></span><?php endif; ?>
				<span class="vm:text-sm vm:text-ink-soft"><?php echo esc_html( $r[ count( $r ) - 1 ] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</div>
