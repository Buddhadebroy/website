"""Make saved Kadence block HTML exactly what Kadence's own save() writes, so the editor never says
"Block contains unexpected or invalid content". (Kadence Section: kadence/column save.js adds
kb-section-dir-* classes from the direction setting, align*, kvs-*-false, sticky / link classes.)"""
import json, re

COL = re.compile(r'(<!-- wp:kadence/column (\{.*?\}) -->\s*<(\w+) class=")([^"]*)(")', re.S)


def column_classes(a):
    u = a.get('uniqueID')
    d = a.get('direction') or []
    want = ['wp-block-kadence-column']
    if u:
        want.append('kadence-column%s' % u)
    if a.get('vsdesk'):
        want.append('kvs-lg-false')
    if a.get('vstablet'):
        want.append('kvs-md-false')
    if a.get('vsmobile'):
        want.append('kvs-sm-false')
    if a.get('link'):
        want.append('kb-section-has-link')
    if a.get('sticky'):
        want.append('kb-section-is-sticky')
    if a.get('align') in ('full', 'wide'):
        want.append('align' + a['align'])
    for i, k in enumerate(('kb-section-dir-%s', 'kb-section-md-dir-%s', 'kb-section-sm-dir-%s')):
        if len(d) > i and d[i]:
            want.append(k % d[i])
    if a.get('className'):
        want += a['className'].split()
    return want


def fix(markup):
    def rep(m):
        try:
            a = json.loads(m.group(2))
        except ValueError:
            return m.group(0)
        have = m.group(4).split()
        out = column_classes(a)
        out += [c for c in have if c not in out and not c.startswith(('kb-section-dir-', 'kb-section-md-dir-', 'kb-section-sm-dir-'))]
        return m.group(1) + ' '.join(out) + m.group(5)
    return COL.sub(rep, markup)
