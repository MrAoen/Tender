function tendersListResize() {
    var t = ge('scroller');
    var c = ge('tablelist');
    setH(t, Math.min(
        getH(c) +
        t.offsetHeight -
        t.clientHeight + 1,
        getH('page') -
        getH('top') -
        getH('menu') -
        getH('submenu') -
        getH('content') +
        getH(t) -
        18
    ));
}

function tenderListLoaded() {
    tendersListResize();
    RegEvent('resize', function () {
        tryex('tendersListResize()');
    }, window);
    var
        t = ge('scroller'),
        s = top.location.search,
        x = s.match(/[\?\&]\x\=(\d+)/),
        y = s.match(/[\?\&]\y\=(\d+)/);
    if (x)
        t.scrollLeft = x[1];
    if (y)
        t.scrollTop = y[1];
}

function courseVis(i, v) {
    setvis('coursecityid' + i, v, 1);
    setvis('courseaddress' + i, v, 1);
    setvis('coursets' + i, v, 1);
}

function courseInit() {
    var i, c = globals.show_course_fields;
    for (i = 1; i <= 10; i++)
        courseVis(i, i <= c);
    setvis('add_course', c < 10, 1);
}

function addCourse() {
    if (globals.show_course_fields < 10) {
        ++globals.show_course_fields;
        courseInit();
    }
    return false;
}
