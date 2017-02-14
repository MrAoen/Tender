function parentChange() {
    var
        i,
        f = d.forms.mform,
        c = f.parentid_0,
        n = globals.commodities_ord['p' + c.options[c.selectedIndex].value],
        o = f.ord_0;
    for (i = o.length - 1; i > 0; i--)
        o.remove(i);
    for (i in n)
        cEl('option', o, {'value': n[i][0]}, n[i][1]);
}

function publishedClick() {
    var f = d.forms.mform;
    setvis('publishchilds', f.ispublished_0.checked, '', 1);
}
