// Feature categories (copy taken from the current multivendorx.com homepage)
const FEATURES = {
  "Store Management": {
    desc: "Give every vendor a fully branded storefront with policies, hours, location and invoicing built in.",
    items: ["Storefront Customization","Store Location & Geolocation Mapping","Location-Based Search","Customizable Operating Hours","Holiday Scheduling & Notifications","Store Policies & Terms","PDF Invoicing & Packing Slips","Local Pickup Options"]
  },
  "Commission": {
    desc: "Model any revenue strategy — from simple percentages to tiered, category-based or subscription commissions.",
    items: ["Fixed / Percentage Commissions","Tiered Commission Plans","Category-Based Commissions","User Types Commission","Dynamic Commission Flow","Recurring Subscription Commissions","Tax Handling in Commissions","Refund Adjustments"]
  },
  "Vendor Engagement": {
    desc: "Build trust and repeat business with reviews, followers and real-time vendor–buyer chat.",
    items: ["Store Reviews & Star Ratings","Store Follow Button","Follower Notifications","Vendor-Buyer Chat","Typing Indicators & Chat History","Chat & Review Analytics","Inventory Alerts","Order Fulfillment Tools"]
  },
  "Analytics & SEO": {
    desc: "Help vendors understand what sells and get found on search engines.",
    items: ["Seller's Advanced Reports","Sales by Product & Category","Stock & Inventory Reports","Tax & Transaction Reports","Google Analytics Integration","SEO Integration (Yoast / Rank Math)","Structured Data Support","Social Media Open Graph"]
  },
  "Catalog Control": {
    desc: "Powerful product tools for vendors, with shared listings for multi-seller catalogs.",
    items: ["Single Product Multiple Vendor","Product Comparison Table","Bulk Product Import / Export","Product Bulk Edit","Duplicate Product","Add / Edit / Publish Products","Upload Media Files","Product Inventory"]
  },
  "Shipping": {
    desc: "Let each vendor control shipping with every rate model your marketplace needs.",
    items: ["Seller Shipping Options","Table Rate Shipping","Flat Rate Shipping","Zone-Wise Shipping","Distance-Based Shipping","Country-Wise Setup","Shipping Tracking","Free Shipping Options"]
  },
  "Payment & Order": {
    desc: "Automate the money: split orders, real-time payouts and withdrawals via Stripe and PayPal.",
    items: ["Split Orders","Realtime Payout to Vendors","Automated Vendor Payouts","Stripe Payment","PayPal Payment","Withdrawal Requests","Order Notes & Status Updates","Payment Gateway Charge Handling"]
  }
};

const tabs = document.getElementById("featTabs");
const panel = document.getElementById("featPanel");
const esc = s => s.replace(/[&<>"]/g, c => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]));

function showFeature(name){
  const f = FEATURES[name];
  tabs.querySelectorAll(".tab").forEach(t => t.setAttribute("aria-selected", t.dataset.k === name));
  panel.innerHTML = `<div><h3>${esc(name)}</h3><p>${esc(f.desc)}</p></div>
    <ul class="feat-list">${f.items.map((i,n) => `<li style="animation-delay:${n*35}ms">${esc(i)}</li>`).join("")}</ul>`;
}
Object.keys(FEATURES).forEach((k, i) => {
  const b = document.createElement("button");
  b.className = "tab"; b.role = "tab"; b.dataset.k = k; b.textContent = k;
  b.addEventListener("click", () => showFeature(k));
  tabs.appendChild(b);
  if (i === 0) showFeature(k);
});

// Sticky nav shadow
const nav = document.querySelector(".nav");
addEventListener("scroll", () => nav.classList.toggle("scrolled", scrollY > 10), {passive:true});

// Mobile menu
const burger = document.getElementById("burger");
const links = document.getElementById("navLinks");
burger.addEventListener("click", () => {
  const open = links.classList.toggle("open");
  burger.setAttribute("aria-expanded", open);
});
document.querySelectorAll(".dd-btn").forEach(b =>
  b.addEventListener("click", () => b.parentElement.classList.toggle("open")));

// Reveal on scroll
const io = "IntersectionObserver" in window ? new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add("in"); io.unobserve(e.target); } });
}, {threshold: .12}) : null;
document.querySelectorAll(".reveal").forEach(el => io ? io.observe(el) : el.classList.add("in"));
