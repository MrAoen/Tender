(function () {
    jQuery.fn.changePassword = function () {
        var $obj = this || [];
        if (!$obj.length)
            return;

        var $oldpassword = $obj.find('.oldpassword input');
        var $password = $obj.find('.password input');
        var $repassword = $obj.find('.repassword input');

        var checkReq = function ($i, k) {
            var $m = $obj.find('.' + k + ' .require');
            if ($i.val().replace(/[\s\-\._]+/g, '') == '') {
                $m.show();
                return false;
            }
            $m.hide();
            return true;
        };

        var checkPassword = function () {
            var $m = $obj.find('.password .missing');
            if ($password.val().length < 6) {
                $m.show();
                return false;
            }
            $m.hide();
            return true;
        };

        var checkRepassword = function () {
            var $m = $obj.find('.repassword .missing');
            if ($password.val() != $repassword.val()) {
                $m.show();
                return false;
            }
            $m.hide();
            return true;
        };

        var checkForm = function () {
            $obj
                .find('.status')
                .hide();

            var sc = (
                checkReq($oldpassword, 'oldpassword') &&
                checkReq($password, 'password') &&
                checkPassword() &&
                checkReq($repassword, 'repassword') &&
                checkRepassword()
            );
            sc ? common.submitEnable($obj) : common.submitDisable($obj);
            return sc;
        };

        $('.text input')
            .bind('focus blur keyup change', function () {
                checkForm();
            });

        $obj.submit(function (e) {
            $obj
                .find('.submit input')
                .blur();

            if (checkForm()) {
                $obj.addClass('loading');
                common.submitDisable($obj);

                $.post(
                    common.url('change-password-send'),
                    {
                        oldpassword: $oldpassword.val(),
                        password: $password.val()
                    },
                    function (data) {
                        if (data.sc)
                            $('.text input').val('');
                        $obj
                            .removeClass('loading')
                            .find('.status')
                            .html(data.mg || '')
                            .show();
                    },
                    'json'
                );
            }

            return false;
        });

        $oldpassword.val('');
        $password.val('');
        $repassword.val('');
        common.submitDisable($obj);

        return this;
    }
})();
