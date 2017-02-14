<?php

require_once('includes/_utils_forms.php');

$sc = false;
$fields = array(
    'email' => array(
        'required' => true,
        'maxlength' => 500,
    ),
    'turing' => array(
        'required' => true,
        'maxlength' => 10,
    ),
);

foreach ($fields as $k => $v) {
    $s = get_post($k, ($v['required'] ? null : ''), $v['maxlength']);
    $fields[$k]['value'] = $s;
    if ($k == 'email' && !utils__is_email($s)) {
        fexit();
    }
}

if (turing_check($fields['turing']['value'])) {
    if (login()) {
        fexit(false, utils__output(89));
    }

    $fields['conf']['value'] = substr(md5(rand() . microtime() . $fields['turing']['value']), 0, 16);

    db__query($req,
        "SELECT *
			FROM `%prefix%users`
			WHERE `email` = '%email%'
			LIMIT 1",
        '%email%',
        $fields['email']['value']
    );
    //$user = mysql_fetch_assoc($req);
    $user = $req->fetch_assoc();
    //mysql_free_result($req);
    $req->free_result();
    if (!$user) {
        fexit(false, utils__output(90));
    }

    db__query($req,
        "UPDATE `%prefix%users`
			SET `conf` = '%conf%'
			WHERE `id` = '%id%'",
        array('%conf%', '%id%'),
        array($fields['conf']['value'], $user['id'])
    );

    $mail = new cMail;
    $subject = str_replace('%фио%', $user['name'], get_template('recover_conf_subj'));
    $message = str_replace(
        array(
            '%дата%',
            '%дата_сокр%',
            '%время%',
            '%фио%',
            '%организация%',
            '%email%',
            '%ссылка_подтв%',
        ),
        array(
            utils__date_ts2text(),
            utils__date_ts2str(),
            date('H:i'),
            $user['name'],
            $user['company'],
            $fields['email']['value'],
            utils__uri('confirm-recovery/' . $user['id'] . '/' . $fields['conf']['value'], true, 2),
        ),
        get_template('recover_conf')
    );
    $sc = $mail->send_plaintext(
        $fields['email']['value'],
        $subject,
        $mail->wrap($message),
        get_template('recover_conf_sender')
    );
} else {
    $_SESSION['DPturing'] = '#';
    fexit(false, utils__output(51), false);
}

fexit($sc, ($sc ? utils__output(91) : ''));
?>