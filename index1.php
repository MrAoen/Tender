<?php
require_once('includes/_utils_forms.php');
/*$mail     = new cMail;
$subject  = 'my dear friend';
$message  = 'test message';
$sc = $mail->send_plaintext(
    'd.litvyak@dneproplast.com',
    'HelloUsername',
    $message,
    'info@retal.kz'
);*/
require_once('includes/_config.php');
require_once('includes/_utils.php');
global $globals;
$globals['lang']='ru';
$globals['serv_prefix']='dp';
db__connect();
//tender.localhost/ru/confirm-email.php?id=153&code=6919725407cc9862
//http://tender.localhost/ru/confirm-email.php?id=153&code=6919725407cc9862

//tendersHtml();
$some = utils__output(78);
echo htmlspecialchars($some);
?>

<script>

    $.ajax({
url: '/ru/login-send'
data:'email=dmitry.litvyak@gmail.com&password=password1',
success: function(data){
        $('copy').html('HELLO USERNAME');
    }
});

</script>


