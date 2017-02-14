wo = function (u, w, h) {
    common.winNew(u, w, h);
    return false;
};

$(document).ready(function () {
    //var brV = navigator.userAgent;
    //brV = $.browser.msie ? brV < 7 ? '8d ie7d ie6' : brV < 8 ? '8d ie7d ie7' : brV < 9 ? '8d ie8' : '9d ie9' : 0;
    //$('body').addClass(brV ? 'ie ie' + brV : 'noie');
    $('a[rel="noindex"],a[rel="nofollow"],a.blank').attr('target', '_blank');
    $('table:not(.nospacer)').wrap('<div class="table"></div>');
    $('th[valign="top"],td[valign="top"]').addClass('v-top');
    $('th[valign="bottom"],td[valign="bottom"]').addClass('v-bottom');
    $('th[valign="center"],td[valign="center"]').addClass('h-center');
    $('th[valign="right"],td[valign="right"]').addClass('h-right');

    /*$('.tenders tr').each(function(index, element) {
     var trebi=$(this).attr('class');
     if(trebi=='complete' || trebi=='complete odd')
     { 

     var numberi=$(this).attr('number');
     $.post(
     '/di/last-update22.php?number=' + numberi,
     {},
     'html'
     );

     }

     });*/

    var tendersInit = function () {
        if (!$('#tenders').length)
            return;

        var lastUpdate = parseInt($('#tenders').attr('ts') || 0);

        var tendersLoad = function (t) {
            $('#tenders').load(
                common.url('tenders-update/'),
                {},
                function () {
                    lastUpdate = Math.max(t, parseInt($('.tenders').attr('ts') || 0));
                    tendersInitHtml();
                }
            );
        };

        var tendersUpdate = function () {
            $.post(
                '/di/last-update?' + (new Date()).getTime(),
                {},
                function (d) {
                    var t = parseInt(d);
                    if (lastUpdate < t)
                        tendersLoad(t);
                },
                'html'
            );
        };

        var propositionSend = function (price, $t) {
            var $b = $('<span class="loader"></span>');
            $t
                .blur()
                .hide()
                .after($b);
            var $p = $t.parents('tr:first');
            var number = $p.attr('number');

            $.post(
                common.url('proposition-send/'),
                {
                    price: price,
                    number: number
                },
                function (d) {
                    $b.remove();
                    $t.show();
                    if (d.sc)
                        tendersLoad(0);
                    else {
                        if (d.mg)
                            alert(d.mg);
                        if (!d.lt)
                            top.location.reload();
                    }
                },
                'json'
            );
        };

        var $localtime = $('.localtime').show();
        var timeOffset = parseInt($localtime.text()) * 1000 + 500 - (new Date()).getTime();
        var timeTexts = {
            14: "¤нвар¤",
            15: "феврал¤",
            16: "марта",
            17: "апрел¤",
            18: "ма¤",
            19: "июн¤",
            20: "июл¤",
            21: "августа",
            22: "сент¤бр¤",
            23: "окт¤бр¤",
            24: "но¤бр¤",
            25: "декабр¤",
            127: "вс",
            128: "пн",
            129: "вт",
            130: "ср",
            131: "чт",
            132: "пт",
            133: "сб"
        };
        var changeTimer = function () {
            var d = new Date((new Date()).getTime() + timeOffset);
            var dd = Math.round(d / 1000);
            var h = d.getHours();
            var i = d.getMinutes();
            var s = d.getSeconds();
            var w = d.getDay();
            var m = d.getMonth();
            var d = d.getDate();
            $localtime.text(
                timeTexts[127 + w] + ' / ' +
                d + ' ' + timeTexts[14 + m] + ' / ' +
                (h > 9 ? '' : '0') + h + ':' +
                (i > 9 ? '' : '0') + i + ':' +
                (s > 9 ? '' : '0') + s
            );


            $rest.each(function () {
                var $t = $(this);
                var start = parseInt($t.attr('start') || 0);

                if (start && start <= dd) {
                    tendersLoad(0);
                    return false;
                }

                var ts = parseInt($t.attr('rest'));
                var dt = ts - dd;
                if (dt < 0)
                    return true;
                if (dt == 0) {
                    var $p =
                        $t
                            .text('тендер окончен')
                            .parent()
                            .addClass('complete');


                    var $i = $p.find('input');
                    $i
                        .parent()
                        .html($i.val() || 0);
                    $p
                        .find('a')
                        .remove();
                    return true;
                }

                var h = Math.floor(dt / 3600);
                var i = Math.floor((dt - h * 3600) / 60);
                var s = dt - h * 3600 - i * 60;
                $t.text(
                    (h ? h + ':' : '') +
                    (h || i ? (i > 9 ? '' : '0') + i + ':' : '') +
                    (s > 9 ? '' : '0') + s
                );
            });
        };

        var tendersInitHtml = function () {
            $('.tenders tr:odd').addClass('odd');

            $('.tenders .pricewin a,.tenders .price a').click(function () {
                var $t = $(this);
                var $p = $t.parent();
                var price = $p.hasClass('pricewin') ? $p.find('span').text() : $p.find('input').val();
                if (confirm('¬ы подтверждаете цену ' + price + ' тенге?'))
                    propositionSend(price, $t);
                return false;
            });

            var step = parseInt($('.tenders').attr('step') || 0);
            if (step > 0)
                $('.tenders .price input').keyup(function () {
                    var $t = $(this);
                    var v = $t.val();
                    var i = parseInt(v);
                    var r = i % step;
                    var n =
                        $t
                            .parent()
                            .prev('.pricewin:first')
                            .find('span')
                            .text()
                            .replace(/\s/g, '')
                            .length;
                    if (r && v.length >= n)
                        $t.val(i - r + (r < step / 2 ? 0 : step));
                });

            $rest = $('.tenders .rest');
            changeTimer();
        };

        tendersInitHtml();
        setInterval(tendersUpdate, 10000);
        setInterval(changeTimer, 1000);
    };
    tendersInit();

    $('.reg-form').register();
    $('.login').login();
    $('.recover-form').recover();
    $('.chpw-form').changePassword();
});
