function imgVis(i, v) {
    var
        j,
        l = globals.lang_available,
        n = ['lf', 'ls', 'le', 'lt', 'mm', 'sf', 'ss', 'se', 'st'];
    for (j in n)
        setvis('img_' + i + '_' + n[j], v, 1);
    for (j in l)
        setvis('img_' + i + '_title_' + l[j], v, 1);
}

function imgCreate() {
    var i, a = globals.show_img_fields;
    for (i in a)
        imgVis(i, a[i]);
    setvis('add_img', inArray(0, a), 1);
}

function addImg() {
    var i, a = globals.show_img_fields;
    for (i in a)
        if (!a[i]) {
            imgVis(i, 1);
            globals.show_img_fields[i] = a[i] = 1;
            setvis('add_img', inArray(0, a), 1);
            break;
        }
    return false;
}

function imgLRemoveClick(t) {
    if (!t.checked) {
        var e = d.forms.mform[t.name.replace(/img_l_/, 'img_s_')];
        if (!und(e))
            e.checked = false;
    }
}

function imgSRemoveClick(t) {
    if (t.checked) {
        var e = d.forms.mform[t.name.replace(/img_s_/, 'img_l_')];
        if (!und(e))
            e.checked = true;
        var e = d.forms.mform[t.name.replace(/_remove$/, '_remove_temp')];
        if (!und(e))
            e.checked = true;
    }
}

function makeMainImg(t) {
    var
        i,
        v = und(t) ? 1 : !t.checked,
        n = ['lf', 'le', 'lt', 'sf', 'se', 'st'],
        c = globals.show_img_fields.length;
    t = und(t) ? -1 : t.name.match(/\d+$/);
    t = und(t) || !t.length ? -1 : t[0];
    for (i = 0; i < c; i++)
        if (i != t)
            d.forms.mform['img_0_make_main_img_' + i].checked = false;
    for (i in n)
        setvis('img_' + n[i], v, 1);
    n = ['l', 's'];
    if (!v)
        for (i in n) {
            var e, s = 'img_' + n[i] + '_0';
            if (!und(e = ge(s + '_clear')))
                iFileClear(e);
            if (!und(e = ge(s + '_remove')))
                e.checked = false;
            if (!und(e = ge(s + '_remove_temp')))
                e.checked = false;
        }
}
