"""Content for the 7 feature (module) pages. build.py renders each with the same layout.

Each module: slug, nav name, icon, eyebrow, h1 (with <span class="hl">), lead, mockup html,
3 highlights, 3 steps, feature categories/names shown in the list, 4 FAQs.
"""

MOCK_ENQUIRY = '''<div class="ui"><div class="ui-bar"><i></i><i></i><i></i><span>yourstore.com/product/oak-table</span></div>
<div class="prod-page"><div class="prod-img">{i:box}</div><div class="prod-info"><h4>Solid Oak Table</h4><div class="stars">{i:star}{i:star}{i:star}{i:star}{i:star}</div>
<div class="price-hidden">{i:lock} Ask for price</div><div class="ui-btn btn-quote">{i:msg} Send Enquiry</div>
<div class="mini-form"><div class="fld-2"><div class="fld"><label>Name</label><div class="in">Lena T.</div></div><div class="fld"><label>Email</label><div class="in">lena@studio.co</div></div></div>
<div class="fld"><label>Your question</label><div class="in ta focus">Can you make it 220 cm long?<span class="caret"></span></div></div></div></div></div>
<div class="sent-toast"><span class="ico">{i:check}</span>Enquiry sent</div></div>'''

MOCK_CATALOG = '''<div class="ui"><div class="ui-bar"><i></i><i></i><i></i><span>yourstore.com/shop</span></div>
<div class="role-sw"><span>Guest</span><span class="on">Wholesale</span><span>VIP</span></div>
<div class="cat-grid">
<div><span class="thumb t-b">{i:tag}</span><b>Leather belt</b><small>{i:msg} Enquire</small></div>
<div><span class="thumb t-a">{i:cap}</span><b>Cap</b><small>{i:quote} Add to quote</small></div>
<div class="lockd"><span class="thumb t-e">{i:shirt}</span><b>Hoodie</b><small>{i:lock} Log in to see</small></div>
<div><span class="thumb t-c">{i:glasses}</span><b>Spectacles</b><small>{i:msg} Enquire</small></div>
<div><span class="thumb t-d">{i:watch}</span><b>Watch</b><small>{i:quote} Add to quote</small></div>
<div><span class="thumb t-a">{i:lamp}</span><b>Lamp</b><small>{i:msg} Enquire</small></div>
</div><div style="padding:0 12px 12px"><div class="price-hidden" style="margin:0">{i:eyeoff} Catalog mode on · Add to Cart hidden</div></div></div>'''

MOCK_EXCLUSION = '''<div class="ui"><div class="ui-bar"><i></i><i></i><i></i><span>CatalogX · Exclusion Hub</span></div><div class="ui-body">
<div class="dash-top"><h5 style="margin:0">Who sees what</h5><span class="pill">4 rules</span></div>
<div class="ui-row hl-row"><span class="thumb t-a">{i:users}</span><div class="grow"><b>Guests</b><small>Prices hidden · Enquiry only</small></div><span class="pill bad">Excluded</span></div>
<div class="ui-row"><span class="thumb t-d">{i:truck}</span><div class="grow"><b>Wholesale role</b><small>Quotes + wholesale prices</small></div><span class="pill ok">Allowed</span></div>
<div class="ui-row"><span class="thumb t-b">{i:tag}</span><div class="grow"><b>Category: Clearance</b><small>Normal checkout, no quote button</small></div><span class="pill warn">Excluded</span></div>
<div class="ui-row"><span class="thumb t-c">{i:user}</span><div class="grow"><b>acme@buyer.com</b><small>Custom pricing customer</small></div><span class="pill ok">Allowed</span></div>
</div></div>'''

