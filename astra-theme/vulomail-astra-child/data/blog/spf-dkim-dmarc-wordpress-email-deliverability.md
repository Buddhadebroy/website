---
title: SPF, DKIM and DMARC for WordPress: Fix Emails Going to Spam
slug: spf-dkim-dmarc-wordpress-email-deliverability
category: Troubleshooting
published: 2026-10-18
updated: 2026-10-27
answer: WordPress emails go to spam when your domain can't prove it authorised the sending server. SPF lists allowed servers, DKIM adds a cryptographic signature, and DMARC tells inboxes what to do when either fails. Publish all three as DNS records for your sending domain, then send from an address on that domain.
description: WordPress emails landing in spam? Learn what SPF, DKIM and DMARC records do, see copy-ready examples, and check them for your sending domain in minutes.
keywords: WordPress emails going to spam, SPF DKIM DMARC WordPress, WordPress email deliverability, fix WordPress spam emails, DMARC record WordPress
related: wordpress-not-sending-emails, woocommerce-emails-not-sending-or-going-to-spam, smtp-vs-email-api-wordpress
---
If your contact form notifications and order emails keep landing in spam, the content is rarely the problem. Mailbox providers decide mostly on one question: can this domain prove it sent this message? Three DNS records answer it.

## What SPF, DKIM and DMARC actually do

| Record | What it does | Where it lives |
|---|---|---|
| SPF | Lists the servers allowed to send for your domain | A TXT record on your domain |
| DKIM | Signs each message so receivers can verify it was not altered | A TXT or CNAME record your provider gives you |
| DMARC | Tells receivers what to do if SPF or DKIM fails, and sends you reports | A TXT record at _dmarc.yourdomain |

Since 2024, Google and Yahoo have required authentication from bulk senders, and in practice every site now benefits from having all three in place.

## Copy-ready examples

``` title="DNS records (replace with your provider's values)"
example.com.   TXT   "v=spf1 include:_spf.example-provider.com ~all"
_dmarc.example.com.   TXT   "v=DMARC1; p=none; rua=mailto:reports@example.com"
```

Your email provider shows the exact SPF include and DKIM records to add. Never copy another site's values.

- **One SPF record only.** Two SPF records break SPF. Combine all senders into one.
- **Mind the lookup limit.** SPF allows 10 DNS lookups. Too many `include:` entries fail silently.
- **Start DMARC at `p=none`.** Read the reports first, then move to `quarantine` and `reject`.

## The five-step fix

1. Pick a sending domain you own and use it as the From address, not a free Gmail or Yahoo address.
2. Add your provider's SPF and DKIM records at your DNS host.
3. Add a DMARC record starting at `p=none` with a reporting address.
4. Run a DNS check from your WordPress dashboard.
5. Send a test and open the message headers to confirm SPF, DKIM and DMARC all show **pass**.

> **Check from your own site** VuloMail's Tools → Diagnostics page includes a sender-domain DNS check for SPF and DMARC that runs from your own server, so your domain is not sent to an outside service. DKIM lives in your provider's dashboard, so confirm it there.

## Other reasons emails reach spam

- **A From address that doesn't match your sending domain.** Mismatch fails DMARC alignment.
- **A shared or low-reputation IP.** Cheap hosts mail from shared IPs. A dedicated provider connection avoids that.
- **Sending through PHP mail.** Unauthenticated messages are suspect by default.
- **Spammy content.** All-image emails, URL shorteners and misleading subjects all hurt.

Moving from PHP mail to an authenticated provider is usually the single biggest improvement. See our guide to [WordPress not sending emails](/blog/wordpress-not-sending-emails) for the full list.

## Keep watching after setup

Deliverability drifts. DNS records get edited, providers change, and a plugin update can change the sender. Review your DMARC reports, re-run diagnostics after any change, and keep a [backup connection](/blog/wordpress-email-failover-backup-smtp) so a reputation problem on one provider does not stop all mail.

Remember that "Sent" in your log means a provider accepted the message. Inbox placement is shown in your provider's own dashboard, where you can look up the message by its provider ID.

## Read a message header to confirm it works

After you publish your records, send a test to a Gmail address, open the message and choose **Show original**. Gmail displays three lines, one each for SPF, DKIM and DMARC. You want to see **PASS** on all three.

- **SPF fail or softfail:** the sending server is missing from your SPF record.
- **DKIM none:** the DKIM record was not published, or your provider is not signing yet.
- **DMARC fail:** SPF or DKIM passed for a different domain than your From address. Align them.

Fix the first failing line, wait for DNS to update, and test again. Change one thing at a time so you know which edit worked.

## Frequently asked questions

### Why do my WordPress emails go to spam?
Usually because the domain lacks SPF, DKIM or DMARC records, the From address doesn't match the sending domain, or the site sends through unauthenticated PHP mail.

### Do I need all three of SPF, DKIM and DMARC?
Yes. They work together, and major inboxes expect all three, especially for higher-volume senders.

### Can a WordPress plugin fix my DNS records?
A plugin can check them and tell you what is missing. You still add the records at your DNS host.

### How long do DNS changes take?
Often minutes, but allow up to 48 hours for changes to propagate everywhere.
