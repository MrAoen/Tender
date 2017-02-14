var
    d = document,
    n = navigator,
    l = n.userAgent.toLowerCase(),
    i = l.indexOf('opera'),
    v,
    isO = i != -1,
    isOO = isO && parseFloat(l.substr(i + 6)) < 7,
    ie4u = (i = l.indexOf('msie')) >= 0 && !isO && d.all && d.all.item && (v = parseFloat(l.substr(i + 5))) >= 4,
    iebdy = 0,
    ie5 = ie4u && v >= 5,
    ie55 = ie5 && v >= 5.5,
    isN4 = n.appName == 'Netscape' && !d.getElementById && !isO,
    isMOZ = l.indexOf('gecko') != -1,
    ex_tm = 0,
    dyn = 0,
    wonl = [],
    tCSS = '',
    alerts = 0,
    topT = 0;

function und(o) {
    return typeof(o) == 'undefined' || o === '' || o == null;
}

function pInt(s) {
    return parseInt(('' + s).replace(/\D/, '').replace(/^0+/, ''));
}

function inArray(e, a) {
    for (var i in a)
        if (a[i] == e) return true;
    return false;
}

function pFloat(s) {
    s = Math.round(s * 100) / 100;
    var r = '' + s, a = r.indexOf('.');
    if (a > 0 && r.length > a + 3)
        r = r.substr(0, a + 3);
    return r;
}

function selected_radio(e, df)
// e - form element, df - default value (optional)
{
    if (und(e))
        return und(df) ? '' : df;
    for (i = 0; i < e.length; i++) {
        if (e[i].checked)
            return e[i].value;
    }
    return und(df) ? '' : df;
}

