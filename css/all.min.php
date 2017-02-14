<?php
$filenames = array(
    'all.min.css',
);
$content_type = 'text/css';
if (isset($_GET['editor'])) {
    $filenames[] = 'editor.css';
}

function compare_asterisk($svar, $compare)
{
    foreach (array_map('trim', explode(',', $_SERVER[$svar])) as $request) {
        if ($request === '*' || $request === $compare) {
            return true;
        }
    }
    return false;
}

function send_status($status)
{
    if (strncasecmp(PHP_SAPI, 'cgi', 3)) {
        header('HTTP/1.1 ' . $status);
    } else {
        header('Status: ' . $status);
    }
}

function send_headers(&$headers)
{
    foreach ($headers as $k => $v) {
        header($k . ': ' . $v);
    }
}

$last_modified = 0;
$md5 = '';

foreach ($filenames as $v) {
    $last_modified = max($last_modified, intval(filemtime($v)) - 2 * 86400);
    $fst = stat($v);
    $md5 .= $fst['mtime'] . '=' . $fst['ino'] . '=' . $fst['size'];
}

$md5 = md5($md5);
$etag = '"' . $md5 . '-' . crc32($md5) . '"';

$headers = array(
    'Pragma' => 'cache',
    'Cache-Control' => 'public, must-revalidate, max-age=0',
    'Last-Modified' => date('D, d M Y H:i:s', $last_modified) . ' GMT',
    'ETag' => $etag,
);

if (
    (
        isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
        && $last_modified == strtotime(current(
            $a = explode(';', $_SERVER['HTTP_IF_MODIFIED_SINCE'])
        ))
    ) || (
        isset($_SERVER['HTTP_IF_NONE_MATCH'])
        && compare_asterisk('HTTP_IF_NONE_MATCH', $etag)
    )
) {
    send_status('304 Not Modified');
    send_headers($headers);
    exit();
}

ob_start('ob_gzhandler');
send_status('200 OK');
$headers['Content-Type'] = $content_type;
send_headers($headers);
foreach ($filenames as $v) {
    readfile($v);
}

?>