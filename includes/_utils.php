<?php


function die_err()
{
    utils__redirect('technical_works.php', false);
}

function db__connect()
{
    global $globals;
    if (!empty($globals['db_link'])) {
        return;
    }
    /*$globals['db_link'] = mysqli_connect(
        $globals['db_host'],
        $globals['db_user'],
        $globals['db_pass']
    ) or die_err();*/
    $globals['db_link'] = new mysqli($globals['db_host'], $globals['db_user'], $globals['db_pass'], $globals['db_name']);
    //mysqli_select_db($globals['db_link'],$globals['db_name']) or die_err();
    if ($globals['db_link']->connect_error) {
        die_err();
    }
    //mysqli_query($globals['db_link'],'SET NAMES cp1251') or die_err();
    $globals['db_link']->query('SET NAMES cp1251', MYSQLI_STORE_RESULT);
}

function utils__id()
{
    global $globals;
    return $globals['db_link']->insert_id;
}

function db__close()
{
    global $globals;
    if (!empty($globals['db_link'])) {
        //mysql_close($globals['db_link']);
        $globals['db_link']->close();
        $globals['db_link'] = false;
    }
}

function db__query(&$req, $query, $params = array(), $values = array())
{
    global $globals;
    if (!is_array($values)) {
        $params = array($params);
        $values = array($values);
    }
    $params[] = '%prefix%';
    $values[] = $globals['serv_prefix'];
    $params[] = '%lang%';
    $values[] = $globals['lang'];
    for ($i = 0, $c = count($values); $i < $c; $i++) {
        //$values[$i] = mysql_real_escape_string($values[$i]);
        $values[$i] = $globals['db_link']->real_escape_string($values[$i]);
    }
    //$req = mysql_query(str_replace($params, $values, $query)) or die_err();
    $req = $globals['db_link']->query(str_replace($params, $values, $query));
    if ($globals['db_link']->connect_error) {
        die_err();
    }
}

if (setlocale(LC_CTYPE, array('ru_RU.CP1251', 'rus_RUS.1251', 'ru_RU.windows-1251'))) {

    function utils__strtolower($s)
    {
        return strtolower($s);
    }

    function utils__strtoupper($s)
    {
        return strtoupper($s);
    }

} else {

    function utils__strtolower($s)
    {
        if ($s == '') {
            return '';
        }
        $ord_f = ord('?') - 1;
        $ord_l = ord('?') + 1;
        $ord_d = ord('?') - ord('?');
        $s = strtolower($s);
        $l = strlen($s);
        for ($i = 0; $i < $l; $i++) {
            $ord_s = ord($s{$i});
            if ($ord_s > $ord_f && $ord_s < $ord_l) {
                $s{$i} = chr($ord_s + $ord_d);
            }
        }
        return $s;
    }

    function utils__strtoupper($s)
    {
        if ($s == '') {
            return '';
        }
        $ord_f = ord('?') - 1;
        $ord_l = ord('?') + 1;
        $ord_d = ord('?') - ord('?');
        $s = strtoupper($s);
        $l = strlen($s);
        for ($i = 0; $i < $l; $i++) {
            $ord_s = ord($s{$i});
            if ($ord_s > $ord_f && $ord_s < $ord_l) {
                $s{$i} = chr($ord_s - $ord_d);
            }
        }
        return $s;
    }

}

