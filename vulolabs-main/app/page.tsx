import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { LogoMark } from "@/components/LogoMark";

export default function Home() {
  return (
    <>
      <Header />
      <main>
        {/* HERO */}
        <section className="section hero">
          <div className="wrap">
            <div className="hero-center">
              <span className="hero-badge">
                Product Engineering · WordPress · SaaS
              </span>
              <h1>
                We build the digital products your business needs to move
                forward.
              </h1>
              <p className="lede">
                We turn ideas, workflows and business problems into SaaS
                platforms, WordPress ecosystems, MVPs and custom software
                that actually works.
              </p>
              <div className="cta-row">
                <a className="btn btn-primary" href="#final-cta">
                  Get Started Free →
                </a>
                <a className="btn btn-ghost" href="#final-cta">
                  Talk to an Engineer
                </a>
              </div>
              <p className="hero-trust">
                No bloated teams · No unnecessary complexity · Built around
                how you work
              </p>
            </div>

            <div className="hero-stage" aria-hidden="true">
              <div className="float-card float-dashboard">
                <div className="mockup">
                  <div className="chrome">
                    <span></span>
                    <span></span>
                    <span></span>
                  </div>
                  <div className="mockup-body">
                    <div className="m-side"></div>
                    <div className="m-main">
                      <div className="m-row"></div>
                      <div className="m-cards">
                        <i></i>
                        <i></i>
                        <i></i>
                      </div>
                      <div className="m-row short"></div>
                    </div>
                  </div>
                </div>
              </div>

              <div className="float-card float-stat-a">
                <div className="fc-head">Delivery</div>
                <div className="fc-body">
                  <div className="fc-num">18</div>
                  <div className="fc-label">Active Builds</div>
                  <div className="fc-row">
                    <span className="dot"></span>Product &amp; SaaS
                  </div>
                </div>
              </div>

              <div className="float-card float-stat-b">
                <div className="fc-head">Client Retention</div>
                <div className="fc-body">
                  <div className="fc-num">96%</div>
                  <div className="fc-label">Repeat engagements</div>
                </div>
              </div>

              <div className="float-card float-chart">
                <div className="fc-head">Product Velocity</div>
                <div className="fc-body">
                  <svg className="mini-chart" viewBox="0 0 180 80">
                    <polyline
                      className="chart-line"
                      points="6,66 50,50 94,40 138,22 174,10"
                    />
                    <circle
                      className="chart-dot"
                      style={{ animationDelay: ".4s" }}
                      cx="6"
                      cy="66"
                      r="3"
                    />
                    <circle
                      className="chart-dot"
                      style={{ animationDelay: ".7s" }}
                      cx="50"
                      cy="50"
                      r="3"
                    />
                    <circle
                      className="chart-dot"
                      style={{ animationDelay: "1s" }}
                      cx="94"
                      cy="40"
                      r="3"
                    />
                    <circle
                      className="chart-dot"
                      style={{ animationDelay: "1.3s" }}
                      cx="138"
                      cy="22"
                      r="3"
                    />
                    <circle
                      className="chart-dot"
                      style={{ animationDelay: "1.6s" }}
                      cx="174"
                      cy="10"
                      r="4"
                    />
                  </svg>
                  <div className="fc-label">+3.4× since v1</div>
                </div>
              </div>

              <div className="float-browser">
                <div className="logo-bubble">
                  <span className="logo-mark">
                    <LogoMark />
                  </span>
                </div>
                <div className="chrome">
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
                <div className="addr-row">
                  <span className="addr-pill">vulolabs.com</span>
                </div>
                <div className="mockup-website">
                  <div className="mockup-body" style={{ padding: "12px" }}>
                    <div
                      className="hero-block"
                      style={{ height: "22px", marginBottom: "6px" }}
                    ></div>
                    <div className="grid3">
                      <i style={{ height: "26px" }}></i>
                      <i style={{ height: "26px" }}></i>
                      <i style={{ height: "26px" }}></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* THE PROBLEM */}
        <section className="section" id="problem">
          <div className="wrap">
            <div className="section-head">
              <span className="eyebrow mono">The Problem</span>
              <h2>
                Your business doesn&apos;t need more developers. It needs the
                right product.
              </h2>
              <p className="sub">
                Most software fails on requirements, not code.
              </p>
            </div>
            <div className="problem-grid">
              <ul className="fail-list">
                <li>Requirements misunderstood</li>
                <li>Over-engineered from day one</li>
                <li>Architecture that can&apos;t grow</li>
                <li>UX treated as an afterthought</li>
                <li>Fragile, one-off integrations</li>
                <li>Debt before product-market fit</li>
              </ul>
              <div className="approach-panel">
                <div>
                  <h3>We approach it differently.</h3>
                  <p>
                    We understand what the product needs to do — then design
                    the simplest system that does it properly.
                  </p>
                </div>
                <div className="approach-compare" aria-hidden="true">
                  <div className="compare-card before">
                    <span className="compare-tag tag-before">Before</span>
                    <div className="chaos-mock">
                      <div
                        className="cm-block"
                        style={{
                          width: "58%",
                          top: "2px",
                          left: "0",
                          transform: "rotate(-7deg)",
                        }}
                      ></div>
                      <div
                        className="cm-block dim"
                        style={{
                          width: "40%",
                          top: "24px",
                          left: "44%",
                          transform: "rotate(5deg)",
                        }}
                      ></div>
                      <div
                        className="cm-block"
                        style={{
                          width: "66%",
                          top: "46px",
                          left: "2px",
                          transform: "rotate(3deg)",
                        }}
                      ></div>
                      <div
                        className="cm-block dim"
                        style={{
                          width: "34%",
                          top: "58px",
                          left: "58%",
                          transform: "rotate(-9deg)",
                        }}
                      ></div>
                    </div>
                  </div>
                  <div className="compare-arrow">
                    <svg viewBox="0 0 24 24">
                      <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                  </div>
                  <div className="compare-card after">
                    <span className="compare-tag tag-after">After</span>
                    <div className="clean-mock">
                      <div className="cm-row" style={{ width: "72%" }}></div>
                      <div className="cm-row" style={{ width: "46%" }}></div>
                      <div className="cm-grid">
                        <i></i>
                        <i></i>
                        <i></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* WHAT WE BUILD */}
        <section className="section" id="build">
          <div className="wrap">
            <div className="section-head">
              <span className="eyebrow mono">What We Build</span>
              <h2>From one idea to an entire product ecosystem.</h2>
              <p className="sub">
                Bring an idea, a workflow, a broken system, or just a
                problem. We&apos;ll shape it into something usable.
              </p>
            </div>
            <div className="card-grid">
              <div className="card">
                <span className="icon-wrap c1">
                  <svg className="icon" viewBox="0 0 24 24">
                    <path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-3 11.2c.6.4 1 1.1 1 1.8h4c0-.7.4-1.4 1-1.8A6 6 0 0 0 12 3Z" />
                  </svg>
                </span>
                <h4>Idea</h4>
                <p>No spec required.</p>
              </div>
              <div className="card">
                <span className="icon-wrap c2">
                  <svg className="icon" viewBox="0 0 24 24">
                    <path d="M4 12a8 8 0 0 1 14-5.3M20 6v5h-5M20 12a8 8 0 0 1-14 5.3M4 18v-5h5" />
                  </svg>
                </span>
                <h4>Workflow</h4>
                <p>Ready to become a system.</p>
              </div>
              <div className="card">
                <span className="icon-wrap c3">
                  <svg className="icon" viewBox="0 0 24 24">
                    <path d="M12 3 2 20h20L12 3Z" />
                    <line x1="12" y1="9" x2="12" y2="14" />
                    <circle cx="12" cy="17" r=".6" fill="currentColor" />
                  </svg>
                </span>
                <h4>Broken system</h4>
                <p>Live, but not working.</p>
              </div>
              <div className="card">
                <span className="icon-wrap c4">
                  <svg className="icon" viewBox="0 0 24 24">
                    <rect x="4" y="7" width="12" height="12" rx="1" />
                    <rect x="8" y="3" width="12" height="12" rx="1" />
                  </svg>
                </span>
                <h4>Prototype</h4>
                <p>Ready to become real.</p>
              </div>
              <div className="card">
                <span className="icon-wrap c5">
                  <svg className="icon" viewBox="0 0 24 24">
                    <rect x="3" y="5" width="18" height="14" rx="2" />
                    <line x1="3" y1="9" x2="21" y2="9" />
                    <circle cx="6" cy="7" r=".6" fill="currentColor" />
                  </svg>
                </span>
                <h4>Existing website</h4>
                <p>Needs to do more.</p>
              </div>
              <div className="card">
                <span className="icon-wrap c6">
                  <svg className="icon" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M9.5 9a2.5 2.5 0 1 1 3.5 2.3c-.7.3-1 1-1 1.7v.5" />
                    <circle cx="12" cy="17" r=".6" fill="currentColor" />
                  </svg>
                </span>
                <h4>Business problem</h4>
                <p>Just needs solving.</p>
              </div>
            </div>
          </div>
        </section>

        {/* PRODUCT DEVELOPMENT */}
        <section className="section" id="product">
          <div className="wrap">
            <div className="service">
              <div>
                <span className="num">Product Development</span>
                <h2>Idea → MVP → Product</h2>
                <p className="kicker">
                  We turn product concepts into scalable digital products.
                </p>
                <div className="quote-flip">
                  <div className="q">Our approach</div>
                  <div className="a">
                    Build the first version. Learn from the market. Build
                    what matters next.
                  </div>
                </div>
                <div className="service-cta">
                  <a className="btn btn-ghost" href="#final-cta">
                    Explore Product Development →
                  </a>
                </div>
              </div>
              <div>
                <div className="service-visual" aria-hidden="true">
                  <span className="cap">Idea → MVP → Product</span>
                  <svg viewBox="0 0 320 130">
                    <line
                      className="funnel-rail"
                      x1="16"
                      y1="17"
                      x2="16"
                      y2="113"
                    />
                    <circle className="rail-tick" cx="16" cy="17" r="3.5" />
                    <circle className="rail-tick" cx="16" cy="49" r="3.5" />
                    <circle className="rail-tick" cx="16" cy="81" r="3.5" />
                    <circle className="rail-tick" cx="16" cy="113" r="3.5" />
                    <circle className="funnel-pulse" cx="16" cy="17" r="4.5" />
                    <g className="funnel-band">
                      <rect x="30" y="6" width="260" height="22" rx="4" />
                      <text x="160" y="20" textAnchor="middle">
                        Idea
                      </text>
                    </g>
                    <g className="funnel-band">
                      <rect x="60" y="38" width="200" height="22" rx="4" />
                      <text x="160" y="52" textAnchor="middle">
                        Prototype
                      </text>
                    </g>
                    <g className="funnel-band">
                      <rect x="90" y="70" width="140" height="22" rx="4" />
                      <text x="160" y="84" textAnchor="middle">
                        MVP
                      </text>
                    </g>
                    <g className="funnel-band">
                      <rect x="120" y="102" width="80" height="22" rx="4" />
                      <text x="160" y="116" textAnchor="middle">
                        Product
                      </text>
                    </g>
                  </svg>
                </div>
                <p className="service-label">We can help with</p>
                <div className="tag-list">
                  <span className="tag">Product discovery</span>
                  <span className="tag">Technical architecture</span>
                  <span className="tag">UX/UI</span>
                  <span className="tag">MVP development</span>
                  <span className="tag">API development</span>
                  <span className="tag">Integrations</span>
                  <span className="tag">Automation</span>
                  <span className="tag">Analytics</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* WORDPRESS */}
        <section className="section section-tint" id="wordpress">
          <div className="wrap">
            <div className="service">
              <div>
                <span className="num">WordPress Development</span>
                <h2>WordPress without the limitations.</h2>
                <p className="kicker">
                  Built for performance, not just installed.
                </p>
                <div className="quote-flip">
                  <div className="q">We don&apos;t start with</div>
                  <div className="a">&quot;Which theme should we install?&quot;</div>
                  <div className="q" style={{ marginTop: "12px" }}>
                    We start with
                  </div>
                  <div className="a">
                    &quot;What does this site need to do for the business?&quot;
                  </div>
                </div>
                <div className="service-cta">
                  <a className="btn btn-ghost" href="#final-cta">
                    Build a Better WordPress Product →
                  </a>
                </div>
              </div>
              <div>
                <div className="service-visual" aria-hidden="true">
                  <span className="cap">Performance, after rebuild</span>
                  <div className="score-grid">
                    <div className="score-item">
                      <div
                        className="score-ring"
                        style={{ ["--pct" as string]: 92 }}
                      >
                        <span>92</span>
                      </div>
                      <div className="score-label">Speed</div>
                    </div>
                    <div className="score-item">
                      <div
                        className="score-ring"
                        style={{ ["--pct" as string]: 98 }}
                      >
                        <span>98</span>
                      </div>
                      <div className="score-label">SEO</div>
                    </div>
                    <div className="score-item">
                      <div
                        className="score-ring"
                        style={{ ["--pct" as string]: 88 }}
                      >
                        <span>88</span>
                      </div>
                      <div className="score-label">A11y</div>
                    </div>
                    <div className="score-item">
                      <div
                        className="score-ring"
                        style={{ ["--pct" as string]: 100 }}
                      >
                        <span>100</span>
                      </div>
                      <div className="score-label">Best Pr.</div>
                    </div>
                    <div className="score-item">
                      <div
                        className="score-ring"
                        style={{ ["--pct" as string]: 95 }}
                      >
                        <span>95</span>
                      </div>
                      <div className="score-label">Security</div>
                    </div>
                  </div>
                </div>
                <p className="service-label">We work across</p>
                <div className="tag-list">
                  <span className="tag">Custom development</span>
                  <span className="tag">Custom plugins</span>
                  <span className="tag">WooCommerce</span>
                  <span className="tag">Membership &amp; LMS</span>
                  <span className="tag">Headless WordPress</span>
                  <span className="tag">CRM integrations</span>
                  <span className="tag">Performance</span>
                  <span className="tag">Security</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* SAAS */}
        <section className="section" id="saas">
          <div className="wrap">
            <div className="service">
              <div>
                <span className="num">SaaS Development</span>
                <h2>SaaS isn&apos;t just a login screen.</h2>
                <p className="kicker">
                  The full product layer — onboarding to billing to
                  analytics.
                </p>
                <div className="quote-flip">
                  <div className="q">Whether you&apos;re building</div>
                  <div className="a">
                    B2B, vertical, AI, marketplace — or something entirely
                    new.
                  </div>
                </div>
                <div className="service-cta">
                  <a className="btn btn-ghost" href="#final-cta">
                    Build My SaaS →
                  </a>
                </div>
              </div>
              <div>
                <div className="service-visual" aria-hidden="true">
                  <span className="cap">The product layer</span>
                  <svg viewBox="0 0 300 150">
                    <line
                      className="stack-rail"
                      x1="10"
                      y1="21"
                      x2="10"
                      y2="123"
                    />
                    <circle className="rail-tick" cx="10" cy="21" r="3.5" />
                    <circle className="rail-tick" cx="10" cy="55" r="3.5" />
                    <circle className="rail-tick" cx="10" cy="89" r="3.5" />
                    <circle className="rail-tick" cx="10" cy="123" r="3.5" />
                    <circle className="stack-pulse" cx="10" cy="21" r="4.5" />
                    <g className="stack-layer">
                      <rect x="70" y="8" width="160" height="26" rx="6" />
                      <text x="150" y="25" textAnchor="middle">
                        Frontend &amp; UX
                      </text>
                    </g>
                    <g className="stack-layer">
                      <rect x="55" y="42" width="190" height="26" rx="6" />
                      <text x="150" y="59" textAnchor="middle">
                        API &amp; Permissions
                      </text>
                    </g>
                    <g className="stack-layer">
                      <rect x="40" y="76" width="220" height="26" rx="6" />
                      <text x="150" y="93" textAnchor="middle">
                        Billing &amp; Data
                      </text>
                    </g>
                    <g className="stack-layer">
                      <rect x="25" y="110" width="250" height="26" rx="6" />
                      <text x="150" y="127" textAnchor="middle">
                        Cloud Infrastructure
                      </text>
                    </g>
                  </svg>
                </div>
                <p className="service-label">Capabilities</p>
                <div className="tag-list">
                  <span className="tag">Multi-tenant architecture</span>
                  <span className="tag">Auth &amp; permissions</span>
                  <span className="tag">Billing</span>
                  <span className="tag">Dashboards</span>
                  <span className="tag">API architecture</span>
                  <span className="tag">Analytics</span>
                  <span className="tag">AI integrations</span>
                  <span className="tag">Monitoring</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* AI & AUTOMATION */}
        <section className="section section-tint" id="ai">
          <div className="wrap">
            <div className="service">
              <div>
                <span className="num">AI &amp; Automation</span>
                <h2>Build software that gets smarter.</h2>
                <p className="kicker">
                  AI where it moves a real metric — not for show.
                </p>
                <div className="quote-flip">
                  <div className="q">We connect what you already use</div>
                  <div className="a">
                    CRM → Website → Payment → Email → WhatsApp → Analytics
                  </div>
                </div>
                <div className="service-cta">
                  <a className="btn btn-ghost" href="#final-cta">
                    Explore AI &amp; Automation →
                  </a>
                </div>
              </div>
              <div>
                <div className="service-visual" aria-hidden="true">
                  <span className="cap">Signal detection</span>
                  <svg viewBox="0 0 200 130">
                    <g transform="translate(65,15)">
                      <circle className="radar-ring" cx="35" cy="35" r="34" />
                      <circle className="radar-ring" cx="35" cy="35" r="22" />
                      <circle className="radar-ring" cx="35" cy="35" r="10" />
                      <line
                        className="radar-sweep"
                        x1="35"
                        y1="35"
                        x2="35"
                        y2="1"
                      />
                      <circle
                        className="radar-blip"
                        cx="55"
                        cy="16"
                        r="3.5"
                        style={{ animationDelay: ".4s" }}
                      />
                      <circle
                        className="radar-blip"
                        cx="14"
                        cy="52"
                        r="3.5"
                        style={{ animationDelay: "1.5s" }}
                      />
                      <circle
                        className="radar-blip"
                        cx="58"
                        cy="54"
                        r="3.5"
                        style={{ animationDelay: "2.6s" }}
                      />
                    </g>
                  </svg>
                </div>
                <p className="service-label">AI product development</p>
                <div className="tag-list">
                  <span className="tag">AI assistants</span>
                  <span className="tag">LLM integrations</span>
                  <span className="tag">RAG</span>
                  <span className="tag">AI search</span>
                  <span className="tag">Support automation</span>
                  <span className="tag">Recommendations</span>
                  <span className="tag">Data extraction</span>
                  <span className="tag">Internal AI tools</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* CUSTOM SOFTWARE */}
        <section className="section" id="custom">
          <div className="wrap">
            <div className="service">
              <div>
                <span className="num">Custom Business Applications</span>
                <h2>Your business isn&apos;t standard.</h2>
                <p className="kicker">
                  Software designed around how you operate.
                </p>
                <div className="quote-flip">
                  <div className="q">The objective</div>
                  <div className="a">
                    Not &quot;custom software&quot; — a system that removes friction.
                  </div>
                </div>
              </div>
              <div>
                <div className="service-visual" aria-hidden="true">
                  <span className="cap">Built around your system</span>
                  <svg viewBox="0 0 300 150">
                    <path
                      className="net-edge"
                      style={{ animationDelay: ".1s" }}
                      d="M45 40 L150 25"
                    />
                    <path
                      className="net-edge"
                      style={{ animationDelay: ".2s" }}
                      d="M150 25 L255 50"
                    />
                    <path
                      className="net-edge"
                      style={{ animationDelay: ".3s" }}
                      d="M45 40 L65 115"
                    />
                    <path
                      className="net-edge"
                      style={{ animationDelay: ".4s" }}
                      d="M150 25 L185 110"
                    />
                    <path
                      className="net-edge"
                      style={{ animationDelay: ".5s" }}
                      d="M255 50 L250 125"
                    />
                    <path
                      className="net-edge"
                      style={{ animationDelay: ".6s" }}
                      d="M65 115 L185 110"
                    />
                    <path
                      className="net-edge"
                      style={{ animationDelay: ".7s" }}
                      d="M185 110 L250 125"
                    />
                    <g
                      className="net-node"
                      style={{ animationDelay: ".1s" }}
                    >
                      <circle cx="45" cy="40" r="9" />
                      <text x="45" y="20" textAnchor="middle">
                        CRM
                      </text>
                    </g>
                    <g
                      className="net-node"
                      style={{ animationDelay: ".3s" }}
                    >
                      <circle cx="150" cy="25" r="9" />
                      <text x="150" y="15" textAnchor="middle">
                        Ops
                      </text>
                    </g>
                    <g
                      className="net-node"
                      style={{ animationDelay: ".5s" }}
                    >
                      <circle cx="255" cy="50" r="9" />
                      <text x="255" y="30" textAnchor="middle">
                        Reporting
                      </text>
                    </g>
                    <g
                      className="net-node"
                      style={{ animationDelay: ".7s" }}
                    >
                      <circle cx="65" cy="115" r="9" />
                      <text x="65" y="135" textAnchor="middle">
                        Inventory
                      </text>
                    </g>
                    <g
                      className="net-node"
                      style={{ animationDelay: ".9s" }}
                    >
                      <circle cx="185" cy="110" r="9" />
                      <text x="185" y="130" textAnchor="middle">
                        Approvals
                      </text>
                    </g>
                    <g
                      className="net-node"
                      style={{ animationDelay: "1.1s" }}
                    >
                      <circle cx="250" cy="125" r="9" />
                      <text x="250" y="145" textAnchor="middle">
                        Vendors
                      </text>
                    </g>
                  </svg>
                </div>
                <p className="service-label">We build software for</p>
                <div className="tag-list">
                  <span className="tag">CRM</span>
                  <span className="tag">ERP</span>
                  <span className="tag">Inventory</span>
                  <span className="tag">Project management</span>
                  <span className="tag">Customer portals</span>
                  <span className="tag">Approval systems</span>
                  <span className="tag">Reporting</span>
                  <span className="tag">Workflow management</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* WHO WE WORK WITH */}
        <section className="section" id="who">
          <div className="wrap">
            <div className="section-head">
              <span className="eyebrow mono">Who We Work With</span>
              <h2>Built for people who need software to do something.</h2>
            </div>
            <div className="who-grid">
              <div className="who-cell">
                <span className="idx mono">01</span>
                <h4>Founders</h4>
                <p>You have the idea — we build the product.</p>
                <span className="tag">Idea → MVP → Scale</span>
              </div>
              <div className="who-cell">
                <span className="idx mono">02</span>
                <h4>Startups</h4>
                <p>Move fast without the technical mess.</p>
              </div>
              <div className="who-cell">
                <span className="idx mono">03</span>
                <h4>Growing Businesses</h4>
                <p>Turn spreadsheets into connected systems.</p>
              </div>
              <div className="who-cell">
                <span className="idx mono">04</span>
                <h4>SaaS Companies</h4>
                <p>We become your extra engineering capacity.</p>
              </div>
              <div className="who-cell">
                <span className="idx mono">05</span>
                <h4>Agencies</h4>
                <p>White-label delivery behind your strategy.</p>
                <span className="tag">White-label available</span>
              </div>
              <div className="who-cell">
                <span className="idx mono">06</span>
                <h4>Enterprises</h4>
                <p>An extension of your existing team.</p>
              </div>
            </div>
          </div>
        </section>

        {/* DEVELOPMENT MODEL */}
        <section className="section section-dark" id="work-model">
          <div className="wrap">
            <div className="section-head">
              <span className="eyebrow mono">Our Development Model</span>
              <h2>
                You don&apos;t need another vendor. You need an engineering
                partner.
              </h2>
              <p className="sub">Six steps. No black boxes.</p>
            </div>
            <div className="model-steps">
              <div className="step-card">
                <div className="step-top">
                  <span className="step-icon">
                    <svg className="icon" viewBox="0 0 24 24">
                      <circle cx="10" cy="10" r="6.5" />
                      <line x1="14.8" y1="14.8" x2="20.5" y2="20.5" />
                    </svg>
                  </span>
                  <span className="step-num">01 / 06</span>
                </div>
                <h4>Discovery</h4>
                <p>We understand the business before touching code.</p>
                <div className="step-tags">
                  <span className="tag">Objectives</span>
                  <span className="tag">Users</span>
                  <span className="tag">Workflows</span>
                </div>
              </div>
              <div className="step-card">
                <div className="step-top">
                  <span className="step-icon">
                    <svg className="icon" viewBox="0 0 24 24">
                      <rect x="3" y="3" width="18" height="18" rx="2" />
                      <line x1="3" y1="9" x2="21" y2="9" />
                      <line x1="9" y1="9" x2="9" y2="21" />
                    </svg>
                  </span>
                  <span className="step-num">02 / 06</span>
                </div>
                <h4>Product Architecture</h4>
                <p>We translate requirements into a technical blueprint.</p>
                <div className="step-tags">
                  <span className="tag">System design</span>
                  <span className="tag">Data model</span>
                  <span className="tag">Security</span>
                </div>
              </div>
              <div className="step-card">
                <div className="step-top">
                  <span className="step-icon">
                    <svg className="icon" viewBox="0 0 24 24">
                      <path d="M8 6 3 12l5 6M16 6l5 6-5 6" />
                    </svg>
                  </span>
                  <span className="step-num">03 / 06</span>
                </div>
                <h4>Design &amp; Build</h4>
                <p>Focused iterations, with visibility at every step.</p>
                <div className="step-tags">
                  <span className="tag">Iterative</span>
                  <span className="tag">Transparent</span>
                </div>
              </div>
              <div className="step-card">
                <div className="step-top">
                  <span className="step-icon">
                    <svg className="icon" viewBox="0 0 24 24">
                      <path d="M12 3l7 3v5c0 4.5-3 7.8-7 9.5-4-1.7-7-5-7-9.5V6l7-3Z" />
                      <path d="M9 12l2 2 4-4" />
                    </svg>
                  </span>
                  <span className="step-num">04 / 06</span>
                </div>
                <h4>Test</h4>
                <p>
                  Finished means production-ready — not just working
                  locally.
                </p>
                <div className="step-tags">
                  <span className="tag">Performance</span>
                  <span className="tag">Edge cases</span>
                  <span className="tag">Security</span>
                </div>
              </div>
              <div className="step-card">
                <div className="step-top">
                  <span className="step-icon">
                    <svg className="icon" viewBox="0 0 24 24">
                      <path d="M12 2.5c3.2 2.3 5 5.6 5 9.3 0 2.1-.9 4.1-2.2 5.4L12 20l-2.8-2.8C7.9 15.9 7 13.9 7 11.8c0-3.7 1.8-7 5-9.3Z" />
                      <circle cx="12" cy="10.5" r="1.8" fill="currentColor" />
                      <path d="M8.5 17 6 21M15.5 17 18 21" />
                    </svg>
                  </span>
                  <span className="step-num">05 / 06</span>
                </div>
                <h4>Launch</h4>
                <p>From development to production, fully configured.</p>
                <div className="step-tags">
                  <span className="tag">Deployment</span>
                  <span className="tag">Monitoring</span>
                  <span className="tag">Support</span>
                </div>
              </div>
              <div className="step-card">
                <div className="step-top">
                  <span className="step-icon">
                    <svg className="icon" viewBox="0 0 24 24">
                      <path d="M5 6 12 17 19 6" />
                      <path d="M13.5 6H19V11.5" />
                    </svg>
                  </span>
                  <span className="step-num">06 / 06</span>
                </div>
                <h4>Improve</h4>
                <p>Launch is where the real learning starts.</p>
                <div className="step-tags">
                  <span className="tag">Optimise → Extend → Scale</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* WHY VULO */}
        <section className="section" id="why">
          <div className="wrap">
            <div className="section-head">
              <span className="eyebrow mono">Why Vulo Labs</span>
              <h2>Software should make your business easier to run.</h2>
            </div>
            <div className="why-grid">
              <div className="why-cell">
                <h4>Product Thinking First</h4>
                <p>
                  We understand why a product exists before deciding how to
                  build it.
                </p>
              </div>
              <div className="why-cell">
                <h4>No Unnecessary Complexity</h4>
                <p>The right architecture for your stage — nothing more.</p>
              </div>
              <div className="why-cell">
                <h4>Built to Evolve</h4>
                <p>
                  Your first version won&apos;t be your last. We build for
                  change.
                </p>
              </div>
              <div className="why-cell">
                <h4>Business-First</h4>
                <p>
                  Software that supports revenue, efficiency and growth —
                  not just specs.
                </p>
              </div>
              <div className="why-cell featured">
                <div>
                  <h4>One Team, Idea to Production</h4>
                  <p>
                    No coordinating five vendors separately. One team, start
                    to finish.
                  </p>
                </div>
                <span className="mono" style={{ color: "#7A7A7E" }}>
                  Design → Dev → DevOps → QA
                </span>
              </div>
            </div>
          </div>
        </section>

        {/* TECHNOLOGY */}
        <section className="section" id="tech">
          <div className="wrap">
            <div className="section-head">
              <span className="eyebrow mono">Technology</span>
              <h2>Modern technology. Practical architecture.</h2>
              <p className="sub">
                We choose tools based on what the product needs.
              </p>
            </div>
            <div className="tech-grid">
              <div className="tech-col">
                <h5>
                  <svg className="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M8 6 3 12l5 6M16 6l5 6-5 6" />
                  </svg>
                  Frontend
                </h5>
                <ul>
                  <li>Next.js</li>
                  <li>React</li>
                  <li>TypeScript</li>
                  <li>Tailwind CSS</li>
                </ul>
              </div>
              <div className="tech-col">
                <h5>
                  <svg className="icon icon-sm" viewBox="0 0 24 24">
                    <rect x="4" y="4" width="16" height="6" rx="1.5" />
                    <rect x="4" y="14" width="16" height="6" rx="1.5" />
                    <circle cx="7.5" cy="7" r=".6" fill="currentColor" />
                    <circle cx="7.5" cy="17" r=".6" fill="currentColor" />
                  </svg>
                  Backend
                </h5>
                <ul>
                  <li>Node.js</li>
                  <li>Python</li>
                  <li>PHP</li>
                  <li>GraphQL</li>
                </ul>
              </div>
              <div className="tech-col">
                <h5>
                  <svg className="icon icon-sm" viewBox="0 0 24 24">
                    <ellipse cx="12" cy="6" rx="7" ry="3" />
                    <path d="M5 6v12c0 1.7 3.1 3 7 3s7-1.3 7-3V6" />
                    <path d="M5 12c0 1.7 3.1 3 7 3s7-1.3 7-3" />
                  </svg>
                  Databases
                </h5>
                <ul>
                  <li>PostgreSQL</li>
                  <li>MySQL</li>
                  <li>MongoDB</li>
                  <li>Redis</li>
                </ul>
              </div>
              <div className="tech-col">
                <h5>
                  <svg className="icon icon-sm" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="3" width="7" height="7" rx="1.5" />
                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                    <rect x="14" y="14" width="7" height="7" rx="1.5" />
                  </svg>
                  CMS
                </h5>
                <ul>
                  <li>WordPress</li>
                  <li>WooCommerce</li>
                  <li>Headless CMS</li>
                </ul>
              </div>
              <div className="tech-col">
                <h5>
                  <svg className="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M7 18a4 4 0 1 1 .7-7.9A5 5 0 0 1 17 9a4 4 0 0 1 0 8H7Z" />
                  </svg>
                  Cloud
                </h5>
                <ul>
                  <li>AWS</li>
                  <li>Google Cloud</li>
                  <li>Vercel</li>
                  <li>Docker</li>
                </ul>
              </div>
              <div className="tech-col">
                <h5>
                  <svg className="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M9 15a4 4 0 0 1 0-6l2-2a4 4 0 0 1 6 6l-1 1" />
                    <path d="M15 9a4 4 0 0 1 0 6l-2 2a4 4 0 0 1-6-6l1-1" />
                  </svg>
                  Integrations
                </h5>
                <ul>
                  <li>Stripe · Razorpay</li>
                  <li>HubSpot · Salesforce</li>
                  <li>WhatsApp</li>
                </ul>
              </div>
              <div className="tech-col">
                <h5>
                  <svg className="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M18.4 5.6l-2.8 2.8M8.4 15.6l-2.8 2.8" />
                  </svg>
                  AI
                </h5>
                <ul>
                  <li>LLM APIs</li>
                  <li>RAG</li>
                  <li>Vector DBs</li>
                  <li>AI agents</li>
                </ul>
              </div>
            </div>
            <div className="tech-principle">
              <span className="p1">Technology is the tool.</span>
              <span className="p2">The product is the objective.</span>
            </div>
          </div>
        </section>

        {/* CASE STUDIES */}
        <section className="section" id="case-studies">
          <div className="wrap">
            <div className="section-head">
              <span className="eyebrow mono">Case Studies</span>
              <h2>We build systems that businesses depend on.</h2>
            </div>
            <div className="case-grid">
              <a
                className="case-card featured"
                href="https://vulopilot.com"
                target="_blank"
                rel="noopener"
              >
                <div className="mockup mockup-chat">
                  <div className="chrome">
                    <span></span>
                    <span></span>
                    <span></span>
                  </div>
                  <div className="mockup-body">
                    <div className="bubble left">
                      <i className="ln" style={{ width: "70px" }}></i>
                      <i className="ln" style={{ width: "44px" }}></i>
                    </div>
                    <div className="bubble right">
                      <i className="ln" style={{ width: "56px" }}></i>
                    </div>
                    <div className="bubble left">
                      <i className="ln" style={{ width: "60px" }}></i>
                      <i className="ln" style={{ width: "36px" }}></i>
                    </div>
                  </div>
                </div>
                <span
                  className="num"
                  style={{ display: "flex", alignItems: "center", gap: "8px" }}
                >
                  01 — VuloPilot ↗{" "}
                  <span
                    className="tag"
                    style={{ padding: "3px 9px", fontSize: "11px" }}
                  >
                    Live
                  </span>
                </span>
                <h4>An AI product we built and run ourselves.</h4>
                <p>
                  Live proof of the same engineering standard we bring to
                  client work.
                </p>
              </a>
              <div className="case-card">
                <div className="mockup mockup-website">
                  <div className="chrome">
                    <span></span>
                    <span></span>
                    <span></span>
                  </div>
                  <div className="mockup-body">
                    <div className="hero-block"></div>
                    <div className="grid3">
                      <i></i>
                      <i></i>
                      <i></i>
                    </div>
                  </div>
                </div>
                <span className="num">02 — WordPress Ecosystem</span>
                <h4>A high-performance WordPress platform.</h4>
                <p>CRM, automation and analytics in one fast platform.</p>
              </div>
              <div className="case-card">
                <div className="mockup mockup-table">
                  <div className="chrome">
                    <span></span>
                    <span></span>
                    <span></span>
                  </div>
                  <div className="mockup-body">
                    <div className="t-row">
                      <div></div>
                      <div></div>
                      <div></div>
                    </div>
                    <div className="t-row">
                      <div></div>
                      <div></div>
                      <div></div>
                    </div>
                    <div className="t-row">
                      <div></div>
                      <div></div>
                      <div></div>
                    </div>
                    <div className="t-row">
                      <div></div>
                      <div></div>
                      <div></div>
                    </div>
                  </div>
                </div>
                <span className="num">03 — Business Operations</span>
                <h4>Replaced spreadsheets with one system.</h4>
                <p>
                  A single connected platform for a business that had
                  outgrown manual work.
                </p>
              </div>
              <div className="case-card">
                <div className="mockup">
                  <div className="chrome">
                    <span></span>
                    <span></span>
                    <span></span>
                  </div>
                  <div className="mockup-body">
                    <div className="m-side"></div>
                    <div className="m-main">
                      <div className="m-row"></div>
                      <div className="m-cards">
                        <i></i>
                        <i></i>
                        <i></i>
                      </div>
                      <div className="m-row short"></div>
                    </div>
                  </div>
                </div>
                <span className="num">04 — AI-Powered Product</span>
                <h4>AI built around a real bottleneck.</h4>
                <p>
                  An AI-enabled application for a high-volume operational
                  workflow.
                </p>
              </div>
            </div>
            <p className="case-note">
              More case studies coming as projects ship.
            </p>
            <div style={{ marginTop: "20px" }}>
              <a className="btn btn-ghost" href="#final-cta">
                View Our Work →
              </a>
            </div>
          </div>
        </section>

        {/* COMMERCIAL MODEL */}
        <section className="section" id="commercial">
          <div className="wrap">
            <div className="section-head">
              <span className="eyebrow mono">The Commercial Model</span>
              <h2>Build the way your business needs.</h2>
            </div>
            <div className="model-grid">
              <div className="model-card">
                <h4>Fixed-Scope</h4>
                <p style={{ fontSize: "13px" }}>
                  Clearly defined websites and product builds.
                </p>
                <div className="best">
                  <b>Best when</b>Scope is predictable.
                </div>
              </div>
              <div className="model-card">
                <h4>MVP Development</h4>
                <p style={{ fontSize: "13px" }}>
                  For founders validating a new product.
                </p>
                <div className="best">
                  <b>Best when</b>You need to learn fast.
                </div>
              </div>
              <div className="model-card">
                <h4>Dedicated Engineering</h4>
                <p style={{ fontSize: "13px" }}>
                  Continuous development capacity.
                </p>
                <div className="best">
                  <b>Best when</b>You have an active roadmap.
                </div>
              </div>
              <div className="model-card">
                <h4>Product Partnership</h4>
                <p style={{ fontSize: "13px" }}>
                  A long-term technical partner.
                </p>
                <div className="best">
                  <b>Best when</b>Tech is core to the business.
                </div>
              </div>
            </div>
            <div style={{ marginTop: "24px" }}>
              <a className="btn btn-primary" href="#final-cta">
                Tell Us What You&apos;re Building →
              </a>
            </div>
          </div>
        </section>

        {/* FAQ */}
        <section className="section" id="faq">
          <div className="wrap">
            <div className="section-head">
              <span className="eyebrow mono">FAQ</span>
              <h2>Questions before we build?</h2>
            </div>
            <div className="faq">
              <details open>
                <summary>
                  Do you only build SaaS products?
                  <span className="plus">+</span>
                </summary>
                <div className="faq-body">
                  <p>
                    No — SaaS, WordPress, custom software, AI and
                    integrations.
                  </p>
                </div>
              </details>
              <details>
                <summary>
                  Can you build an MVP from an idea?
                  <span className="plus">+</span>
                </summary>
                <div className="faq-body">
                  <p>Yes — no spec required. We&apos;ll help scope it.</p>
                </div>
              </details>
              <details>
                <summary>
                  Do you work with existing products?
                  <span className="plus">+</span>
                </summary>
                <div className="faq-body">
                  <p>
                    Yes — features, fixes, integrations or ongoing capacity.
                  </p>
                </div>
              </details>
              <details>
                <summary>
                  Do you provide WordPress development?
                  <span className="plus">+</span>
                </summary>
                <div className="faq-body">
                  <p>Yes — from simple sites to complex platforms.</p>
                </div>
              </details>
              <details>
                <summary>
                  Can you work with our existing developers?
                  <span className="plus">+</span>
                </summary>
                <div className="faq-body">
                  <p>Yes — we plug into your team.</p>
                </div>
              </details>
              <details>
                <summary>
                  Can you work with agencies?
                  <span className="plus">+</span>
                </summary>
                <div className="faq-body">
                  <p>Yes — including white-label delivery.</p>
                </div>
              </details>
              <details>
                <summary>
                  How early should we involve you?
                  <span className="plus">+</span>
                </summary>
                <div className="faq-body">
                  <p>
                    As early as possible — decisions are cheaper before
                    they&apos;re built.
                  </p>
                </div>
              </details>
              <details>
                <summary>
                  Do you provide ongoing support?
                  <span className="plus">+</span>
                </summary>
                <div className="faq-body">
                  <p>Yes — maintenance, features and dedicated capacity.</p>
                </div>
              </details>
            </div>
          </div>
        </section>

        {/* FINAL CTA + FOOTER (dark closer) */}
        <section className="section section-dark closer" id="final-cta">
          <div className="wrap">
            <div className="closer-inner">
              <span className="eyebrow mono" style={{ color: "#7A7A7E" }}>
                Let&apos;s Talk
              </span>
              <h2>Have a product in your head? Let&apos;s build it.</h2>
              <p className="sub">
                No spec. No framework decisions. Just tell us what
                you&apos;re building.
              </p>
              <div
                className="cta-row"
                style={{ justifyContent: "center", marginTop: "6px" }}
              >
                <a className="btn btn-primary" href="mailto:hello@vulolabs.com">
                  Start a Project →
                </a>
                <a className="btn btn-ghost" href="mailto:hello@vulolabs.com">
                  Book a Discovery Call →
                </a>
              </div>
              <span className="final-note">
                Usually takes less than 15 minutes.
              </span>
            </div>

            <Footer />
          </div>
        </section>
      </main>
    </>
  );
}