MOCK_WHOLESALE = '''<div class="ui"><div class="ui-bar"><i></i><i></i><i></i><span>Wholesale order form</span></div>
<table class="ws-tbl"><thead><tr><th>Product</th><th>Wholesale</th><th>Qty</th></tr></thead><tbody>
<tr class="hl-row"><td><span class="pn"><span class="thumb t-a">{i:cap}</span>Abc Cap</span></td><td><s>$31</s><strong>$25</strong><br><small style="color:var(--mut);font-size:10.5px">Min. 20 units</small></td><td><span class="qty"><i>−</i><b>40</b><i>+</i></span></td></tr>
<tr><td><span class="pn"><span class="thumb t-c">{i:glasses}</span>EBC spectacles</span></td><td><s>$36</s><strong>$28.80</strong><br><small style="color:var(--mut);font-size:10.5px">20% off 30+</small></td><td><span class="qty"><i>−</i><b>30</b><i>+</i></span></td></tr>
<tr><td><span class="pn"><span class="thumb t-d">{i:shirt}</span>Linen shirt</span></td><td><strong>$14</strong><br><small style="color:var(--mut);font-size:10.5px">Min. 50 units</small></td><td><span class="qty"><i>−</i><b>50</b><i>+</i></span></td></tr>
</tbody></table><div style="padding:12px;display:flex;justify-content:space-between;align-items:center;gap:10px"><b style="color:var(--ink)">Total $2,564</b><div class="ui-btn" style="padding:0 16px">{i:cart} Add all to cart</div></div></div>'''

MOCK_PORTAL = '''<div class="ui"><div class="ui-bar"><i></i><i></i><i></i><span>Enquiry Center</span></div><div class="hub">
<div class="hub-list"><div class="ui-row on"><span class="av">AM</span><div class="grow"><b>Anna M.</b><small>Berry cap · bulk</small></div></div>
<div class="ui-row"><span class="av a2">RK</span><div class="grow"><b>Rishi K.</b><small>Steel bolts</small></div></div>
<div class="ui-row"><span class="av a3">LT</span><div class="grow"><b>Lena T.</b><small>Oak table</small></div></div>
<div class="ui-row"><span class="av a4">DW</span><div class="grow"><b>Dan W.</b><small>Linen shirts</small></div></div></div>
<div class="hub-chat"><div class="pin">{i:star} Pinned · needs 500 units</div><div class="bub">Hi! What would 500 Berry Casual Caps cost?</div>
<div class="bub me">I can offer a tiered price for that volume.</div><div class="bub att">{i:file} logo-placement.pdf</div>
<div class="bub quote"><b style="font-size:12px">Quote #6211 · Berry Casual Cap</b><span style="font-size:11.5px;color:var(--mut)">500 units × $18.95</span><div class="ui-btn" style="height:28px">Accept &amp; Checkout</div></div>
<div class="typing"><i></i><i></i><i></i></div></div></div></div>'''

MOCK_BUILDER = '''<div class="ui"><div class="ui-bar"><i></i><i></i><i></i><span>Live page builder · Single product</span></div><div class="lb">
<div class="lb-can"><div class="prod-img">{i:cap}</div><div class="lb-btns"><div class="ui-btn sel">Buy Now</div><div class="ui-btn o">{i:msg} Send Enquiry</div></div></div>
<div class="lb-ctl"><small>Button color</small><div class="sw"><i class="sel"></i><i></i><i></i><i></i></div><small>Corner radius</small><div class="rng"><i></i></div>
<small>Label</small><div class="fld" style="margin:0"><div class="in">Buy Now</div></div><small>Position</small><div class="seg"><span>Above</span><span class="on">Below</span></div></div></div></div>'''

MOCK_PRICING = '''<div class="ui"><div class="ui-bar"><i></i><i></i><i></i><span>CatalogX · Pricing rules</span></div><div class="rule">
<h5 style="color:var(--p);font-size:16px">Add new rule</h5>
<div class="fld"><label>Name</label><div class="in focus">VIP spring tier<span class="caret"></span></div></div>
<div class="fld"><label>Applicable for</label><div class="seg"><span>Product</span><span>Category</span><span class="on">User role</span></div></div>
<div class="fld-2"><div class="fld"><label>Role</label><div class="in">VIP {i:chev}</div></div><div class="fld"><label>Min. quantity</label><div class="in">10</div></div></div>
<div class="fld-2"><div class="fld"><label>Discount</label><div class="in">15</div></div><div class="fld"><label>Type</label><div class="in">Percentage {i:chev}</div></div></div>
<div style="display:flex;justify-content:flex-end"><div class="ui-btn" style="width:150px">Add and Activate</div></div></div></div>'''

