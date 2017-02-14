<?php
db__close();
sleep(28);
fexit();

require_once('includes/_utils_forms.php');

$sc = false;
$fields = array(
    'company' => array(
        'required' => true,
        'maxlength' => 200,
    ),
    'name' => array(
        'required' => true,
        'maxlength' => 200,
    ),
    'city' => array(
        'required' => true,
        'maxlength' => 200,
    ),
    'phone' => array(
        'required' => true,
        'maxlength' => 200,
    ),
    'email' => array(
        'required' => true,
        'maxlength' => 500,
    ),
    'password' => array(
        'required' => true,
        'maxlength' => 50,
    ),
    'turing' => array(
        'required' => true,
        'maxlength' => 10,
    ),
);

$values = array();
foreach ($fields as $k => $v) {
    $s = message_split(get_post($k, ($v['required'] ? null : ''), $v['maxlength'], true), 0, false, ($k == 'email' ? $v['maxlength'] : 25));
    $fields[$k]['value'] = $s;
    if (isset($v['values']) && !in_array($s, $v['values'])) {
        fexit();
    }
    if ($k == 'email' && !utils__is_email($s)) {
        fexit();
    }
    $values[$k] = $s;
}
unset($values['turing']);

if (turing_check($fields['turing']['value'])) {
    if (login()) {
        fexit(false, utils__output(87));
    }

    $values['addts'] = time();
    $values['addip'] = ip2long(utils__ip());
    $values['lastin'] = '0';
    $values['lastip'] = '0';
    $values['remember'] = '0';
    $values['status'] = '1';
    $values['conf'] = substr(md5(rand() . microtime() . $fields['turing']['value']), 0, 16);
    $password = $values['password'];
    $values['password'] = sha1($password);

    $keys = array();
    foreach (array_keys($values) as $k) {
        $keys[] = '%' . $k . '%';
    }

    db__query($req,
        "SELECT 1
			FROM `%prefix%users`
			WHERE `email` = '%email%'
			LIMIT 1",
        '%email%',
        $values['email']
    );
    //$res = mysql_fetch_row($req);
    $res = $req->fetch_row();
    //mysql_free_result($req);
    $req->free_result();
    if ($res) {
        fexit(false, utils__output(88));
    }

    db__query(
        $req,
        "INSERT INTO `%prefix%users` (
				`srvnotes`,
				`lastchangets`,
				`lastchangeadminid`,
				`addts`,
				`addip`,
				`lastin`,
				`lastip`,
				`company`,
				`name`,
				`city`,
				`phone`,
				`email`,
				`conf`,
				`password`,
				`remember`,
				`status`
			) VALUES (
				'',
				'0',
				'0',
				'%addts%',
				'%addip%',
				'%lastin%',
				'%lastip%',
				'%company%',
				'%name%',
				'%city%',
				'%phone%',
				'%email%',
				'%conf%',
				'%password%',
				'%remember%',
				'%status%'
			)",
        $keys,
        array_values($values)
    );
    //$id = mysql_insert_id();
    $id = utils__id();
    if (!$id) {
        fexit();
    }

    $mail = new cMail;
    $subject = str_replace('%фио%', $values['name'], get_template('registration_subj'));
    $message = str_replace(
        array(
            '%дата%',
            '%дата_сокр%',
            '%время%',
            '%фио%',
            '%организация%',
            '%email%',
            '%пароль%',
            '%ссылка_подтв%',
            '%ссылка_логин%',
        ),
        array(
            utils__date_ts2text(),
            utils__date_ts2str(),
            date('H:i'),
            $values['name'],
            $values['company'],
            $values['email'],
            $password,
            utils__uri('confirm-email/' . $id . '/' . $values['conf'], true, 2),
            utils__uri('', false, 2),
        ),
        get_template('registration')
    );
    $sc = $mail->send_plaintext(
        $values['email'],
        $subject,
        $mail->wrap($message),
        get_template('registration_sender')
    );
} else {
    $_SESSION['DPturing'] = '#';
    fexit(false, utils__output(51), false);
}

fexit($sc, ($sc ? utils__output(44) : ''));
?>