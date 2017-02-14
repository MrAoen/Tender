(function () {
    jQuery.fn.recover = function () {
        var $obj = this || [];
        if (!$obj.length)
            return;

        var $email = $obj.find('.email input');
        var $turing = $obj.find('.turing input');

        var checkEmail = function () {
            var $r = $obj.find('.email .require');
            var $m = $obj.find('.email .missing');
            var v = $email.val();

            if (v == '') {
                $r.show();
                $m.hide();
                return false;
            }
            $r.hide();

            if (v != '' && !common.isEmail(v)) {
                $m.show();
                return false;
            }
            $m.hide();

            return checkTuring();
        };

        var checkTuring = function () {
            var $o = $obj.find('.turing .alert');

            if ($turing.val() == '') {
                $o.show();
                return false;
            }

            $o.hide();
            return true;
        };

        var checkForm = function () {
            $obj
                .find('.status')
                .hide();

            var sc = checkEmail();
            sc ? common.submitEnable($obj) : common.submitDisable($obj);
            return sc;
        };

        $('.text input,.richtext textarea,.turing input')
            .bind('focus blur keyup change', function () {
                checkForm();
            });

        $obj
            .find('.turing a')
            .click(function (e) {
                $(this).blur();
                common.turingImg($obj);
                $turing.val('');
                $turing.focus();
                return false;
            });

        $obj.submit(function (e) {
            $obj
                .find('.submit input')
                .blur();

            if (checkForm()) {
                $obj.addClass('loading');
                common.submitDisable($obj);

                $.post(
                    common.url('recover-send'),
                    {
                        email: $email.val(),
                        turing: $turing.val()
                    },
                    function (data) {
                        var $o =
                            $obj
                                .removeClass('loading')
                                .find('.status');

                        if (data.sc) {
                            $('.text input,.turing input').val('');
                            $o
                                .html(data.mg || '')
                                .show();
                            common.turingImg($obj);
                        }
                        else {
                            if (data.lt)
                                common.submitEnable($obj);
                            else
                                $obj
                                    .find('.turing a')
                                    .click();
                            $o
                                .html(data.mg || '')
                                .show();
                        }
                    },
                    'json'
                );
            }

            return false;
        });

        $turing.val('');
        common.turingImg($obj);

        return this;
    }
})();
