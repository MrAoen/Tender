function usersListResize() {
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

function userListLoaded() {
    usersListResize();
    RegEvent('resize', function () {
        tryex('usersListResize()');
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
