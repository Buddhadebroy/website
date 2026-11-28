---
title: WordPress Not Sending Emails? 8 Causes and How to Fix Each One
slug: wordpress-not-sending-emails
category: Troubleshooting
published: 2026-10-14
updated: 2026-10-27
answer: WordPress usually fails to send email because it hands messages to your web host's basic PHP mail function, which hosts throttle or block and inboxes distrust. The fix is to send through an authenticated SMTP or email-API connection, add SPF, DKIM and DMARC records for your domain, and keep a log so every failure is visible.
description: WordPress not sending emails? Find the real cause (PHP mail, blocked ports, missing SPF/DKIM, plugin conflicts) and fix it with a step-by-step checklist.
keywords: WordPress not sending emails, WordPress email not working, fix WordPress email, wp_mail not working, WordPress SMTP
related: wordpress-email-failover-backup-smtp, spf-dkim-dmarc-wordpress-email-deliverability, wordpress-email-log-and-resend
---
When WordPress stops sending email, password resets, order receipts and contact-form messages all disappear at once, and you usually find out from an unhappy customer. The cause is rarely WordPress itself. It is almost always how the message leaves your server.

## Why WordPress emails fail in the first place

Every email WordPress creates goes through one function, `wp_mail()`. Out of the box, that function passes the message to PHP's `mail()`, which asks your web server to deliver it with no login and no proof that your site owns the sender address. Receiving servers treat that as suspicious, and many hosts disable or rate-limit it to stop spam.

Fixing it means giving WordPress a proper, authenticated route out. That is what an SMTP plugin or email-API connector does.

## The 8 most common causes, with fixes

1. **Your host blocks PHP mail.** Many shared hosts switch it off. Fix: send through SMTP or an email API instead.
2. **The message is sent but lands in spam.** The domain has no SPF, DKIM or DMARC record. Fix: add all three (see our [SPF, DKIM and DMARC guide](/blog/spf-dkim-dmarc-wordpress-email-deliverability)).
3. **A mismatched From address.** Sending as `you@gmail.com` from a different server fails authentication. Fix: use an address on your own domain that your provider has verified.
4. **Wrong SMTP port or encryption.** Port 25 is commonly blocked. Fix: use 587 with STARTTLS or 465 with SSL.
5. **Bad credentials.** Gmail, Yahoo, iCloud and others need an app password, not your normal one. Fix: generate an app password.
6. **Two email plugins fighting.** Both try to deliver the same message. Fix: keep one and deactivate the other.
7. **A plugin that bypasses `wp_mail()`.** It talks to an email service directly, so your SMTP plugin never sees it. Fix: check that plugin's own mail settings.
8. **A provider rejection you never see.** The provider refuses the message and nothing records it. Fix: use a plugin with a delivery log and failure alerts.

## Symptom-to-cause cheat sheet

| What you see | Most likely cause | First thing to try |
|---|---|---|
| No email at all, no error | Host blocks PHP mail | Connect SMTP or an email API |
| Arrives in spam | Missing SPF, DKIM or DMARC | Run a DNS check on your sending domain |
| Connection or timeout error | Blocked port or wrong host | Switch to port 587 or 465 |
| Authentication failed | Wrong or normal password | Create an app password or API key |
| Only some emails send | A plugin bypasses wp_mail() | Review that plugin's mail settings |

## How to fix it in five steps

1. Install an email delivery plugin such as [VuloMail](/get-started). Activating it changes nothing until you add a connection.
2. Add a primary connection: any SMTP server, one of [22 email APIs](/providers) or a preset such as Gmail or Amazon SES.
3. Send a test email from the plugin and confirm it arrives.
4. Open the diagnostics page and read the sender-domain DNS result. Add any missing SPF or DMARC record.
5. Add a backup connection so one provider outage cannot silence your site.

> **Check the log before you guess** A delivery log tells you whether WordPress tried to send, which provider answered and what error came back. Without one you are debugging blind.

## How to stop it happening again

Reliable email comes down to three habits: authenticate your domain, add a backup connection, and get told when something fails. VuloMail covers all three in the free plugin. It logs every message, falls back from the primary to a backup and then to WordPress's own mailer, and can text you when an email fails to send, at most once every 15 minutes.

Other plugins can do this too, but several keep the log, backup and alerts behind a paid plan. See how the options compare on our [comparison page](/compare).

- Test after every host, plugin or DNS change.
- Use a sender address on a domain you control.
- Keep a backup connection with a different provider than your primary.

## Know what "sent" really means

A "Sent" status means your provider accepted the message. Whether it reached the inbox is reported in the provider's own dashboard. VuloMail keeps the provider's message ID in each log entry so you can look it up there.

## Frequently asked questions

### Why are my WordPress emails not sending?
Most often your host blocks or throttles PHP mail, or the message fails authentication. Send through SMTP or an email API and add SPF, DKIM and DMARC records.

### How do I test whether WordPress can send email?
Use an email plugin's test-send feature to email yourself. If it fails, the plugin's log shows the provider error. If it succeeds but lands in spam, check your DNS records.

### Do I need an SMTP plugin to send WordPress email?
It is strongly recommended. Without one, WordPress relies on your host's PHP mail, which is the most common reason emails go missing.

### Will VuloMail change how my site sends email as soon as I install it?
No. Nothing changes until you add a connection and set it as primary. Until then WordPress sends exactly as before.
