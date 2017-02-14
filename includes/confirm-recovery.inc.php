<?php
require_once('includes/_utils_forms.php');

$id = intval(utils__get_path(2));
$conf = utils__get_path(3);

if (empty($id) || empty($conf) || strlen($conf) < 10) {
    utils__redirect('recover/confirm-error/');
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
$req->free_result();

if (!$user) {
    utils__redirect('recover/confirm-error/');
}

$password = md5(rand() . microtime() . $user['id']);
$password = substr(str_shuffle($password . strtoupper($password)), -10);
$mail = new cMail;
$subject = str_replace('%фио%', $user['name'], get_template('recover_subj'));
$message = str_replace(
    array(
        '%дата%',
        '%дата_сокр%',
        '%время%',
        '%фио%',
        '%организация%',
        '%email%',
        '%пароль%',
        '%ссылка_логин%',
    ),
    array(
        utils__date_ts2text(),
        utils__date_ts2str(),
        date('H:i'),
        $user['name'],
        $user['company'],
        $user['email'],
        $password,
        utils__uri('', false, 2),
    ),
    get_template('recover')
);
if (!$mail->send_plaintext(
    $user['email'],
    $subject,
    $mail->wrap($message),
    get_template('recover_sender')
)
) {
    utils__redirect('recover/confirm-error/');
}

$password = sha1($password);
db__query($req,
    "UPDATE `%prefix%users` SET
			`conf` = '',
			`status` = CASE `status` WHEN 1 THEN 2 ELSE `status` END,
			`password` = '%password%'
		WHERE `id` = '%id%'",
    array('%id%', '%password%'),
    array($user['id'], $password)
);
utils__redirect('recover/confirmed/');
?>