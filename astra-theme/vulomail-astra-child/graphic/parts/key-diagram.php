<?php
/**
 * Graphic: Split-key encryption diagram
 *
 * Props: parallax (speed).
 *
 * @package vulomail
 */

defined( 'ABSPATH' ) || exit;

$box = 'vm:flex vm:items-center vm:gap-3 vm:rounded-2xl vm:border vm:border-line vm:bg-surface vm:p-4';
$out = '<div class="vm:card vm:relative vm:overflow-hidden vm:p-6 vm:sm:p-8" data-vm-keydiag>'
	. '<div class="vm:dot-grid vm:absolute vm:inset-0 vm:opacity-40"></div>'
	. '<div class="vm:relative vm:grid vm:gap-4 vm:sm:grid-cols-2">'
	. '<div data-vm-k="0" class="' . $box . '" style="opacity:0;transform:translateX(-30px)"><span class="vm:grid vm:h-10 vm:w-10 vm:place-items-center vm:rounded-xl vm:bg-blue-soft vm:text-blue">' . vm_icon( 'file-key-2', 'vm:h-5 vm:w-5' ) . '</span><span><span class="vm:block vm:text-sm vm:font-medium">Secret</span><span class="vm:block vm:font-mono vm:text-[0.7rem] vm:text-ink-mute">VULOMAIL_ENCRYPTION_KEY or wp_salt(\'auth\')</span></span></div>'
	. '<div data-vm-k="1" class="' . $box . '" style="opacity:0;transform:translateX(30px)"><span class="vm:grid vm:h-10 vm:w-10 vm:place-items-center vm:rounded-xl vm:bg-amber-soft vm:text-amber-ink">' . vm_icon( 'database', 'vm:h-5 vm:w-5' ) . '</span><span><span class="vm:block vm:text-sm vm:font-medium">Random material</span><span class="vm:block vm:font-mono vm:text-[0.7rem] vm:text-ink-mute">vulomail_key_material option</span></span></div>'
	. '</div>'
	. '<div data-vm-k="2" class="vm:relative vm:mx-auto vm:h-8 vm:w-px vm:origin-top vm:bg-ink" style="transform:scaleY(0)"></div>'
	. '<div data-vm-k="3" class="vm:relative vm:mx-auto vm:flex vm:w-fit vm:items-center vm:gap-3 vm:rounded-2xl vm:bg-ink vm:px-5 vm:py-3 vm:text-paper" style="opacity:0;transform:scale(0.8)">' . vm_icon( 'key-round', 'vm:h-4 vm:w-4' ) . '<span class="vm:font-mono vm:text-sm">key = sha256( secret | material )</span></div>'
	. '<div data-vm-k="4" class="vm:relative vm:mx-auto vm:h-8 vm:w-px vm:origin-top vm:bg-ink" style="transform:scaleY(0)"></div>'
	. '<div data-vm-k="5" class="vm:relative vm:flex vm:flex-col vm:items-center vm:gap-2 vm:rounded-2xl vm:border-2 vm:border-dashed vm:border-line-strong vm:bg-paper vm:p-4 vm:text-center" style="opacity:0;transform:translateY(20px)"><span class="vm:flex vm:items-center vm:gap-2 vm:text-sm vm:font-medium">' . vm_icon( 'lock', 'vm:h-4 vm:w-4 vm:text-green' ) . ' sodium_crypto_secretbox + random nonce</span><span class="vm:font-mono vm:text-xs vm:text-ink-mute">stored as vm1:&lt;base64(nonce . ciphertext)&gt;</span></div>'
	. '<p data-vm-k="6" class="vm:relative vm:mt-5 vm:flex vm:items-center vm:justify-center vm:gap-2 vm:text-center vm:text-sm vm:text-ink-soft" style="opacity:0">' . vm_icon( 'server', 'vm:h-4 vm:w-4' ) . ' A database dump alone, or the config file alone, can&#039;t read a credential.</p>'
	. '</div>';
if ( isset( $a['parallax'] ) ) {
	$out = '<div data-vm-parallax="' . esc_attr( (string) $a['parallax'] ) . '">' . $out . '</div>';
}
echo $out; // phpcs:ignore
