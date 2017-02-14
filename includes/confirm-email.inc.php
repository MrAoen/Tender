<?php
require_once('includes/_utils_forms.php');

$id = intval(utils__get_path(2));
$conf = utils__get_path(3);
if (empty($id) || empty($conf) || strlen($conf) < 10) {
    utils__redirect('register/confirm-error/');
}

db__query($req,
    "SELECT *
		FROM `%prefix%users`
		WHERE `id` = '%id%'
			AND `conf` = '%conf%'
		LIMIT 1",
    array('%id%', '%conf%'),
    array($id, $conf)
);
//$user = mysql_fetch_assoc($req);
$user = $req->fetch_assoc();
//mysql_free_result($req);
//не стоит освобождать req раньше окончания проверки
if (!$user) {
    utils__redirect('register/confirm-error/');
}
$req->free_result();

db__query($req,
    "UPDATE `%prefix%users`
		SET `conf` = '', `status` = 2
		WHERE `id` = '%id%'",
    '%id%',
    $id
);

$cmail = new cMail();
$mparam_search = array(
    '%дата%',
    '%дата_сокр%',
    '%время%',
    '%а_фамилия%',
    '%а_имя%',
    '%а_отчество%',
    '%а_email%',
    '%о_орг%',
    '%о_фио%',
    '%о_город%',
    '%о_email%',
    '%о_телефон%',
);
db__query($req,
    "SELECT `emails`, `fname`, `sname`, `lname`
		FROM `%prefix%admins`
		WHERE `typeid` < 30
			AND `notify` = 1"
);
//while ( $res = mysql_fetch_assoc($req) )
while ($res = $req->fetch_assoc()) {
    $mparam_replace = array(
        utils__date_ts2text(),
        utils__date_ts2str(),
        date('H:i'),
        $res['fname'],
        $res['sname'],
        $res['lname'],
        $res['emails'],
        ($user['company'] == '' ? '-' : $user['company']),
        ($user['name'] == '' ? '-' : $user['name']),
        ($user['city'] == '' ? '-' : $user['city']),
        ($user['email'] == '' ? '-' : $user['email']),
        ($user['phone'] == '' ? '-' : $user['phone']),
    );
    $subject = str_replace($mparam_search, $mparam_replace, get_template('notifyregistration_subj'));
    $body = $cmail->wrap(str_replace($mparam_search, $mparam_replace, get_template('notifyregistration')));
    $cmail->send_plaintext($res['emails'], $subject, $body, get_template('notifyregistration_sender'));
}
//mysql_free_result($req);
$req->free_result();

utils__redirect('register/confirmed/');
?>