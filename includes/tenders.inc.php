<?php

if (!login()) {
    utils__redirect('auth-required/');
}

$tenders = '<div id="tenders" ts="' . time() . '">' . tendersHtml() . '</div>';
if (strpos($globals['tpl']['main_content'], '%%tenders%%')) {
    $globals['tpl']['main_content'] = preg_replace(
        '/\s*\<p[^\>]*>\s*\%\%tenders\%\%\s*\<\/p\>/i',
        $tenders,
        $globals['tpl']['main_content'],
        1
    );
} else {
    $globals['tpl']['main_content'] .= $tenders;
}

?>