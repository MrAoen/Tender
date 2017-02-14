<?php

$parentId = 0;
$item = false;
$c = count($globals['path']);
$loggedIn = login();
for ($i = 1; $i < $c; $i++) {
    db__query($req,
        "SELECT
				`id`,
				`level`,
				`isfolder`,
				`isprotected`,
				`title_%lang%` AS `title`,
				`keywords_%lang%` AS `keywords`,
				`description_%lang%` AS `description`" .
        (
        $i == $c - 1
            ?
            ", `body_%lang%` AS `body`"
            :
            ''
        ) . "
			FROM `%prefix%commodities`
			WHERE `url` = '%url%'
				AND `parentid` = '%pid%'
				" . ($i == 1 && utils__get_path($i) == '404' ? '' : "AND `ispublished` = 1") . "
			LIMIT 1",
        array('%url%', '%pid%'),
        array(utils__get_path($i), $parentId)
    );
    //$item = mysql_fetch_assoc($req);
    $item = $req->fetch_assoc();
    //mysql_free_result($req);
    $req->free_result();

    if (!$item) {
        if (utils__get_path(1) == '404') {
            die_err();
        }
        utils__redirect('404/');
    }

    if ($item['isprotected'] == 1 && !$loggedIn) {
        utils__redirect('auth-required/');
    }

    utils__add_title($item['title']);
    utils__add_keywords($item['keywords']);
    utils__add_description($item['description']);

    if ($item['isfolder']) {
        $parentId = $item['id'];
    }
}

$globals['tpl']['main_content'] = $item['body'];

if (utils__get_path(1) == 'tenders') {
    require_once('includes/tenders.inc.php');
}

?>