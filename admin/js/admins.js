function admVld() {
    var f = d.forms.mform, p = f.password_0, v = p.value, e = v != '' && v.length < 8;
    cssEr(p, e);
    cssEr(f.password_0_conf, e);
    if (e) {
        if (alerts) {
            alert(globals.admin_pw_short);
            p.focus();
        }
        return false;
    }
    return true;
}

function admVldEdit() {
    var
        f = d.forms.mform,
        v = f.old_password_0,
        o = und(v.value),
        w = f.password_0,
        p = und(w.value),
        e = (o && !p) || (!o && p);
    cssEr(w, e);
    cssEr(f.password_0_conf, e);
    if (e) {
        if (alerts) {
            alert(globals.admin_pw_conf);
            (o ? v : w).focus();
        }
        return false;
    }
    return admVld();
}
