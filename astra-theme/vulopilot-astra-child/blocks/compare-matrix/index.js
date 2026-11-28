/**
 * Block editor script for vulopilot/compare-matrix (built file: only used inside the editor).
 * The front-end output is in render.php next to this file.
 */
(() => {
	var P = (o, t) => () => {
		try {
			return t || o((t = { exports: {} }).exports, t), t.exports;
		} catch (n) {
			throw ((t = 0), n);
		}
	};
	var k = P((W, I) => {
		I.exports = {
			$schema: 'https://schemas.wp.org/trunk/block.json',
			apiVersion: 3,
			category: 'vulopilot',
			textdomain: 'vulopilot',
			editorScript: 'file:./index.js',
			name: 'vulopilot/compare-matrix',
			title: 'Plan Comparison (VuloPilot)',
			icon: 'editor-table',
			description:
				'Free vs Paid feature matrix with expandable groups, a \u2018Paid extras only\u2019 switch and Expand all.',
			keywords: ['pricing', 'plans', 'compare', 'matrix', 'features'],
			viewScript: 'file:./view.js',
			render: 'file:./render.php',
			supports: { html: !1, align: ['wide', 'full'], anchor: !0, spacing: { margin: !0, padding: !0 } },
			attributes: {
				showHeading: { type: 'boolean', default: !0 },
				eyebrow: { type: 'string', default: 'Compare plans' },
				title: { type: 'string', default: 'Every feature,' },
				titleAccent: { type: 'string', default: 'side by side.' },
				subtitle: { type: 'string', default: '{free} features are free. Paid plans add {paid} more.' },
				freeLabel: { type: 'string', default: 'Free' },
				freePrice: { type: 'string', default: '$0' },
				paidLabel: { type: 'string', default: 'Paid' },
				paidPrice: { type: 'string', default: 'from $19/mo' },
				toggleLabel: { type: 'string', default: 'Paid extras only' },
				expandLabel: { type: 'string', default: 'Expand all' },
				collapseLabel: { type: 'string', default: 'Collapse all' },
				paidTag: { type: 'string', default: 'Paid' },
				freeCountLabel: { type: 'string', default: 'free' },
				paidCountLabel: { type: 'string', default: 'paid' },
				limits: {
					type: 'array',
					default: [
						{ icon: 'server', label: 'Sites', free: 'Free 1 \xB7', paid: '1 / 5 / 25' },
						{ icon: 'coins', label: 'AI credits / mo', free: 'Free 100 \xB7', paid: '1k / 5k / 10k' },
					],
				},
				groups: {
					type: 'array',
					default: [
						{
							title: 'AI Copilot',
							icon: 'bot',
							free: `Copilot Q&A: questions answered from your real scan results
Blog drafting, saved as drafts and never published for you`,
							paid: `Full interactive chat with your site
MCP server for Claude Desktop and other AI tools`,
							linkText: '',
							linkUrl: '',
							open: !0,
						},
						{
							title: 'Search Visibility',
							icon: 'search',
							free: `SEO score and a ranked problem list
Page titles and descriptions
Sitemap and instant indexing (IndexNow)
Redirects, 404 tracking and robots.txt
Per-page SEO panel in the post editor
Table of Contents and FAQ blocks
Site verification and Google Search Console connection`,
							paid: `Technical SEO: schema, sitemap, duplicate descriptions, multiple H1s, focus keywords
Keyword rank tracking, opportunities and groups
One-click fixes, single or in bulk`,
							linkText: 'About Search Visibility \u2192',
							linkUrl: '/features/search-visibility/',
							open: !0,
						},
						{
							title: 'AI Search',
							icon: 'radar',
							free: `GEO: AI summary, question coverage, evidence, structure, entity clarity, freshness
AEO: answer-readiness checks and engine testing
Brand visibility: Trust and Authority scores
Crawler traffic from GPTBot, ClaudeBot and PerplexityBot
Editable llms.txt
Business profile, knowledge graph and schema coverage`,
							paid: `Answer engine citation coverage and stale-content flags
AI crawler tracking with alerts when a bot stops or is blocked
Deeper brand and knowledge graph findings`,
							linkText: 'About AI Search \u2192',
							linkUrl: '/features/ai-search/',
							open: !1,
						},
						{
							title: 'Website Health',
							icon: 'heart-pulse',
							free: `WordPress health checkup: updates, database, scheduled tasks, server
Manual and automatic backups
Download, restore or delete backups
WCAG-based accessibility scan
Manual accessibility checklist`,
							paid: `Copy backups to Amazon S3 or Google Drive, credentials stored encrypted
Scheduled accessibility audits (hourly to weekly) with score history`,
							linkText: 'About Website Health \u2192',
							linkUrl: '/features/website-health/',
							open: !1,
						},
						{
							title: 'Performance',
							icon: 'gauge',
							free: `Overall speed score, mobile vs desktop
Core Web Vitals
Biggest speed opportunity and slow-page finder
AI speed assistant in plain language
Real-time monitoring and speed history
Quick fixes: clear caches, minify, optimize images, database cleanup`,
							paid: '',
							linkText: 'About Performance \u2192',
							linkUrl: '/features/performance/',
							open: !1,
						},
						{
							title: 'Security',
							icon: 'shield-check',
							free: `Security score and ranked problems
Login and account checks
Password-guessing protection and attack filter
Public exposure and browser protection checks
Alerts when something looks wrong`,
							paid: `Known vulnerability feed for WordPress core, plugins and themes
File change monitoring
Exposed files, debug mode, security headers and XML-RPC checks
Incident log with alerts`,
							linkText: 'About Security \u2192',
							linkUrl: '/features/security/',
							open: !1,
						},
						{
							title: 'Content',
							icon: 'edit3',
							free: `AI Writer and Blog Generator
Before and after preview, approve or reject
Duplicate title fixer
Content audit and readability checks
Content stats`,
							paid: `Content gaps against competitors
Readability and structure suggestions
AI drafts for titles, descriptions and summaries`,
							linkText: 'About Content \u2192',
							linkUrl: '/features/content/',
							open: !1,
						},
						{
							title: 'WooCommerce',
							icon: 'shopping-bag',
							free: `Product, checkout and store issue list
AI sales assistant with review-first suggestions
Product schema checks`,
							paid: `Missing images, duplicate SKUs, pricing gaps, low stock, failed payments
Revenue, top products and unsold products
Abandoned carts and revenue at risk
Daily store trends
Bulk AI product writing, up to 50 at a time`,
							linkText: 'About WooCommerce \u2192',
							linkUrl: '/features/woocommerce/',
							open: !1,
						},
						{
							title: 'Automation',
							icon: 'calendar-clock',
							free: `Scheduled full-site scan: daily, weekly or monthly
Emailed visibility report
History timeline with search, export and undo
PDF or CSV reports`,
							paid: `Workflow builder with conditions, retries and run logs
Custom and health reports, scheduled and exported as PDF
AI credit analytics`,
							linkText: 'About Automation \u2192',
							linkUrl: '/features/automation/',
							open: !1,
						},
					],
				},
				maxWidth: { type: 'number', default: 896 },
				titleSize: { type: 'number', default: 0 },
				colorPrimary: { type: 'string', default: '' },
				colorPrimarySoft: { type: 'string', default: '' },
				colorAccent: { type: 'string', default: '' },
				colorBg: { type: 'string', default: '' },
				colorSurface: { type: 'string', default: '' },
				colorText: { type: 'string', default: '' },
				colorMuted: { type: 'string', default: '' },
				colorBorder: { type: 'string', default: '' },
				radius: { type: 'number', default: 4 },
			},
		};
	});
	var { __: p } = wp.i18n,
		{ PanelColorSettings: S } = wp.blockEditor,
		{
			PanelBody: d,
			RangeControl: g,
			Button: c,
			ComboboxControl: A,
			TextControl: s,
			TextareaControl: f,
			ToggleControl: h,
			Flex: T,
			FlexItem: b,
		} = wp.components,
		B = window.VP_EDITOR || { registry: {}, icons: [], checklists: [] },
		L = [
			['colorPrimary', p('Primary (blue)', 'vulopilot'), '#002991'],
			['colorPrimarySoft', p('Primary soft', 'vulopilot'), '#dfe7fb'],
			['colorAccent', p('Accent (Paid / highlight)', 'vulopilot'), '#ff5d50'],
			['colorText', p('Text', 'vulopilot'), '#0a1230'],
			['colorMuted', p('Muted text', 'vulopilot'), '#5d6784'],
			['colorSurface', p('Card background', 'vulopilot'), '#ffffff'],
			['colorBg', p('Soft background', 'vulopilot'), '#f5f7fc'],
			['colorBorder', p('Borders', 'vulopilot'), '#e1e6f2'],
		];
	function y({ attributes: o, setAttributes: t, children: n }) {
		return wp.element.createElement(
			wp.element.Fragment,
			null,
			wp.element.createElement(S, {
				title: p('Colours', 'vulopilot'),
				initialOpen: !1,
				colorSettings: L.map(([r, e]) => ({ label: e, value: o[r] || void 0, onChange: (i) => t({ [r]: i || '' }) })),
			}),
			wp.element.createElement(
				d,
				{ title: p('Shape & size', 'vulopilot'), initialOpen: !1 },
				wp.element.createElement(g, {
					label: p('Corner radius (px)', 'vulopilot'),
					value: o.radius,
					min: 0,
					max: 32,
					onChange: (r) => t({ radius: r != null ? r : 4 }),
					allowReset: !0,
					resetFallbackValue: 4,
				}),
				n,
			),
		);
	}
	function m({ label: o, value: t, onChange: n }) {
		let r = B.icons.map((e) => ({ value: e, label: e }));
		return wp.element.createElement(A, {
			label: o || p('Icon', 'vulopilot'),
			value: t,
			options: r,
			onChange: (e) => n(e || 'sparkles'),
			help: p('Lucide icon name, e.g. search, radar, bot', 'vulopilot'),
		});
	}
	function v({ list: o, index: t, onChange: n, label: r }) {
		let e = (i) => {
			let a = [...o],
				[x] = a.splice(t, 1);
			a.splice(t + i, 0, x), n(a);
		};
		return wp.element.createElement(
			T,
			{ justify: 'flex-end', gap: 1, style: { marginBottom: 12 } },
			wp.element.createElement(
				b,
				null,
				wp.element.createElement(c, {
					size: 'small',
					variant: 'secondary',
					disabled: t === 0,
					onClick: () => e(-1),
					label: p('Move up', 'vulopilot'),
					icon: 'arrow-up-alt2',
				}),
			),
			wp.element.createElement(
				b,
				null,
				wp.element.createElement(c, {
					size: 'small',
					variant: 'secondary',
					disabled: t === o.length - 1,
					onClick: () => e(1),
					label: p('Move down', 'vulopilot'),
					icon: 'arrow-down-alt2',
				}),
			),
			wp.element.createElement(
				b,
				null,
				wp.element.createElement(c, {
					size: 'small',
					variant: 'secondary',
					isDestructive: !0,
					onClick: () => {
						window.confirm(p('Remove this ', 'vulopilot') + (r || p('item', 'vulopilot')) + '?') &&
							n(o.filter((i, a) => a !== t));
					},
					icon: 'trash',
					label: p('Remove', 'vulopilot'),
				}),
			),
		);
	}
	var u = (o, t, n) => o.map((r, e) => (e === t ? { ...r, ...n } : r));
	var { __: l } = wp.i18n,
		{ registerBlockType: F } = wp.blocks,
		{ InspectorControls: C, useBlockProps: O } = wp.blockEditor,
		E = wp.serverSideRender,
		w = k();
	F(w.name, {
		edit({ attributes: o, setAttributes: t }) {
			let n = o.groups || [],
				r = o.limits || [];
			return wp.element.createElement(
				'div',
				{ ...O() },
				wp.element.createElement(
					C,
					null,
					wp.element.createElement(
						d,
						{ title: l('Heading', 'vulopilot') },
						wp.element.createElement(h, {
							label: l('Show heading', 'vulopilot'),
							checked: o.showHeading,
							onChange: (e) => t({ showHeading: e }),
						}),
						o.showHeading &&
							wp.element.createElement(
								wp.element.Fragment,
								null,
								wp.element.createElement(s, {
									label: l('Small label (eyebrow)', 'vulopilot'),
									value: o.eyebrow,
									onChange: (e) => t({ eyebrow: e }),
								}),
								wp.element.createElement(s, {
									label: l('Title', 'vulopilot'),
									value: o.title,
									onChange: (e) => t({ title: e }),
								}),
								wp.element.createElement(s, {
									label: l('Title \u2013 coloured part', 'vulopilot'),
									value: o.titleAccent,
									onChange: (e) => t({ titleAccent: e }),
								}),
								wp.element.createElement(f, {
									label: l('Subtitle', 'vulopilot'),
									help: l('{free} and {paid} are replaced with the counts.', 'vulopilot'),
									value: o.subtitle,
									onChange: (e) => t({ subtitle: e }),
								}),
								wp.element.createElement(g, {
									label: l('Title size (px, 0 = default)', 'vulopilot'),
									value: o.titleSize,
									min: 0,
									max: 80,
									onChange: (e) => t({ titleSize: e || 0 }),
								}),
							),
					),
					wp.element.createElement(
						d,
						{ title: l('Columns & labels', 'vulopilot'), initialOpen: !1 },
						wp.element.createElement(s, {
							label: l('Free column', 'vulopilot'),
							value: o.freeLabel,
							onChange: (e) => t({ freeLabel: e }),
						}),
						wp.element.createElement(s, {
							label: l('Free price', 'vulopilot'),
							value: o.freePrice,
							onChange: (e) => t({ freePrice: e }),
						}),
						wp.element.createElement(s, {
							label: l('Paid column', 'vulopilot'),
							value: o.paidLabel,
							onChange: (e) => t({ paidLabel: e }),
						}),
						wp.element.createElement(s, {
							label: l('Paid price', 'vulopilot'),
							value: o.paidPrice,
							onChange: (e) => t({ paidPrice: e }),
						}),
						wp.element.createElement(s, {
							label: l('Switch label', 'vulopilot'),
							value: o.toggleLabel,
							onChange: (e) => t({ toggleLabel: e }),
						}),
						wp.element.createElement(s, {
							label: l('\u201CExpand all\u201D text', 'vulopilot'),
							value: o.expandLabel,
							onChange: (e) => t({ expandLabel: e }),
						}),
						wp.element.createElement(s, {
							label: l('\u201CCollapse all\u201D text', 'vulopilot'),
							value: o.collapseLabel,
							onChange: (e) => t({ collapseLabel: e }),
						}),
						wp.element.createElement(s, {
							label: l('Paid tag', 'vulopilot'),
							value: o.paidTag,
							onChange: (e) => t({ paidTag: e }),
						}),
						wp.element.createElement(s, {
							label: l('Count word \u201Cfree\u201D', 'vulopilot'),
							value: o.freeCountLabel,
							onChange: (e) => t({ freeCountLabel: e }),
						}),
						wp.element.createElement(s, {
							label: l('Count word \u201Cpaid\u201D', 'vulopilot'),
							value: o.paidCountLabel,
							onChange: (e) => t({ paidCountLabel: e }),
						}),
					),
					wp.element.createElement(
						d,
						{ title: l('Limits row (Sites, AI credits\u2026)', 'vulopilot'), initialOpen: !1 },
						r.map((e, i) =>
							wp.element.createElement(
								'div',
								{ key: i, style: { borderBottom: '1px solid #ddd', marginBottom: 12 } },
								wp.element.createElement(s, {
									label: l('Label', 'vulopilot'),
									value: e.label,
									onChange: (a) => t({ limits: u(r, i, { label: a }) }),
								}),
								wp.element.createElement(m, { value: e.icon, onChange: (a) => t({ limits: u(r, i, { icon: a }) }) }),
								wp.element.createElement(s, {
									label: l('Free text', 'vulopilot'),
									value: e.free,
									onChange: (a) => t({ limits: u(r, i, { free: a }) }),
								}),
								wp.element.createElement(s, {
									label: l('Paid value', 'vulopilot'),
									value: e.paid,
									onChange: (a) => t({ limits: u(r, i, { paid: a }) }),
								}),
								wp.element.createElement(v, {
									list: r,
									index: i,
									onChange: (a) => t({ limits: a }),
									label: l('limit', 'vulopilot'),
								}),
							),
						),
						wp.element.createElement(
							c,
							{
								variant: 'secondary',
								onClick: () =>
									t({ limits: [...r, { icon: 'server', label: 'New limit', free: 'Free 1 \xB7', paid: '5' }] }),
							},
							l('Add limit', 'vulopilot'),
						),
					),
					n.map((e, i) =>
						wp.element.createElement(
							d,
							{ key: i, title: `${i + 1}. ${e.title || l('Group', 'vulopilot')}`, initialOpen: !1 },
							wp.element.createElement(s, {
								label: l('Group title', 'vulopilot'),
								value: e.title,
								onChange: (a) => t({ groups: u(n, i, { title: a }) }),
							}),
							wp.element.createElement(m, { value: e.icon, onChange: (a) => t({ groups: u(n, i, { icon: a }) }) }),
							wp.element.createElement(f, {
								label: l('Free features (one per line)', 'vulopilot'),
								rows: 6,
								value: e.free,
								onChange: (a) => t({ groups: u(n, i, { free: a }) }),
							}),
							wp.element.createElement(f, {
								label: l('Paid features (one per line)', 'vulopilot'),
								rows: 4,
								value: e.paid,
								onChange: (a) => t({ groups: u(n, i, { paid: a }) }),
							}),
							wp.element.createElement(s, {
								label: l('Link text', 'vulopilot'),
								value: e.linkText,
								onChange: (a) => t({ groups: u(n, i, { linkText: a }) }),
							}),
							wp.element.createElement(s, {
								label: l('Link URL', 'vulopilot'),
								value: e.linkUrl,
								onChange: (a) => t({ groups: u(n, i, { linkUrl: a }) }),
							}),
							wp.element.createElement(h, {
								label: l('Open on page load', 'vulopilot'),
								checked: !!e.open,
								onChange: (a) => t({ groups: u(n, i, { open: a }) }),
							}),
							wp.element.createElement(v, {
								list: n,
								index: i,
								onChange: (a) => t({ groups: a }),
								label: l('group', 'vulopilot'),
							}),
						),
					),
					wp.element.createElement(
						d,
						{ title: l('Add a feature group', 'vulopilot'), initialOpen: !1 },
						wp.element.createElement(
							c,
							{
								variant: 'primary',
								onClick: () =>
									t({
										groups: [
											...n,
											{
												title: 'New group',
												icon: 'sparkles',
												free: 'Free feature',
												paid: 'Paid feature',
												linkText: '',
												linkUrl: '',
												open: !1,
											},
										],
									}),
							},
							l('Add group', 'vulopilot'),
						),
					),
				),
				wp.element.createElement(
					C,
					{ group: 'styles' },
					wp.element.createElement(
						y,
						{ attributes: o, setAttributes: t },
						wp.element.createElement(g, {
							label: l('Max width (px)', 'vulopilot'),
							value: o.maxWidth,
							min: 480,
							max: 1400,
							step: 8,
							onChange: (e) => t({ maxWidth: e || 896 }),
						}),
					),
				),
				wp.element.createElement(E, { block: w.name, attributes: o, httpMethod: 'POST' }),
			);
		},
		save: () => null,
	});
})();
