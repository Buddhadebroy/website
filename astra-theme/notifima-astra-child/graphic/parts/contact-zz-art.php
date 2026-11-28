<?php
/**
 * Contact Us · zz-art: Name Email Topic Sales question Partnership Billing Something else Mes
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="zz-art nfg nfg-4e94c4" style="background:#fff;border:1px solid var(--line-2)">
<form action="mailto:contact@notifima.com" class="card cform" enctype="text/plain" method="post">
<div class="cf-row"><label>Name<input name="name" placeholder="Your name" required="" type="text"/></label><label>Email<input name="email" placeholder="you@store.com" required="" type="email"/></label></div>
<label>Topic<select name="topic"><option>Sales question</option><option>Partnership</option><option>Billing</option><option>Something else</option></select></label>
<label>Message<textarea name="message" placeholder="How can we help?" required="" rows="4"></textarea></label>
<button class="btn btn-p" type="submit">Send message <svg aria-hidden="true" class="i"><use href="#send"></use></svg></button>
</form>
</div>
