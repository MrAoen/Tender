var vCalendars = {};

function calendarDOW(w) {
    w += globals.clndSDOW;
    return w < 0 ? w + 7 : w > 6 ? w - 7 : w;
}

function calendarDOWb(w) {
    w -= globals.clndSDOW;
    return w < 0 ? w + 7 : w > 6 ? w - 7 : w;
}

function calendarPad9(v) {
    v = pInt(v);
    return v > 9 ? v : '0' + v;
}

function calendarAdd(eN, cFloat, cMF, cFmt, cMS, cMI)
/*
 eN - начальная часть имени элементов
 cFloat - всплывающий календарь, или inline
 cMF - делятся ли поля даты-времени, или ввод одной строкой
 cFmt - формат вывода. Будут заменены буквы: Y, m, d, H, i, s. Необходимо при cMF = 0
 cMS - RegExp, выделяющее части даты из input-text. Необходимо при cMF = 0
 cMI - строка, собирающая дату по частям, выделенным при помощи cMS. Необходимо при cMF = 0
 */ {
    if (!dyn)
        return;
    var cDiv = eN + '_calendar', f = d.forms.mform;
    vCalendars[cDiv] = {
        'div': ge(cDiv),
        'isfloat': und(cFloat) ? 0 : 1,
        'mf': cMF
    };
    var e = vCalendars[cDiv], v, n = new Date();
    if (cMF) {
        e.target = [f[eN + '_y'], f[eN + '_m'], f[eN + '_d']];
        v = [e.target[0].value, e.target[1].value, e.target[2].value];
    }
    else {
        e.fmt = cFmt;
        e.ms = cMS;
        e.mi = cMI;
        e.target = f[eN];
        e.dt = 0;
        v = e.target.value.replace(cMS, cMI).split('-');
        if (v.length < 3)
            v = [0, 0, 0];
        else if (v.length > 4) {
            e.dt = 1;
            e.dtH = pInt(v[3]) || 0;
            e.dtI = pInt(v[4]) || 0;
            if (v.length > 5) {
                e.dt = 2;
                e.dtS = pInt(v[5]) || 0;
            }
        }
    }
    v = [pInt(v[0]) || 0, pInt(v[1]) || 0, pInt(v[2]) || 0];
    e.year = v[0] || n.getFullYear();
    e.month = v[1] ? Math.max(0, v[1] - 1) : n.getMonth();
    e.selYear = v[0];
    e.selMonth = e.month;
    e.selDay = v[2];
    e.selClass = '';
    e.ovrEl = '';
    e.ovrClass = '';
    e.timeout = 0;
    calendarDraw(cDiv, 1);
    return e;
}

function calendarDraw(id, isCreate) {
    var c = vCalendars[id];
    if (und(c))
        return;
    var
        cy = c.year,
        cm = c.month,
        sy = c.selYear,
        sm = c.selMonth,
        sd = c.selDay,
        e = c.div,
        r,
        n = new Date(cy, cm, 1),
        i = 0,
        w = calendarDOW(n.getDay()),
        wb,
        o,
        m,
        y,
        s,
        t = new Date(),
        ty = t.getFullYear(),
        tm = t.getMonth(),
        td = t.getDate();
    r =
        '<table>' +
        '<tr>' +
        '<th class="header" colspan="7"><table>' +
        '<tr>' +
        '<th><a title="' + (globals.clndMonths[cm > 0 ? cm - 1 : 11]) + '" class="button prev-month" href="#" onclick="return calendarPrevM(\'' + id + '\')">&nbsp;</a></th>' +
        '<th class="caption">' + globals.clndMonths[cm] + ',' + ' ' + cy + '</th>' +
        '<th><a title="' + (globals.clndMonths[cm < 11 ? cm + 1 : 0]) + '" class="button next-month" href="#" onclick="return calendarNextM(\'' + id + '\')">&nbsp;</a></th>' +
        '</tr>' +
        '</table></th>' +
        '</tr>' +
        '<tr>' +
        '<th class="hr" colspan="7">&nbsp;</th>' +
        '</tr>' +
        '<tr>';
    for (i = 0; i < 7; i++) {
        m = calendarDOWb(i);
        r += '<th' + (m == 0 || m == 6 ? ' class="weekend"' : '') + '>' + globals.clndDOW[m] + '</th>';
    }
    r +=
        '</tr>' +
        '<tr>' +
        '<th class="hr" colspan="7">&nbsp;</th>' +
        '</tr>' +
        '<tr>';
    n.setTime(n.getTime() - w * 86400000);
    w = calendarDOW(n.getDay());
    m = n.getMonth();
    i = 0;
    while (i++ < 50 && (i < 28 || (cm < 11 ? m <= cm : m > 0) || w > 0)) {
        y = n.getFullYear();
        o = n.getDate();
        s = y == cy && m == cm ? 'current' : 'other';
        wb = calendarDOWb(w);
        s += wb == 0 || wb == 6 ? '-we' : '';
        s += y == ty && m == tm && o == td ? '-td' : '';
        if (y == sy && m == sm && o == sd) {
            c.ovrClass = s;
            c.selDay = 0;
        }
        r +=
            (w > 0 ? '' : '<tr>') +
            '<td class="' + s + '" id="clndd' + id + y + calendarPad9(m) + calendarPad9(o) + '"' +
            ' onmouseover="calendarOver(\'' + id + '\', ' + y + ', ' + m + ', ' + o + ')"' +
            ' onmouseout="calendarOut(\'' + id + '\', ' + y + ', ' + m + ', ' + o + ')"' +
            ' onclick="calendarClick(\'' + id + '\', ' + y + ', ' + m + ', ' + o + ')">' +
            o + '</td>' +
            (w < 6 ? '' : '</tr>');
        n.setDate(o + 1);
        w = calendarDOW(n.getDay());
        m = n.getMonth();
    }
    r +=
        '<tr>' +
        '<th class="hr" colspan="7">&nbsp;</th>' +
        '</tr>' +
        '<tr>' +
        '<th colspan="7" class="result">' +
        (c.isfloat ? '<span class="close"><a title="' + globals.clndClose + '" class="button remove" href="#" onclick="return calendarHide(\'' + id + '\')">&nbsp;</a></span>' : '') +
        '<span id="clndres' + id + '">&nbsp;</span>' +
        '</th>' +
        '</tr>';
    '</table>';
    if (isCreate && c.isfloat && ie55)
        cEl('div', c.div.parentElement, {
            'id': id + 'bg',
            'className': 'calendar-iframe'
        }, '<iframe frameborder="0"></iframe>');
    e.innerHTML = r;
    if (c.ovrClass != '')
        calendarClick(id, sy, sm, sd, 1);
}

