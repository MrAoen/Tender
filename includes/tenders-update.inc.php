<?php

function tendersOutput($s)
{
    db__close();
    if (!headers_sent()) {
        ob_start('ob_gzhandler');
        header('Content-Type: text/html;charset=Windows-1251');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 10800) . ' GMT');
        header('Expires: ' . gmdate('D, d M Y H:i:s', 0) . ' GMT');
        header('Cache-Control: no-cache');
    }
    exit($s);
}

if (!login()) {
    tendersOutput('');
}

tendersOutput(tendersHtml());

?>