(function () {
    jQuery.fn.login = function () {
        var $obj = this || [];
        if (!$obj.length)
            return;

        var $form = $obj.find('form');
        var $login = $obj.find('input[name="email"]');
        var $password = $obj.find('input[name="password"]');

        var checkForm = function () {
            return common.isEmail($login.val()) && $password.val() != '';
        };

        if (checkForm()) {
            $obj.addClass('loading');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: common.url('login-send'),
                data: ({
                    login: $login.val,
                    password: $password.val
                }),
                success: function (data) {
                    if (data.sc) {
                        $obj.animate(
                            {
                                width: 1,
                                marginLeft: 995
                            },
                            400,
                            function () {
                                $('body')
                                    .removeClass('logged-out')
                                    .addClass('logged-in');
                            }
                        );
                        return true;
                    } else {
                        $obj.removeClass('loading');
                        if (data.mg) {
                            alert(data.mg);
                            return false;
                        }
                    }
                }
            })
        }

        return this;
    }
})();