MODULES = [
  dict(slug="enquiry", name="Enquiry", icon="msg", cats=["enquiry"],
    eyebrow="Enquiry module", h1='Let shoppers ask <span class="hl">before they buy</span>',
    lead="Add an Enquiry button so shoppers can ask a question before they buy.",
    mock=MOCK_ENQUIRY,
    hi=[("msg","Enquiry button anywhere","Show it on product pages, in the shop, or inside any page with the Enquiry block."),
        ("edit","Forms that fit your products","Text, dropdown, checkbox, date and file fields, each optional or required."),
        ("cart","Enquiry Cart for bigger requests","Buyers collect several products and send one enquiry for all of them.")],
    steps=[("Turn it on","Enable the Enquiry module and pick which products, categories or roles show the button."),
           ("Build the form","Drag in the fields you need, add CAPTCHA, and customize the email template."),
           ("Answer and convert","Reply from the Enquiry Portal and turn a good enquiry into a quote in one click.")],
    faq=[("Can I keep Add to Cart and show an Enquiry button too?","Yes. Show Add to Cart alone, Enquiry alone, or both side by side, per product or across the store."),
         ("Can guests send enquiries?","Yes, or you can limit enquiries to logged-in users or specific roles with Role-Based Enquiry."),
         ("Where do enquiries go?","Each enquiry is emailed to you with a link to the product, and saved in the Enquiry Portal with the full conversation."),
         ("Does it work with WPML?","Yes. Buttons, forms and emails can be translated for multilingual stores.")]),
  dict(slug="product-catalog", name="Product Catalog", icon="catalog", cats=["catalog"],
    eyebrow="Product Catalog module", h1='Turn your shop into a <span class="hl">catalog that sells</span>',
    lead="Hide prices or Add to Cart, and show an enquiry or quote button instead.",
    mock=MOCK_CATALOG,
    hi=[("eyeoff","Catalog mode","Hide Add to Cart site-wide or for chosen products and categories."),
        ("lock","Private store","Show prices and buying only to logged-in customers."),
        ("users","Role-based visibility","Guests, wholesale, VIP and retail buyers each see the catalog meant for them.")],
    steps=[("Pick the scope","Choose the whole store, categories, or single products."),
           ("Set who sees what","Hide prices or buttons by login status or user role."),
           ("Replace the button","Swap Add to Cart for Send Enquiry or Add to Quote, with your own label.")],
    faq=[("Will catalog mode hurt my SEO?","No. Product pages stay public and indexable; only prices and buttons change."),
         ("Can some products stay purchasable?","Yes. Exclude any product or category and it keeps normal checkout."),
         ("Can logged-in buyers see prices?","Yes. Private Store Mode hides prices from guests and shows them after login."),
         ("Is catalog mode in the free version?","Yes. Catalog mode is free; role-based visibility and private store are Pro.")]),
  dict(slug="exclusion-hub", name="Exclusion Hub", icon="eyeoff", cats=["catalog"], only=["Exclusion Hub","Strategic Price Visibility","Private Store Mode","Product-Specific Management"],
    eyebrow="Exclusion Hub", h1='Decide exactly <span class="hl">who sees what</span>',
    lead="Choose which users, roles and products each rule applies to.",
    mock=MOCK_EXCLUSION,
    hi=[("users","By user role","Include or exclude guests, retail, wholesale, VIP or any custom role."),
        ("box","By product or category","Keep clearance items on normal checkout while the rest of the store quotes."),
        ("user","By customer","Give a single account its own access and pricing.")],
    steps=[("Choose a module","Catalog, Enquiry, Quotation or Wholesale."),
           ("Add include or exclude rules","Pick roles, users, products or categories."),
           ("Save once","The same rules apply across every CatalogX module.")],
    faq=[("Do exclusion rules apply to every module?","Yes. Each module reads the same rules, so settings never contradict each other."),
         ("Can I exclude a single customer?","Yes. Add their account by email and they get their own experience."),
         ("Can clearance items keep normal checkout?","Yes. Exclude any product or category and it keeps the standard Add to Cart."),
         ("Is the Exclusion Hub free?","The Exclusion Hub is part of CatalogX Pro.")]),
  dict(slug="wholesale", name="Wholesale", icon="truck", cats=["wholesale"],
    eyebrow="Wholesale module", h1='Run retail and <span class="hl">wholesale</span> from one store',
    lead="Give wholesale buyers their own prices, while retail stays the same.",
    mock=MOCK_WHOLESALE,
    hi=[("tag","Wholesale prices","Set wholesale prices on simple and variable products, or one global rule."),
        ("box","Minimums that protect margin","Minimum cart quantity and per-product minimums for wholesale orders."),
        ("list","Wholesale order form","A one-page order list so bulk buyers reorder in seconds.")],
    steps=[("Create the role","Add a Wholesale Customer role, or use your own."),
           ("Set prices and rules","Wholesale prices, minimums, and whether retail prices are shown."),
           ("Share the order form","Publish the Wholesale Order List page for approved buyers.")],
    faq=[("Do retail customers see wholesale prices?","No. Wholesale prices only show to the wholesale role."),
         ("Can wholesale buyers use coupons?","You decide. Coupons can be blocked for wholesale orders."),
         ("Does it work with existing WooCommerce discounts?","Yes. It works alongside them and you choose which apply to wholesale customers."),
         ("Can wholesale buyers request quotes?","Yes. Wholesale customers can add items to a quote cart and request quotes at their negotiated rates.")]),
  dict(slug="enquiry-portal", name="Enquiry Portal", icon="inbox", cats=["enquiry"], only=["Enquiry Portal","Enquiry Email Template","Multi Enquiry Support","Enquiry Cart","File Attachments"],
    eyebrow="Enquiry Portal", h1='Every customer conversation in <span class="hl">one inbox</span>',
    lead="Reply to every enquiry and send quotes from one place.",
    mock=MOCK_PORTAL,
    hi=[("inbox","Centralized communication","All enquiries from every product in one unified enquiry box."),
        ("tag","Enhanced organization","Tag users and products, pin important messages and share attachments."),
        ("quote","Quotes from the chat","Create and send a quote inside the conversation.")],
    steps=[("Enquiry arrives","A shopper sends an enquiry and you get an instant notification."),
           ("Talk it through","Reply, attach specs, and pin key details."),
           ("Close the deal","Send a quote; the buyer accepts and it becomes an order.")],
    faq=[("Will I know when a new enquiry arrives?","Yes. Instant email notifications go out for every enquiry."),
         ("Can I organize conversations?","Yes. Tag users and products and pin important messages."),
         ("Can I send files?","Yes. Both you and the customer can attach files to the conversation."),
         ("Can I export enquiries?","Yes. Export enquiries and quotations as CSV or PDF.")]),
  dict(slug="page-builder", name="Real-Time Page Builder", icon="brush", cats=["builder"], only=["Live Page Builder","Drag-and-Drop Button Placement","Enquiry Button Block","Ask Quote Button Block"],
    eyebrow="Real-time page builder", h1='Design your shop page <span class="hl">live</span>',
    lead="Style CatalogX buttons with a live preview. No code needed.",
    mock=MOCK_BUILDER,
    hi=[("eye","Real-time visual editing","See every change on the page as you make it."),
        ("move","Drag-and-drop placement","Move Add to Cart, Enquiry and Quote buttons where they convert best."),
        ("brush","Full button control","Text, colors, fonts, radius and destinations.")],
    steps=[("Open the builder","Pick a product page to edit."),
           ("Style and place","Drag buttons into place and set colors and labels."),
           ("Publish","Save once and every product page updates.")],
    faq=[("Do I need to know CSS?","No. Everything is visual."),
         ("Will it match my theme?","Yes. Pick the colors and fonts your theme uses and check them in the live preview."),
         ("Does it work with page builders?","Yes. CatalogX buttons are also available as blocks for any page."),
         ("Is the page builder free?","The Real-Time Page Builder is part of CatalogX Pro.")]),
  dict(slug="dynamic-pricing", name="Dynamic & Role-Based Pricing", icon="percent", cats=["pricing"],
    eyebrow="Dynamic & role-based pricing", h1='Price rules that <span class="hl">sell more</span>',
    lead="Set prices by role, quantity or product. Each buyer sees their price.",
    mock=MOCK_PRICING,
    hi=[("users","Role-based prices","Different prices for retail, wholesale, VIP and guest buyers."),
        ("sliders","Quantity tiers","Bulk pricing such as 50+ units at 20% off."),
        ("move","Rule priority","Drag rules to decide which one wins.")],
    steps=[("Create a rule","Name it and pick products, categories or roles."),
           ("Set the price","Fixed price, percentage or amount discount, or a markup."),
           ("Activate","Prices update instantly across the store.")],
    faq=[("Is there a limit to the number of pricing rules?","No set limit. Very complex rule sets can affect page load times."),
         ("Can I create my own roles?","Yes. Create custom user roles without code."),
         ("Can rules target one customer?","Yes. Customer-specific pricing is built in."),
         ("Do rules show on the product page?","Yes. Buyers see their price on the product, cart and checkout.")]),
]
