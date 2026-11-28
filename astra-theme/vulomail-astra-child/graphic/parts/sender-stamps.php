<?php
/**
 * Graphic: Before / after sender stamps (Email)
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;
?>
<div data-vm-parallax="0.15">
	<div class="vm:relative">
		<div class="vm:stamp vm:rotate-[-3deg] vm:bg-paper vm:p-6 vm:text-ink">
			<p class="vm:eyebrow vm:text-ink-mute">Before</p>
			<p class="vm:mt-2 vm:font-mono vm:text-sm vm:line-through vm:decoration-red vm:decoration-2">WordPress &lt;wordpress@juniper.example&gt;</p>
		</div>
		<div class="vm:stamp vm:relative vm:-mt-3 vm:ml-8 vm:rotate-[2deg] vm:bg-surface vm:p-6 vm:text-ink vm:shadow-2xl">
			<p class="vm:eyebrow vm:text-green">After</p>
			<p class="vm:mt-2 vm:font-mono vm:text-sm">Juniper &amp; Co. &lt;hello@juniper.example&gt;</p>
		</div>
	</div>
</div>
