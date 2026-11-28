---
title: Send WordPress Email With Gmail or Microsoft 365 SMTP (and When Not To)
slug: gmail-smtp-wordpress-app-password
category: Guide
published: 2026-10-23
updated: 2026-10-27
answer: To send WordPress email through Gmail, Google Workspace or Microsoft 365, turn on two-step verification, create an app password, then enter your address and that password in an SMTP plugin's Gmail or Microsoft preset. It suits low-volume sites. Higher-volume or e-commerce sites are better served by a transactional email API.
description: Set up Gmail, Google Workspace or Microsoft 365 SMTP in WordPress with an app password, and learn the limits that make an email API a better choice.
keywords: Gmail SMTP WordPress, Google Workspace SMTP WordPress, Microsoft 365 SMTP WordPress, Gmail app password WordPress, WordPress Outlook SMTP
related: smtp-vs-email-api-wordpress, wordpress-not-sending-emails, wp-mail-smtp-alternatives-free
---
Using the mailbox you already pay for is the quickest way to get WordPress email working. It is also the setup that most often breaks, because normal passwords no longer work for SMTP. Here is how to do it right and when to choose something else.

## Why your normal password fails

Google, Microsoft, Yahoo and iCloud block ordinary passwords for SMTP when two-step verification is on. They want an **app password**: a separate, revocable password made just for one app. Entering your normal password is the number one cause of "authentication failed" errors.

## Set up Gmail or Google Workspace

1. Turn on 2-Step Verification for the Google account.
2. Create an app password in your Google account's security settings and copy it.
3. In VuloMail, add a connection and pick the **Gmail** or **Google Workspace** preset.
4. Enter the full address and paste the app password.
5. Set the From address to the same mailbox, then send a test.

## Set up Microsoft 365 or Outlook.com

1. If multi-factor authentication is on, create an app password. For Outlook.com, use Advanced security options → App passwords.
2. For Microsoft 365, ask your admin to allow app passwords and SMTP AUTH. Without that, sign-in fails.
3. Pick the **Microsoft 365** or **Outlook.com** preset in VuloMail and enter your address and password.
4. Send a test email.

> **Other mailbox presets** VuloMail also has one-click presets for Zoho Mail, Yahoo, iCloud, AOL, Yandex, Fastmail, Titan, Purelymail, Migadu, Namecheap Private Email and Rackspace. Most need an app password too.

## The limits you should know

| Limit | What it means for your site |
|---|---|
| Daily sending caps | Personal Gmail allows roughly 500 messages a day and Workspace roughly 2,000. Check Google's current limits |
| From address locked to the mailbox | Sending as another address often gets rewritten or rejected |
| No transactional features | No bounce webhooks or per-message delivery dashboards |
| Shared reputation | Your mailbox mixes human and automated mail |
| Credential risk | The app password sits on your site, so revoke it if the site is ever compromised |

## When to use an email API instead

- You run WooCommerce or send more than a few hundred messages a day.
- You want delivery and bounce reporting per message.
- You want to keep sending separate from your team's inbox.

Services such as Postmark, SendGrid, Amazon SES or Resend are built for this. Our [SMTP vs email API guide](/blog/smtp-vs-email-api-wordpress) explains the trade-offs, and the [providers page](/providers) shows what each one needs.

## Make Gmail a backup, not the only route

A mailbox preset is a handy second connection. Set an API provider as primary and Gmail as the backup, and an outage on either side no longer stops your site. See the [failover guide](/blog/wordpress-email-failover-backup-smtp) for the setup.

VuloMail stores your app password encrypted with libsodium, masks it after saving and sends it only to the provider it belongs to. If a connection suddenly reads "Incomplete" after a site migration, your wp-config security keys changed, so re-enter the password.

## Troubleshooting checklist

- **Authentication failed:** you used a normal password. Create an app password.
- **App password option missing:** 2-Step Verification is off, or an admin has disabled app passwords.
- **Microsoft 365 sign-in refused:** SMTP AUTH is disabled for the mailbox. Ask your admin to enable it.
- **Emails sent but in spam:** add SPF, DKIM and DMARC for your domain. See our [deliverability guide](/blog/spf-dkim-dmarc-wordpress-email-deliverability).
- **Worked, then stopped:** the app password was revoked, or the account hit its daily cap.

Yandex app passwords can take two to three hours to become active, so wait before assuming a new one is wrong.

## Your next step

If you are starting from scratch, set up the mailbox preset today to get email flowing, then plan a move to a transactional provider before traffic grows. Because VuloMail lets you hold several connections, you can add the new provider as primary without removing the mailbox, and keep it as your safety net.

## Frequently asked questions

### Can I use Gmail SMTP with WordPress?
Yes. Turn on 2-Step Verification, create an app password and use it in a Gmail preset or SMTP settings. It suits low-volume sites.

### Why does Gmail SMTP say authentication failed?
You probably used your normal password. Gmail needs an app password when 2-Step Verification is on.

### Is Gmail SMTP good enough for WooCommerce?
For a very small store, perhaps. Daily caps and no delivery reporting make a transactional email API the safer choice.

### Does VuloMail support Google sign-in?
No. Its Gmail preset uses an app password. Some other plugins offer Google sign-in.
