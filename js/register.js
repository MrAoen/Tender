(function () {
    jQuery.fn.register = function () {
        var $obj = this || [];
        if (!$obj.length)
            return;

        var $company = $obj.find('.company input');
        var $name = $obj.find('.name input');
        var $city = $obj.find('.city input');
        var $phone = $obj.find('.phone input');
        var $email = $obj.find('.email input');
        var $password = $obj.find('.password input');
        var $repassword = $obj.find('.repassword input');
        var $rules = $obj.find('.rules input');
        var $turing = $obj.find('.turing input');

        var checkReq = function ($i, k) {
            var $m = $obj.find('.' + k + ' .require');
            if ($i.val().replace(/[\s\-\._]+/g, '') == '') {
                $m.show();
                return false;
            }
            $m.hide();
            return true;
        };

        var checkEmail = function () {
            var $m = $obj.find('.email .missing');
            if (!common.isEmail($email.val())) {
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

        var checkRules = function () {
            var $o = $obj.find('.rules .require');
            if (!$rules.get(0).checked) {
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

            var sc = (
                checkReq($company, 'company') &&
                checkReq($name, 'name') &&
                checkReq($city, 'city') &&
                checkReq($phone, 'phone') &&
                checkReq($email, 'email') &&
                checkEmail() &&
                checkReq($password, 'password') &&
                checkPassword() &&
                checkReq($repassword, 'repassword') &&
                checkRepassword() &&
                checkRules() &&
                checkReq($turing, 'turing')
            );
            sc ? common.submitEnable($obj) : common.submitDisable($obj);
            return sc;
        };

        $('.text input,.richtext textarea,.turing input')
            .bind('focus blur keyup change', function () {
                checkForm();
            });

        $('.checkbox input')
            .bind('click change', function () {
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
                    common.url('register-send'),
                    {
                        company: $company.val(),
                        name: $name.val(),
                        city: $city.val(),
                        phone: $phone.val(),
                        email: $email.val(),
                        password: $password.val(),
                        turing: $turing.val()
                    },
                    function (data) {
                        var $o =
                            $obj
                                .removeClass('loading')
                                .find('.status');

                        if (data.sc) {
                            $('.text input,.richtext textarea,.turing input').val('');
                            $o
                                .html(data.mg || '')
                                .show();

                            var slash = top.location.href.substr(-1) == '/' ? '' : '/';
                            setTimeout('top.location.href += "' + slash + 'succeed/"', 2000);
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

        $password.val('');
        $repassword.val('');
        $turing.val('');
        common.turingImg($obj);
        common.submitDisable($obj);

        return this;
    }
})();
