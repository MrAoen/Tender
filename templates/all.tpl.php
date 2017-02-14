<?php
if (utils__get_path(1) == '404') {
    if (strncasecmp(PHP_SAPI, 'cgi', 3)) {
        header('HTTP/1.1 404 Not Found');
    } else {
        header('Status: 404 Not Found');
    }
} else {
    ob_start('ob_gzhandler');
}
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 10800) . ' GMT');
header('Expires: ' . gmdate('D, d M Y H:i:s', 0) . ' GMT');
header('Cache-Control: no-cache');
$tab0 = utils__nl();
$tab1 = utils__nl(1);
$tab2 = utils__nl(2);
$tab3 = utils__nl(3);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=windows-1251"/>
    <title><?php echo $globals['tpl']['title']; ?></title>
    <?php if (utils__get_path(1) == '404') { ?>
        <meta name="robots" content="DISALLOW"/>
    <?php } else { ?>
        <meta name="robots" content="ALL"/><?php
        echo
            ($globals['tpl']['keywords'] == '' ? '' : $tab1 . '<meta name="keywords" content="' . htmlspecialchars($globals['tpl']['keywords']) . '" />') .
            ($globals['tpl']['description'] == '' ? '' : $tab1 . '<meta name="description" content="' . htmlspecialchars($globals['tpl']['description']) . '" />') .
            $tab1;
    }
    ?>
    <meta http-equiv="content-language" content="<?php echo($globals['lang'] == 'ru' ? 'uk' : $globals['lang']); ?>"/>
    <meta http-equiv="expires" content="Thu, 01 Jan 1970 02:00:00 GMT"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <link rel="stylesheet" type="text/css" href="/css/all.min.css"/><?php
    if (count($globals['tpl']['css']) > 0) {
        echo $tab1 . '<style type="text/css">';
        foreach ($globals['tpl']['css'] as $n => $a) {
            echo $tab2 . $n . ' {';
            foreach ($a as $k => $v) {
                echo $tab3 . $k . ': ' . $v . ';';
            }
            echo $tab2 . '}';
        }
        echo $tab1 . '</style>';
    }
    echo $tab1;
    ?>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
    <script language="javascript" type="text/javascript" src="/js/all.min.js"></script>
    <?php if (count($globals['tpl']['js_bottom']) > 0) { ?>
        <script language="javascript" type="text/javascript"><?php
            echo $tab1 . implode($tab1, $globals['tpl']['js_bottom']) . $tab1;
            ?></script>
    <?php } ?>
</head>
<body<?php echo($globals['tpl']['body_class'] == '' ? '' : ' class="' . $globals['tpl']['body_class'] . '"'); ?>>
<div class="content-bg">
    <div class="content-bottom">
        <div class="content-top">
            <div class="content">
                <div class="localtime"><?php echo time(); ?></div>
                <div id="top">
                    <a class="logo" href="/">&nbsp;</a>
                    <div class="menu<?php echo(login() ? '' : ' hidden'); ?>">
                        <a href="<?php echo utils__uri('tenders/'); ?>"><?php echo utils__output(81); ?></a>
                        <a href="<?php echo utils__uri('change-password/'); ?>"><?php echo utils__output(82); ?></a>
                        <a href="<?php echo utils__uri('logout/'); ?>"><?php echo utils__output(83); ?></a>
                    </div>
                    <?php if (!login()) { ?>
                        <div class="login">
                            <form>
                                <h4><?php echo utils__output(76); ?></h4>
                                <div class="email">
                                    <input class="text" type="text" name="email"
                                           placeholder="<?php echo htmlspecialchars(utils__output(77)); ?>"/>
                                </div>
                                <div>
                                    <input class="text" type="password" name="password"
                                           placeholder="<?php echo htmlspecialchars(utils__output(78)); ?>"/>
                                    <input class="submit" type="submit" value=" "/>
                                </div>
                                <p>
                                    <a href="<?php echo utils__uri('register/'); ?>"><?php echo utils__output(79); ?></a>
                                    <a href="<?php echo utils__uri('recover/'); ?>"><?php echo utils__output(80); ?></a>
                                </p>
                            </form>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($globals['tpl']['inner_tpl'] == '') { ?>
                    <?php echo $globals['tpl']['main_content']; ?>
                <?php } else require_once($globals['tpl']['inner_tpl']); ?>
                <div class="copy"><?php echo utils__copy(2); ?></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>