<?php
require_once('includes/_utils_forms.php');

$sc = false;
$fields = array(
    'price' => array(
        'required' => true,
        'maxlength' => 10,
    ),
    'number' => array(
        'required' => true,
        'maxlength' => 10,
    ),
);

$values = array();
foreach ($fields as $k => $v) {
    $s = intval(get_post($k, ($v['required'] ? null : ''), $v['maxlength'], true));
    $values[$k] = $s;
    $fields[$k]['value'] = $s;
}

if ($values['price'] <= 0) {
    fexit(false, utils__output(94));
}
if (!login()) {
    fexit(false, utils__output(93), false);
}

db__query($req,
    "SELECT
			t.*,
			c.`title_%lang%` AS `loadingcitytitle`
		FROM `%prefix%tenders` t
		LEFT JOIN `%prefix%cities` c
			ON c.`id` = t.`loadingcityid`
		WHERE t.`number` = '%n%'
		LIMIT 1",
    '%n%',
    $values['number']
);
//$tender = mysql_fetch_assoc($req);
$tender = $req->fetch_assoc();
//mysql_free_result($req);

if (!$tender) {
    fexit(false, utils__output(94));
}

if (
    $tender['iscomplete'] == 1
    || $tender['startts'] > time()
    || $tender['startts'] + $tender['length'] * 60 < time()
) {
    fexit(false, utils__output(96));
}
if ($tender['pricecurrent'] > 0 && $tender['pricecurrent'] <= $values['price']) {
    fexit(false, utils__output(95));
}
$req->free_result();

db__query($req,
    "INSERT INTO `%prefix%propositions` (
			`tenderid`,
			`userid`,
			`datets`,
			`price`
		) VALUES (
			'%tenderid%',
			'%userid%',
			'%datets%',
			'%price%'
		)",
    array(
        '%tenderid%',
        '%userid%',
        '%datets%',
        '%price%',
    ),
    array(
        $tender['id'],
        $globals['user']['id'],
        time(),
        $values['price'],
    )
);

db__query($req,
    "UPDATE `%prefix%tenders` SET
			`pricecurrent` = '%price%',
			`lastuserid` = '%userid%',
			`propositions` = `propositions` + 1,
			`iscomplete` = %complete%
		WHERE `id` = '%id%'",
    array(
        '%price%',
        '%userid%',
        '%complete%',
        '%id%',
    ),
    array(
        $values['price'],
        $globals['user']['id'],
        ($values['price'] <= $tender['pricewin'] ? 1 : 'iscomplete'),
        $tender['id'],
    )
);

if ($tender['iscomplete'] == 0 && $values['price'] <= $tender['pricewin']) {
    $mail = new cMail;
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
        'Предложенная вами цена: %lastprice% Тенге' .
        (empty($tender['body_ru']) ? '' : $mail->line_ending . 'Дополнительно: ' . $tender['body_ru']);

    db__query($req,
        "SELECT u.`id`, u.`company`, u.`name`, u.`email`
			FROM `%prefix%users` u, `%prefix%propositions` p
			WHERE u.`id` = p.`userid`
				AND u.`id` != '%uid%'
				AND p.`tenderid` = '%tid%'
			GROUP BY u.`id`",
        array('%uid%', '%tid%'),
        array($globals['user']['id'], $tender['id'])
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
    $reqc->free_result();

    $subject = str_replace('%фио%', $globals['user']['name'], get_template('tenderwin_subj'));
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
            $globals['user']['name'],
            $globals['user']['company'],
            $globals['user']['email'],
            $tenderBrief,
            $tenderBody,
            $values['price'],
        ),
        get_template('tenderwin')
    );
    $sc = $mail->send_plaintext(
        $globals['user']['email'],
        $subject,
        $mail->wrap($message),
        get_template('tenderwin_sender')
    );
}

file_put_contents('di/last-update', time());
fexit(true);
?>