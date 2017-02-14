common =
{
    baseDir: '/',

    url: function (s) {
        return common.baseDir + 'ru/' + s;
    },

    isEmail: function (s) {
        return s.match(/^[0-9a-zA-Z\-_\.]+@[0-9a-zA-Z\-_]+\.[0-9a-zA-Z\-_\.]+$/) ? true : false;
    },

    storageGet: function (n) {
        var v = (document.cookie || '').match(new RegExp(n + '\=([^\;]+)', 'i'));
        return v ? decodeURI(v[1]) : '';
    },

    storagePut: function (n, v) {
        document.cookie = n + '=' + decodeURI(v) +
            '; expires=' + (new Date(3000, 1, 1)).toString() +
            '; path=/';
    },

    turingImg: function ($o) {
        $o
            .find('.turing img')
            .attr(
                'src',
                common.baseDir + 'turing/' + common.storageGet('SID') + '.png?' +
                (new Date()).getTime()
            );
    },

    submitDisable: function ($o) {
        $o
            .find('.submit input')
            .attr('disabled', 'true');
    },

    submitEnable: function ($o) {
        $o
            .find('.submit input')
            .removeAttr('disabled');
    },

    winNew: function (u, w, h, p) {
        var b = $('body');
        w = w || 800;
        h = h || 450;
        var ww = Math.min(b.innerWidth(), w);
        var wh = Math.min(b.innerHeight(), h);
        var l = Math.round((screen.width - ww) / 2);
        var t = Math.round((screen.height - wh) / 2);
        p = p || 'copyhistory=0,directories=0,location=0,menubar=0,scrollbars=' + (b.innerWidth() < w || b.innerHeight() < h ? 'yes' : '0') + ',status=0,toolbar=0,resizable=yes';
        return window.open(u, '', p + ',width=' + ww + ',height=' + wh + ',left=' + l + ',top=' + t);
    }
};