function utils__input($s, $maxlength = 0, $striptags = false)
{
    if ($striptags !== false) {
        $s = strip_tags($s, $striptags);
    }
    if (get_magic_quotes_gpc()) {
        $s = str_replace(array('\\"', "\\'", '\\\\'), array('"', "'", '\\'), $s);
    }
    if ($maxlength > 0) {
        $s = substr($s, 0, $maxlength);
    }
    return strtr($s, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
}

function utils__is_email($email)
{
    return preg_match('/^[0-9a-zA-Z\\-_\\.]+@[0-9a-zA-Z\\-_]+\\.[0-9a-zA-Z\\-_\\.]+$/', $email);
}

function fexit($sc = false, $mg = '', $lt = true)
{
    json_out(array(
        'sc' => $sc,
        'mg' => ($mg == '' ? utils__output(86) : $mg),
        'lt' => $lt,
    ));
    db__close();
    exit;
}

function get_post($name, $default = null, $maxlen = 0, $decode = false)
{
    if (isset($_POST[$name]) || isset($_GET[$name])) {
        return (
        $decode
            ?
            substr(@iconv('UTF-8', 'CP1251//IGNORE', (isset($_POST[$name]) ? $_POST[$name] : $_GET[$name])), 0, $maxlen)
            :
            substr((isset($_POST[$name]) ? $_POST[$name] : $_GET[$name]), 0, $maxlen)
        );
    } else if ($default === null) {
        fexit();
    } else {
        return $default;
    }
}

function utils__json_encode($a, $isUtf = true)
{
    $ret = '';
    foreach ($a as $k => $v) {
        $ret .=
            ($ret == '' ? '"' : ',"') . $k . '":' .
            (
            is_array($v)
                ?
                utils__json_encode($v, $isUtf)
                :
                (
                is_bool($v)
                    ?
                    ($v ? 'true' : 'false')
                    :
                    (
                    is_numeric($v)
                        ?
                        $v
                        :
                        '"' . str_replace(
                            array("\t", "\r", "\n", "\\'"),
                            array(' ', '', '\\n', '&#039;'),
                            addslashes(
                                $isUtf
                                    ?
                                    @iconv('CP1251', 'UTF-8//IGNORE', $v)
                                    :
                                    $v
                            )
                        ) . '"'
                    )
                )
            );
    }
    return '{' . $ret . '}';
}

function message_split($s, $maxlength = 0, $allow_nl = false, $word_max_length = 25)
{
    if (get_magic_quotes_gpc()) {
        $s = str_replace(array('\\"', "\\'", '\\\\'), array('"', "'", '\\'), $s);
    }
    $s = trim(strip_tags($s));
    if ($allow_nl) {
        $s = preg_replace(array('/\r+/', '/([\,\!\?\;\:])([^\s\.\,\!\?\;\:])/', '/([^\s]{' . strval($word_max_length - 1) . '})([^\s])/', '/\n{2,}/', '/[\t ]+/'), array('', '$1 $2', '$1 $2', "\n\n", ' '), $s);
    } else {
        $s = preg_replace(array('/\s+/', '/([\,\!\?\;\:])([^\s\.\,\!\?\;\:])/', '/([^\s]{' . strval($word_max_length - 1) . '})([^\s])/'), array(' ', '$1 $2', '$1 $2'), $s);
    }
    if ($maxlength > 0) {
        $s = substr($s, 0, $maxlength);
    }
    return strtr($s, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
}

function utils__output($id, $s = false, $r = false)
{
    db__query($req, "SELECT `text_%lang%` FROM %prefix%texts WHERE `id` = '" . intval($id) . "' LIMIT 1");
    //$res = mysql_fetch_row($req);
    $res = $req->fetch_row();
    //mysql_free_result($req);
    $lResult = (is_array($res) ? ($s === false ? $res[0] : str_replace($s, $r, $res[0])) : '');
    $req->free_result();
    return $lResult;
}

function utils__strip_float($v)
{
    $v = strval($v);
    $p = strpos($v, '.');
    if ($p === false) {
        return ($v);
    }
    for ($i = strlen($v) - 1, --$p; $i > $p; $i--) {
        if (!($v{$i} == '0' || $v{$i} == '.')) {
            return (substr($v, 0, $i + 1));
        }
    }
    return (substr($v, 0, $p + 1));
}

function utils__str_cut($s, $l = 76, $max_left = 8, $max_right = 20, $a = '...')
{
    $s = trim(preg_replace('/(\s|\&nbsp\;)+/s', ' ', strip_tags($s)));
    if (strlen($s) <= $l + strlen($a)) {
        return $s;
    }
    $splitters = array(' ', ',', '.', '!', '?', '/', '\\', '&', '+', '-', ';', ':', '$', '=', '(', '<', '>');
    if ($l < 0) {
        $l = 0;
    }
    $c = $l - min($l, max(0, $max_left)) - 1;
    for ($i = $l; $i > $c; $i--) {
        if (in_array($s{$i}, $splitters)) {
            return substr($s, 0, $i) . $a;
        }
    }
    $c = min(strlen($s), $l + max(0, $max_right) + 1);
    for ($i = $l + 1; $i < $c; $i++) {
        if (in_array($s{$i}, $splitters)) {
            return substr($s, 0, $i) . $a;
        }
    }
    return substr($s, 0, $l) . $a;
}

function utils__word_by_no($n, $w1, $w2_4, $w_oth)
{
    $n = strval($n);
    return (strlen($n) > 1 && ($c = intval(substr($n, -2))) > 10 && $c < 20 ? $w_oth : (($c = intval($n{strlen($n) - 1})) == 0 || $c > 4 ? $w_oth : ($c == 1 ? $w1 : $w2_4)));
}

function utils__date_ts2text($timestamp = false, $has_time = false, $has_seconds = false)
{ //date_default_timezone_set('Asia/Almaty');
    global $globals;

    if ($timestamp === false) {
        $timestamp = time();
    }
    $months = array(14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25);
    return str_replace('~', utils__output($months[intval(date('n', $timestamp)) - 1]), date(str_replace('M', '~', $globals['datetime'][($has_time ? ($has_seconds ? 'ymdhis' : 'ymdhi') : 'ymd')]['long']), $timestamp));
}

function utils__date_ts2str($timestamp = false, $has_time = false, $has_seconds = false)
{
    global $globals;
    if ($timestamp === false) {
        $timestamp = time();
    }
    return date($globals['datetime'][($has_time ? ($has_seconds ? 'ymdhis' : 'ymdhi') : 'ymd')]['short'], $timestamp);
}

function utils__date_str2ts($s, $has_time = false, $has_seconds = false)
{
    //date_default_timezone_set('Asia/Almaty');
    global $globals;
    $dt =& $globals['datetime'][($has_time ? ($has_seconds ? 'ymdhis' : 'ymdhi') : 'ymd')];
    $a = explode('-', preg_replace($dt['str_mask'], $dt['int_mask'], $s), 6);
    if (count($a) > 2 && checkdate($a[1], $a[2], $a[0])) {
        if ($has_time) {
            if ($has_seconds) {
                return (count($a) == 6 && $a[3] >= 0 && $a[3] < 24 && $a[4] >= 0 && $a[4] < 60 && $a[5] >= 0 && $a[5] < 60 ? mktime($a[3], $a[4], $a[5], $a[1], $a[2], $a[0]) : false);
            } else {
                return (count($a) == 5 && $a[3] >= 0 && $a[3] < 24 && $a[4] >= 0 && $a[4] < 60 ? mktime($a[3], $a[4], 0, $a[1], $a[2], $a[0]) : false);
            }
        } else {
            return mktime(0, 0, 0, $a[1], $a[2], $a[0]);
        }
    }
    return false;
}

function utils__uri($uri, $al = true, $ad = 1, $k = '', $v = '')
{
    global $globals;
    if ($al) {
        $uri = $globals['lang'] . '/' . $uri;
    }
    if ($ad == 1) {
        $uri = '/' . $uri;
    } else if ($ad == 2) {
        $uri = $globals['serv_home'] . $uri;
    }
    if ($k == '') {
        return $uri;
    }
    $q = strpos($uri, '?');
    if ($q === false) {
        return $uri . '?' . $k . '=' . $v;
    }
    if (strpos($uri, '&' . $k . '=', $q + 1) === false && substr($uri, $q + 1, strlen($k) + 1) != $k . '=') {
        return $uri . '&' . $k . '=' . $v;
    }
    return preg_replace('/([\\?\\&])' . $k . '=[^\\&]*/', '$1' . $k . '=' . $v, $uri);
}

function utils__redirect($uri, $al = true, $ad = 2, $k = '', $v = '')
{
    $uri = utils__uri($uri, $al, $ad, $k, $v);
    db__close();
    if (!headers_sent()) {
        @header('location: ' . $uri);
    }
    die(
        '<html>' . utils__nl() .
        '<head>' . utils__nl(1) .
        '<meta http-equiv="refresh" content="1; url=' . $uri . '" />' . utils__nl(1) .
        '<meta http-equiv="expires" content="Thu, 01 Jan 1970 02:00:00 GMT" />' . utils__nl(1) .
        '<meta http-equiv="cache-control" content="no-cache" />' . utils__nl(1) .
        '<meta http-equiv="pragma" content="no-cache" />' . utils__nl(1) .
        '<script language="javascript" type="text/javascript">' . utils__nl(2) .
        '<!--' . utils__nl(2) .
        'window.location.href = "' . $uri . '";' . utils__nl(2) .
        '//-->' . utils__nl(1) .
        '</script>' . utils__nl() .
        '</head>' . utils__nl() .
        '<body>' . utils__nl() .
        '<layer name="da">' . utils__nl(1) .
        '<div id="da">' . utils__nl(2) .
        '<a href="' . $uri . '">' . $uri . '</a>' . utils__nl(1) .
        '</div>' . utils__nl() .
        '</layer>' . utils__nl() .
        '<script language="javascript" type="text/javascript">' . utils__nl(1) .
        '<!--' . utils__nl(1) .
        'var d = document, n = "da", s = d.getElementById ? d.getElementById(n).style : d.all ? d.all[n].style : d.layers ? d.layers[n] : null;' . utils__nl(1) .
        'if (s)' . utils__nl(1) .
        '{' . utils__nl(2) .
        's.visibility = navigator.appName == "Netscape" && !d.getElementById ? "hide" : "hidden";' . utils__nl(2) .
        's.display = "none";' . utils__nl(1) .
        '}' . utils__nl(1) .
        '//-->' . utils__nl() .
        '</script>' . utils__nl() .
        '</body>' . utils__nl() .
        '</html>'
    );
}

function utils__nl($tab = 0)
{
    return "\r\n" . str_repeat("\t", $tab);
}

function utils__spacer($width = 1, $height = 1, $alt = false)
{
    return '<img' . (empty($alt) ? '' : ' alt="' . htmlspecialchars($alt) . '"') . ' src="/di/s.png" width="' . $width . '" height="' . $height . '" />';
}

function utils__img($src, $alt = false)
{
    $s = @getimagesize('di/' . $src);
    return '<img' . (empty($alt) ? '' : ' alt="' . htmlspecialchars($alt) . '"') . ' src="/di/' . $src . '"' . (is_array($s) ? ' width="' . $s[0] . '" height="' . $s[1] . '"' : '') . ' />';
}

function utils__make_flash($url, $width, $height, $tab = 0, $bgcolor = '#ffffff', $nojs = false)
{
    $tab1 = $tab + 1;
    return
        ($nojs ? '' :
            '<script language="javascript" type="text/javascript">' . utils__nl($tab1) .
            '<!--' . utils__nl($tab1) .
            'dwSwf("' . $url . '",' . $width . ',' . $height . ');' . utils__nl($tab1) .
            '//-->' . utils__nl($tab) .
            '</script>' . utils__nl($tab) .
            '<noscript>') .
        '<object type="application/x-shockwave-flash" data="' . $url . '" width="' . $width . '" height="' . $height . '" align="middle" hspace="0" vspace="0" border="0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">' . utils__nl($tab1) .
        '<param name="allowScriptAccess" value="sameDomain" />' . utils__nl($tab1) .
        '<param name="movie" value="' . $url . '" />' . utils__nl($tab1) .
        '<param name="bgcolor" value="' . $bgcolor . '" />' . utils__nl($tab1) .
        '<param name="quality" value="high" />' . utils__nl($tab1) .
        '<param name="salign" value="r" />' . utils__nl($tab1) .
        '<param name="pluginurl" value="http://www.macromedia.com/go/getflashplayer" />' . utils__nl($tab) .
        '</object>' .
        ($nojs ? '' : '</noscript>');
}

function utils__add_css($n, $a)
{
    global $globals;
    if (!isset($globals['tpl']['css'][$n])) {
        $globals['tpl']['css'][$n] = array();
    }
    foreach ($a as $k => $v) {
        $globals['tpl']['css'][$n][$k] = $v;
    }
}

function utils__js_popup($filename)
{
    $e = strtolower(substr(strrchr($filename, '.'), 1, 10));
    if (in_array($e, array('jpg', 'gif', 'png'))) {
        $s = @getimagesize('di/' . $filename);
        return (' onclick="return wo(\'' . utils__uri('preview/' . addslashes($filename)) . "'" . (is_array($s) ? ', ' . $s[0] . ', ' . $s[1] : '') . ')"');
    } else {
        return ('');
    }
}

function utils__options(&$optgroup, $tab = false, $selected_value = '', $level = 0)
{
    $ret = '';
    if ($tab === false) {
        $tab0 = '';
        $tab1p = false;
    } else {
        $tab0 = utils__nl($tab);
        $tab1p = $tab + 1;
    }
    foreach ($optgroup as $item) {
        $ret .=
            (isset($item['value']) ?
                $tab0 . '<option' . (isset($item['params']) ? $item['params'] : '') .
                ' value="' . $item['value'] . '"' .
                ($selected_value == $item['value'] || !empty($item['selected']) ? ' selected="selected"' : '') . '>' .
                str_repeat('&nbsp; &nbsp; ', $level) . $item['title'] . '</option>' .
                (empty($item['items']) ? '' : utils__options($item['items'], $tab1p, $selected_value, $level + 1))
                :
                $tab0 . '<optgroup label="' . $item['title'] . '"' . (isset($item['params']) ? $item['params'] : '') . '>' .
                (empty($item['items']) ? '' : utils__options($item['items'], $tab1p, $selected_value)) .
                $tab0 . '</optgroup>');
    }
    return ($ret);
}

function utils__add_title($title)
{
    global $globals;
    if ($title != '' && substr($globals['tpl']['title'], 0, strlen($title)) != $title) {
        $globals['tpl']['title'] = $title . ($globals['tpl']['title'] == '' ? '' : ' :: ') . $globals['tpl']['title'];
    }
}

function utils__add_keywords($keywords)
{
    global $globals;
    if ($keywords != '') {
        $globals['tpl']['keywords'] = $keywords . ($globals['tpl']['keywords'] == '' ? '' : ', ') . $globals['tpl']['keywords'];
    }
}

function utils__add_description($description)
{
    global $globals;
    if ($description != '') {
        $globals['tpl']['description'] .= ($globals['tpl']['description'] == '' ? '' : '. ') . $description;
    }
}

function utils__ip()
{
    global $globals;
    if (!isset($globals['user_ip'])) {
        $globals['user_ip'] = preg_replace('/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}).*/', '$1', substr((empty($_SERVER['REMOTE_ADDR']) ? (empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? (empty($_SERVER['HTTP_CLIENT_IP']) ? '' : $_SERVER['HTTP_CLIENT_IP']) : $_SERVER['HTTP_X_FORWARDED_FOR']) : $_SERVER['REMOTE_ADDR']), 0, 500));
        if ($globals['user_ip'] == '') {
            $globals['user_ip'] = '0.0.0.0';
        }
    }
    return $globals['user_ip'];
}

function utils__append_log($last_in)
{
    global $globals;
    db__query($req, "INSERT INTO `%prefix%log` (`addts`, `url`, `ip`, `lang`, `last_in`, `useragent`, `referer`) VALUES ('%addts%', '%url%', '%ip%', '%lang%', '%last_in%', '%useragent%', '%referer%')", array('%addts%', '%url%', '%ip%', '%lang%', '%last_in%', '%useragent%', '%referer%'), array(time(), $_SERVER['REQUEST_URI'], ip2long(utils__ip()), $globals['lang'], $last_in, (empty($_SERVER['HTTP_USER_AGENT']) ? '' : substr($_SERVER['HTTP_USER_AGENT'], 0, 200)), (empty($_SERVER['HTTP_REFERER']) ? '' : substr($_SERVER['HTTP_REFERER'], 0, 200))));
}

function json_out($v)
{
    global $globals;
    $globals['tpl']['main_tpl'] = false;
    if (!headers_sent()) {
        ob_start('ob_gzhandler');
        header('Content-Type: application/json');
        header('Charset: windows-1251');
    }
    echo utils__json_encode($v);
}

function utils__path_init()
{
    global $globals;

    if (empty($_SERVER['REQUEST_URI'])) {
        $url = $_SERVER['PHP_SELF'] . (empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']);
    } else {
        $url = $_SERVER['REQUEST_URI'];
    }
    $globals['page_url'] = $url;//$_SERVER['REQUEST_URI'];
    if (in_array($globals['page_url'], array('/', ''))) {
        $globals['lang'] = substr((isset($_COOKIE['lang']) ? $_COOKIE['lang'] : (getenv('HTTP_ACCEPT_LANGUAGE') == '' ? $globals['default_lang'] : strtolower(getenv('HTTP_ACCEPT_LANGUAGE')))), 0, 2);
        $globals['page_location'] = $globals['page_url'] = '/' . ($globals['lang'] == 'uk' ? 'ru' : $globals['lang']) . '/index/';
        $detect_lang = true;
    } else {
        $globals['page_location'] = preg_replace('/\?.*$/', '', $globals['page_url']);
        if ($globals['page_url']{0} != '/') {
            $globals['page_url'] = '/' . $globals['page_url'];
            $globals['page_location'] = '/' . $globals['page_location'];
        }
        $detect_lang = false;
    }
    $query = explode('/', $globals['page_location']);
    $globals['path'] = array();

    foreach ($query as $q) {
        if ($q != '') {
            $globals['path'][] = $q;
        }
    }

    if (isset($globals['avail_lang'][$globals['path'][0]])) {
        $globals['lang'] = preg_replace('/[^a-z]/', '', $globals['path'][0]);
    } else if ($detect_lang) {
        $globals['path'][0] = $globals['lang'] = $globals['default_lang'];
        $globals['page_location'] = $globals['page_url'] = '/' . $globals['lang'] . '/index/';
    } /*else if(!empty($_GET)){
		$globals['path'][0] = empty($_GET['lang']) ? $globals['default_lang'] : $_GET['lang'];
		$globals['lang'] = preg_replace('/[^a-z]/', '', $globals['path'][0]);
		if (empty($_GET['path'])){return false;}else {
			$globals['path'][1] = $_GET['path'];
		}
		$globals['page_location'] = $_GET['path'];
	}*/
    else {
        if ($globals['path'][0] == 'courses') {
            $globals['courses_dir'] = true;
            $globals['lang'] = $globals['default_lang'];
            array_unshift($globals['path'], $globals['lang']);
            $globals['page_location'] = $globals['page_url'] = '/' . implode('/', $globals['path']) . '/';
        } else {
            return false;
        }
    }

    if (!empty($_GET)) {
        foreach ($_GET as $q) {
            if ($q != '') {
                $globals['path'][] = $q;
            }
        }
    };
    $globals['page_url'] = substr($globals['page_url'], 4);
    $globals['page_location'] = substr($globals['page_location'], 4);
    if (substr($globals['page_location'], -1) != '/') {
        $globals['page_location'] .= '/';
    }
    return true;
}

function utils__body_class()
{
    global $globals;
    $ret = array();
    foreach ($globals['path'] as $path) {
        $ret[] = (
            $path{0} >= '0' &&
            $path{0} <= '9'
                ?
                'c'
                :
                ''
            ) . $path;
    }
    return implode(' ', $ret);
}

function utils__get_path($i)
{
    global $globals;
    return (isset($globals['path'][$i]) ? $globals['path'][$i] : '');
}

function utils__page_init()
{
    global $globals;
    $globals = array();
    require_once('includes/_config.php');
    ini_set('display_errors', $globals['display_errors']);

    if (!utils__path_init()) {
        utils__redirect($globals['default_lang'] . '/404/', false);
    }
    setcookie('lang', $globals['lang'], time() + 31536000, '/');
    $last_in = (isset($_COOKIE['last_in']) ? intval($_COOKIE['last_in']) : 0);
    setcookie('last_in', time(), time() + 31536000, '/');

    if (count($globals['path']) < 2) {
        utils__redirect('404/');
    }
    session_start();
    db__connect();
    if (utils__get_path(1) != 'cron') {
        utils__append_log($last_in);
    }
    login();
    $globals['tpl'] = array(
        'title' => utils__output(1),
        'keywords' => utils__output(2),
        'description' => utils__output(3),
        'slogan' => '',
        'main_content' => '',
        'content_bottom' => '',
        'js_bottom' => array(),
        'css' => array(),
        'main_tpl' => 'templates/all.tpl.php',
        'inner_tpl' => '',
        'body_class' => (login() ? 'logged-in' : 'logged-out'),
        'user' => array(),
    );
    $globals['params'] = array();
    db__query($req, "SELECT `paramname`, `paramvalue` FROM %prefix%params");
    //while ( $res = mysql_fetch_assoc($req) )
    while ($res = $req->fetch_assoc()) {
        $globals['params'][strval($res['paramname'])] = $res['paramvalue'];
    }
    //mysql_free_result($req);
    $req->free_result();
    switch (utils__get_path(1)) {
        case 'tenders-update':
        case 'cron':
        case 'login-send':
        case 'proposition-send':
        case 'register-send':
        case 'confirm-email':
        case 'recover-send':
        case 'confirm-recovery':
        case 'change-password-send':
        case 'logout':
            require_once('includes/' . $globals['path'][1] . '.inc.php');
            break;
        default:
            require_once('includes/production.inc.php');
    }
    if ($globals['tpl']['main_tpl']) {
        require_once($globals['tpl']['main_tpl']);
    }
    db__close();
}

function utils__menu_lang($tab)
{
    global $globals;
    $tab1 = utils__nl($tab + 1);
    $ret = '';

    foreach ($globals['avail_lang'] as $k => $v) {
        $ret .=
            ($ret == '' ? '' : ' <b>|</b>') .
            $tab1 . '<a' . ($k == $globals['lang'] ? ' class="active"' : '') .
            ' title="' . htmlspecialchars($v['title']) . '"' .
            ' href="/' . $k . '/' . $globals['page_url'] . '">' .
            $v['menu'] . '</a>';
    }

    return $ret . utils__nl($tab);
}

function utils__menu($tab, $hideSeparator = false)
{
    global $globals;

    db__query($req,
        "SELECT
				`id`,
				`title_%lang%` AS `title`,
				`url`,
				`urltype`
			FROM `%prefix%menu_top`
			ORDER BY `ord`"
    );
    //$rows = mysql_num_rows($req);
    $rows = $req->num_rows;
    if ($rows == 0) {
        //mysql_free_result($req);
        $req->free_result();
        return '';
    }

    $ret = '';
    $tab1 = utils__nl($tab + 1);
    $i = 0;
    //$c = mysql_num_rows($req);
    $c = $req->num_rows;
    //while ( $res = mysql_fetch_assoc($req) )
    while ($res = $req->fetch_assoc()) {
        ++$i;
        $ret .=
            $tab1 . '<a' .
            (
            strpos($globals['page_url'], $res['url']) !== false
            && $res['urltype'] == 0
                ?
                ' class="active' . ($i == $c ? ' last' : '') . '"'
                :
                ($i == $c ? ' class="last"' : '')
            ) .
            (
            $res['urltype'] == 1
                ?
                ' href="' . $res['url'] . '" target="_blank"'
                :
                ' href="' . utils__uri($res['url']) . '"'
            ) .
            '>' . $res['title'] . '</a>';
    }
    //mysql_free_result($req);
    $req->free_result();

    return $ret;
}

function utils__menu_top($tab)
{
    $tab1 = utils__nl($tab + 1);
    $tab2 = utils__nl($tab + 2);
    $tab3 = utils__nl($tab + 3);
    $tab4 = utils__nl($tab + 4);
    $ret = '';
    $menu = array(
        $tab2 . '<div>' .
        $tab3 . '<a' .
        (utils__get_path(1) == 'index' && utils__get_path(2) == '' ? ' class="active"' : '') .
        ' href="' . utils__uri('index/') . '">' .
        utils__output(101) . '</a>' .
        $tab2 . '</div>'
    );

    db__query($req,
        "SELECT
				`id`,
				`haschilds`,
				`menu_title_%lang%` AS `menu_title`,
				`url`
			FROM `%prefix%commodities`
			WHERE `level` = 0
				AND `ispublished` = 1
				AND `inmenutop` = 1
				AND `menu_title_%lang%` != ''
			ORDER BY `ord`"
    );
    //while ( $res = mysql_fetch_assoc($req) )
    while ($res = $req->fetch_assoc()) {
        if ($res['url'] == 'history' && !login()) {
            continue;
        }
        $ret =
            $tab2 . '<div>' .
            $tab3 . '<a' .
            (utils__get_path(1) == $res['url'] && !($res['url'] == 'index' && utils__get_path(2) == '') ? ' class="active"' : '') .
            ' href="' . utils__uri($res['url'] . '/' . ($res['url'] == 'index' ? 'details/' : '')) . '">' .
            $res['menu_title'] . '</a>';
        if ($res['haschilds']) {
            db__query($reqc,
                "SELECT
						`menu_title_%lang%` AS `menu_title`,
						CASE `parentid`
							WHEN 39 THEN CONCAT('it/', `url`)
							WHEN 40 THEN CONCAT('trenings/', `url`)
							WHEN 41 THEN CONCAT('students/', `url`)
							ELSE `url`
						END AS `url`
					FROM `%prefix%commodities`
					WHERE `parentid` IN (%pid%)
						AND `ispublished` = 1
						AND `inmenutop` = 1
						AND `menu_title_%lang%` != ''
					ORDER BY `parentid`, `ord`",
                '%pid%',
                ($res['id'] == 9 ? '39, 40, 41' : $res['id'])
            );
            //if ( mysql_num_rows($reqc) )
            if ($reqc->num_rows) {
                $ret2 = $tab3 . '<div>';
                $i = 0;
                //while ( $resc = mysql_fetch_assoc($reqc) )
                while ($resc = $reqc - fetch_assoc()) {
                    if ($res['url'] == 'remote-training' && ($resc['url'] == 'login' || $resc['url'] == 'registration') && login()) {
                        continue;
                    }
                    ++$i;
                    $ret2 .=
                        $tab4 . '<a' .
                        (
                        utils__get_path(1) == $res['url']
                        && utils__get_path(2) == $resc['url']
                            ?
                            ' class="' . ($i == 1 ? 'first ' : '') . 'active"'
                            :
                            ' class="' . ($i == 1 ? 'first' : '') . '"'
                        ) .
                        ' href="' . utils__uri($res['url'] . '/' . $resc['url'] . '/') . '"><i>' .
                        $resc['menu_title'] . '</i></a>';
                }
                $ret .= ($ret2 == $tab3 . '<div>' ? '' : $ret2 . $tab3 . '</div>');
            }
            //mysql_free_result($reqc);
            $reqc->free_result();
        }
        $ret .= $tab2 . '</div>';
        $menu[] = $ret;
    }
    //mysql_free_result($req);
    $req->free_result();

    if (login()) {
        $menu[] =
            $tab2 . '<div>' .
            $tab3 . '<a' .
            ' href="' . utils__uri('logout/') . '">' .
            utils__output(83) . '</a>' .
            $tab2 . '</div>';
    }

    $c = count($menu) - 1;
    $w = floor(100 / count($menu));
    $ws = max(0, floor((100 - $w * $c) / 2));
    $we = max(0, 100 - $w * $c - $ws);

    return
        $tab1 . '<td class="no-vr" width="' . $ws . '%">&nbsp;</td>' .
        $tab1 . '<th>' .
        implode(
            $tab1 . '</th>' .
            $tab1 . '<td width="' . $w . '%">&nbsp;</td>' .
            $tab1 . '<th>',
            $menu
        ) .
        $tab1 . '</th>' .
        $tab1 . '<td class="no-vr" width="' . $we . '%">&nbsp;</td>' . utils__nl($tab);
}

function utils__menu_bottom($tab)
{
    return utils__menu($tab, true) . utils__nl($tab);
}

function utils__menu_main($tab)
{
    global $globals;
    $tab1 = utils__nl($tab + 1);
    $ret = '';

    foreach (array(
                 array(
                     'url' => 'about/licencing/',
                     'title' => utils__output(77),
                 ),
                 array(
                     'url' => 'about/projecting/',
                     'title' => utils__output(78),
                 ),
                 array(
                     'url' => 'metal-cabines/',
                     'title' => utils__output(79),
                 ),
                 array(
                     'url' => 'about/metalware/',
                     'title' => utils__output(80),
                 ),
                 array(
                     'url' => 'sandwich-buildings/dwelling-houses/',
                     'title' => utils__output(81),
                 ),
             ) as $k => $v) {
        $ret .=
            $tab1 . '<a class="m' . strval($k + 1) .
            (strpos($globals['page_url'], $v['url']) === false ? '' : ' active') .
            '" href="' . utils__uri($v['url']) . '">' . $v['title'] . '</a>';
    }

    return $ret;
}

function utils__menu_left($tab)
{
    global $globals;
    $tab1 = utils__nl($tab + 1);
    $tab2 = utils__nl($tab + 2);
    $tab3 = utils__nl($tab + 3);

    if (!$globals['tpl']['isfullmenuleft']) {
        if (!$globals['tpl']['menu_parentid']) {
            $globals['tpl']['isfullmenuleft'] = true;
        } else {
            db__query($req,
                "SELECT 1
					FROM `%prefix%commodities`
					WHERE `parentid` = '%pid%'
						AND `ispublished` = 1
						AND `inmenuleft` = 1
						AND `menu_title_%lang%` != ''
					ORDER BY `ord`",
                '%pid%',
                $globals['tpl']['menu_parentid']
            );
            //if ( !mysql_num_rows($req) )
            if (!$req->num_rows) {
                $globals['tpl']['isfullmenuleft'] = true;
            }
            //mysql_free_result($req);
            $req->free_result();
        }
    }

    $ret = (
    $globals['tpl']['isfullmenuleft']
        ?
        $tab1 . '<div>' .
        $tab2 . '<span class="hh"><a' .
        ' href="' . utils__uri('index/') . '">' .
        utils__output(101) . '</a></span>' .
        $tab1 . '</div>'
        :
        ''
    );

    $adUrl = ($globals['tpl']['isfullmenuleft'] ? '' : utils__get_path(1) . '/');
    db__query($req,
        "SELECT
				`id`,
				`haschilds`,
				`menu_title_%lang%` AS `menu_title`,
				`title_%lang%` AS `title`,
				`url`
			FROM `%prefix%commodities`
			WHERE %cond%
				AND `ispublished` = 1
				AND `inmenuleft` = 1
				AND `menu_title_%lang%` != ''
			ORDER BY `ord`",
        '%cond%',
        (
        $globals['tpl']['isfullmenuleft']
            ?
            '`level` = 0'
            :
            '`parentid` = ' . $globals['tpl']['menu_parentid']
        )
    );
    //while ( $res = mysql_fetch_assoc($req) )
    while ($res = $req->fetch_assoc()) {
        if ($globals['tpl']['isfullmenuleft'] && $res['url'] == 'history' && !login()) {
            continue;
        }
        if (!$globals['tpl']['isfullmenuleft'] && utils__get_path(1) == 'remote-training' && ($res['url'] == 'login' || $res['url'] == 'registration') && login()) {
            continue;
        }
        $ret .=
            $tab1 . '<div>' .
            $tab2 . '<span class="hh"><a' .
            ($res['title'] == $res['menu_title'] ? '' : ' title="' . htmlspecialchars($res['title']) . '"') .
            (($globals['tpl']['isfullmenuleft'] ? utils__get_path(1) == $res['url'] && !($res['url'] == 'index' && utils__get_path(2) == '') : utils__get_path(2) == $res['url']) ? ' class="active"' : '') .
            ' href="' . utils__uri($adUrl . $res['url'] . '/' . ($globals['tpl']['isfullmenuleft'] && $res['url'] == 'index' ? 'details/' : '')) . '">' .
            $res['menu_title'] . '</a></span>';
        if ($res['haschilds']) {
            db__query($reqc,
                "SELECT
						`menu_title_%lang%` AS `menu_title`,
						`title_%lang%` AS `title`,
						`url`
					FROM `%prefix%commodities`
					WHERE `parentid` = '%pid%'
						AND `ispublished` = 1
						AND `inmenuleft` = 1
						AND `menu_title_%lang%` != ''
					ORDER BY `ord`",
                '%pid%',
                $res['id']
            );
            //if ( mysql_num_rows($reqc) )
            if ($reqc->num_rows) {
                $ret2 = $tab2 . '<p>';
                //while ( $resc = mysql_fetch_assoc($reqc) )
                while ($resc = $reqc->fetch_assoc()) {
                    if ($globals['tpl']['isfullmenuleft'] && $res['url'] == 'remote-training' && ($resc['url'] == 'login' || $resc['url'] == 'registration') && login()) {
                        continue;
                    }
                    $ret2 .=
                        $tab3 . '<a' .
                        ($resc['title'] == $resc['menu_title'] ? '' : ' title="' . htmlspecialchars($resc['title']) . '"') .
                        (
                        utils__get_path($globals['tpl']['isfullmenuleft'] ? 1 : 2) == $res['url']
                        && utils__get_path($globals['tpl']['isfullmenuleft'] ? 2 : 3) == $resc['url']
                            ?
                            ' class="active"'
                            :
                            ''
                        ) .
                        ' href="' . utils__uri($adUrl . $res['url'] . '/' . $resc['url'] . '/') . '">' .
                        $resc['menu_title'] . '</a>';
                }
                $ret .= ($ret2 == $tab2 . '<p>' ? '' : $ret2 . $tab2 . '</p>');
            }
            //mysql_free_result($reqc);
            $reqc->free_result();
        }
        $ret .= $tab1 . '</div>';
    }
    //mysql_free_result($req);
    $req->free_result();

    if ($globals['tpl']['isfullmenuleft'] && login()) {
        $ret .=
            $tab1 . '<div>' .
            $tab2 . '<span class="hh"><a' .
            ' href="' . utils__uri('logout/') . '">' .
            utils__output(83) . '</a></span>' .
            $tab1 . '</div>';
    }

    return $ret;
}

function utils__search($tab)
{
    $tab1 = utils__nl($tab + 1);
    $tab2 = utils__nl($tab + 1);
    return
        $tab1 . '<form action="' . utils__uri('search/') . '" method="get">' .
        $tab2 . '<input class="button" type="image" src="/di/search2.png" />' .
        $tab2 . '<input class="text" type="text" name="text" value="' . (
        isset($_GET['text'])
            ?
            htmlspecialchars($_GET['text'])
            :
            utils__output(104)
        ) . '" />' .
        $tab1 . '</form>';
}

function utils__banner_left($tab)
{
    return utils__img('banner.png');
}

function utils__copy($tab)
{
    global $globals;
    $tab1 = utils__nl($tab + 1);
    return
        //$tab1 . '<a class="developer blank" href="http://www.promios.com/">??????????: Promios Studio</a>' .
        $tab1 . utils__output(5, '%year%', date('Y')) .
        utils__nl($tab);
}

function implode_not_empty($a, $spacer = ' ')
{
    $s = '';
    foreach ($a as $v) {
        $s .= (empty($s) || empty($v) ? '' : $spacer) . $v;
    }
    return $s;
}

function login($login = '', $password = '')
{
    global $globals;

    if ($login == '' && $password == '' && !empty($globals['user']['id'])) {
        return true;
    }

    if ($login == '') {
        $login = (isset($_SESSION['login']) ? $_SESSION['login'] : (isset($_COOKIE['login']) ? $_COOKIE['login'] : ''));
    }
    if ($password == '') {
        $password = (isset($_SESSION['password']) ? $_SESSION['password'] : (isset($_COOKIE['password']) ? $_COOKIE['password'] : ''));
    }

    $user = false;
    if (strlen($login) > 1 && strlen($password) > 1) {
        db__query($req,
            "SELECT *
				FROM `%prefix%users`
				WHERE `email` = '%login%'
					AND `password` = '%password%'
					AND `status` = 3
				LIMIT 1",
            array('%login%', '%password%'),
            array($login, $password)
        );
        //$user = mysql_fetch_assoc($req);
        $user = $req->fetch_assoc();
        //mysql_free_result($req);
        $req->free_result();
    }

    if ($user) {
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
        $globals['user'] = $user;
        return true;
    }

    $_SESSION['login'] = '';
    $_SESSION['password'] = '';
    $globals['user'] = array();
    return false;
}

function tendersHtml()
{
    global $globals;
    $tab0 = utils__nl(0);
    $tab1 = utils__nl(1);
    $tab2 = utils__nl(2);

    /*db__query($req,
        "SELECT
                t.*,
                t.`body_%lang%` AS `body`,
                c.`title_%lang%` AS `loadingcitytitle`
            FROM `%prefix%tenders` t
            LEFT JOIN `%prefix%cities` c
                ON c.`id` = t.`loadingcityid`
            WHERE t.`startts` >= '%fromts%'
                AND t.`startts` <= '%tots%'
            ORDER BY t.`number`",
        array('%uid%', '%fromts%', '%tots%'),
        array($globals['user']['id'], mktime(0, 0, 0), mktime(23, 59, 59))
    );*/
    db__query($req,
        "SELECT
				t.*,
				t.`body_%lang%` AS `body`,
				c.`title_%lang%` AS `loadingcitytitle`
			FROM `%prefix%tenders` t
			LEFT JOIN `%prefix%cities` c
				ON c.`id` = t.`loadingcityid`
			WHERE t.`startts` >= '%fromts%'
				AND t.`startts` <= '%tots%'
			ORDER BY t.`number`",
        array('%fromts%', '%tots%'),
        array(mktime(0, 0, 0), mktime(23, 59, 59))
    );
    //if ( mysql_num_rows($req) )
    if ($req->num_rows) {
        $tenders =
            $tab0 . '<table class="tenders" ts="' . time() . '" step="' . intval($globals['params']['price_step']) . '">' .
            $tab1 . '<tr>' .
            $tab2 . '<th>№ маршрута</th>' .
            $tab2 . '<th>Дата окончания тендера</th>' .
            $tab2 . '<th>Время до окончания тендера</th>' .
            $tab2 . '<th>Маршрут</th>' .
            $tab2 . '<th>Дата прибытия на&nbsp;погрузку</th>' .
            $tab2 . '<th>Даты прибытия в точки доставки</th>' .
            $tab2 . '<th>Объем груза, т</th>' .
            $tab2 . '<th>Начальная цена, тенге</th>' .
            $tab2 . '<th>Текущая цена, тенге</th>' .
            $tab2 . '<th>Призовая цена, тенге</th>' .
            $tab2 . '<th>Ваша цена, тенге</th>' .
            $tab2 . '<th>Кол-во предло- жений</th>' .
            $tab1 . '<tr>';
        //while ( $res = mysql_fetch_assoc($req) )
        while ($res = $req->fetch_assoc()) {
            $rest = max(0, $res['startts'] + $res['length'] * 60 - time());
            $complete = ($res['iscomplete'] == 1 || $rest == 0 ? true : false);
            $isfuture = ($res['startts'] > time() ? true : false);
            $h = floor($rest / 3600);
            $i = floor(($rest - $h * 3600) / 60);
            $s = $rest - $h * 3600 - $i * 60;
            $rest = (
                ($h ? $h . ':' : '') .
                ($h || $i ? ($i > 9 ? '' : '0') . $i . ':' : '') .
                ($s > 9 ? '' : '0') . $s
            );

            db__query($reqc,
                "SELECT `price`
					FROM `%prefix%propositions`
					WHERE `tenderid` = '%tid%'
						AND `userid` = '%uid%'
					ORDER BY `datets` DESC
					LIMIT 1",
                array('%tid%', '%uid%'),
                array($res['id'], $globals['user']['id'])
            );
            //$resc = mysql_fetch_assoc($reqc);
            $resc = $reqc->fetch_assoc();
            //mysql_free_result($reqc);
            $reqc->free_result();
            $res['lastuserprice'] = ($resc ? $resc['price'] : 0);
//date_default_timezone_set('Asia/Almaty');
            $res['course'] = unserialize($res['course']);
            $course = array($res['loadingcitytitle']);
            $loadingDates = array();
            foreach ($res['course'] as $i => $r) {
                $course[] = '<i>' . (count($res['course']) > 1 ? $i . '. ' : '') . $r['citytitle'] . '</i>';
                $loadingDates[] = '<i>' . date('d.m.Y, H:i', $r['datets']) . '</i>';
            }
            if (!empty($res['body'])) {
                $course[] = '<b>' . $res['body'] . '</b>';
            }
//			date_default_timezone_set('Asia/Almaty');

            $tenders .=
                $tab1 . '<tr class="' . ($complete ? ($globals['user']['id'] == $res['lastuserid'] && $res['pricecurrent'] <= $res['pricestart'] ? 'won' : 'complete') : ($isfuture ? 'future' : '')) . '" number="' . $res['number'] . '">' .
                $tab2 . '<td>' . $res['number'] . '</td>' .
                $tab2 . '<td class="left">' . date('d.m.Y, H:i', $res['startts'] + $res['length'] * 60) . '</td>' .
                $tab2 . (
                $complete
                    ?
                    '<td>тендер окончен</td>'
                    :
                    '<td class="rest" rest="' .
                    strval($res['startts'] + $res['length'] * 60) . '"' .
                    ($isfuture ? ' start="' . $res['startts'] . '"' : '') . '>' .
                    $rest . '</td>'
                ) .
                $tab2 . '<td class="left">' . implode(' ', $course) . '</td>' .
                $tab2 . '<td class="left">' . date('d.m.Y, H:i', $res['loadingts']) . '</td>' .
                $tab2 . '<td class="left">' . implode(' ', $loadingDates) . '</td>' .
                $tab2 . '<td>' . $res['volume'] . '</td>' .
                $tab2 . '<td>' . $res['pricestart'] . '</td>' .
                $tab2 . '<td>' . $res['pricecurrent'] . '</td>' .
                $tab2 . (
                $complete || $isfuture
                    ?
                    '<td class="nowrap">' . $res['pricewin'] . '</td>'
                    :
                    '<td class="nowrap pricewin"><span>' .
                    $res['pricewin'] .
                    '</span> <a href="#">&nbsp;</a></td>'
                ) .
                (
                $isfuture
                    ?
                    $tab2 . '<td class="nowrap" colspan="2">тендер начнется<br /> ' . date('d.m.Y ? H:i', $res['startts']) . '</td>'
                    :
                    $tab2 . (
                    $complete
                        ?
                        '<td class="nowrap">' . $res['lastuserprice'] . '</td>'
                        :
                        '<td class="nowrap price"><input value="' .
                        htmlspecialchars($res['lastuserprice'] == 0 ? '' : $res['lastuserprice']) .
                        '" /> <a href="#">&nbsp;</a></td>'
                    ) .
                    $tab2 . '<td>' . $res['propositions'] . '</td>'
                ) .
                $tab1 . '</tr>';
        }
        $tenders .=
            $tab0 . '</table>' .
            $tab0 . '<p class="right legend">Обозначения: <span class="complete">завершенный тендер;</span> <span class="won">выигранный вами тендер</span></p>';
    } else {
        $tenders = '<p class="large-center no-tenders">' . utils__output(92) . '</p>';
    }
    //mysql_free_result($req);
    $req->free_result();

    return $tenders;
}

?>