---
title: WooCommerce SMS Notifications: 14 Order and Stock Alerts to Set Up
slug: woocommerce-sms-notifications
category: SMS & WooCommerce
published: 2026-10-20
updated: 2026-10-27
answer: WooCommerce SMS notifications are text messages sent to you or your customers when store events happen, such as a new order, failed payment, low stock or shipped order. Connect an SMS gateway like Twilio, Vonage, Plivo or Clickatell through a plugin, then enable the alerts and edit their templates.
description: Set up WooCommerce SMS notifications for new orders, failed payments, low stock and customer order updates using Twilio, Vonage, Plivo or Clickatell.
keywords: WooCommerce SMS notifications, WooCommerce order SMS, WordPress SMS alerts, WooCommerce low stock alert, WooCommerce customer text updates
related: woocommerce-emails-not-sending-or-going-to-spam, wordpress-email-failover-backup-smtp, wordpress-not-sending-emails
---
Email is easy to miss. A text about a failed payment or a sold-out bestseller is read within minutes. That is why store owners add SMS on top of email, not instead of it.

## Which WooCommerce events deserve a text?

VuloMail ships 14 WooCommerce alerts, split between messages to you and messages to your customers, plus 4 site alerts such as a failed-email warning.

| Group | Sent to | Alerts |
|---|---|---|
| Store (8) | Your admin phone | New order, payment failed, cancelled, refunded, low stock, out of stock, backorder, new review |
| Customer (6) | The billing phone on the order | Order confirmed, completed, refunded, cancelled, any other status change, note added |

## Start with these five

- **New order** so you know the store is working.
- **Order payment failed** to recover a sale quickly.
- **Product out of stock** so you can restock or pause ads.
- **Customer order confirmed** to reassure buyers.
- **Customer note added** to send tracking numbers.

## How to set it up

1. Create an account with an SMS gateway and note your credentials and sender. Twilio needs an Account SID, auth token and sender, while Plivo and Vonage use their own key pairs.
2. Install VuloMail and add the SMS connection under Settings → SMS.
3. Set your admin phone number in international format, such as `+14155550123`.
4. Enable the alerts you want and open each template to edit it.
5. Place a test order and confirm both texts arrive.

Customers' numbers come from the billing phone on each order. If shoppers type numbers without a country code, set a default country code in the SMS settings so messages are not rejected.

## Write short templates with placeholders

Templates use placeholders that fill in real data. A new-order alert looks like this:

`New order #{order_number} from {customer_name}: {order_total}.`

Keep texts tight. A message over 160 plain characters is billed as two or more, and emoji or non-Latin scripts drop that limit to 70. The live editor on our [SMS page](/sms) shows the segment count as you type, so you can see the cost before you send.

> **Consent is your responsibility** VuloMail sends what your site asks it to. You need your recipients' consent and must follow the rules on text messages in your country. In the US, carriers also expect business senders to register their numbers, so check your gateway's requirements.

## How much does it cost?

VuloMail is free and has no Pro edition, so you pay only your SMS gateway, per message. Pricing varies by country and provider, so check your gateway's current rates. Keeping templates short and enabling only the alerts you need is the simplest way to control spend.

## What if you need more than alerts?

VuloMail focuses on delivery and alerts across four gateways. If SMS is your main channel and you need OTP login, scheduled campaigns, subscriber forms or a two-way inbox, a dedicated SMS suite such as WSMS goes much further. See our [honest comparison](/compare/wsms).

Pair texts with reliable email. Our guide to [WooCommerce emails going to spam](/blog/woocommerce-emails-not-sending-or-going-to-spam) covers the other half, and our [features page](/features) lists everything else the plugin does.

## Mistakes that waste money or annoy customers

- **Enabling every alert.** Texts about every comment and login add noise. Start with the five above.
- **Long templates.** Each extra segment is billed separately.
- **No default country code.** Local-format numbers get rejected as invalid.
- **Texting without consent.** Add a clear opt-in at checkout, and honour opt-outs.
- **Testing on live customers.** Place a test order to your own phone first.

Treat SMS as the urgent channel and email as the record. Reserve texts for things people act on.

## Your next step

Pick one gateway, enable the new-order and payment-failed alerts, and place a test order. Once you trust the flow, add stock alerts and customer updates one at a time. Adding alerts gradually shows you what each one costs and which ones your customers actually appreciate. The full list of alerts is on the [SMS page](/sms).

## Frequently asked questions

### Does WooCommerce have built-in SMS notifications?
No. WooCommerce sends email by default. You need a plugin that connects an SMS gateway, such as VuloMail.

### Which SMS gateways does VuloMail support?
Twilio, Vonage, Plivo and Clickatell. Email goes through any SMTP server, 22 email APIs or 18 presets.

### Can customers get order status texts?
Yes. Six customer alerts cover processing, completed, refunded, cancelled, other status changes and order notes.

### How many characters can an alert have?
Up to 1,530 characters. Anything over 160 plain characters, or 70 with emoji, is billed as multiple messages.
