<?php
require_once('includes/_utils_forms.php');
$mail = new cMail;

function cronExit()
{
    db__close();
    exit;
}

db__query($requ,
    "SELECT
			t.*,
			c.`title_%lang%` AS `loadingcitytitle`
		FROM `%prefix%tenders` t
		LEFT JOIN `%prefix%cities` c
			ON c.`id` = t.`loadingcityid`
		WHERE t.`iscomplete` = '0'
			AND t.`startts` + t.`length` * 60 <= '%ts%'
			AND t.`pricecurrent` > 0
			AND t.`pricecurrent` <= t.`pricestart`
		ORDER BY t.`startts` + t.`length` * 60",
    '%ts%',
    time()
);
//while ( $tender = mysql_fetch_assoc($requ) )
while ($tender = $requ->fetch_assoc()) {
    db__query($req,
        "SELECT *
			FROM `%prefix%users`
			WHERE `id` = '%id%'
				AND `status` = 3
			LIMIT 1",
        '%id%',
        $tender['lastuserid']
    );
    //$user = mysql_fetch_assoc($req);
    $user = $req->fetch_assoc();
    //mysql_free_result($req);
    $req->free_result();
    if (!$user) {
        continue;
    }

    $tender['course'] = unserialize($tender['course']);
    $course = array();
    $loadingDates = array();
    foreach ($tender['course'] as $i => $r) {
        $course[] = (count($tender['course']) > 1 ? $i . '. ' : '') . $r['citytitle'];
        $loadingDates[] = date('d.m.Y, H:i', $r['datets']);
    }
    $tenderBrief =
        '№ маршрута: ' . $tender['number'] . $mail->line_ending .
        'Погрузка: ' . $tender['loadingcitytitle'] . $mail->line_ending .
        (
        count($course)
            ?
            (
            count($course) > 1
                ?
                'Точки доставки:' . $mail->line_ending .
                '  ' . implode('  ' . $mail->line_ending, $course)
                :
                'Доставка: ' . $course[0]
            )
            :
            ''
        );
    $tenderBody =
        $tenderBrief . $mail->line_ending .
        'Дата прибытия на погрузку: ' . date('d.m.Y, H:i', $tender['loadingts']) . $mail->line_ending .
        (
        count($loadingDates)
            ?
            (
            count($loadingDates) > 1
                ?
                'Даты прибытия в точки доставки:' . $mail->line_ending .
                '  ' . implode('  ' . $mail->line_ending, $loadingDates)
                :
                'Дата прибытия в точку доставки: ' . $loadingDates[0]
            ) . $mail->line_ending
            :
            ''
        ) .
        'Объем груза: ' . $tender['volume'] . ' т' . $mail->line_ending .
        'Предложенная вами цена: %lastprice% KZT' .
        (empty($tender['body_ru']) ? '' : $mail->line_ending . 'Дополнительно: ' . $tender['body_ru']);

    db__query($req,
        "SELECT u.`id`, u.`company`, u.`name`, u.`email`
			FROM `%prefix%users` u, `%prefix%propositions` p
			WHERE u.`id` = p.`userid`
				AND u.`id` != '%uid%'
				AND p.`tenderid` = '%tid%'
			GROUP BY u.`id`",
        array('%uid%', '%tid%'),
        array($user['id'], $tender['id'])
    );
    //while ( $res = mysql_fetch_assoc($req) )
    while ($res = $req->fetch_assoc()) {
        db__query($reqc,
            "SELECT `price`
				FROM `%prefix%propositions`
				WHERE `tenderid` = '%tid%'
					AND `userid` = '%uid%'
				ORDER BY `datets` DESC
				LIMIT 1",
            array('%tid%', '%uid%'),
            array($tender['id'], $res['id'])
        );
        //$resc = mysql_fetch_assoc($reqc);
        $resc = $reqc->fetch_assoc();
        //mysql_free_result($reqc);
        $reqc->free_result();

        $subject = str_replace('%фио%', $res['name'], get_template('tenderlost_subj'));
        $message = str_replace(
            array(
                '%дата%',
                '%дата_сокр%',
                '%время%',
                '%фио%',
                '%организация%',
                '%email%',
                '%тендер_сокр%',
                '%тендер_полн%',
                '%lastprice%',
            ),
            array(
                utils__date_ts2text(),
                utils__date_ts2str(),
                date('H:i'),
                $res['name'],
                $res['company'],
                $res['email'],
                $tenderBrief,
                $tenderBody,
                ($resc ? $resc['price'] : 0),
            ),
            get_template('tenderlost')
        );
        $sc = $mail->send_plaintext(
            $res['email'],
            $subject,
            $mail->wrap($message),
            get_template('tenderlost_sender')
        );
    }
    //mysql_free_result($req);
    $req->free_result();

    $subject = str_replace('%фио%', $user['name'], get_template('tenderwin_subj'));
    $message = str_replace(
        array(
            '%дата%',
            '%дата_сокр%',
            '%время%',
            '%фио%',
            '%организация%',
            '%email%',
            '%тендер_сокр%',
            '%тендер_полн%',
            '%lastprice%',
        ),
        array(
            utils__date_ts2text(),
            utils__date_ts2str(),
            date('H:i'),
            $user['name'],
            $user['company'],
            $user['email'],
            $tenderBrief,
            $tenderBody,
            $tender['pricecurrent'],
        ),
        get_template('tenderwin')
    );
    $sc = $mail->send_plaintext(
        $user['email'],
        $subject,
        $mail->wrap($message),
        get_template('tenderwin_sender')
    );

    db__query($req,
        "UPDATE `%prefix%tenders` SET
				`iscomplete` = '1'
			WHERE `id` = '%id%'",
        '%id%',
        $tender['id']
    );
}
if (mysql_num_rows($requ)) {
    file_put_contents('di/last-update', time());
}
//mysql_free_result($requ);
$requ->free_result();

cronExit();
?>