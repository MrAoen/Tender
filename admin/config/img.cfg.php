<?php
/*
*/

$caller->img_all_filename_prefix = '';
$caller->img_visible_count = 1;
$caller->img_l = array(
    'max_file_size' => 5242880,
    'allowed_filetypes' => array('jpg', 'png', 'gif'),
    'filename_prefix' => 'l_',
    'min_width' => 156,
    'max_width' => 980,
    'min_height' => 156,
    'max_height' => 980,
    'quality' => 85,
    'stamp_filename' => 'img/stamp_l.png',
    'stamp_align' => 'rb',
    'stamp_padding' => array('x' => 13, 'y' => 9),
);
$caller->img_s = array(
    'max_file_size' => 512000,
    'allowed_filetypes' => array('jpg', 'png', 'gif'),
    'filename_prefix' => 's_',
    'min_width' => 200,
    'max_width' => 200,
    'min_height' => 50,
    'max_height' => 400,
    'quality' => 85,
    'stamp_filename' => 'img/stamp_s.png',
    'stamp_align' => 'rb',
    'stamp_padding' => array('x' => 4, 'y' => 4),
);
?>