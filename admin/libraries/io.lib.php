<?php

/*
*/

class cIO
{
    var
        $lang_available_frontend,
        $lang_available,
        $lang_active,
        $lang_show_fields,
        $lang_charset,
        $lang_word_by_no_method,
        $datetime,
        $image_resize_method,
        $all,
        $voutput,
        $locale_set;

    function cIO(&$all)
    {
        $this->all =& $all;
        $this->all->finclude('config/io.cfg.php', $this);
        $this->lang_active = substr((isset($_GET['lang']) ? $_GET['lang'] : (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : (getenv('HTTP_ACCEPT_LANGUAGE') == '' ? $this->lang_default : strtolower(getenv('HTTP_ACCEPT_LANGUAGE'))))), 0, 2);
        if (!in_array($this->lang_active, $this->lang_available)) {
            $this->lang_active = $this->lang_default;
        }
        $this->lang_show_fields = (count($this->lang_available_frontend) > 1);
        $this->all->finclude('config/languages/' . $this->lang_active . '/all.lng.php', $this);
        $this->locale_set = setlocale(LC_CTYPE, $this->lang_locales);
    }

    function str_to_lower($s)
    {
        if ($this->locale_set) {
            return strtolower($s);
        }
        if ($s == '') {
            return '';
        }
        $ord_f = ord('À') - 1;
        $ord_l = ord('ß') + 1;
        $ord_d = ord('à') - ord('À');
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

    function str_to_upper($s)
    {
        if ($this->locale_set) {
            return strtoupper($s);
        }
        if ($s == '') {
            return '';
        }
        $ord_f = ord('à') - 1;
        $ord_l = ord('ÿ') + 1;
        $ord_d = ord('À') - ord('ÿ');
        $s = strtoupper($s);
        $l = strlen($s);
        for ($i = 0; $i < $l; $i++) {
            $ord_s = ord($s{$i});
            if ($ord_s > $ord_f && $ord_s < $ord_l) {
                $s{$i} = chr($ord_s + $ord_d);
            }
        }
        return $s;
    }

    function str_cut($s, $l = 76, $max_left = 8, $max_right = 20, $a = '...')
    {
        $s = trim(preg_replace('/\s+/s', ' ', strip_tags($s)));
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

    function date_ts2text($timestamp = false, $has_time = true, $has_seconds = false)
    {
        if ($timestamp === false) {
            $timestamp = time();
        }
        return str_replace('~', $this->output(intval(date('n', $timestamp)) + 4), date(str_replace('M', '~', $this->datetime[($has_time ? ($has_seconds ? 'ymdhis' : 'ymdhi') : 'ymd')]['long']), $timestamp));
    }

    function output($id, $params = false, $values = false)
    {
        return ($params === false ? $this->voutput[strval($id)] : str_replace($params, $values, $this->voutput[strval($id)]));
    }

    function date_ts2str($timestamp = false, $has_time = false, $has_seconds = false)
    {
        if ($timestamp === false) {
            $timestamp = time();
        }
        return date($this->datetime[($has_time ? ($has_seconds ? 'ymdhis' : 'ymdhi') : 'ymd')]['short'], $timestamp);
    }

    function filesize_str($v, $is_filename = false)
    {
        if ($is_filename) {
            $v = (is_file($v) ? filesize($v) : 0);
        }
        return
            ($v < 1024 ? $v . $this->output(69) :
                ($v < 1048576 ? sprintf('%01.2f', $v / 1024) . $this->output(70) :
                    ($v < 1073741824 ? sprintf('%01.2f', $v / 1048576) . $this->output(71) :
                        sprintf('%01.2f', $v / 1073741824) . $this->output(72))));
    }

    function time_term_str($s, $rp = false, $show_seconds = true, $show_minutes = true)
    {
        $ret = array();
        $dl = (isset($this->all->prj) && isset($this->all->prj->day_length) ? $this->all->prj->day_length : 24) * 3600;
        if ($s >= $dl) {
            $v = floor($s / $dl);
            $ret[] = $v . ' ' . $this->output($dl == 86400 ? $this->word_by_no($v, 64, 65, 66) : $this->word_by_no($v, 76, 77, 78));
            $s -= $v * $dl;
            $show_minutes = false;
            $show_seconds = false;
        }
        if ($s > 3600) {
            $v = floor($s / 3600);
            $ret[] = $v . ' ' . $this->output($this->word_by_no($v, 61, 62, 63));
            $s -= $v * 3600;
            $show_seconds = false;
        }
        if ($show_minutes && $s > 60) {
            $v = floor($s / 60);
            $ret[] = $v . ' ' . $this->output($this->word_by_no($v, ($rp ? 68 : 58), 59, 60));
            $s -= $v * 60;
        }
        if ($show_seconds && $s > 0) {
            $ret[] = $s . ' ' . $this->output($this->word_by_no($s, ($rp ? 67 : 55), 56, 57));
        }
        return implode(', ', $ret);
    }

    function word_by_no($n, $w1, $w2_4, $w_oth)
    {
        $n = strval($n);
        return ($this->lang_word_by_no_method == 0 ? ($n == '1' ? $w1 : $w_oth) : (strlen($n) > 1 && ($c = intval(substr($n, -2))) > 10 && $c < 20 ? $w_oth : (($c = intval($n{strlen($n) - 1})) == 0 || $c > 4 ? $w_oth : ($c == 1 ? $w1 : $w2_4))));
    }

    function time_term_str_short($s, $show_seconds = true, $show_minutes = true)
    {
        $s -= ($h = floor($s / 3600)) * 3600;
        $s -= ($i = floor($s / 60)) * 60;
        return $h . ($show_minutes ? ':' . ($i > 9 ? $i : '0' . $i) . ($show_seconds ? ':' . ($s > 9 ? $s : '0' . $s) : '') : '');
    }

    function user_longname(&$fname, &$sname, &$lname, $cwords = 3)
    {
        return ($cwords == 3 ? $this->implode_not_empty(array($fname, $sname, $lname)) : ($cwords == 2 ? $this->implode_not_empty(array($sname, $lname)) : $sname));
    }

    function implode_not_empty($args, $glue = ' ')
    {
        $ret = '';
        foreach ($args as $v) {
            if (!empty($v)) {
                $ret .= ($ret == '' ? '' : $glue) . $v;
            }
        }
        return $ret;
    }

    function strip_float($v)
    {
        $v = strval($v);
        $p = strpos($v, '.');
        if ($p === false) {
            return $v;
        }
        for ($i = strlen($v) - 1, --$p; $i > $p; $i--) {
            if (!($v{$i} == '0' || $v{$i} == '.')) {
                return substr($v, 0, $i + 1);
            }
        }
        return substr($v, 0, $p + 1);
    }

    function resize_image($img_orig, $img_orig_ext, $prefix, $location, $img_new, &$alerts, $rewrite_same_size = true)
    {
        if ($location === false) {
            $location = $this->all->tmp_dir;
        }
        $ret = array();
        $missing = false;
        if ($this->image_resize_method == 1) {
            if (!in_array($img_orig_ext, array('jpg', 'png', 'gif'))) {
                if (isset($alerts) && is_array($alerts)) {
                    $alerts[] = array(
                        'type' => 'err_msg_mask',
                        'params' => array('error', 22, '%ext%', $img_orig_ext),
                    );
                } else {
                    $this->all->tpl->add_sys_alert('error', 22, '%ext%', $img_orig_ext);
                }
                return false;
            }
            foreach ($img_new as $img_n) {
                $is_stamp = !empty($img_n['stamp_file']);
                if ($is_stamp) {
                    $stamp_ext = $this->get_file_ext($img_n['stamp_file']);
                    if (!in_array($stamp_ext, array('jpg', 'png', 'gif'))) {
                        $missing = true;
                        break;
                    }
                    switch ($img_n['stamp_align']) {
                        case 'lt':
                            $stamp_align = 'NorthWest';
                            break;
                        case 'rt':
                            $stamp_align = 'NorthEast';
                            break;
                        case 'lb':
                            $stamp_align = 'SouthWest';
                            break;
                        case 'rb':
                            $stamp_align = 'SouthEast';
                            break;
                        default:
                            $missing = true;
                            break 2;
                    }
                }
                if (!($s = @getimagesize($img_orig))) {
                    $missing = true;
                    break;
                }
                $tmp = tempnam($location, $prefix);
                $ret[] = $tmp;
                if (!$rewrite_same_size && !$is_stamp && $s[0] == $img_n['width'] && $s[1] == $img_n['height']) {
                    if (!copy($img_orig, $tmp)) {
                        $missing = true;
                        break;
                    }
                } else {
                    shell_exec(($is_stamp ? $this->all->img_composite_path . ' -compose src-over -gravity ' . $stamp_align . ' -geometry +' . $img_n['stamp_padding']['x'] . '+' . $img_n['stamp_padding']['y'] . ' "' . $img_n['stamp_file'] . '" "' . $img_orig . '" -thumbnail ' . $img_n['width'] . 'x' . $img_n['height'] . (empty($img_n['grayscale']) ? '' : ' -colorspace Gray') . ' -quality ' . $img_n['quality'] . ' "' . $tmp . '"' : $this->all->img_convert_path . ' "' . $img_orig . '" -thumbnail ' . $img_n['width'] . 'x' . $img_n['height'] . (empty($img_n['grayscale']) ? '' : ' -colorspace Gray') . ' -quality ' . $img_n['quality'] . ' "' . $tmp . '"'));
                    if (!file_exists($tmp)) {
                        $missing = true;
                        break;
                    }
                }
            }
        } else {
            if (!(
                ($img_orig_ext == 'jpg' && ($im = imagecreatefromjpeg($img_orig))) ||
                ($img_orig_ext == 'png' && ($im = imagecreatefrompng($img_orig))) ||
                ($img_orig_ext == 'gif' && ($im = imagecreatefromgif($img_orig)))
            )
            ) {
                if (isset($alerts) && is_array($alerts)) {
                    $alerts[] = array(
                        'type' => 'err_msg_mask',
                        'params' => array('error', 22, '%ext%', $img_orig_ext),
                    );
                } else {
                    $this->all->tpl->add_sys_alert('error', 22, '%ext%', $img_orig_ext);
                }
                return false;
            }
            foreach ($img_new as $img_n) {
                $is_stamp = !empty($img_n['stamp_file']);
                if ($is_stamp) {
                    $stamp_ext = $this->get_file_ext($img_n['stamp_file']);
                    if (!(
                        ($stamp_ext == 'jpg' && ($simg = imagecreatefromjpeg($img_n['stamp_file']))) ||
                        ($stamp_ext == 'png' && ($simg = imagecreatefrompng($img_n['stamp_file']))) ||
                        ($stamp_ext == 'gif' && ($simg = imagecreatefromgif($img_n['stamp_file'])))
                    )
                    ) {
                        $missing = true;
                        break;
                    }
                    $stamp_w = imagesx($simg);
                    $stamp_h = imagesy($simg);
                    switch ($img_n['stamp_align']) {
                        case 'lt':
                            $stamp_x = $img_n['stamp_padding']['x'];
                            $stamp_y = $img_n['stamp_padding']['y'];
                            break;
                        case 'rt':
                            $stamp_x = $img_n['width'] - $img_n['stamp_padding']['x'] - $stamp_w;
                            $stamp_y = $img_n['stamp_padding']['y'];
                            break;
                        case 'lb':
                            $stamp_x = $img_n['stamp_padding']['x'];
                            $stamp_y = $img_n['height'] - $img_n['stamp_padding']['y'] - $stamp_h;
                            break;
                        case 'rb':
                            $stamp_x = $img_n['width'] - $img_n['stamp_padding']['x'] - $stamp_w;
                            $stamp_y = $img_n['height'] - $img_n['stamp_padding']['y'] - $stamp_h;
                            break;
                        default:
                            $missing = true;
                            break 2;
                    }
                }
                $orig_w = imagesx($im);
                $orig_h = imagesy($im);
                $tmp = tempnam($location, $prefix);
                $ret[] = $tmp;
                if (!$rewrite_same_size && !$is_stamp && $orig_w == $img_n['width'] && $orig_h == $img_n['height']) {
                    if (!copy($img_orig, $tmp)) {
                        $missing = true;
                        break;
                    }
                } else {
                    $k = min($orig_w / $img_n['width'], $orig_h / $img_n['height']);
                    $x = max(round(($orig_w - $k * $img_n['width']) / 2), 0);
                    $y = max(round(($orig_h - $k * $img_n['height']) / 2), 0);
                    if (!(
                        ($n = imagecreatetruecolor($img_n['width'], $img_n['height'])) &&
                        (function_exists('imagecopyresampled') ?
                            imagecopyresampled($n, $im, 0, 0, $x, $y, $img_n['width'], $img_n['height'], $orig_w - $x * 2, $orig_h - $y * 2) :
                            imagecopyresized($n, $im, 0, 0, $x, $y, $img_n['width'], $img_n['height'], $orig_w - $x * 2, $orig_h - $y * 2)) &&
                        (empty($img_n['grayscale']) || !function_exists('imagefilter') ? true : imagefilter($n, IMG_FILTER_GRAYSCALE)) &&
                        ($is_stamp ?
                            imagecopyresized($n, $simg, $stamp_x, $stamp_y, 0, 0, $stamp_w, $stamp_h, $stamp_w, $stamp_h) &&
                            imagedestroy($simg) :
                            true
                        ) &&
                        imagejpeg($n, $tmp, $img_n['quality']) &&
                        imagedestroy($n) &&
                        file_exists($tmp)
                    )
                    ) {
                        $missing = true;
                        break;
                    }
                }
            }
            imagedestroy($im);
        }
        if ($missing) {
            foreach ($ret as $tmp) {
                if (is_file($tmp)) {
                    unlink($tmp);
                }
            }
            return false;
        }
        return $ret;
    }

    function get_file_ext($filename)
    {
        return strtolower(substr(strrchr($filename, '.'), 1, 10));
    }

    function move_tree_items($table_name, $move_id, $ord_name, $ord_old, $ord_new, $parent_name = '', $parent_old = 0, $parent_new = 0, $parent_check = false)
    {
        if ($ord_old == $ord_new && $parent_old == $parent_new) {
            return 1;
        }
        $move_id = intval($move_id);
        $ord_old = intval($ord_old);
        $ord_new = intval($ord_new);
        if ($ord_new < 1) {
            return 3;
        }
        $this->all->db->query($req,
            (empty($parent_name) ? 112 : 111),
            array(
                '%table%',
                '%ord%',
                '%id%',
                '%parent%',
                '%parentid%',
            ),
            array(
                $table_name,
                $ord_name,
                $move_id,
                $parent_name,
                $parent_new,
            )
        );
        $res = $this->all->db->fetch_row($req);
        $this->all->db->free_result($req);
        if (!(is_array($res) && $ord_new < $res[0] + 2)) {
            return 3;
        }
        if ($parent_old == $parent_new) {
            if ($ord_new > $ord_old) {
                $ord_new--;
            }
            $this->all->db->query($req,
                (empty($parent_name) ?
                    ($ord_new > $ord_old ? 102 : 103) :
                    ($ord_new > $ord_old ? 100 : 101)),
                array(
                    '%table%',
                    '%ord%',
                    '%v1%',
                    '%v2%',
                    '%parent%',
                    '%parentid%',
                ),
                array(
                    $table_name,
                    $ord_name,
                    ($ord_new > $ord_old ? $ord_old : strval($ord_new - 1)),
                    ($ord_new > $ord_old ? strval($ord_new + 1) : $ord_old),
                    $parent_name,
                    $parent_old,
                )
            );
            $this->all->db->query($req, 104,
                array(
                    '%table%',
                    '%ord%',
                    '%ord_new%',
                    '%id%',
                ),
                array(
                    $table_name,
                    $ord_name,
                    $ord_new,
                    $move_id,
                )
            );
        } else {
            if ($parent_check) {
                $parentid = $parent_new;
                while (!empty($parentid)) {
                    $this->all->db->query($req, 108,
                        array(
                            '%table%',
                            '%parent%',
                            '%parentid%',
                        ),
                        array(
                            $table_name,
                            $parent_name,
                            $parentid,
                        )
                    );
                    $res = $this->all->db->fetch_assoc($req);
                    $this->all->db->free_result($req);
                    $parentid = (is_array($res) ? $res['parentid'] : false);
                    if ($parentid == $move_id) {
                        return 2;
                    }
                }
            }
            $this->all->db->query($req, 105,
                array(
                    '%table%',
                    '%ord%',
                    '%ord_value%',
                    '%parent%',
                    '%parentid%',
                ),
                array(
                    $table_name,
                    $ord_name,
                    $ord_old,
                    $parent_name,
                    $parent_old,
                )
            );
            $this->all->db->query($req, 106,
                array(
                    '%table%',
                    '%ord%',
                    '%ord_value%',
                    '%parent%',
                    '%parentid%',
                ),
                array(
                    $table_name,
                    $ord_name,
                    strval($ord_new - 1),
                    $parent_name,
                    $parent_new,
                )
            );
            $this->all->db->query($req, 107,
                array(
                    '%table%',
                    '%ord%',
                    '%ord_new%',
                    '%id%',
                    '%parent%',
                    '%parentid%',
                ),
                array(
                    $table_name,
                    $ord_name,
                    $ord_new,
                    $move_id,
                    $parent_name,
                    $parent_new,
                )
            );
        }
        return 0;
    }

    function move_tree_items_add($table_name, $ord_name, $ord_new, $parent_name = '', $parent_new = 0)
    {
        if ($ord_new < 1) {
            return 3;
        }
        $this->all->db->query($req,
            (empty($parent_name) ? 114 : 113),
            array(
                '%table%',
                '%ord%',
                '%parent%',
                '%parentid%',
            ),
            array(
                $table_name,
                $ord_name,
                $parent_name,
                $parent_new,
            )
        );
        $res = $this->all->db->fetch_row($req);
        $this->all->db->free_result($req);
        if (!(is_array($res) && $ord_new < $res[0] + 2)) {
            return 3;
        }
        $this->all->db->query($req,
            (empty($parent_name) ? 110 : 109),
            array(
                '%table%',
                '%ord%',
                '%ord_value%',
                '%parent%',
                '%parentid%',
            ),
            array(
                $table_name,
                $ord_name,
                strval($ord_new - 1),
                $parent_name,
                $parent_new,
            )
        );
        return 0;
    }

    function move_tree_items_remove($table_name, $ord_name, $ord_old, $parent_name = '', $parent_old = 0)
    {
        $this->all->db->query($req,
            (empty($parent_name) ? 116 : 115),
            array(
                '%table%',
                '%ord%',
                '%ord_value%',
                '%parent%',
                '%parentid%',
            ),
            array(
                $table_name,
                $ord_name,
                $ord_old,
                $parent_name,
                $parent_old,
            )
        );
    }

    function before_init_object_declaration(&$obj, $params, $parent_objects, $parent_item_names)
    {
        $obj->all =& $this->all;
        $obj->parent_objects = $parent_objects;
        $obj->parent_item_names = $parent_item_names;
        if (count($obj->parent_objects) > 0 && $obj->parent_objects[0]->readonly) {
            $obj->readonly = true;
        }
        if (!isset($obj->counts)) {
            $obj->counts = array();
        }
        if (!isset($obj->counts['default'])) {
            $obj->counts['default'] = 1;
        }
        if (!isset($obj->counts['min'])) {
            $obj->counts['min'] = $obj->counts['default'];
        }
        if (!isset($obj->counts['max'])) {
            $obj->counts['max'] = $obj->counts['default'];
        }
        if (!isset($obj->counts['required'])) {
            $obj->counts['required'] = $obj->counts['min'];
        }
        $obj->counts['current'] = $obj->counts['default'];
        if (isset($params['counts'])) {
            $obj->counts = $params['counts'] + $obj->counts;
        }
    }

    function after_init_object_declaration(&$obj, $params, $parent_objects, $parent_item_names)
    {
        if (!empty($obj->db_ident)) {
            $this->all->db->squery($req,
                'SELECT COUNT(*)' .
                ' FROM ' . $this->all->db->prefix . $obj->table_name .
                ' WHERE ' . $obj->db_ident
            );
            if (($res = $this->all->db->fetch_row($req)) && $res[0] > 0) {
                $obj->counts['current'] = max($obj->counts['min'], $res[0]);
            }
            $this->all->db->free_result($req);
        }
        foreach ($obj->items as $k => $v) {
            if ($v['type'] == 'object') {
                $obj->items[$k]['object'] = new $v['object']($this->all, (isset($v['params']) ? $v['params'] : array()), array_merge($obj->parent_objects, array(&$obj)), array_merge($obj->parent_item_names, array($k)));
            }
        }
    }

    function save_object_values(&$obj)
    {
        $sent = isset($_POST['_obj_count']);
        $display_errors = true;
        if (!$this->init_object_values($obj, $display_errors, $sent, $sent)) {
            return false;
        }
        if ($sent) {
            $this->all->db->transaction_begin();
            $remove_on_fail = array();
            $remove_on_success = array();
            if ($this->_save_object_values($obj, $remove_on_fail, $remove_on_success)) {
                $this->_save_object_values_remove_files($remove_on_success);
                $this->all->db->transaction_commit();
                return true;
            } else {
                $this->_save_object_values_remove_files($remove_on_fail);
                $this->all->db->transaction_rollback();
            }
        }
        return false;
    }

    function init_object_values(&$obj, $display_errors = true, $sent = false, $reset = true)
    {
        $critical_error = false;
        $alerts = array();
        list ($obj_group_sent, $obj_group_required_error) = $this->_init_object_values($obj, $critical_error, $alerts, $display_errors, $sent, $reset);
        if ($obj_group_required_error || $critical_error) {
            if ($display_errors) {
                foreach ($alerts as $alert) {
                    call_user_func_array(array(&$this->all->tpl, 'add_sys_alert'), $alert['params']);
                }
            }
            return false;
        }
        return true;
    }

    function _init_object_values(&$obj, &$critical_error, &$alerts, $display_errors = true, $sent = false, $reset = true, $prefix = '', $parent_values = array())
    {
        if (method_exists($obj, 'action_init')) {
            return $obj->action_init($critical_error, $alerts, $display_errors, $sent, $reset, $parent_values);
        }
        if (method_exists($obj, 'before_init') && !$obj->before_init($critical_error, $alerts, $display_errors, $sent, $reset, $parent_values)) {
            return array(false, true);
        }
        $all_lang = array();
        foreach ($this->lang_available_frontend as $k => $v) {
            $all_lang[] = array('_' . $k, ($this->lang_show_fields ? ' (' . $k . ')' : ''));
        }
        $one_lang = array(array('', ''));
        $old_items_req = false;
        if (!empty($obj->db_ident)) {
            $query_items = array();
            foreach ($obj->items as $k => $v) {
                if ($v['type'] == 'object' || $v['type'] == 'own' || ($reset && $v['type'] != 'file') || (isset($v['in_db']) && !$v['in_db']) || ($v['type'] == 'string' && isset($v['input_type']) && $v['input_type'] == 5)) {
                    continue;
                }
                $switch_lang = (empty($v['multilingual']) ? $one_lang : $all_lang);
                foreach ($switch_lang as $lang) {
                    $query_items[] = $k . $lang[0];
                }
            }
            $this->all->db->squery($old_items_req,
                'SELECT `id`' . (count($query_items) > 0 ? ', `' . implode('`, `', $query_items) . '`' : '') .
                ' FROM ' . $this->all->db->prefix . $obj->table_name .
                ' WHERE ' . $obj->db_ident .
                ' LIMIT ' . $obj->counts['max']
            );
        }
        if (count($parent_values) > 0) {
            if (!isset($parent_values[count($parent_values) - 1]['items'][$obj->parent_item_names[count($obj->parent_item_names) - 1]])) {
                $parent_values[count($parent_values) - 1]['items'][$obj->parent_item_names[count($obj->parent_item_names) - 1]] = array();
            }
            $values =& $parent_values[count($parent_values) - 1]['items'][$obj->parent_item_names[count($obj->parent_item_names) - 1]];
        } else {
            if (!isset($obj->values)) {
                $obj->values = array();
            }
            $values =& $obj->values;
        }
        if ($sent && isset($_POST[$prefix . '_obj_count'])) {
            $obj->counts['current'] = max($obj->counts['min'], min($obj->counts['max'], intval($_POST[$prefix . '_obj_count'])));
        }
        $obj_cp = array();
        $obj_cp_group_count = 0;
        $obj_cp_group_sent = false;
        $obj_cp_group_has_req_errors = false;
        for ($i = 0; $i < $obj->counts['current']; $i++) {
            if (!isset($values[$i])) {
                $values[$i] = array(
                    'items' => array(),
                );
            }
            if (count($parent_values) > 0) {
                $values[$i]['parent_values'] = $parent_values;
            }
            $obj_cp[$i] = array(
                'sent' => false,
                'required_error' => false,
                'alerts' => array(),
            );
            $values[$i]['exists'] = ($old_items_req && ($old_items = $this->all->db->fetch_assoc($old_items_req)) ? true : false);
            $values[$i]['action'] = ($sent && isset($_POST[$prefix . '_' . $i . '_remove']) && trim($_POST[$prefix . '_' . $i . '_remove']) == 'on' ? 'remove' : ($obj->action == 'edit' && !$values[$i]['exists'] ? 'add' : $obj->action));
            $obj_files = array();
            foreach ($obj->items as $k => $v) {
                if (!((empty($v['add_only']) || $values[$i]['action'] == 'add') && (empty($v['edit_only']) || $values[$i]['action'] == 'edit'))) {
                    continue;
                }
                $switch_lang = (empty($v['multilingual']) ? $one_lang : $all_lang);
                foreach ($switch_lang as $lang) {
                    $kl = $k . $lang[0];
                    switch ($v['type']) {
                        case 'object':
                            $v['object']->action = $values[$i]['action'];
                            list ($obj_group_sent, $obj_group_required_error) = $this->_init_object_values($v['object'], $critical_error, $obj_cp[$i]['alerts'], $display_errors, $sent, $reset, $prefix . $kl . '_' . $i . '_', array_merge($parent_values, array(&$values[$i])));
                            if ($obj_group_sent) {
                                $obj_cp[$i]['sent'] = true;
                            }
                            if ($obj_group_required_error) {
                                $obj_cp[$i]['required_error'] = true;
                            }
                            break;
                        case 'integer':
                        case 'datetime':
                            $values[$i]['items'][$kl] = (isset($obj->items[$k]['default' . $lang[0]]) ? $obj->items[$k]['default' . $lang[0]] : (isset($obj->items[$k]['default']) ? $obj->items[$k]['default'] : 0));
                            break;
                        case 'string':
                            $values[$i]['items'][$kl] = (isset($obj->items[$k]['default' . $lang[0]]) ? $obj->items[$k]['default' . $lang[0]] : (isset($obj->items[$k]['default']) ? $obj->items[$k]['default'] : ''));
                            break;
                        case 'file':
                            $obj_files[] = $kl;
                            $values[$i]['items'][$kl] = '';
                            break;
                    }
                }
            }
            if ($values[$i]['exists']) {
                $values[$i]['id'] = $old_items['id'];
                foreach ($old_items as $k => $v) {
                    if ((empty($obj->items[$k]['add_only']) || $values[$i]['action'] == 'add') && (empty($obj->items[$k]['edit_only']) || $values[$i]['action'] == 'edit')) {
                        $values[$i]['items'][$k] = $v;
                    }
                }
            }
            $values[$i]['is_ready4db'] = false;
            foreach ($obj_files as $k) {
                if (!empty($values[$i]['items'][$k])) {
                    $values[$i]['items'][$k] = array('existing_filename' => $values[$i]['items'][$k]);
                }
            }
            if ($sent && !($obj->readonly || $values[$i]['action'] == 'remove')) {
                foreach ($obj->items as $k => $v) {
                    if ($v['type'] == 'object' || !(empty($v['readonly']) && (empty($v['add_only']) || $values[$i]['action'] == 'add') && (empty($v['edit_only']) || $values[$i]['action'] == 'edit'))) {
                        continue;
                    }
                    $switch_lang = (empty($v['multilingual']) ? $one_lang : $all_lang);
                    foreach ($switch_lang as $lang) {
                        $kl = $k . $lang[0];
                        $kp = $prefix . $kl . '_' . $i;
                        $required = !empty($v['required']);
                        switch ($v['type']) {
                            case 'integer':
                                $d = $values[$i]['items'][$kl];
                                if (isset($v['input_format']) && $v['input_format'] == 3) {
                                    $values[$i]['items'][$kl] = $m = (isset($_POST[$kp]) && trim($_POST[$kp]) == 'on' ? 1 : 0);
                                    if ($required && ((isset($v['min']) && $m < $v['min']) || (isset($v['max']) && $m > $v['max']) || (isset($v['default_ignore' . $lang[0]]) && $m == $this->output($v['default_ignore' . $lang[0]])) || (isset($v['default_ignore']) && $m == $this->output($v['default_ignore'])))) {
                                        $obj_cp[$i]['required_error'] = true;
                                        if ($display_errors) {
                                            $obj_cp[$i]['alerts'][] = array(
                                                'type' => 'err_msg_req',
                                                'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                            );
                                        }
                                    }
                                } else if (isset($_POST[$kp]) && $_POST[$kp] != '' && (isset($v['default_ignore' . $lang[0]]) ? $_POST[$kp] != $this->output($v['default_ignore' . $lang[0]]) : (isset($v['default_ignore']) ? $_POST[$kp] != $this->output($v['default_ignore']) : true))) {
                                    $m = intval($_POST[$kp]);
                                    if (preg_match('/\D/', $_POST[$kp]) || (isset($v['min']) && $m < $v['min']) || (isset($v['max']) && $m > $v['max'])) {
                                        if (isset($v['options']) && $required) {
                                            $obj_cp[$i]['required_error'] = true;
                                            if ($display_errors) {
                                                $obj_cp[$i]['alerts'][] = array(
                                                    'type' => 'err_msg_req',
                                                    'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                                );
                                            }
                                        } else {
                                            $critical_error = true;
                                            if ($display_errors) {
                                                $obj_cp[$i]['alerts'][] = array(
                                                    'type' => 'err_msg_mask',
                                                    'params' => array('warning', $v['err_msg_mask'], '%lang%', $lang[1]),
                                                );
                                            }
                                        }
                                        $values[$i]['items'][$kl] = $_POST[$kp];
                                    } else {
                                        $values[$i]['items'][$kl] = $m;
                                        if (isset($v['options']) && (!isset($v['match_options']) || $v['match_options']) && !$this->in_options($v['options'], $m)) {
                                            $critical_error = true;
                                            if ($display_errors) {
                                                $obj_cp[$i]['alerts'][] =
                                                    ($required ?
                                                        array(
                                                            'type' => 'err_msg_req',
                                                            'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                                        )
                                                        :
                                                        array(
                                                            'type' => 'err_msg_mask',
                                                            'params' => array('warning', $v['err_msg_mask'], '%lang%', $lang[1]),
                                                        )
                                                    );
                                            }
                                        } else if (!empty($v['unique'])) {
                                            $this->all->db->squery($req,
                                                'SELECT `id`' .
                                                ' FROM ' . $this->all->db->prefix . $obj->table_name .
                                                " WHERE `" . $kl . "` = '" . intval($m) . "'" .
                                                ($values[$i]['exists'] ? " AND `id` != '" . $values[$i]['id'] . "'" : '') .
                                                ' LIMIT 1'
                                            );
                                            $res = $this->all->db->fetch_row($req);
                                            $this->all->db->free_result($req);
                                            if ($res) {
                                                $critical_error = true;
                                                if ($display_errors) {
                                                    $obj_cp[$i]['alerts'][] = array(
                                                        'type' => 'err_msg_unique',
                                                        'params' => array('warning', $v['err_msg_unique'], array('%value%', '%lang%'), array($m, $lang[1])),
                                                    );
                                                }
                                            }
                                        }
                                    }
                                } else if ($required) {
                                    $obj_cp[$i]['required_error'] = true;
                                    if ($display_errors) {
                                        $obj_cp[$i]['alerts'][] = array(
                                            'type' => 'err_msg_req',
                                            'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                        );
                                    }
                                }
                                if ($values[$i]['items'][$kl] != $d) {
                                    $obj_cp[$i]['sent'] = true;
                                }
                                break;
                            case 'string':
                                $d = $values[$i]['items'][$kl];
                                if (isset($_POST[$kp]) && $_POST[$kp] != '' && (isset($v['default_ignore' . $lang[0]]) ? $_POST[$kp] != $this->output($v['default_ignore' . $lang[0]]) : (isset($v['default_ignore']) ? $_POST[$kp] != $this->output($v['default_ignore']) : true))) {
                                    $values[$i]['items'][$kl] = $this->input($_POST[$kp]);
                                    $err_length = (empty($v['maxlength']) || strlen($values[$i]['items'][$kl]) <= $v['maxlength'] ? false : true);
                                    $str_succeed = true;
                                    if ($err_length || (isset($v['imask']) && preg_match($v['imask'], $values[$i]['items'][$kl])) || (isset($v['mask']) && !preg_match($v['mask'], $values[$i]['items'][$kl]))) {
                                        $str_succeed = false;
                                        if (isset($v['options']) && $required) {
                                            $obj_cp[$i]['required_error'] = true;
                                            if ($display_errors) {
                                                $obj_cp[$i]['alerts'][] = array(
                                                    'type' => 'err_msg_req',
                                                    'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                                );
                                            }
                                        } else {
                                            $critical_error = true;
                                            if ($display_errors) {
                                                $obj_cp[$i]['alerts'][] = array(
                                                    'type' => 'err_msg_mask',
                                                    'params' => array('warning', ($err_length ? (isset($v['err_msg_length']) ? $v['err_msg_length'] : (isset($v['err_msg_mask']) ? $v['err_msg_mask'] : $v['err_msg_req'])) : $v['err_msg_mask']), '%lang%', $lang[1]),
                                                );
                                            }
                                        }
                                    } else if (isset($v['options']) && (!isset($v['match_options']) || $v['match_options']) && !$this->in_options($v['options'], $values[$i]['items'][$kl])) {
                                        $str_succeed = false;
                                        $critical_error = true;
                                        if ($display_errors) {
                                            $obj_cp[$i]['alerts'][] =
                                                ($required ?
                                                    array(
                                                        'type' => 'err_msg_req',
                                                        'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                                    )
                                                    :
                                                    array(
                                                        'type' => 'err_msg_mask',
                                                        'params' => array('warning', $v['err_msg_mask'], '%lang%', $lang[1]),
                                                    )
                                                );
                                        }
                                    } else if (isset($v['input_format']) && $v['input_format'] == 5) {
                                        if ((isset($v['confirm']) && !$v['confirm']) || (isset($_POST[$kp . '_conf']) && $_POST[$kp . '_conf'] == $values[$i]['items'][$kl])) {
                                            $values[$i]['items'][$kl] = sha1($values[$i]['items'][$kl]);
                                        } else {
                                            $str_succeed = false;
                                            $critical_error = true;
                                            $obj_cp[$i]['alerts'][] = array(
                                                'type' => 'err_msg_conf',
                                                'params' => array('warning', $v['err_msg_conf'], '%lang%', $lang[1]),
                                            );
                                        }
                                    }
                                    if ($str_succeed) {
                                        if (isset($v['proc_strip_tags']) && $v['proc_strip_tags'] !== false) {
                                            $values[$i]['items'][$kl] = strip_tags($values[$i]['items'][$kl], $v['proc_strip_tags']);
                                        }
                                        if (!empty($v['proc_preg_replace'])) {
                                            $values[$i]['items'][$kl] = preg_replace($v['proc_preg_replace']['pattern'], $v['proc_preg_replace']['replacement'], $values[$i]['items'][$kl]);
                                        }
                                        if (isset($v['proc_str_cut']) && $v['proc_str_cut'] !== false) {
                                            $values[$i]['items'][$kl] = call_user_func_array(array(&$this, 'str_cut'), array_merge(array($values[$i]['items'][$kl]), $v['proc_str_cut']));
                                        }
                                        if ($required && ($values[$i]['items'][$kl] == '' || (isset($v['default_ignore' . $lang[0]]) ? $values[$i]['items'][$kl] == $this->output($v['default_ignore' . $lang[0]]) : (isset($v['default_ignore']) ? $values[$i]['items'][$kl] == $this->output($v['default_ignore']) : false)))) {
                                            $obj_cp[$i]['required_error'] = true;
                                            if ($display_errors) {
                                                $obj_cp[$i]['alerts'][] = array(
                                                    'type' => 'err_msg_req',
                                                    'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                                );
                                            }
                                        } else if (!empty($v['unique'])) {
                                            $this->all->db->squery($req,
                                                'SELECT `id`' .
                                                ' FROM ' . $this->all->db->prefix . $obj->table_name .
                                                " WHERE `" . $kl . "` = '" . $this->all->db->escape($values[$i]['items'][$kl]) . "'" .
                                                ($values[$i]['exists'] ? " AND `id` != '" . $values[$i]['id'] . "'" : '') .
                                                ' LIMIT 1'
                                            );
                                            $res = $this->all->db->fetch_row($req);
                                            $this->all->db->free_result($req);
                                            if ($res) {
                                                $critical_error = true;
                                                if ($display_errors) {
                                                    $obj_cp[$i]['alerts'][] = array(
                                                        'type' => 'err_msg_unique',
                                                        'params' => array('warning', $v['err_msg_unique'], array('%value%', '%lang%'), array($values[$i]['items'][$kl], $lang[1])),
                                                    );
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $values[$i]['items'][$kl] = '';
                                    if ($required) {
                                        $obj_cp[$i]['required_error'] = true;
                                        if ($display_errors) {
                                            $obj_cp[$i]['alerts'][] = array(
                                                'type' => 'err_msg_req',
                                                'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                            );
                                        }
                                    }
                                }
                                if ($values[$i]['items'][$kl] != $d) {
                                    $obj_cp[$i]['sent'] = true;
                                }
                                break;
                            case 'datetime':
                                if (isset($v['multifield']) && $v['multifield'] === false) {
                                    if (isset($_POST[$kp]) && $_POST[$kp] != '' && (isset($v['default_ignore' . $lang[0]]) ? $_POST[$kp] != $this->output($v['default_ignore' . $lang[0]]) : (isset($v['default_ignore']) ? $_POST[$kp] != $this->output($v['default_ignore']) : true))) {
                                        $obj_cp[$i]['sent'] = true;
                                        $m = $this->date_str2ts($this->input($_POST[$kp], 50), !empty($v['has_time']), !empty($v['has_seconds']));
                                        if ($m === false) {
                                            $critical_error = true;
                                            if ($display_errors) {
                                                $obj_cp[$i]['alerts'][] = array(
                                                    'type' => 'err_msg_mask',
                                                    'params' => array('warning', $v['err_msg_mask'], '%lang%', $lang[1]),
                                                );
                                            }
                                        } else {
                                            $values[$i]['items'][$kl] = $m;
                                        }
                                    } else if ($required) {
                                        $obj_cp[$i]['required_error'] = true;
                                        if ($display_errors) {
                                            $obj_cp[$i]['alerts'][] = array(
                                                'type' => 'err_msg_req',
                                                'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                            );
                                        }
                                    }
                                } else {
                                    $fld_values = array('h' => 0, 'i' => 0, 's' => 0);
                                    $fld = array(
                                        'y' => array('min' => 1000, 'max' => 9999),
                                        'm' => array('min' => 1, 'max' => 12),
                                        'd' => array('min' => 1, 'max' => 31),
                                    );
                                    if (!empty($v['has_time'])) {
                                        $fld['h'] = array('min' => 0, 'max' => 23);
                                        $fld['i'] = array('min' => 0, 'max' => 59);
                                        if (!empty($v['has_seconds'])) {
                                            $fld['s'] = array('min' => 0, 'max' => 59);
                                        }
                                    }
                                    $und_errors = 0;
                                    $mis_errors = 0;
                                    $und_alert = false;
                                    $mis_alert = false;
                                    foreach ($fld as $fk => $fv) {
                                        $af = '_' . $fk;
                                        $kf = $kp . $af;
                                        if (isset($_POST[$kf]) && $_POST[$kf] != '' && (isset($v['default_ignore' . $lang[0] . $af]) ? $_POST[$kf] != $this->output($v['default_ignore' . $lang[0] . $af]) : (isset($v['default_ignore' . $af]) ? $_POST[$kf] != $this->output($v['default_ignore' . $af]) : (isset($v['default_ignore' . $lang[0]]) ? $_POST[$kf] != $this->output($v['default_ignore' . $lang[0]]) : (isset($v['default_ignore']) ? $_POST[$kf] != $this->output($v['default_ignore']) : true))))) {
                                            $obj_cp[$i]['sent'] = true;
                                            $m = intval($_POST[$kf]);
                                            if (preg_match('/\D/', $_POST[$kf]) || $m < $fv['min'] || $m > $fv['max']) {
                                                ++$mis_errors;
                                                if (isset($v['err_msg_mask' . $af])) {
                                                    $mis_alert = array(
                                                        'type' => 'err_msg_mask',
                                                        'params' => array('warning', $v['err_msg_mask' . $af], '%lang%', $lang[1]),
                                                    );
                                                }
                                            } else {
                                                $fld_values[$fk] = $m;
                                            }
                                        } else {
                                            ++$und_errors;
                                            if (isset($v['err_msg_req' . $af])) {
                                                $und_alert = array(
                                                    'type' => 'err_msg_req',
                                                    'params' => array('warning', $v['err_msg_req' . $af], '%lang%', $lang[1]),
                                                );
                                            }
                                        }
                                    }
                                    if ($und_errors == 0 && $mis_errors == 0) {
                                        if (checkdate($fld_values['m'], $fld_values['d'], $fld_values['y'])) {
                                            $values[$i]['items'][$kl] = mktime($fld_values['h'], $fld_values['i'], $fld_values['s'], $fld_values['m'], $fld_values['d'], $fld_values['y']);
                                        } else {
                                            $critical_error = true;
                                            if ($display_errors) {
                                                $obj_cp[$i]['alerts'][] = array(
                                                    'type' => 'err_msg_mask',
                                                    'params' => array('warning', $v['err_msg_mask'], '%lang%', $lang[1]),
                                                );
                                            }
                                        }
                                    } else if ($und_errors == count($fld)) {
                                        if ($required) {
                                            $obj_cp[$i]['required_error'] = true;
                                            if ($display_errors) {
                                                $obj_cp[$i]['alerts'][] = array(
                                                    'type' => 'err_msg_req',
                                                    'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                                );
                                            }
                                        }
                                    } else if ($und_errors > 0) {
                                        $critical_error = true;
                                        if ($display_errors) {
                                            $obj_cp[$i]['alerts'][] =
                                                ($und_errors > 1 || $und_alert === false ?
                                                    array(
                                                        'type' => 'err_msg_req',
                                                        'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                                    )
                                                    :
                                                    $und_alert
                                                );
                                        }
                                    } else if ($mis_errors > 0) {
                                        $critical_error = true;
                                        if ($display_errors) {
                                            $obj_cp[$i]['alerts'][] =
                                                ($mis_errors > 1 || $mis_alert === false ?
                                                    array(
                                                        'type' => 'err_msg_mask',
                                                        'params' => array('warning', $v['err_msg_mask'], '%lang%', $lang[1]),
                                                    )
                                                    :
                                                    $mis_alert
                                                );
                                        }
                                    }
                                }
                                break;
                            case 'file':
                                $temp_files = $this->all->session_get_var('temp_files', false);
                                $temp_file_id = 0;
                                if ($temp_files !== false) {
                                    $temp_files = unserialize($temp_files);
                                    if (isset($temp_files[$kp]) && $this->temp_file_get($temp_files[$kp], $res)) {
                                        if (isset($_POST[$kp . '_remove_temp']) && trim($_POST[$kp . '_remove_temp']) == 'on' && !$required) {
                                            $this->temp_file_remove($temp_files[$kp]);
                                            unset($temp_files[$kp]);
                                            $this->all->session_set_var('temp_files', serialize($temp_files));
                                            if (is_file($res['temp_filename'])) {
                                                unlink($res['temp_filename']);
                                            }
                                        } else {
                                            $temp_file_id = intval($temp_files[$kp]);
                                            $values[$i]['items'][$kl]['temp_file_id'] = $temp_file_id;
                                            $values[$i]['items'][$kl]['temp_orig_filename'] = $res['temp_orig_filename'];
                                            $values[$i]['items'][$kl]['temp_filename'] = $res['temp_filename'];
                                            $values[$i]['items'][$kl]['temp_extension'] = $res['temp_extension'];
                                            $values[$i]['items'][$kl]['future_filename'] = $res['future_filename'];
                                        }
                                    }
                                }
                                if (isset($_POST[$kp . '_remove']) && trim($_POST[$kp . '_remove']) == 'on' && !$required && isset($values[$i]['items'][$kl]['existing_filename'])) {
                                    $values[$i]['items'][$kl]['existing_remove'] = true;
                                    $obj_cp[$i]['sent'] = true;
                                }
                                $m = $this->try_upload_file($kp, $obj_cp[$i]['alerts'], (isset($v['max_file_size']) ? $v['max_file_size'] : 0), (isset($v['filename_prefix']) ? $v['filename_prefix'] : ''), false, (isset($v['allowed_filetypes']) ? $v['allowed_filetypes'] : false));
                                if ($m === false) {
                                    $critical_error = true;
                                } else if ($m === true) {
                                    if ($required && $temp_file_id == 0 && (empty($values[$i]['items'][$kl]['existing_filename']) || !empty($values[$i]['items'][$kl]['existing_remove']))) {
                                        $obj_cp[$i]['required_error'] = true;
                                        if ($display_errors) {
                                            $obj_cp[$i]['alerts'][] = array(
                                                'type' => 'err_msg_req',
                                                'params' => array('warning', $v['err_msg_req'], '%lang%', $lang[1]),
                                            );
                                        }
                                    }
                                } else {
                                    $obj_cp[$i]['sent'] = true;
                                    $values[$i]['items'][$kl]['temp_orig_filename'] = $m['orig'];
                                    $values[$i]['items'][$kl]['temp_filename'] = $m['new'];
                                    $values[$i]['items'][$kl]['temp_extension'] = $m['ext'];
                                    $values[$i]['items'][$kl]['future_filename'] = $this->temp_file_add_update((isset($v['filename_prefix']) ? $v['filename_prefix'] : ''), $m['orig'], $m['new'], $m['ext'], $this->all->frontend_rw_dir . '/', $temp_file_id);
                                    $values[$i]['items'][$kl]['temp_file_id'] = $temp_file_id;
                                    if (!is_array($temp_files)) {
                                        $temp_files = array();
                                    }
                                    $temp_files[$kp] = $temp_file_id;
                                    $this->all->session_set_var('temp_files', serialize($temp_files));
                                }
                                break;
                        }
                    }
                }
            } else {
                $temp_files = $this->all->session_get_var('temp_files', false);
                if ($temp_files !== false) {
                    $temp_files = unserialize($temp_files);
                    $rm_ids = array();
                    foreach ($obj_files as $fk) {
                        $kp = $prefix . $fk . '_' . $i;
                        if (isset($temp_files[$kp]) && $this->temp_file_get($temp_files[$kp], $res)) {
                            $rm_ids[] = intval($temp_files[$kp]);
                            unset($temp_files[$kp]);
                            if (is_file($res['temp_filename'])) {
                                unlink($res['temp_filename']);
                            }
                        }
                    }
                    $this->temp_file_multiremove($rm_ids);
                    $this->all->session_set_var('temp_files', serialize($temp_files));
                }
            }
            if ($values[$i]['action'] != 'remove') {
                if ($obj_cp[$i]['sent']) {
                    $obj_cp_group_sent = true;
                    if ($obj_cp[$i]['required_error']) {
                        $critical_error = true;
                    }
                }
                if ($obj_cp[$i]['required_error']) {
                    $obj_cp_group_has_req_errors = true;
                } else {
                    $values[$i]['is_ready4db'] = ($sent && !$critical_error);
                    ++$obj_cp_group_count;
                }
            }
        }
        if ($old_items_req) {
            $this->all->db->free_result($old_items_req);
        }
        $obj_cp_group_required_error = ($obj_cp_group_count < $obj->counts['required'] ? true : false);
        for ($i = 0; $i < $obj->counts['current']; $i++) {
            if ($obj_cp[$i]['required_error'] && ++$obj_cp_group_count > $obj->counts['required']) {
                for ($j = 0; $j < count($obj_cp[$i]['alerts']); $j++) {
                    if ($obj_cp[$i]['alerts'][$j]['type'] = 'err_msg_req') {
                        unset($obj_cp[$i]['alerts'][$j]);
                    }
                }
            }
            array_splice($alerts, count($alerts), 0, $obj_cp[$i]['alerts']);
        }
        if ($sent && $obj_cp_group_required_error && !$critical_error && !$obj_cp_group_has_req_errors && $display_errors && isset($obj->counts['err_msg_req'])) {
            $alerts = array(
                'type' => 'err_msg_req',
                'params' => array('warning', $obj->counts['err_msg_req']),
            );
        }
        if (method_exists($obj, 'after_init') && !$obj->after_init($critical_error, $alerts, $display_errors, $sent, $reset, $parent_values)) {
            return array(false, true);
        }
        return array($obj_cp_group_sent, $obj_cp_group_required_error);
    }

    function in_options(&$optgroup, $v)
    {
        foreach ($optgroup as $item) {
            if ((isset($item['value']) && $item['value'] == $v) || (isset($item['items']) && $this->in_options($item['items'], $v))) {
                return true;
            }
        }
        return false;
    }

    function input($s, $maxlength = 0, $striptags = false)
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

    function date_str2ts($s, $has_time = false, $has_seconds = false)
    {
        $dt =& $this->datetime[($has_time ? ($has_seconds ? 'ymdhis' : 'ymdhi') : 'ymd')];
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

    function temp_file_get($id, &$res)
    {
        $this->all->db->query($req, 7, '%id%', intval($id));
        $res = $this->all->db->fetch_assoc($req);
        $this->all->db->free_result($req);
        return is_array($res);
    }

    function temp_file_remove($id)
    {
        $this->all->db->query($req, 5, '%id%', intval($id));
        return ($this->all->db->affected_rows() == 1 ? true : false);
    }

    function try_upload_file($varname, &$alerts, $max_file_size = 0, $prefix = '', $location = false, $allowed_filetypes = false)
    {
        if ($location === false) {
            $location = $this->all->tmp_dir;
        }
        if (!isset($_FILES[$varname]) || $_FILES[$varname]['name'] == '') {
            return true;
        }
        if ($_FILES[$varname]['size'] > 0 && !$_FILES[$varname]['error']) {
            $e = $this->get_file_ext($_FILES[$varname]['name']);
            if (!is_array($allowed_filetypes)) {
                $allowed_filetypes =& $this->all->auth->allowed_filetypes;
            }
            if (!in_array($e, $allowed_filetypes)) {
                $alert = array('error', 17, '%types%', (count($allowed_filetypes) == 0 ? '' : $this->output(18, '%types%', implode(', ', $allowed_filetypes))));
            } else if ($max_file_size > 0 && $_FILES[$varname]['size'] > $max_file_size) {
                $alert = array('error', 21, '%size%', filesize_str($max_file_size));
            } else {
                $tmp = tempnam($location, $prefix);
                if (move_uploaded_file($_FILES[$varname]['tmp_name'], $tmp)) {
                    chmod($tmp, 0666);
                    return array(
                        'orig' => $_FILES[$varname]['name'],
                        'new' => $tmp,
                        'ext' => $e,
                    );
                } else {
                    if (is_file($tmp)) {
                        unlink($tmp);
                    }
                    $alert = array('error', 19);
                }
            }
        } else {
            switch ($_FILES[$varname]['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $alert = array('error', 199);
                    break;
                default:
                    $alert = array('error', 20);
            }
        }
        if (is_file($_FILES[$varname]['tmp_name'])) {
            unlink($_FILES[$varname]['tmp_name']);
        }
        if (isset($alerts) && is_array($alerts)) {
            $alerts[] = array(
                'type' => 'err_msg_file',
                'params' => $alert,
            );
        } else {
            call_user_func_array(array(&$this->all->tpl, 'add_sys_alert'), $alert);
        }
        return false;
    }

    function temp_file_add_update($prefix, $temp_orig_filename, $temp_filename, $temp_extension, $future_location, &$id)
    {
        $fut_prefix = $prefix . substr(preg_replace('/[^0-9a-z\-\.]+/', '_', strtolower($this->input(substr($temp_orig_filename, 0, min(strlen($temp_orig_filename) - strlen($temp_extension) - 1, 500))))), 0, 40);
        $fut_ext = '.' . $temp_extension;
        $fut_ind = 1;
        $fut_ad = '';
        do {
            while (file_exists($future_location . $fut_prefix . $fut_ad . $fut_ext) || $this->temp_file_exists($future_location, $fut_prefix . $fut_ad . $fut_ext, $id)) {
                ++$fut_ind;
                $fut_ad = '_' . $fut_ind;
            }
            $future_filename = $fut_prefix . $fut_ad . $fut_ext;
            if ($id == 0) {
                $this->all->db->query($req, 9, array('%datead%', '%temp_orig_filename%', '%temp_filename%', '%temp_extension%', '%future_location%', '%future_filename%'), array(time(), $temp_orig_filename, $temp_filename, $temp_extension, $future_location, $future_filename));
                $id = $this->all->db->insert_id();
            } else {
                $this->all->db->query($req, 10, array('%datead%', '%temp_orig_filename%', '%temp_filename%', '%temp_extension%', '%future_location%', '%future_filename%', '%id%'), array(time(), $temp_orig_filename, $temp_filename, $temp_extension, $future_location, $future_filename, $id));
            }
        } while ($this->temp_file_exists($future_location, $future_filename, $id));
        return $future_filename;
    }

    function temp_file_exists($location, $filename, $ignore_id = 0)
    {
        $this->all->db->query($req, 8, array('%filename%', '%location%', '%id%'), array($filename, $location, $ignore_id));
        $res = $this->all->db->fetch_row($req);
        $this->all->db->free_result($req);
        return is_array($res);
    }

    function temp_file_multiremove(&$ids)
    {
        if (count($ids) > 0) {
            $this->all->db->query($req, 6, '%ids%', implode(', ', $ids));
        }
    }

    function _save_object_values(&$obj, &$remove_on_fail, &$remove_on_success, $parent_values = array(), $strict_remove = false)
    {
        $all_lang = array();
        foreach ($this->lang_available_frontend as $k => $v) {
            $all_lang[] = array('_' . $k, ($this->lang_show_fields ? ' (' . $k . ')' : ''));
        }
        $one_lang = array(array('', ''));
        $succeed = true;
        if (count($parent_values) > 0) {
            $values =& $parent_values[count($parent_values) - 1]['items'][$obj->parent_item_names[count($obj->parent_item_names) - 1]];
        } else {
            $values =& $obj->values;
        }
        for ($i = 0; $i < count($values); $i++) {
            if ($strict_remove || $values[$i]['action'] == 'remove') {
                if ($values[$i]['exists']) {
                    if (method_exists($obj, 'action_remove')) {
                        if (!$obj->action_remove("`id` = " . intval($values[$i]['id']), $remove_on_fail, $remove_on_success)) {
                            return false;
                        }
                        continue;
                    }
                    if (method_exists($obj, 'before_remove') && !$obj->before_remove("`id` = " . intval($values[$i]['id']), $remove_on_fail, $remove_on_success)) {
                        return false;
                    }
                }
                foreach ($obj->items as $k => $v) {
                    $switch_lang = (empty($v['multilingual']) ? $one_lang : $all_lang);
                    foreach ($switch_lang as $lang) {
                        $kl = $k . $lang[0];
                        switch ($v['type']) {
                            case 'object':
                                if (!$this->_save_object_values($v['object'], $remove_on_fail, $remove_on_success, array_merge($parent_values, array(&$values[$i])), true)) {
                                    return false;
                                }
                                break;
                            case 'file':
                                $m =& $values[$i]['items'][$kl];
                                if (!empty($m['existing_filename'])) {
                                    $remove_on_success[] = array('filename' => $this->all->frontend_rw_dir . '/' . $m['existing_filename']);
                                }
                                if (!empty($m['future_filename'])) {
                                    $remove_on_success[] = array(
                                        'filename' => $m['temp_filename'],
                                        'temp_file_id' => $m['temp_file_id'],
                                    );
                                }
                                break;
                        }
                    }
                }
                if ($values[$i]['exists']) {
                    $this->all->db->squery($req,
                        'DELETE FROM ' . $this->all->db->prefix . $obj->table_name .
                        " WHERE id = '" . intval($values[$i]['id']) . "'"
                    );
                    if ($this->all->db->affected_rows() == 1) {
                        if (isset($obj->messages['remove_succeed'])) {
                            $this->all->tpl->add_sys_alert('status', $obj->messages['remove_succeed']);
                        }
                    } else {
                        if (isset($obj->messages['remove_failed'])) {
                            $this->all->tpl->add_sys_alert('error', $obj->messages['remove_failed']);
                        }
                        $succeed = false;
                    }
                }
                if (($values[$i]['exists'] && method_exists($obj, 'after_remove') && !$obj->after_remove("`id` = " . intval($values[$i]['id']), $remove_on_fail, $remove_on_success, $succeed)) || !$succeed) {
                    return false;
                }
                array_splice($values, $i, 1);
                --$i;
                continue;
            }
            if (!$values[$i]['is_ready4db']) {
                continue;
            }
            $child_objects = array();
            if ($values[$i]['exists']) {
                if (method_exists($obj, 'action_edit')) {
                    if (!$obj->action_edit($values[$i], $remove_on_fail, $remove_on_success)) {
                        return false;
                    }
                    continue;
                }
                if (method_exists($obj, 'before_edit') && !$obj->before_edit($values[$i], $remove_on_fail, $remove_on_success)) {
                    return false;
                }
                $q = '';
                foreach ($obj->items as $k => $v) {
                    if (!empty($v['add_only']) || (isset($v['in_db']) && !$v['in_db'])) {
                        continue;
                    }
                    $switch_lang = (empty($v['multilingual']) ? $one_lang : $all_lang);
                    foreach ($switch_lang as $lang) {
                        $kl = $k . $lang[0];
                        switch ($v['type']) {
                            case 'object':
                                $child_objects[] =& $v['object'];
                                break;
                            case 'integer':
                            case 'datetime':
                            case 'string':
                                $q .= ($q == '' ? '`' : ', `') . $kl . "` = '" . $this->all->db->escape($values[$i]['items'][$kl]) . "'";
                                break;
                            case 'file':
                                $m =& $values[$i]['items'][$kl];
                                if (empty($m['existing_remove'])) {
                                    $f = (isset($m['existing_filename']) ? $m['existing_filename'] : '');
                                } else {
                                    $remove_on_success[] = array('filename' => $this->all->frontend_rw_dir . '/' . $m['existing_filename']);
                                    $f = '';
                                }
                                if (!empty($m['future_filename'])) {
                                    if (copy($m['temp_filename'], $this->all->frontend_rw_dir . '/' . $m['future_filename'])) {
                                        $remove_on_fail[] = array('filename' => $this->all->frontend_rw_dir . '/' . $m['future_filename']);
                                        $remove_on_success[] = array(
                                            'filename' => $m['temp_filename'],
                                            'temp_file_id' => $m['temp_file_id'],
                                        );
                                        if ($f != '') {
                                            $remove_on_success[] = array('filename' => $this->all->frontend_rw_dir . '/' . $f);
                                        }
                                        $f = $m['future_filename'];
                                    } else {
                                        $this->all->tpl->add_sys_alert('warning', 23, '%filename%', $m['future_filename']);
                                        return false;
                                    }
                                }
                                $q .= ($q == '' ? '' : ', ') . $kl . " = '" . $this->all->db->escape($f) . "'";
                                break;
                        }
                    }
                }
                $this->all->db->squery($req,
                    'UPDATE ' . $this->all->db->prefix . $obj->table_name .
                    ' SET ' . $q .
                    " WHERE id = '" . intval($values[$i]['id']) . "'"
                );
                if (isset($obj->messages['edit_succeed'])) {
                    $this->all->tpl->add_sys_alert('status', $obj->messages['edit_succeed']);
                }
                if (method_exists($obj, 'after_edit') && !$obj->after_edit($values[$i], $remove_on_fail, $remove_on_success, $succeed)) {
                    return false;
                }
            } else {
                if (method_exists($obj, 'action_add')) {
                    if (!$obj->action_add($values[$i], $remove_on_fail, $remove_on_success)) {
                        return false;
                    }
                    continue;
                }
                if (method_exists($obj, 'before_add') && !$obj->before_add($values[$i], $remove_on_fail, $remove_on_success)) {
                    return false;
                }
                $q0 = '';
                $q1 = '';
                foreach ($obj->items as $k => $v) {
                    if (!empty($v['edit_only']) || (isset($v['in_db']) && !$v['in_db'])) {
                        continue;
                    }
                    $switch_lang = (empty($v['multilingual']) ? $one_lang : $all_lang);
                    foreach ($switch_lang as $lang) {
                        $kl = $k . $lang[0];
                        switch ($v['type']) {
                            case 'object':
                                $child_objects[] =& $v['object'];
                                break;
                            case 'integer':
                            case 'datetime':
                            case 'string':
                                $q0 .= ($q0 == '' ? '`' : ', `') . $kl . '`';
                                $q1 .= ($q1 == '' ? "'" : ", '") . $this->all->db->escape($values[$i]['items'][$kl]) . "'";
                                break;
                            case 'file':
                                $m =& $values[$i]['items'][$kl];
                                $f = '';
                                if (!empty($m['future_filename'])) {
                                    if (copy($m['temp_filename'], $this->all->frontend_rw_dir . '/' . $m['future_filename'])) {
                                        $remove_on_fail[] = array('filename' => $this->all->frontend_rw_dir . '/' . $m['future_filename']);
                                        $remove_on_success[] = array(
                                            'filename' => $m['temp_filename'],
                                            'temp_file_id' => $m['temp_file_id'],
                                        );
                                        $f = $m['future_filename'];
                                    } else {
                                        $this->all->tpl->add_sys_alert('error', 23, '%filename%', $m['future_filename']);
                                        return false;
                                    }
                                }
                                $q0 .= ($q0 == '' ? '`' : ', `') . $kl . '`';
                                $q1 .= ($q1 == '' ? "'" : ", '") . $this->all->db->escape($f) . "'";
                                break;
                        }
                    }
                }
                $this->all->db->squery($req,
                    'INSERT INTO ' . $this->all->db->prefix . $obj->table_name .
                    ' (' . $q0 . ') VALUES (' . $q1 . ')'
                );
                $id = $this->all->db->insert_id();
                if ($id > 0) {
                    if (isset($obj->messages['add_succeed'])) {
                        $this->all->tpl->add_sys_alert('status', $obj->messages['add_succeed']);
                    }
                    $values[$i]['id'] = $id;
                } else {
                    if (isset($obj->messages['add_failed'])) {
                        $this->all->tpl->add_sys_alert('error', $obj->messages['add_failed']);
                    }
                    $succeed = false;
                }
                if (method_exists($obj, 'after_add') && !$obj->after_add($values[$i], $remove_on_fail, $remove_on_success, $succeed)) {
                    return false;
                }
            }
            if (!$succeed) {
                return false;
            }
            foreach ($child_objects as $v) {
                if (!$this->_save_object_values($v, $remove_on_fail, $remove_on_success, array_merge($parent_values, array(&$values[$i])))) {
                    return false;
                }
            }
        }
        return $succeed;
    }

    function _save_object_values_remove_files(&$filelist)
    {
        $temp_files = $this->all->session_get_var('temp_files', false);
        if ($temp_files !== false) {
            $temp_files = unserialize($temp_files);
        }
        $rm_ids = array();
        foreach ($filelist as $v) {
            if (is_file($v['filename'])) {
                unlink($v['filename']);
            }
            if (isset($v['temp_file_id'])) {
                $rm_ids[] = intval($v['temp_file_id']);
                if ($temp_files !== false) {
                    foreach ($temp_files as $fk => $fv) {
                        if ($fv == $v['temp_file_id']) {
                            unset($temp_files[$fk]);
                            break;
                        }
                    }
                }
            }
        }
        $this->temp_file_multiremove($rm_ids);
        if ($temp_files !== false) {
            $this->all->session_set_var('temp_files', serialize($temp_files));
        }
    }

    function remove_object(&$obj)
    {
        $this->init_object_values($obj, true, false, false);
        for ($i = 0; $i < count($obj->values); $i++) {
            $obj->values[$i]['action'] = 'remove';
        }
        $succeed = true;
        $this->all->db->transaction_begin();
        $remove_on_fail = array();
        $remove_on_success = array();
        $succeed = $this->_save_object_values($obj, $remove_on_fail, $remove_on_success);
        if ($succeed) {
            $this->_save_object_values_remove_files($remove_on_success);
            $this->all->db->transaction_commit();
        } else {
            $this->_save_object_values_remove_files($remove_on_fail);
            $this->all->db->transaction_rollback();
        }
        unset($obj->values);
        return $succeed;
    }

}

?>