<?php

function login_exit($sc = false, $mg = '')
{
    json_out(array(
        'sc' => $sc,
        'mg' => ($mg == '' ? utils__output(84) : $mg),
    ));
    db__close();
    exit;
}

global $globals;
$sc = false;
$fields = array(
    'login' => array(
        'required' => true,
        'maxlength' => 500,
    ),
    'password' => array(
        'required' => true,
        'maxlength' => 50,
    ),
);

usleep(rand(500000, 1500000));
foreach ($fields as $k => $v) {
    $s = get_post($k, '', $v['maxlength']);
    if ($v['required'] && $s == '') {
        login_exit();
    }
    $fields[$k]['value'] = $s;
}

if (!login($fields['login']['value'], sha1($fields['password']['value']))) {
    db__query($req,
        "SELECT `status`, `lastin`
			FROM `%prefix%users`
			WHERE `email` = '%login%'
				AND `password` = '%password%'
			LIMIT 1",
        array('%login%', '%password%'),
        array($fields['login']['value'], sha1($fields['password']['value']))
    );
    //$user = mysql_fetch_assoc($req);
    $user = $req->fetch_assoc();
    //mysql_free_result($req);

    login_exit(false, ($user ? ($user['status'] == 3 ? '' : ($user['status'] == 4 && $user['lastin'] != 0 ? utils__output(97) : utils__output(85))) : ''));
    $req->free_result();
}

db__query($req,
    "UPDATE `%prefix%users`
		SET
			`lastin` = '%lastin%',
			`lastip` = '%lastip%'
		WHERE `id` = '%id%'",
    array(
        '%lastin%',
        '%lastip%',
        '%id%',
    ),
    array(
        time(),
        ip2long(utils__ip()),
        $globals['user']['id'],
    )
);

login_exit(true, ' ');
?>