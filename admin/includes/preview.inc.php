<?php
$smarty =& $caller->tpl->smarty;
$path =& $caller->path;
if (count($path) < 2 || preg_match('/[^\w\-\.]/', substr($path[count($path) - 1], 0, 500))) {
    $caller->close();
    exit();
}
$direction = $path[0];
$filename = substr($path[count($path) - 1], 0, 500);
$ext = $caller->io->get_file_ext($filename);
$is_img = in_array($ext, array('jpg', 'png', 'gif'));
if (!$is_img || count($path) == 3) {
    if ($direction == 'temp') {
        $caller->db->query($req, 500, array('%filename%', '%location%'), array($filename, $caller->frontend_rw_dir . '/'));
        $res = $caller->db->fetch_assoc($req);
        $caller->db->free_result($req);
        $real_filename = (is_array($res) ? $res['temp_filename'] : '');
    } else {
        $real_filename = $caller->frontend_rw_dir . '/' . $filename;
    }
    if (!is_file($real_filename)) {
        $caller->close();
        exit();
    }
    if (function_exists('mime_content_type')) {
        $content_type = mime_content_type($filename);
    } else {
        switch ($ext) {
            case 'jpg':
                $content_type = 'image/jpeg';
                break;
            case 'png':
                $content_type = 'image/png';
                break;
            case 'gif':
                $content_type = 'image/gif';
                break;
            case 'doc':
                $content_type = 'application/msword';
                break;
            case 'xls':
                $content_type = 'application/vnd.ms-excel';
                break;
            case 'pdf':
                $content_type = 'application/pdf';
            case 'html':
            case 'htm':
                $content_type = 'text/html';
                break;
        }
    }
    $caller->close();
    header('Content-Type: ' . (empty($content_type) ? 'application/x-gzip' : $content_type));
    header('Content-Length: ' . filesize($real_filename));
    header('Content-Transfer-Encoding: binary');
    header('Content-Disposition: inline; filename=' . $filename);
    readfile($real_filename);
    exit();
} else {
    $smarty->assign('bgcolor', (isset($_GET['bg']) ? $_GET['bg'] : 'fff'));
    $s = @getimagesize($caller->frontend_rw_dir . '/' . $filename);
    $smarty->assign('img', '<img src="' . $caller->uri($direction . '/file/' . $filename) . '"' . (is_array($s) ? ' width="' . $s[0] . '" height="' . $s[1] . '"' : '') . ' />');
    $smarty->display('preview.tpl');
}
?>