function tryex(s, t) {
    if (!ex_tm)
        ex_tm = setTimeout('ex_t("' + s.replace(/\\/g, '\\\\').replace(/\"/g, '\\"').replace(/\'/g, "\\'") + '")', und(t) ? 200 : t);
    return false;
}

function ex_t(s) {
    ex_tc();
    eval(s);
}

function ex_tc() {
    if (ex_tm) {
        clearTimeout(ex_tm);
        ex_tm = 0;
    }
}

function wload()
// window onload (unreal, called before endOfPage)
{
    for (i in wonl)
        eval(wonl[i]);
}

function winonload(s) {
    // real window onload event setup
    wonl.push(s);
}

function gl(i, p) {
    var l = und(p) ? document : p.document;
    if (und(l[i])) {
        var j, e, c = l.layers.length;
        if (c == 0) return;
        else
            for (j = 0; j < c; j++) {
                e = gl(i, l.layers[j]);
                if (!und(e))
                    return e;
            }
    }
    else
        return l[i];
}

function ge(i) {
    return typeof(i) == 'string' ? d.getElementById ? d.getElementById(i) : d.all ? d.all[i] : d.layers ? gl(i) : null : i;
}

function ges(i) {
    var l = ge(i);
    return und(l) ? null : d.layers ? l : l.style;
}

function getW(e) {
    e = ge(e);
    return und(e) ? 0 : isN4 ? e.clip.width : isOO ? ges(e).pixelWidth : e.offsetWidth;
}

function getH(e) {
    e = ge(e);
    return und(e) ? 0 : isN4 ? e.clip.height : isOO ? ges(e).pixelHeight : e.offsetHeight;
}

function setW(e, w) {
    e = ge(e);
    if (und(e)) return;
    var s = ges(e);
    if (isN4) e.resizeTo(w, getH(e));
    else if (isOO) s.pixelWidth = w;
    else s.width = w + 'px';
}

function setH(e, h) {
    e = ge(e);
    if (und(e)) return;
    var s = ges(e);
    if (isN4) e.resizeTo(getW(e), h);
    else if (isOO) s.pixelHeight = h;
    else s.height = h + 'px';
}

function setpos(e, x, y) {
    var s = ges(e);
    if (und(s)) return;
    if (isN4)
        s.moveTo(x, y);
    else if (isOO) {
        s.pixelLeft = x;
        s.pixelTop = y;
    }
    else {
        s.left = x + 'px';
        s.top = y + 'px';
    }
}

function setvis(e, v, tr, bl) {
    var s = ges(e);
    if (und(s)) return;
    s.visibility = v ? isN4 ? 'show' : 'visible' : isN4 ? 'hide' : 'hidden';
    s.display = v ? und(tr) ? und(bl) ? 'inline' : 'block' : ie4u ? '' : 'table-row' : 'none';
}

function winw() {
    return ie4u ? iebdy.clientWidth : self.innerWidth;
}

function winh() {
    return ie4u ? iebdy.clientHeight : self.innerHeight;
}

function winl() {
    return ie4u ? iebdy.scrollLeft : self.pageXOffset;
}

function wint() {
    return ie4u ? iebdy.scrollTop : self.pageYOffset;
}

function winnew(u, w, h, p) {
    w = und(w) ? 800 : w;
    h = und(h) ? 450 : h;
    var
        ww = Math.min(winw(), w),
        wh = Math.min(winh(), h),
        l = und(screen) ? 0 : Math.round((screen.width - ww) / 2),
        t = und(screen) ? 0 : Math.round((screen.height - wh) / 2);
    p = und(p) ? 'copyhistory=0,directories=0,location=0,menubar=0,scrollbars=' + (winw() < w || winh() < h ? 'yes' : '0') + ',status=0,toolbar=0,resizable=yes' : p;
    return window.open(u, '', p + ',width=' + ww + ',height=' + wh + ',left=' + l + ',top=' + t);
}

function RegEvent(e, h, o) {
    if (und(o)) o = d;
    e = e.toLowerCase();
    if (isN4) {
        var n = e.toUpperCase();
        o.captureEvents(Event[n]);
    }
    var f = typeof(h) == 'function' ?
        function (e) {
            var v = ie4u ? window.event : e;
            return h(v)
        } :
        typeof(h) == 'string' ?
            new Function('e', 'var v = ie4u ? window.event : e;' + h + '; return true') :
            null;
    if (und(o['on' + e]))
        o['on' + e] = f;
    else {
        var r = o['on' + e];
        var t = typeof(r) == 'function' ?
            function (e) {
                var v = ie4u ? window.event : e, x = r(v);
                return und(x) ? true : x;
            } :
            typeof(r) == 'string' ?
                new Function('e', 'var v = ie4u ? window.event : e;' + r + '; return true') :
                null;
        o['on' + e] = function (e) {
            var v = ie4u ? window.event : e;
            return t(v) && f(v);
        };
    }
}

function chCSS(e, c) {
    e = ge(e), a = ie4u ? 'className' : 'class';
    tCSS = e.getAttribute(a);
    e.setAttribute(a, c);
}

function ACCss(p, e, v) {
    e = ge(e);
    if (und(e))
        return;
    var
        a = ie4u ? 'className' : 'class',
        s = e.getAttribute(a) || '',
        r = new RegExp('(^| )' + p + '( |$)', 'i'),
        m = s.match(r);
    if (v && !m) e.setAttribute(a, s + ' ' + p);
    else if (!v && m) e.setAttribute(a, s.replace(r, ''));
}

function SetFocus(e) {
    var e = ge(e);
    if (e.focus)
        e.focus();
    if (e.scrollIntoView)
        e.scrollIntoView();
}

function cEl(e, p, a, h)
/*
 e - tag name, string
 p - parent element, use '' to skip
 a - attributes {'name' : value,...}, use 0 to skip
 h - inner HTML, string
 */ {
    if (!d.createElement) return false;
    p = p || iebdy;
    e = d.createElement(e);
    if (a)
        for (var k in a)
            e.setAttribute(k, a[k]);
    if (!und(h)) e.innerHTML = h;
    p.appendChild(e);
    return e;
}

function iFileClear(t) {
    var y = t.relEl;
    y.relEl.value = '';
    if (y.value == '') return false;
    var w = cEl('input', t.parentNode, {
        'type': 'file',
        'name': y.name,
        'size': y.size,
        'className': 'file-hidden',
        'class': 'file-hidden'
    });
    w.relEl = y.relEl;
    w.onchange = y.onchange;
    w.onkeypress = y.onkeypress;
    y.destroy;
    t.relEl = w;
    return false;
}

function mkIFile(e) {
    if (!(e.type == 'file' && e.parentNode.getAttribute(ie4u ? 'className' : 'class') == 'file')) return;
    var n = e.name, c = ge(n + '_clear');
    e.relEl = ge(n + '_fictive');
    RegEvent('change', function (e) {
        var t = und(e.currentTarget) ? e.srcElement : e.currentTarget;
        t.relEl.value = t.value;
    }, e);
    RegEvent('keypress', 'return false;', e);
    c.relEl = e;
    RegEvent('click', function (e) {
        return iFileClear(und(e.currentTarget) ? e.srcElement : e.currentTarget);
    }, c);
    setvis(n + '_fictive', 1);
    setvis(n + '_browse', 1);
    setvis(n + '_clear', 1);
    chCSS(e, 'file-hidden');
}

function thisload() {
    iebdy = ie4u && d.compatMode == 'CSS1Compat' ? d.documentElement : d.body;
    dyn = !(und(iebdy) || und(iebdy.innerHTML));
    var
        i, p,
        a = [
            // images to preload
            'btn-down-over.png',
            'btn-up-over.png',
            'btn-remove-over.png',
            'btn-file-clear-over.png',
            'btn-calendar-open-over.png',
            'calendar-next-month-over.png',
            'calendar-prev-month-over.png',
            'logout-over.png'
        ];
    for (i in a) {
        p = a[i];
        a[i] = new Image();
        a[i].src = 'img/' + p;
    }
    a = d.getElementsByTagName('input');
    for (i in a)
        mkIFile(a[i]);

    var localtime = ge('localtime');
    var timeOffset = parseInt(localtime.innerHTML) * 1000 + 500 - (new Date()).getTime();
    var timeTexts = {
        14: "января",
        15: "февраля",
        16: "марта",
        17: "апреля",
        18: "мая",
        19: "июня",
        20: "июля",
        21: "августа",
        22: "сентября",
        23: "октября",
        24: "ноября",
        25: "декабря",
        127: "Вс",
        128: "Пн",
        129: "Вт",
        130: "Ср",
        131: "Чт",
        132: "Пт",
        133: "Сб"
    };
    var changeClock = function () {
        var d = new Date((new Date()).getTime() + timeOffset);
        var h = d.getHours();
        var i = d.getMinutes();
        var s = d.getSeconds();
        var w = d.getDay();
        var m = d.getMonth();
        var d = d.getDate();
        localtime.innerHTML =
            timeTexts[127 + w] + ' / ' +
            d + ' ' + timeTexts[14 + m] + ' / ' +
            (h > 9 ? '' : '0') + h + ':' +
            (i > 9 ? '' : '0') + i + ':' +
            (s > 9 ? '' : '0') + s;
    };
    changeClock();
    setInterval(changeClock, 1000);
}

function RgEv(f, o) {
    var n = und(f) ? 'mform' : f, g = globals[n + 'Req'], i, a = 'VF("' + n + '"' + (und(o) ? ')' : ',0,"' + o + '")');
    f = d.forms[n];
    for (i in g) {
        RegEvent(inArray(g[i].type, [101, 102, 103, 201, 202, 400]) ? 'change' : 'keyup', a, f[g[i].nm]);
        RegEvent('blur', a, f[g[i].nm]);
        if (g[i].type == 205 && g[i].conf) {
            RegEvent('keyup', a, f[g[i].nm + '_conf']);
            RegEvent('blur', a, f[g[i].nm + '_conf']);
        }
    }
}

function cssEr(e, v)
// v - есть ли ошибка
{
    ACCss('error', e, v);
}

function VF(f, a, o)
/*
 f - form name (default = 'mform'), string
 a - show alerts (default = false), boolean
 o - additional (own) validation function name, string
 */ {
    f = und(f) ? 'mform' : f;
    var g = globals[f + 'Req'], e, i, r = 1, c, m, v, s, h, t;
    f = d.forms[f];
    a = !und(a) && a;
    for (i in g) {
        c = 1;
        e = g[i];
        s = und(e.nm) ? 0 : f[e.nm];
        h = 1;
        switch (e.type) {
            case 103: // integer, checkbox
            case 102: // integer, radio
                h = 0;
            case 101: // integer, select
                e.req = 1;
                e.ermask = e.erreq;
            case 100: // integer, input-text
                v = e.type == 100 ? s.value : e.type == 101 ? und(s.selectedIndex) ? '' : s.options[s.selectedIndex].value : e.type == 102 ? selected_radio(s, '') : s.checked ? 1 : 0;
                t = und(v) || (!und(e.ign) && v == e.ign);
                if (e.req && t) {
                    c = 0;
                    m = e.erreq;
                }
                else if ((e.req || !t) && (/\D/.test(v) || !((und(e.min) || e.min <= v) && (und(e.max) || e.max >= v)))) {
                    c = 0;
                    m = e.ermask;
                }
                break;
            case 104: // integer, own HTML
            case 204: // string, own HTML
                h = 0;
                if (!(c = e.vld(a && r))) r = 0;
                break;
            case 202: // string, radio
                h = 0;
            case 201: // string, select
                e.req = 1;
                e.ermask = e.erreq;
            case 207: // string, fckeditor
                if (e.type == 207)
                    s = CKEDITOR.instances[e.nm];
            case 205: // string, password
            case 203: // string, textarea
            case 200: // string, input-text
                v = e.type == 201 ? und(s.selectedIndex) ? '' : s.options[s.selectedIndex].value : e.type == 202 ? selected_radio(s, '') : e.type == 207 ? s.getData() : s.value; // 200, 203, 205, 207
                t = und(v) || (!und(e.ign) && v == e.ign);
                if (e.req && t) {
                    c = 0;
                    m = e.erreq;
                }
                else if (!und(e.max) && v.length > e.max) {
                    c = 0;
                    m = e.erlen;
                }
                else if (!((und(e.imask) || !e.imask.test(v)) && (und(e.mask) || e.mask.test(v)))) {
                    c = 0;
                    m = e.ermask;
                }
                if (e.type == 205 && e.conf) {
                    if (c && v != f[e.nm + '_conf'].value) {
                        c = 0;
                        m = e.erconf;
                    }
                    cssEr(f[e.nm + '_conf'], !c);
                }
                else if (e.type == 207) {
                    s = ge('cke_' + e.nm); // get ckeditor html object
                }
                break;
            case 400:
                v = und(s.value), t = e.nm + '_fictive';
                if (v) {
                    c = 0;
                    m = e.erreq;
                }
                if (!und(f[t])) cssEr(f[t], v);
                break;
        }
        if (c) {
            if (h) cssEr(s, 0);
        }
        else {
            if (h) cssEr(s, 1);
            if (a && r) {
                alert(m);
                try {
                    s.focus();
                }
                catch (e) {
                }
            }
            r = 0;
        }
    }
    alerts = a;
    return r ? und(o) ? true : eval(o) : false;
}

function rmc(t) {
    t = und(t) ? globals.removec : t;
    return confirm(t);
}

function ro(e) {
    ACCss('row-over', e, 1);
}

function ru(e) {
    ACCss('row-over', e, 0);
}

function cl(h) {
    top.location.href = h;
}

function wo(u, w, h) {
    return tryex('winnew("' + u + '",' + w + ',' + h + ')');
}

function chkAll(s, p, a, f) {
    a = globals[a];
    f = d.forms[und(f) ? 'mform' : f];
    for (var i in a)
        f[p + a[i]].checked = s;
    return false;
}

function editorInit(f) {
    CKFinder.SetupCKEditor(null, '/admin/ckfinder/');
}

function editorAdd(n, f) {
    var e = d.forms[und(f) ? 'mform' : f][n];
    CKEDITOR.replace(n, {
        height: getH(e)
        //toolbar:  'Full'
    });
    var e = CKEDITOR.instances[n];
    e.on('focus', function () {
        VF();
    });
    e.on('blur', typeof(mkBrief) == 'undefined' ? VF : function () {
        VF();
        mkBrief();
    });
}

function str_cut(s, l) {
    var max_left = 8, max_right = 20, a = '';
    s = s.replace(/\<[^\>]+\>/g, ' ').replace(/\&nbsp\;/g, ' ').replace(/\s+/g, ' ').replace(/^\s/, '').replace(/\s$/, '');
    if (s.length <= l + a.length) {
        return s;
    }
    var splitters = [' ', ',', '.', '!', '?', '/', '\\', '&', '+', '-', ';', ':', '', '=', '(', '<', '>'];
    if (l < 0) {
        l = 0;
    }
    var c = l - Math.min(l, Math.max(0, max_left)) - 1, i;
    for (i = l; i > c; i--) {
        if (inArray(s.charAt(i), splitters)) {
            return s.substr(0, i) + a;
        }
    }
    c = Math.min(s.length, l + Math.max(0, max_right) + 1);
    for (i = l + 1; i < c; i++) {
        if (inArray(s.charAt(i), splitters)) {
            return s.substr(0, i) + a;
        }
    }
    return s.substr(0, l) + a;
}

function topC(v) {
    topX();
    if (ie55)
        setvis('topinfobg', v);
    setvis('topinfopane', v);
}

function topX() {
    if (topT) {
        clearTimeout(topT);
        topT = 0;
    }
}

function topH() {
    topX();
    topT = setTimeout('topC(0)', 800);
}

winonload('thisload();');