function calendarPrevM(id) {
    var c = vCalendars[id];
    if (c.month > 0)
        c.month--;
    else {
        c.month = 11;
        c.year--;
    }
    calendarDraw(id, 0);
    return false;
}

function calendarNextM(id) {
    var c = vCalendars[id];
    if (c.month < 11)
        c.month++;
    else {
        c.month = 0;
        c.year++;
    }
    calendarDraw(id, 0);
    return false;
}

function calendarOver(id, y, m, o) {
    var
        c = vCalendars[id],
        cd = calendarPad9(o),
        cm = calendarPad9(m),
        t = ge('clndd' + id + y + cm + cd);
    calendarClearTimeout(c);
    if (!und(c.ovrEl))
        c.ovrEl.className = c.ovrClass;
    c.ovrEl = t;
    c.ovrClass = t.className;
    t.className = c.ovrClass.replace(/\-sel/, '') + '-ovr';
    ge('clndres' + id).innerHTML = globals.clndStatusFMT.replace(/Y/, y).replace(/m/, calendarPad9(m + 1)).replace(/d/, cd);
}

function calendarOut(id, y, m, o) {
    var c = vCalendars[id];
    if (c.timeout) {
        calendarClearTimeout(c);
        if (!und(c.ovrEl))
            c.ovrEl.className = c.ovrClass;
        c.ovrEl = '';
        c.ovrClass = '';
        ge('clndres' + id).innerHTML = '&nbsp;';
    }
    else
        c.timeout = setTimeout('calendarOut("' + id + '", ' + y + ', ' + m + ', ' + o + ')', 100);
}

function calendarClearTimeout(c) {
    clearTimeout(c.timeout);
    c.timeout = 0;
}

function calendarClick(id, y, m, o, nc) {
    var
        c = vCalendars[id],
        cd = calendarPad9(o),
        cm = calendarPad9(m),
        e = ge('clndd' + id + c.selYear + calendarPad9(c.selMonth) + calendarPad9(c.selDay)),
        t = ge('clndd' + id + y + cm + cd), a = '', v, w = c.target;
    calendarClearTimeout(c);
    if (c.mf) {
        w[0].value = y;
        w[1].value = calendarPad9(m + 1);
        w[2].value = calendarPad9(cd);
    }
    else {
        v = c.fmt.replace(/Y/, y).replace(/m/, calendarPad9(m + 1)).replace(/d/, calendarPad9(cd));
        if (c.dt) {
            var h = new Date();
            v = v.replace(/H/, calendarPad9(c.dtH || h.getHours())).replace(/i/, calendarPad9(c.dtI || h.getMinutes())).replace(/s/, und(c.dtS) ? '' : calendarPad9(c.dtS || h.getSeconds()));
        }
        w.value = v;
    }
    if (c.isfloat && und(nc))
        calendarHide(id);
    if (e == t)
        return;
    if (!und(e))
        e.className = c.selClass;
    c.selClass = c.ovrClass;
    c.ovrClass = c.selClass + '-sel';
    t.className = c.ovrClass;
    c.selYear = y;
    c.selMonth = m;
    c.selDay = o;
}

function calendarShow(id) {
    setvis(id, 1);
    var
        c = vCalendars[id],
        w = getW(id) || 213,
        h = getH(id) || 194,
        i,
        e = c.mf ? c.target[0] : c.target,
        x = e.offsetLeft,
        y = e.offsetTop + e.offsetHeight + 5;
    if (c.mf)
        for (i = 0; i < 3; i++)
            x = Math.min(x, c.target[i].offsetLeft);
    if (ie55) {
        var b = ge(id + 'bg');
        setW(b, w);
        setH(b, h);
        setpos(b, x, y);
        setvis(b, 1);
    }
    setpos(id, x, y);
    return false;
}

function calendarHide(id) {
    var c = vCalendars[id];
    if (ie55)
        setvis(id + 'bg', 0);
    setvis(id, 0);
    if (!und(c.onHide)) eval(c.onHide);
    return false;
}
