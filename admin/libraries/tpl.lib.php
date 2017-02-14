<?php

/*
*/

class cTemplates
{
    var
        $title,
        $caption,
        $smarty_root_dir,
        $smarty,
        $all,
        $vsys_alerts,
        $css,
        $vjs_globals,
        $vjs_top,
        $vjs_files,
        $vjs_bottom,
        $tree_pair,
        $submenu,
        $is_first_editor = true;

    function cTemplates(&$all)
    {
        $this->all =& $all;
        $this->all->finclude('config/tpl.cfg.php', $this);
        $this->title = $this->all->io->output(1);
        $this->caption = $this->all->io->output(2);
        $this->css = array();
        $this->vsys_alerts = array();
        $this->vjs_globals = array();
        $this->vjs_top = '';
        $this->vjs_files = array();
        $this->vjs_bottom = '';
        $this->smarty_init();
    }

    function smarty_init()
    {
        define('SMARTY_DIR', $this->smarty_root_dir . 'libs/');
        $this->all->finclude(SMARTY_DIR . 'Smarty.class.php', $this);
        $this->smarty = new Smarty();
        $this->smarty->template_dir = $this->smarty_root_dir . 'templates/';
        $this->smarty->compile_dir = $this->smarty_root_dir . 'templates_c/';
        $this->smarty->config_dir = $this->smarty_root_dir . 'configs/';
        $this->smarty->cache_dir = $this->smarty_root_dir . 'cache/';
        $this->smarty->compile_check = true;
        $this->smarty->debugging = false;
        $this->smarty->assign('copyright', $this->all->io->output(31, '%year%', date('Y')));
        $this->smarty->assign_by_ref('all', $this->all);
        $this->smarty->assign_by_ref('title', $this->title);
        $this->smarty->assign_by_ref('caption', $this->caption);
        $this->smarty->assign_by_ref('vsys_alerts', $this->vsys_alerts);
        $this->smarty->assign_by_ref('css', $this->css);
        $this->smarty->assign_by_ref('vjs_globals', $this->vjs_globals);
        $this->smarty->assign_by_ref('vjs_top', $this->vjs_top);
        $this->smarty->assign_by_ref('vjs_files', $this->vjs_files);
        $this->smarty->assign_by_ref('vjs_bottom', $this->vjs_bottom);
        $this->smarty->assign_by_ref('submenu', $this->submenu);
        $this->smarty->register_function('send_headers', array(&$this, 'send_headers'));
        $this->smarty->register_function('spacer', array(&$this, 'tpl_spacer'));
        $this->smarty->register_function('options_ext', array(&$this, 'options_ext'));
        $this->smarty->register_function('radio_options_ext', array(&$this, 'radio_options_ext'));
        $this->smarty->register_block('pane', array(&$this, 'pane'));
        $this->smarty->register_function('tree', array(&$this, 'tree'));
        $this->smarty->register_function('sys_alerts', array(&$this, 'sys_alerts'));
        $this->smarty->register_function('menu_left', array(&$this, 'menu_left'));
        $this->smarty->register_function('menu_right', array(&$this, 'menu_right'));
        $this->smarty->register_function('pagination_pane', array(&$this, 'pagination_pane'));
    }

    function img($src, $alt = '', $no_htmlspecialchars = false)
    {
        $s = @getimagesize($src);
        return '<img' . ($alt == '' ? '' : ' alt="' . ($no_htmlspecialchars ? $alt : htmlspecialchars($alt)) . '"') . ' src="' . $src . '"' . (is_array($s) ? ' width="' . $s[0] . '" height="' . $s[1] . '"' : '') . ' />';
    }

    function add_sys_alert($atype, $id, $params = false, $values = false)
    {
        $this->vsys_alerts[] = array(
            'type' => $atype,
            'alert' => $this->all->io->output($id, $params, $values),
        );
    }

    function add_css($n, $a)
    {
        if (!isset($this->css[$n])) {
            $this->css[$n] = array();
        }
        foreach ($a as $k => $v) {
            $this->css[$n][$k] = $v;
        }
    }

    function add_js_top($js)
    {
        $this->vjs_top .= ($this->vjs_top == '' ? '' : $this->nl()) . $js;
    }

    function nl($tab = 0)
    {
        return "\r\n" . str_repeat("\t", $tab);
    }

    function add_title($title)
    {
        $this->title = strip_tags($title) . ($this->title == '' || $title == '' ? '' : $this->all->io->output(3)) . $this->title;
    }

    function add_caption($caption)
    {
        $this->caption .= ($this->caption == '' || $caption == '' ? '' : $this->all->io->output(4)) . $caption;
    }

    function last_change_info($timestamp, $adminid, $default = '')
    {
        if ($timestamp == 0) {
            return $default;
        }
        $last_admin = $this->get_admin_info($adminid);
        return $this->all->io->output(26, array('%name%', '%date%'), array(($last_admin ? $this->all->io->user_longname($last_admin['fname'], $last_admin['sname'], $last_admin['lname']) : $this->all->io->output(27)), $this->all->io->date_ts2text($timestamp)));
    }

    function get_admin_info($id, $params = '`fname`, `sname`, `lname`')
    {
        return $this->get_info($id, $params, 'admins');
    }

    function get_info($id, $params, $table)
    {
        $this->all->db->query($req, 11, array('%id%', '%params%', '%table%'), array($id, $params, $table));
        $res = $this->all->db->fetch_assoc($req);
        $this->all->db->free_result($req);
        return $res;
    }

    function votes_comments_info($comments, $votes, $votetotal, $objid, $objtype, $objurl)
    {
        return false;
        return
            '<a title="' . htmlspecialchars($this->all->io->output(101)) . '" href="' . $this->all->uri('comments/' . $objurl . '/' . $objid . '/') . '">' . $this->all->io->output(100, '%n%', $comments) . '</a>' .
            ', ' . $this->all->io->output(102, array('%v%', '%m%'), array(sprintf('%1.1f', ($votes > 0 ? $votetotal / $votes : 0)), 5)) .
            ', ' . $this->all->io->output($this->all->io->word_by_no($votes, 103, 104, 105), '%n%', $votes) . '.';
    }

    function init_object_values(&$obj)
    {
        $js_params = '';
        if (!$this->_init_object_values($obj, $js_params, isset($_POST['submit']), '', array())) {
            return false;
        }
        $this->add_js_global('mformReq', '[' . ($js_params == '' ? '' : str_replace($this->nl(), $this->nl(2), $js_params) . $this->nl(2)) . ']');
        return true;
    }

    function _init_object_values(&$obj, &$js_params, $sent, $prefix, $parent_values)
    {
        $all_lang = array();
        foreach ($this->all->io->lang_available_frontend as $k => $v) {
            $all_lang[] = array('_' . $k, ($this->all->io->lang_show_fields ? ' (' . $k . ')' : ''), $k);
        }
        $one_lang = array(array('', '', ''));
        $tabjs1 = $this->nl(1);
        if (count($parent_values) > 0) {
            $values =& $parent_values[count($parent_values) - 1]['items'][$obj->parent_item_names[count($obj->parent_item_names) - 1]];
        } else {
            $values =& $obj->values;
        }
        if (!isset($obj->counts['tpl'])) {
            $obj->counts['tpl'] = array();
        }
        $obj->counts['tpl']['current'] = '<input type="hidden" name="' . $prefix . '_obj_count" value="' . $obj->counts['current'] . '" />';
        if (!isset($obj->counts['tpl']['add']) && $obj->counts['max'] > $obj->counts['default']) {
            $obj->counts['tpl']['add'] = array(
                'html' => 'add',
            );
        }
        for ($i = 0; $i < $obj->counts['current']; $i++) {
            if (!isset($values[$i]['tpl'])) {
                $values[$i]['tpl'] = array(
                    'items' => array(),
                );
            }
            if (!isset($values[$i]['tpl']['remove']) && $obj->counts['min'] < $obj->counts['default']) {
                $values[$i]['tpl']['remove'] = array(
                    'html' => 'remove',
                );
            }
            foreach ($obj->items as $k => $v) {
                if (!(empty($obj->readonly) && empty($v['readonly']) && (empty($v['add_only']) || $values[$i]['action'] == 'add') && (empty($v['edit_only']) || $values[$i]['action'] == 'edit'))) {
                    continue;
                }
                $switch_lang = (empty($v['multilingual']) ? $one_lang : $all_lang);
                foreach ($switch_lang as $lang) {
                    $kl = $k . $lang[0];
                    $kp = $prefix . $kl . '_' . $i;
                    $required = !empty($v['required']);
                    switch ($v['type']) {
                        case 'object':
                            if (!$this->_init_object_values($v['object'], $js_params, $sent, $prefix . $kl . '_' . $i . '_', array_merge($parent_values, array(&$values[$i])))) {
                                return false;
                            }
                            break;
                        case 'integer':
                            if (!isset($v['input_format'])) {
                                $obj->items[$k]['input_format'] = $v['input_format'] = 0;
                            }
                            $d =& $values[$i]['items'][$kl];
                            $input_actions = (isset($v['input_actions']) ? str_replace('%lang%', $lang[2], $v['input_actions']) : '');
                            $input_caption = (isset($v['input_hint']) ? '<acronym title="' . htmlspecialchars($this->all->io->output($v['input_hint'], '%lang%', $lang[1])) . '">' : '') . (isset($v['input_caption']) ? (is_string($v['input_caption']) ? str_replace('%lang%', $lang[1], $v['input_caption']) : $this->all->io->output($v['input_caption'], '%lang%', $lang[1])) : '') . (isset($v['input_hint']) ? '</acronym>:' : ':') . ($required ? ' <font class="required">*</font>' : '');
                            $input_idname = ' id="' . $kp . '" name="' . $kp . '"';
                            switch ($v['input_format']) {
                                case 0:
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : false));
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'input' => '<input class="text" type="text"' . $input_idname . ' size="' . $v['input_size'] . '"' . (empty($v['input_maxlength']) ? '' : ' maxlength="' . $v['input_maxlength'] . '"') . ' value="' . ($values[$i]['action'] == 'add' && $m !== false && !($sent && isset($_POST[$kp]) && $_POST[$kp] != $m) ? htmlspecialchars($m) : $d) . '"' . $input_actions . ' />',
                                    );
                                    $js_params .=
                                        ($js_params == '' ? '' : ',') . $tabjs1 .
                                        "{'type':100,'nm':'" . $kp . "'" .
                                        ",'req':" . ($required ? '1' : '0') .
                                        (isset($v['min']) ? ",'min':" . $v['min'] : '') .
                                        (isset($v['max']) ? ",'max':" . $v['max'] : '') .
                                        ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                        (isset($v['err_msg_req']) ? ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'" : '') .
                                        ",'ermask':'" . addslashes($this->all->io->output($v['err_msg_mask'], '%lang%', $lang[1])) . "'}";
                                    break;
                                case 1:
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'before' => '<select' . $input_idname . ' size="' . (isset($v['input_size']) ? $v['input_size'] : '1') . '"' . $input_actions . '>',
                                        'after' => '</select>',
                                        'selected' => $d,
                                    );
                                    if (isset($v['options'])) {
                                        $values[$i]['tpl']['items'][$kl]['options'] =& $obj->items[$k]['options'];
                                    } else {
                                        $values[$i]['tpl']['items'][$kl]['options'] = array();
                                        for ($j = $v['min']; $j < $v['max']; $j++) {
                                            $values[$i]['tpl']['items'][$kl]['options'][] = array(
                                                'title' => $j,
                                                'value' => $j,
                                            );
                                        }
                                    }
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : false));
                                    if ($required && (isset($v['min']) || isset($v['max']) || $m !== false)) {
                                        $js_params .=
                                            ($js_params == '' ? '' : ',') . $tabjs1 .
                                            "{'type':101,'nm':'" . $kp . "'" .
                                            (isset($v['min']) ? ",'min':" . $v['min'] : '') .
                                            (isset($v['max']) ? ",'max':" . $v['max'] : '') .
                                            ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                            ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'}";
                                    }
                                    break;
                                case 2:
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'name' => $kp,
                                        'actions' => $input_actions,
                                        'selected' => $d,
                                        'multiline' => !empty($v['input_multiline']),
                                    );
                                    if (isset($v['options'])) {
                                        $values[$i]['tpl']['items'][$kl]['options'] =& $obj->items[$k]['options'];
                                    } else {
                                        $values[$i]['tpl']['items'][$kl]['options'] = array();
                                        for ($j = $v['min']; $j < $v['max']; $j++) {
                                            $values[$i]['tpl']['items'][$kl]['options'][] = array(
                                                'title' => $j,
                                                'value' => $j,
                                            );
                                        }
                                    }
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : false));
                                    if ($required && (isset($v['min']) || isset($v['max']) || $m !== false)) {
                                        $js_params .=
                                            ($js_params == '' ? '' : ',') . $tabjs1 .
                                            "{'type':102,'nm':'" . $kp . "'" .
                                            (isset($v['min']) ? ",'min':" . $v['min'] : '') .
                                            (isset($v['max']) ? ",'max':" . $v['max'] : '') .
                                            ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                            ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'}";
                                    }
                                    break;
                                case 3:
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => (isset($v['input_hint']) ? '<acronym title="' . htmlspecialchars($this->all->io->output($v['input_hint'], '%lang%', $lang[1])) . '">' : '') . (isset($v['input_caption']) ? (is_string($v['input_caption']) ? str_replace('%lang%', $lang[1], $v['input_caption']) : $this->all->io->output($v['input_caption'], '%lang%', $lang[1])) : '') . (isset($v['input_hint']) ? '</acronym>' : '') . ($required ? ' <font class="required">*</font>' : ''),
                                        'before' => '<label for="' . $kp . '">',
                                        'after' => '</label>',
                                        'input' => '<input class="checkbox" type="checkbox"' . $input_idname . ($d == 0 ? '' : ' checked="checked"') . $input_actions . ' />',
                                    );
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : false));
                                    if ($required && (isset($v['min']) || isset($v['max']) || $m !== false)) {
                                        $js_params .=
                                            ($js_params == '' ? '' : ',') . $tabjs1 .
                                            "{'type':103,'nm':'" . $kp . "'" .
                                            (isset($v['min']) ? ",'min':" . $v['min'] : '') .
                                            (isset($v['max']) ? ",'max':" . $v['max'] : '') .
                                            ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                            ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'}";
                                    }
                                    break;
                                case 4:
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'html' => str_replace('%lang%', $lang[2], $v['input_html']),
                                    );
                                    if (!empty($v['input_validation'])) {
                                        $js_params .=
                                            ($js_params == '' ? '' : ',') . $tabjs1 .
                                            "{'type':104" .
                                            ",'vld':" . str_replace('%lang%', $lang[2], $v['input_validation']) . '}';
                                    }
                                    break;
                            }
                            break;
                        case 'string':
                            if (!isset($v['input_format'])) {
                                $obj->items[$k]['input_format'] = $v['input_format'] = 0;
                            }
                            $d =& $values[$i]['items'][$kl];
                            $input_actions = (isset($v['input_actions']) ? str_replace('%lang%', $lang[2], $v['input_actions']) : '');
                            $input_caption = (isset($v['input_hint']) ? '<acronym title="' . htmlspecialchars($this->all->io->output($v['input_hint'], '%lang%', $lang[1])) . '">' : '') . (isset($v['input_caption']) ? (is_string($v['input_caption']) ? str_replace('%lang%', $lang[1], $v['input_caption']) : $this->all->io->output($v['input_caption'], '%lang%', $lang[1])) : '') . (isset($v['input_hint']) ? '</acronym>:' : ':') . ($required ? ' <font class="required">*</font>' : '');
                            $input_idname = ' id="' . $kp . '" name="' . $kp . '"';
                            switch ($v['input_format']) {
                                case 0:
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : false));
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'input' => '<input class="text" type="text"' . $input_idname . ' size="' . $v['input_size'] . '"' . (empty($v['maxlength']) ? '' : ' maxlength="' . $v['maxlength'] . '"') . ' value="' . htmlspecialchars(($values[$i]['action'] == 'add' && $m !== false && !($sent && isset($_POST[$kp]) && $_POST[$kp] != $m) ? $m : $d)) . '"' . $input_actions . ' />',
                                    );
                                    $js_params .=
                                        ($js_params == '' ? '' : ',') . $tabjs1 .
                                        "{'type':200,'nm':'" . $kp . "'" .
                                        ",'req':" . ($required ? '1' : '0') .
                                        ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                        (isset($v['imask']) ? ",'imask':" . $v['imask'] : '') .
                                        (isset($v['mask']) ? ",'mask':" . $v['mask'] : '') .
                                        (isset($v['err_msg_req']) ? ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'" : '') .
                                        (isset($v['err_msg_mask']) ? ",'ermask':'" . addslashes($this->all->io->output($v['err_msg_mask'], '%lang%', $lang[1])) . "'" : '') . '}';
                                    break;
                                case 1:
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'before' => '<select' . $input_idname . ' size="' . (isset($v['input_size']) ? $v['input_size'] : '1') . '"' . $input_actions . '>',
                                        'after' => '</select>',
                                        'selected' => $d,
                                    );
                                    $values[$i]['tpl']['items'][$kl]['options'] =& $obj->items[$k]['options'];
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : false));
                                    if ($required && (isset($v['imask']) || isset($v['mask']) || $m !== false)) {
                                        $js_params .=
                                            ($js_params == '' ? '' : ',') . $tabjs1 .
                                            "{'type':201,'nm':'" . $kp . "'" .
                                            (isset($v['imask']) ? ",'imask':" . $v['imask'] : '') .
                                            (isset($v['mask']) ? ",'mask':" . $v['mask'] : '') .
                                            ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                            ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'}";
                                    }
                                    break;
                                case 2:
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'name' => $kp,
                                        'actions' => $input_actions,
                                        'selected' => $d,
                                        'multiline' => !empty($v['input_multiline']),
                                    );
                                    $values[$i]['tpl']['items'][$kl]['options'] =& $obj->items[$k]['options'];
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : false));
                                    if ($required && (isset($v['imask']) || isset($v['mask']) || $m !== false)) {
                                        $js_params .=
                                            ($js_params == '' ? '' : ',') . $tabjs1 .
                                            "{'type':202,'nm':'" . $kp . "'" .
                                            (isset($v['imask']) ? ",'imask':" . $v['imask'] : '') .
                                            (isset($v['mask']) ? ",'mask':" . $v['mask'] : '') .
                                            ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                            ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'}";
                                    }
                                    break;
                                case 3:
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : false));
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'input' => '<textarea' . $input_idname . ' rows="' . $v['input_rows'] . '" cols="' . $v['input_size'] . '"' . (empty($v['maxlength']) ? '' : ' maxlength="' . $v['maxlength'] . '"') . $input_actions . '>' . htmlspecialchars(($values[$i]['action'] == 'add' && $m !== false && !($sent && isset($_POST[$kp]) && $_POST[$kp] != $m) ? $m : $d)) . '</textarea>',
                                    );
                                    $js_params .=
                                        ($js_params == '' ? '' : ',') . $tabjs1 .
                                        "{'type':203,'nm':'" . $kp . "'" .
                                        ",'req':" . ($required ? '1' : '0') .
                                        (isset($v['maxlength']) ? ",'max':" . $v['maxlength'] : '') .
                                        ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                        (isset($v['imask']) ? ",'imask':" . $v['imask'] : '') .
                                        (isset($v['mask']) ? ",'mask':" . $v['mask'] : '') .
                                        (isset($v['err_msg_req']) ? ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'" : '') .
                                        (isset($v['err_msg_length']) ? ",'erlen':'" . addslashes($this->all->io->output($v['err_msg_length'], '%lang%', $lang[1])) . "'" : '') .
                                        (isset($v['err_msg_mask']) ? ",'ermask':'" . addslashes($this->all->io->output($v['err_msg_mask'], '%lang%', $lang[1])) . "'" : '') . '}';
                                    break;
                                case 4:
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'html' => str_replace('%lang%', $lang[2], $v['input_html']),
                                    );
                                    if (!empty($v['input_validation'])) {
                                        $js_params .=
                                            ($js_params == '' ? '' : ',') . $tabjs1 .
                                            "{'type':204" .
                                            ",'vld':" . str_replace('%lang%', $lang[2], $v['input_validation']) . '}';
                                    }
                                    break;
                                case 5:
                                    $is_conf = (isset($v['confirm']) && !$v['confirm'] ? false : true);
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'caption_conf' => ($is_conf ? (isset($v['conf_hint']) ? '<acronym title="' . htmlspecialchars($this->all->io->output($v['conf_hint'], '%lang%', $lang[1])) . '">' : '') . (isset($v['conf_caption']) ? $this->all->io->output($v['conf_caption'], '%lang%', $lang[1]) : '') . (isset($v['conf_hint']) ? '</acronym>:' : ':') . ($required ? ' <font class="required">*</font>' : '') : ''),
                                        'input' => '<input class="password" type="password"' . $input_idname . ' size="' . $v['input_size'] . '"' . (empty($v['maxlength']) ? '' : ' maxlength="' . $v['maxlength'] . '"') . ' value="' . ($sent && isset($_POST[$kp]) && $_POST[$kp] != '' ? htmlspecialchars($this->all->io->input($_POST[$kp])) : '') . '"' . $input_actions . ' />',
                                        'input_conf' => ($is_conf ? '<input class="password confirm" type="password" id="' . $kp . '_conf" name="' . $kp . '_conf" size="' . $v['input_size'] . '"' . (empty($v['maxlength']) ? '' : ' maxlength="' . $v['maxlength'] . '"') . ' value="' . ($sent && isset($_POST[$kp . '_conf']) && $_POST[$kp . '_conf'] != '' ? htmlspecialchars($this->all->io->input($_POST[$kp . '_conf'])) : '') . '"' . (isset($v['conf_actions']) ? str_replace('%lang%', $lang[2], $v['conf_actions']) : '') . ' />' : ''),
                                    );
                                    $js_params .=
                                        ($js_params == '' ? '' : ',') . $tabjs1 .
                                        "{'type':205,'nm':'" . $kp . "'" .
                                        ",'req':" . ($required ? '1' : '0') .
                                        ",'conf':" . ($is_conf ? '1' : '0') .
                                        ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                        (isset($v['imask']) ? ",'imask':" . $v['imask'] : '') .
                                        (isset($v['mask']) ? ",'mask':" . $v['mask'] : '') .
                                        (isset($v['err_msg_req']) ? ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'" : '') .
                                        (isset($v['err_msg_mask']) ? ",'ermask':'" . addslashes($this->all->io->output($v['err_msg_mask'], '%lang%', $lang[1])) . "'" : '') .
                                        (isset($v['err_msg_conf']) ? ",'erconf':'" . addslashes($this->all->io->output($v['err_msg_conf'], '%lang%', $lang[1])) . "'" : '') . '}';
                                    break;
                                case 7:
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $obj->items[$k]['default_ignore' . $lang[0]] : (isset($obj->items[$k]['default_ignore']) ? $obj->items[$k]['default_ignore'] : false));
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'input' => $this->get_editor($kp, ' rows="' . $v['input_rows'] . '" cols="' . $v['input_size'] . '"' . (empty($v['maxlength']) ? '' : ' maxlength="' . $v['maxlength'] . '"') . $input_actions, htmlspecialchars(($values[$i]['action'] == 'add' && $m !== false && !($sent && isset($_POST[$kp]) && $_POST[$kp] != $m) ? $m : $d))),
                                    );
                                    $js_params .=
                                        ($js_params == '' ? '' : ',') . $tabjs1 .
                                        "{'type':207,'nm':'" . $kp . "'" .
                                        ",'req':" . ($required ? '1' : '0') .
                                        (isset($v['maxlength']) ? ",'max':" . $v['maxlength'] : '') .
                                        ($m === false ? '' : ",'ign':'" . htmlspecialchars($m) . "'") .
                                        (isset($v['imask']) ? ",'imask':" . $v['imask'] : '') .
                                        (isset($v['mask']) ? ",'mask':" . $v['mask'] : '') .
                                        (isset($v['err_msg_req']) ? ",'erreq':'" . htmlspecialchars($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'" : '') .
                                        (isset($v['err_msg_length']) ? ",'erlen':'" . htmlspecialchars($this->all->io->output($v['err_msg_length'], '%lang%', $lang[1])) . "'" : '') .
                                        (isset($v['err_msg_mask']) ? ",'ermask':'" . htmlspecialchars($this->all->io->output($v['err_msg_mask'], '%lang%', $lang[1])) . "'" : '') . '}';
                                    break;
                            }
                            break;
                        case 'datetime':
                            if (!isset($v['input_format'])) {
                                $obj->items[$k]['input_format'] = $v['input_format'] = 0;
                            }
                            if ($v['input_format'] == 2) {
                                $values[$i]['tpl']['items'][$kl] = array(
                                    'html' => str_replace('%lang%', $lang[2], $v['input_html']),
                                );
                                if (!empty($v['input_validation'])) {
                                    $js_params .=
                                        ($js_params == '' ? '' : ',') . $tabjs1 .
                                        "{'type':204" .
                                        ",'vld':" . str_replace('%lang%', $lang[2], $v['input_validation']) . '}';
                                }
                            } else {
                                if ($this->add_js_file('js/calendar.js')) {
                                    $js_clnd = '';
                                    for ($clnd = 35; $clnd < 47; $clnd++) {
                                        $js_clnd .= ($js_clnd == '' ? "'" : ", '") . addslashes($this->all->io->output($clnd)) . "'";
                                    }
                                    $this->add_js_global('clndMonths', '[' . $js_clnd . ']');
                                    $js_clnd = '';
                                    for ($clnd = 48; $clnd < 55; $clnd++) {
                                        $js_clnd .= ($js_clnd == '' ? "'" : ", '") . addslashes($this->all->io->output($clnd)) . "'";
                                    }
                                    $this->add_js_global('clndDOW', '[' . $js_clnd . ']');
                                    $this->add_js_global('clndSDOW', $this->all->io->datetime['shift_dow']);
                                    $this->add_js_global('clndStatusFMT', "'" . addslashes($this->all->io->datetime['ymd']['short']) . "'");
                                    $this->add_js_global('clndClose', "'" . addslashes($this->all->io->output(47)) . "'");
                                }
                                $d =& $values[$i]['items'][$kl];
                                $multifield = (isset($v['multifield']) && $v['multifield'] === false ? false : true);
                                $has_time = !empty($v['has_time']);
                                $has_seconds = !empty($v['has_seconds']);
                                $dt =& $this->all->io->datetime[($has_time ? ($has_seconds ? 'ymdhis' : 'ymdhi') : 'ymd')];
                                $input_actions = (isset($v['input_actions']) ? str_replace('%lang%', $lang[2], $v['input_actions']) : '');
                                $input_caption = (isset($v['input_hint']) ? '<acronym title="' . htmlspecialchars($this->all->io->output($v['input_hint'], '%lang%', $lang[1])) . '">' : '') . (isset($v['input_caption']) ? (is_string($v['input_caption']) ? str_replace('%lang%', $lang[1], $v['input_caption']) : $this->all->io->output($v['input_caption'], '%lang%', $lang[1])) : '') . (isset($v['input_hint']) ? '</acronym>:' : ':') . ($required ? ' <font class="required">*</font>' : '');
                                $err_msg_req = (isset($v['err_msg_req']) ? ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'" : '');
                                $err_msg_mask = ",'ermask':'" . addslashes($this->all->io->output($v['err_msg_mask'], '%lang%', $lang[1])) . "'";
                                if ($multifield) {
                                    $fld = array(
                                        'y' => array('min' => 1000, 'max' => 9999, 'len' => 4, 'dt' => 'Y'),
                                        'm' => array('min' => 1, 'max' => 12, 'len' => 2, 'dt' => 'm'),
                                        'd' => array('min' => 1, 'max' => 31, 'len' => 2, 'dt' => 'd'),
                                    );
                                    if (!empty($v['has_time'])) {
                                        $fld['h'] = array('min' => 0, 'max' => 23, 'len' => 2, 'dt' => 'H');
                                        $fld['i'] = array('min' => 0, 'max' => 59, 'len' => 2, 'dt' => 'i');
                                        if (!empty($v['has_seconds'])) {
                                            $fld['s'] = array('min' => 0, 'max' => 59, 'len' => 2, 'dt' => 's');
                                        }
                                    }
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                    );
                                    $fld_names = '';
                                    foreach ($fld as $fk => $fv) {
                                        $af = '_' . $fk;
                                        $kf = $kp . $af;
                                        $fld_names .= ($fld_names == '' ? "'" : ",'") . $kf . "'";
                                        $m = (isset($obj->items[$k]['default_ignore' . $lang[0] . $af]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0] . $af]) : (isset($obj->items[$k]['default_ignore' . $af]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $af]) : false));
                                        $values[$i]['tpl']['items'][$kl]['input' . $af] = '<input class="text" type="text" id="' . $kf . '" name="' . $kf . '" size="' . $fv['len'] . '" maxlength="' . $fv['len'] . '" value="' . ($values[$i]['action'] == 'add' && $m !== false && !($sent && isset($_POST[$kf]) && $_POST[$kf] != $m) ? htmlspecialchars($m) : ($d == 0 ? '' : date($fv['dt'], $d))) . '"' . (isset($v['input_actions' . $af]) ? str_replace('%lang%', $lang[2], $v['input_actions' . $af]) : $input_actions) . ' />';
                                        $js_params .=
                                            ($js_params == '' ? '' : ',') . $tabjs1 .
                                            "{'type':100,'nm':'" . $kf . "'" .
                                            ",'req':" . ($required ? '1' : '0') .
                                            ",'min':" . $fv['min'] .
                                            ",'max':" . $fv['max'] .
                                            ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                            (isset($v['err_msg_req' . $af]) ? ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req' . $af], '%lang%', $lang[1])) . "'" : $err_msg_req) .
                                            (isset($v['err_msg_mask' . $af]) ? ",'ermask':'" . addslashes($this->all->io->output($v['err_msg_mask' . $af], '%lang%', $lang[1])) . "'" : $err_msg_mask) . '}';
                                    }
                                } else {
                                    $m = (isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : false));
                                    $values[$i]['tpl']['items'][$kl] = array(
                                        'caption' => $input_caption,
                                        'input' => '<input class="text" type="text" id="' . $kp . '" name="' . $kp . '" size="' . $v['input_size'] . '" maxlength="50" value="' . htmlspecialchars(($values[$i]['action'] == 'add' && $m !== false && !($sent && isset($_POST[$kp]) && $_POST[$kp] != $m) ? $m : ($d == 0 ? '' : date($dt['short'], $d)))) . '"' . $input_actions . ' />',
                                    );
                                    $js_params .=
                                        ($js_params == '' ? '' : ',') . $tabjs1 .
                                        "{'type':200,'nm':'" . $kp . "'" .
                                        ",'req':" . ($required ? '1' : '0') .
                                        ($m === false ? '' : ",'ign':'" . addslashes($m) . "'") .
                                        ",'mask':" . $dt['str_mask'] .
                                        $err_msg_req . $err_msg_mask . '}';
                                }
                                $values[$i]['tpl']['items'][$kl]['calendar'] = '<div class="calendar' . ($v['input_format'] == 0 ? ' float' : '') . '" id="' . $kp . '_calendar">&nbsp;</div>';
                                $values[$i]['tpl']['items'][$kl]['button'] = ($v['input_format'] == 0 ? '<a' . (isset($v['button_hint']) ? ' title="' . htmlspecialchars($this->all->io->output($v['button_hint'], '%lang%', $lang[1])) . '"' : '') . ' class="button calendar-open" id="' . $kp . '_button" href="#"' . (isset($v['button_actions']) ? str_replace('%lang%', $lang[2], $v['button_actions']) : '') . ' onclick="return calendarShow(\'' . $kp . '_calendar\')">&nbsp;</a>' : '');
                                $this->add_js_bottom('winonload("calendarAdd(\'' . $kp . "', " . ($v['input_format'] == 0 ? '1' : '0') . ', ' . ($multifield ? '1' : "0, '" . $dt['short'] . "', " . $dt['str_mask'] . ", '" . $dt['int_mask'] . "'") . ');");');
                            }
                            break;
                        case 'file':
                            if (!isset($v['input_format'])) {
                                $obj->items[$k]['input_format'] = $v['input_format'] = 0;
                            }
                            if ($v['input_format'] == 1) {
                                $values[$i]['tpl']['items'][$kl] = array(
                                    'html' => str_replace('%lang%', $lang[2], $v['input_html']),
                                );
                                if (!empty($v['input_validation'])) {
                                    $js_params .=
                                        ($js_params == '' ? '' : ',') . $tabjs1 .
                                        "{'type':204" .
                                        ",'vld':" . str_replace('%lang%', $lang[2], $v['input_validation']) . '}';
                                }
                            } else {
                                $d =& $values[$i]['items'][$kl];
                                $values[$i]['tpl']['items'][$kl] = array(
                                    'input_caption' => (isset($v['input_hint']) ? '<acronym title="' . htmlspecialchars($this->all->io->output($v['input_hint'], '%lang%', $lang[1])) . '">' : '') . (isset($v['input_caption']) ? (is_string($v['input_caption']) ? str_replace('%lang%', $lang[1], $v['input_caption']) : $this->all->io->output($v['input_caption'], '%lang%', $lang[1])) : '') . (isset($v['input_hint']) ? '</acronym>:' : ':') . ($required ? ' <font class="required">*</font>' : ''),
                                    'max_file_size' => (empty($v['max_file_size']) ? '' : '<input type="hidden" name="max_file_size" value="' . $v['max_file_size'] . '" />'),
                                    'input_file' => '<input class="file" type="file" id="' . $kp . '" name="' . $kp . '" size="' . $v['input_size'] . '"' . (isset($v['input_actions']) ? str_replace('%lang%', $lang[2], $v['input_actions']) : '') . ' />',
                                    'before' => '<div class="file">',
                                    'after' => '</div>',
                                    'input_fictive' => '<input class="text fictive" type="text" id="' . $kp . '_fictive" name="' . $kp . '_fictive" size="' . $v['input_size'] . '" value="' . htmlspecialchars((isset($obj->items[$k]['default_ignore' . $lang[0]]) ? $this->all->io->output($obj->items[$k]['default_ignore' . $lang[0]]) : (isset($obj->items[$k]['default_ignore']) ? $this->all->io->output($obj->items[$k]['default_ignore']) : ''))) . '" disabled="disabled" />',
                                    'browse' => '<input' . (isset($v['browse_hint']) ? ' title="' . htmlspecialchars($this->all->io->output($v['browse_hint'], '%lang%', $lang[1])) . '"' : '') . ' class="button file-browse" type="button" id="' . $kp . '_browse" value="' . $this->all->io->output(32) . '"' . (isset($v['browse_actions']) ? str_replace('%lang%', $lang[2], $v['browse_actions']) : '') . ' />',
                                    'clear' => '<a' . (isset($v['clear_hint']) ? ' title="' . htmlspecialchars($this->all->io->output($v['clear_hint'], '%lang%', $lang[1])) . '"' : '') . ' class="file-clear" id="' . $kp . '_clear" href="#"' . (isset($v['clear_actions']) ? str_replace('%lang%', $lang[2], $v['clear_actions']) : '') . '>&nbsp;</a>',
                                    'existing_caption' => (empty($d['existing_filename']) || !isset($v['existing_caption']) ? '' : $this->all->io->output($v['existing_caption'], '%lang%', $lang[1])),
                                    'existing_file' => (empty($d['existing_filename']) ? '' : '<a' . (isset($v['existing_hint']) ? ' title="' . htmlspecialchars($this->all->io->output($v['existing_hint'], '%lang%', $lang[1])) . '"' : '') . ' href="' . $this->all->uri('preview/' . $d['existing_filename']) . '" target="_blank"' . $this->file_js_popup($d['existing_filename']) . (isset($v['existing_actions']) ? str_replace('%lang%', $lang[2], $v['existing_actions']) : '') . '>' . $d['existing_filename'] . '</a>'),
                                    'remove_existing' => ($required || empty($d['existing_filename']) ? '' : '<label class="remove-file" for="' . $kp . '_remove"><input type="checkbox" id="' . $kp . '_remove" name="' . $kp . '_remove"' . (empty($v['existing_remove']) ? '' : ' checked="checked"') . (isset($v['remove_actions']) ? str_replace('%lang%', $lang[2], $v['remove_actions']) : '') . '>' . (isset($v['remove_hint']) ? '<acronym title="' . htmlspecialchars($this->all->io->output($v['remove_hint'], '%lang%', $lang[1])) . '">' : '') . $this->all->io->output(75) . (isset($v['remove_hint']) ? '</acronym>' : '') . '</label>'),
                                    'temp_caption' => (empty($d['future_filename']) || !isset($v['temp_caption']) ? '' : $this->all->io->output($v['temp_caption'], '%lang%', $lang[1])),
                                    'temp_file' => (empty($d['future_filename']) ? '' : '<a' . (isset($v['temp_hint']) ? ' title="' . htmlspecialchars($this->all->io->output($v['temp_hint'], '%lang%', $lang[1])) . '"' : '') . ' href="' . $this->all->uri('preview/' . $d['future_filename']) . '" target="_blank"' . $this->temp_file_js_popup($d['temp_filename'], $d['future_filename'], $d['temp_extension']) . (isset($v['temp_actions']) ? str_replace('%lang%', $lang[2], $v['temp_actions']) : '') . '>' . $d['future_filename'] . '</a>'),
                                    'remove_temp' => ($required || empty($d['temp_filename']) ? '' : '<label class="remove-file" for="' . $kp . '_remove_temp"><input type="checkbox" id="' . $kp . '_remove_temp" name="' . $kp . '_remove_temp"' . (isset($v['remove_temp_actions']) ? str_replace('%lang%', $lang[2], $v['remove_temp_actions']) : '') . '>' . (isset($v['remove_temp_hint']) ? '<acronym title="' . htmlspecialchars($this->all->io->output($v['remove_temp_hint'], '%lang%', $lang[1])) . '">' : '') . $this->all->io->output(75) . (isset($v['remove_temp_hint']) ? '</acronym>' : '') . '</label>'),
                                );
                                if ($required && empty($d['existing_filename'])) {
                                    $js_params .=
                                        ($js_params == '' ? '' : ',') . $tabjs1 .
                                        "{'type':400,'nm':'" . $kp . "'" .
                                        ",'erreq':'" . addslashes($this->all->io->output($v['err_msg_req'], '%lang%', $lang[1])) . "'}";
                                }
                            }
                            break;
                        case 'own':
                            $values[$i]['tpl']['items'][$kl] = array(
                                'html' => str_replace('%lang%', $lang[2], $v['html']),
                            );
                            if (!empty($v['input_validation'])) {
                                $js_params .=
                                    ($js_params == '' ? '' : ',') . $tabjs1 .
                                    "{'type':204" .
                                    ",'vld':" . str_replace('%lang%', $lang[2], $v['input_validation']) . '}';
                            }
                            break;
                    }
                }
            }
        }
        return true;
    }

    function get_editor($name, $tag_params, $value)
    {
        if ($this->is_first_editor) {
            $this->is_first_editor = false;
            $this->add_js_file('/admin/ckeditor/ckeditor.js');
            $this->add_js_file('/admin/ckfinder/ckfinder.js');
            $this->add_js_bottom('editorInit();');
            $this->add_js_bottom(
                "winonload(\"setTimeout('VF()', " .
                strval(500 + count($this->all->io->lang_available_frontend) * 250) .
                ");\");"
            );
        }
        $this->add_js_bottom("editorAdd('" . $name . "');");
        return '<textarea class="editor" name="' . $name . '"' . $tag_params . '>' . $value . '</textarea>';
    }

    function add_js_file($filename)
    {
        if (!in_array($filename, $this->vjs_files)) {
            $this->vjs_files[] = $filename;
            return true;
        }
        return false;
    }

    function add_js_bottom($js)
    {
        $this->vjs_bottom .= ($this->vjs_bottom == '' ? '' : $this->nl(1)) . $js;
    }

    function add_js_global($k, $v)
    {
        $this->vjs_globals[strval($k)] = $v;
    }

    function file_js_popup($filename)
    {
        $e = $this->all->io->get_file_ext($filename);
        if (in_array($e, array('jpg', 'gif', 'png'))) {
            $s = @getimagesize($this->all->frontend_rw_dir . '/' . $filename);
            return (' onclick="return wo(\'' . $this->all->uri('preview/' . addslashes($filename)) . "'" . (is_array($s) ? ', ' . $s[0] . ', ' . $s[1] : '') . ')"');
        } else {
            return ('');
        }
    }

    function temp_file_js_popup($temp_filename, $future_filename, $ext)
    {
        if (in_array($ext, array('jpg', 'gif', 'png'))) {
            $s = @getimagesize($temp_filename);
            return (' onclick="return wo(\'' . $this->all->uri('temp/' . addslashes($future_filename)) . "'" . (is_array($s) ? ', ' . $s[0] . ', ' . $s[1] : '') . ')"');
        } else {
            return ('');
        }
    }

    function pagination_init(&$pagination, $items_per_page, $pages_count_max, $link_prefix, &$req, $query_id, $query_params = array(), $query_values = array())
    {
        $pagination = array(
            'page' => (isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1),
            'items_per_page' => $items_per_page,
            'pages_count_max' => $pages_count_max,
            'link_prefix' => $link_prefix,
        );
        if (!is_array($query_params)) {
            $query_params = array($query_params);
        }
        $query_params[] = '%lim%';
        if (!is_array($query_values)) {
            $query_values = array($query_values);
        }
        $query_values[] = strval(($pagination['page'] - 1) * $pagination['items_per_page']) . ', ' . $pagination['items_per_page'];
        $this->all->db->query($req, $query_id, $query_params, $query_values);
        if (!$this->all->db->num_rows($req) && $pagination['page'] > 1) {
            $pagination['page'] = 1;
            $this->all->db->free_result($req);
            $query_values[count($query_values) - 1] = $pagination['items_per_page'];
            $this->all->db->query($req, $query_id, $query_params, $query_values);
        }
        $this->all->db->query($reqc, 12);
        $resc = $this->all->db->fetch_row($reqc);
        $pagination['items_count'] = ($resc ? $resc[0] : 0);
        $this->all->db->free_result($reqc);
        $pagination['pages_count'] = ceil($pagination['items_count'] / $pagination['items_per_page'] - 0.0001);
        return $pagination['items_count'];
    }

    function smarty_init_after_login()
    {
        if (!$this->all->auth->logged_in) {
            return false;
        }
        $admin =& $this->all->auth->admin;
        $this->smarty->assign('admin_name', $this->all->io->user_longname($admin['fname'], $admin['sname'], $admin['lname'], 2));
        $this->smarty->assign('logout', array(
            'href' => $this->all->uri('logout/'),
            'title' => $this->all->io->output(29),
        ));
        $admin_info = array($this->all->io->output(201, '%type%', $this->all->io->output($admin['typeid'] == 10 ? 202 : ($admin['typeid'] == 20 ? 203 : 207))));
        if ($admin['lastin'] > 0) {
            $admin_info[] = $this->all->io->output(200, array('%date%', '%ip%'), array($this->all->io->date_ts2text($admin['lastin'], true, true), long2ip($admin['lastip'])));
        }
        $this->smarty->assign('admin_info', $admin_info);
    }

    function send_headers()
    {
        ob_start('ob_gzhandler');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
        header('Expires: ' . gmdate('D, d M Y H:i:s', 0) . ' GMT');
        header('Cache-Control: no-cache');
    }

    function tpl_spacer($params, &$smarty)
    {
        return $this->spacer((isset($params['width']) ? $params['width'] : 1), (isset($params['height']) ? $params['height'] : 1));
    }

    function spacer($width = 1, $height = 1)
    {
        return '<img src="img/s.png" width="' . $width . '" height="' . $height . '" />';
    }

    function options_ext($params, &$smarty)
    {
        return (empty($params['options']) ? '' : $this->_options_ext($params['options'], (isset($params['tab']) ? intval($params['tab']) : false), (isset($params['selected']) ? $params['selected'] : false)));
    }

    function _options_ext(&$optgroup, $tab = false, $selected_value = '', $level = 0)
    {
        $ret = '';
        if ($tab === false) {
            $tab0 = '';
            $tab1p = false;
        } else {
            $tab0 = $this->nl($tab);
            $tab1p = $tab + 1;
        }
        foreach ($optgroup as $item) {
            $ret .=
                (isset($item['value']) ?
                    ($ret == '' && $level == 0 ? '' : $tab0) . '<option' . (empty($item['params']) ? '' : $this->implode_tag_params($item['params'])) .
                    ' value="' . $item['value'] . '"' .
                    ($selected_value == $item['value'] || !empty($item['selected']) ? ' selected="selected"' : '') . '>' .
                    str_repeat('&nbsp; &nbsp; ', $level) . $item['title'] . '</option>' .
                    (empty($item['items']) ? '' : $this->_options_ext($item['items'], $tab1p, $selected_value, $level + 1))
                    :
                    ($ret == '' && $level == 0 ? '' : $tab0) . '<optgroup label="' . $item['title'] . '"' . (empty($item['params']) ? '' : $this->implode_tag_params($item['params'])) . '>' .
                    (empty($item['items']) ? '' : $this->_options_ext($item['items'], $tab1p, $selected_value, $level + 1)) .
                    $tab0 . '</optgroup>');
        }
        return $ret;
    }

    function implode_tag_params(&$params)
    {
        $ret = '';
        foreach ($params as $k => $v) {
            $ret .= ' ' . $k . '="' . $v . '"';
        }
        return $ret;
    }

    function radio_options_ext($params, &$smarty)
    {
        return (empty($params['options']) || empty($params['name']) ? '' : $this->_radio_options_ext($params['options'], $c, $params['name'], (isset($params['tab']) ? intval($params['tab']) : false), (isset($params['selected']) ? $params['selected'] : false), (isset($params['multiline']) ? $params['multiline'] : false), (isset($params['actions']) ? $params['actions'] : '')));
    }

    function _radio_options_ext(&$optgroup, &$counter, $name, $tab = false, $selected_value = '', $multiline = false, $actions = '', $level = 0)
    {
        $ret = '';
        if ($tab === false) {
            $tab0 = '';
            $tab1p = false;
        } else {
            $tab0 = $this->nl($tab);
            $tab1p = $tab + 1;
        }
        if (empty($counter)) {
            $counter = 0;
        }
        foreach ($optgroup as $item) {
            ++$counter;
            $ret .=
                (isset($item['value']) ?
                    ($counter == 1 ? '' : ($multiline ? $tab0 . str_repeat('&nbsp; &nbsp; ', $level) : ' &nbsp; ' . $tab0)) .
                    '<label' . $this->implode_tag_params_intersect((isset($item['params']) ? $item['params'] : array()), ($multiline ? array('class' => array('multiline', 0)) : array())) . ' for="' . $name . '_' . $counter . '"> ' .
                    '<input class="radio" type="radio" id="' . $name . '_' . $counter . '" name="' . $name . '" value="' . $item['value'] . '"' . ($selected_value == $item['value'] || !empty($item['selected']) ? ' checked="checked"' : '') . $actions . ' /> ' .
                    (isset($item['hint']) ? '<acronym title="' . htmlspecialchars($item['hint']) . '">' . $item['title'] . '</acronym>' : $item['title']) .
                    '</label>' : '') .
                (empty($item['items']) ? '' : $this->_radio_options_ext($item['items'], $counter, $name, $tab1p, $selected_value, $multiline, $actions, $level + 1));
        }
        return $ret;
    }

    function implode_tag_params_intersect($params, $intersect)
    {
        $ret = '';
        foreach ($params as $k => $v) {
            $ret .= ' ' . $k . '="';
            if (isset($intersect[$k])) {
                switch ($intersect[$k][1]) {
                    case 0:
                        $ret .= $v . ' ' . $intersect[$k][0];
                        break;
                    case 1:
                        $ret .= $intersect[$k][0] . ' ' . $v;
                        break;
                }
            } else {
                $ret .= $v;
            }
            $ret .= '"';
        }
        foreach ($intersect as $k => $v) {
            if (!isset($params[$k])) {
                $ret .= ' ' . $k . '="' . $v[0] . '"';
            }
        }
        return $ret;
    }

    function pane($params, $content, &$smarty, &$repeat)
    {
        if (isset($content)) {
            $tab = (isset($params['tab']) ? intval($params['tab']) : 0);
            $tab0 = str_repeat("\t", $tab);
            $nl = $this->nl();
            $tab1 = $this->nl($tab + 1);
            $tab2 = $this->nl($tab + 2);
            $tab3 = $this->nl($tab + 3);
            if (empty($params['params'])) {
                $paneParams = '';
            } else {
                if (!$this->str2array($a, $params['params'])) {
                    return $this->all->io->output(24);
                }
                $paneParams = $this->implode_tag_params($a);
            }
            return
                $tab0 . '<div' . $paneParams . '>' .
                (empty($params['title']) ? '' : $tab1 . '<h2 class="pheader">' . $params['title'] . '</h2>') .
                $tab1 . '<div class="cc1">' .
                ($content == '' ? '&nbsp;' : ($content{0} == "\r" || $content{0} == "\n" ? '' : $nl) . $content . str_repeat("\t", $tab + 1)) .
                '</div>' .
                $nl . $tab0 . '</div>' . $nl;
        }
    }

    function str2array(&$a, &$s, $i = 0, $r = false)
    {
        $a = array();
        $sc = false;
        $ss = false;
        $so = '';
        $sd = true;
        $sv = false;
        $k = '';
        $v = '';
        for ($c = strlen($s); $i < $c; $i++) {
            if ($ss) {
                if ($s{$i} == '\\') {
                    if ($sc) {
                        ${($sv ? 'v' : 'k')} .= '\\';
                    }
                    $sc = !$sc;
                } else if ($s{$i} == $so) {
                    if ($sc) {
                        ${($sv ? 'v' : 'k')} .= $s{$i};
                        $sc = false;
                    } else {
                        if ($sv) {
                            $a[$k] = $v;
                            $k = '';
                            $v = '';
                        } else if ($k == '') {
                            return false;
                        }
                        $ss = false;
                        $sv = !$sv;
                    }
                } else {
                    ${($sv ? 'v' : 'k')} .= $s{$i};
                }
            } else if ($s{$i} == "'" || $s{$i} == '"') {
                if ($sd) {
                    $sd = false;
                    $ss = true;
                    $so = $s{$i};
                } else {
                    return false;
                }
            } else if (!$sd && $s{$i} == ($sv ? ':' : ',')) {
                $sd = true;
            } else if (!$sd && $sv && $s{$i} == ',') {
                $sd = true;
                $sv = false;
                $a[] = $k;
                $k = '';
            } else if ($s{$i} == '[') {
                if (!$sd) {
                    return false;
                }
                $sd = false;
                if (!$sv) {
                    $k = count($a);
                }
                $a[$k] = array();
                $i = $this->str2array($a[$k], $s, $i + 1, true);
                if ($i === false) {
                    return false;
                }
                $k = '';
                $sv = false;
            } else if (!$sv && $s{$i} == ']') {
                return ($r ? $i : false);
            } else if (!in_array($s{$i}, array(' ', "\n", "\r", "\t"))) {
                return false;
            }
        }
        if (!$sd && $sv && !$ss) {
            $a[] = $k;
        }
        return true;
    }

    function tree($params, &$smarty)
    {
        if (empty($params['cols']) || !$this->str2array($cols, $params['cols']) || empty($params['values'])) {
            return '';
        }
        $tab = (isset($params['tab']) ? intval($params['tab']) : 0);
        $tab0 = $this->nl($tab);
        $tab1 = $this->nl($tab + 1);
        $tab2 = $this->nl($tab + 2);
        $ret = '';
        $s = '';
        $this->tree_pair = false;
        $h = (empty($params['headers']) ? 10 : 1);
        foreach ($cols as $v) {
            $s .= $tab2 . '<th width="' . $v . '">' . $this->spacer(1, $h) . '</th>';
        }
        $ret .= ($s == '' ? '' : $tab1 . '<tr>' . $s . $tab1 . '</tr>');
        if (!empty($params['headers'])) {
            if (is_array($params['headers'])) {
                $cheader = array_reverse($params['headers'], true);
                $c = count($cols);
                $s = '';
                foreach ($cheader as $k => $v) {
                    $i = $c - intval($k);
                    $c = intval($k);
                    $t = (is_string($v['caption']) ? $v['caption'] : $this->all->io->output($v['caption']));
                    $s = $tab2 . '<td' . ($i > 1 ? ' colspan="' . $i . '"' : '') . '><h2>' . (isset($v['hint']) ? '<acronym title="' . htmlspecialchars((is_string($v['hint']) ? $v['hint'] : $this->all->io->output($v['hint']))) . '">' . $t . '</acronym>' : $t) . '</h2></td>' . $s;
                }
                $ret .= ($s == '' ? '' : $tab1 . '<tr class="header">' . ($c > 0 ? $tab2 . '<td' . ($c > 1 ? ' colspan="' . $c . '"' : '') . '>' . $this->spacer() . '</td>' : '') . $s . $tab1 . '</tr>');
            } else {
                $ret .= $params['headers'];
            }
        }
        $s = $this->_tree_items($params['values'], count($cols), $tab + 1);
        return ($s == '' ? '' : $tab0 . '<table class="list" id="tablelist">' . $ret . $s . $tab0 . '</table>');
    }

    function _tree_items(&$ctree, $cols, $tab = 0, $level = 0)
    {
        $tab0 = $this->nl($tab);
        $tab1 = $this->nl($tab + 1);
        $ret = '';
        foreach ($ctree as $row) {
            $c = $cols;
            $s = '';
            $row['fields'] = array_reverse($row['fields'], true);
            foreach ($row['fields'] as $k => $v) {
                $i = $c - intval($k);
                $c = intval($k);
                if (!isset($v['type'])) {
                    $v['type'] = 'link';
                }
                $own_href = isset($v['href']);
                if (!$own_href) {
                    $v['href'] = (isset($row['href']) ? $row['href'] : '#');
                }
                if (!isset($v['alt'])) {
                    $v['alt'] = false;
                }
                switch ($v['type']) {
                    case 'link':
                        if (isset($v['is_header']) && $v['is_header']) {
                            $h_o = '<h2>';
                            $h_c = '</h2>';
                        } else {
                            $h_o = '';
                            $h_c = '';
                        }
                        $td_params = '';
                        $td_class = 'pointer' . ($c == 0 && $level > 0 ? ' li' . $level : '');
                        if (isset($v['col_params']) && is_array($v['col_params'])) {
                            $v['col_params']['class'] = $td_class . (isset($v['col_params']['class']) ? ' ' . $v['col_params']['class'] : '');
                            $td_params .= $this->implode_tag_params($v['col_params']);
                        } else {
                            $td_params .= ' class="' . $td_class . '"';
                        }
                        if (isset($v['popup_file'])) {
                            $cl = $this->file_js_popup($v['popup_file']);
                            $td_params .= $cl;
                            $params = ' href="' . $this->all->uri('preview/' . $v['popup_file']) . '" target="blank"' . $cl;
                        } else {
                            $td_params .= (empty($v['noonclick']) ? ' onclick="cl(\'' . $v['href'] . '\')"' : '');
                            $params = ' href="' . $v['href'] . '"';
                        }
                        if (isset($v['link_params']) && is_array($v['link_params'])) {
                            $params .= $this->implode_tag_params($v['link_params']);
                        }
                        $s =
                            $tab1 . '<td' . $td_params . ($i > 1 ? ' colspan="' . $i . '"' : '') . '>' .
                            (isset($v['prefix']) ? $v['prefix'] : '') .
                            $h_o . '<a' . (isset($v['hint']) ? ' title="' . htmlspecialchars((is_string($v['hint']) ? $v['hint'] : $this->all->io->output($v['hint']))) . '"' : '') . $params . '>' .
                            (is_string($v['caption']) ? $v['caption'] : $this->all->io->output($v['caption'])) . '</a>' .
                            $h_c . '</td>' . $s;
                        break;
                    case 'text':
                        if (isset($v['is_header']) && $v['is_header']) {
                            $h_o = '<h2>';
                            $h_c = '</h2>';
                        } else {
                            $h_o = '';
                            $h_c = '';
                        }
                        if (isset($v['hint'])) {
                            $h_o .= '<acronym title="' . htmlspecialchars((is_string($v['hint']) ? $v['hint'] : $this->all->io->output($v['hint']))) . '">';
                            $h_c = '</acronym>' . $h_c;
                        }
                        $td_params = '';
                        $td_class = ($c == 0 && $level > 0 ? 'li' . $level : '');
                        if (isset($v['col_params']) && is_array($v['col_params'])) {
                            $v['col_params']['class'] = $td_class . (isset($v['col_params']['class']) ? ($td_class == '' ? '' : ' ') . $v['col_params']['class'] : '');
                            $td_params .= $this->implode_tag_params($v['col_params']);
                        } else if ($td_class != '') {
                            $td_params .= ' class="' . $td_class . '"';
                        }
                        $s =
                            $tab1 . '<td' . $td_params . ($i > 1 ? ' colspan="' . $i . '"' : '') . '>' .
                            (isset($v['prefix']) ? $v['prefix'] : '') .
                            $h_o . (is_string($v['caption']) ? $v['caption'] : $this->all->io->output($v['caption'])) .
                            $h_c . '</td>' . $s;
                        break;
                    case 'remove_btn':
                        $s =
                            $tab1 . '<td' . ($i > 1 ? ' colspan="' . $i . '"' : '') . '>' .
                            $this->button_remove($v['href'] . ($own_href ? '' : 'remove/'), $v['alt'], (isset($v['conf_text']) ? $v['conf_text'] : false)) .
                            '</td>' . $s;
                        break;
                    case 'up_btn':
                        $s =
                            $tab1 . '<td' . ($i > 1 ? ' colspan="' . $i . '"' : '') . '>' .
                            $this->button_up($v['href'] . ($own_href ? '' : 'up/'), $v['alt']) .
                            '</td>' . $s;
                        break;
                    case 'down_btn':
                        $s =
                            $tab1 . '<td' . ($i > 1 ? ' colspan="' . $i . '"' : '') . '>' .
                            $this->button_down($v['href'] . ($own_href ? '' : 'down/'), $v['alt']) .
                            '</td>' . $s;
                        break;
                }
            }
            if (isset($row['row_params']) && is_array($row['row_params'])) {
                if ($this->tree_pair = !$this->tree_pair) {
                    $row['row_params']['class'] = 'pair' . (isset($row['row_params']['class']) ? ' ' . $row['row_params']['class'] : '');
                }
                $tr_params = $this->implode_tag_params($row['row_params']);
            } else {
                $tr_params = (($this->tree_pair = !$this->tree_pair) ? ' class="pair"' : '');
            }
            $ret .=
                ($s == '' ? '' :
                    $tab0 . '<tr' . $tr_params . (empty($row['nomouseover']) ? ' onmouseover="ro(this)" onmouseout="ru(this)"' : '') . '>' .
                    ($c > 0 ? $tab1 . '<td' . ($c > 1 ? ' colspan="' . $c . '"' : '') . '>' . $this->spacer() . '</td>' : '') .
                    $s .
                    $tab0 . '</tr>');
            if (isset($row['items'])) {
                $ret .= $this->_tree_items($row['items'], $cols, $tab, $level + 1);
            }
        }
        return $ret;
    }

    function button_remove($href, $alt = false, $conf_text = false, $no_htmlspecialchars = false)
    {
        return '<a title="' . ($alt === false ? htmlspecialchars($this->all->io->output(25)) : ($no_htmlspecialchars ? $alt : htmlspecialchars($alt))) . '" class="button remove" href="' . $href . '" onclick="return rmc(' . ($conf_text === false ? '' : "'" . ($no_htmlspecialchars ? $conf_text : htmlspecialchars($conf_text)) . "'") . ')">&nbsp;</a>';
    }

    function button_up($href, $alt = false, $no_htmlspecialchars = false)
    {
        return '<a title="' . ($alt === false ? htmlspecialchars($this->all->io->output(33)) : ($no_htmlspecialchars ? $alt : htmlspecialchars($alt))) . '" class="button up" href="' . $href . '">&nbsp;</a>';
    }

    function button_down($href, $alt = false, $no_htmlspecialchars = false)
    {
        return '<a title="' . ($alt === false ? htmlspecialchars($this->all->io->output(34)) : ($no_htmlspecialchars ? $alt : htmlspecialchars($alt))) . '" class="button down" href="' . $href . '">&nbsp;</a>';
    }

    function sys_alerts($params, &$smarty)
    {
        $ret = '';
        $tab = (isset($params['tab']) ? intval($params['tab']) : 0);
        $tab0 = $this->nl($tab);
        foreach ($this->vsys_alerts as $alert) {
            $ret .= $tab0 . '<div class="alert-' . $alert['type'] . '">' . $alert['alert'] . '</div>';
        }
        return $ret;
    }

    function menu_left($params, &$smarty)
    {
        $tab = (isset($params['tab']) ? intval($params['tab']) : 0);
        return $this->_menu_tree_node($this->all->map->tree_left, $tab, 1);
    }

    function _menu_tree_node(&$tree_node, $tab = 0, $add_submenu_tab = 0, $parent_path = '', $level = 0)
    {
        $ret = '';
        $tab0 = $this->nl($level == 0 ? $tab : $tab + $add_submenu_tab);
        $is_spacer = false;
        foreach ($tree_node as $k => $v) {
            if ($this->all->auth->check_permissions($v['permissions'])) {
                if (isset($v['menu_item_type']) && $v['menu_item_type'] == 1) {
                    $ret .= $tab0 . '<span class="vr">&nbsp;</span>';
                    $is_spacer = true;
                } else if (isset($v['menu_item_type']) && $v['menu_item_type'] == 2) {
                    $ret .=
                        ($is_spacer || $level == 0 || $ret == '' ? '' : $tab0 . '<span class="vr">&nbsp;</span>') .
                        $tab0 . '<span>' . $v['menu_title'] . '</span>';
                    $is_spacer = false;
                } else if (isset($v['menu_title'])) {
                    $ret .=
                        ($is_spacer || $level == 0 || $ret == '' ? '' : $tab0 . '<span class="vr">&nbsp;</span>') .
                        $tab0 . (isset($v['menu_row_params']) ? '<span' . $this->implode_tag_params($v['menu_row_params']) . '>' : '') .
                        '<a' . $this->implode_tag_params_intersect((isset($v['menu_link_params']) ? $v['menu_link_params'] : array()), (empty($v['menu_active']) ? array() : array('class' => array('active', 0)))) .
                        ' href="' . (isset($v['menu_url']) ? $v['menu_url'] : $this->all->uri($parent_path . $k . '/')) . '">' .
                        (is_string($v['menu_title']) ? $v['menu_title'] : $this->all->io->output($v['menu_title'])) . '</a>' .
                        (isset($v['menu_row_params']) ? '</span>' : '');
                    $is_spacer = false;
                    if (isset($v['items'])) {
                        $items = $this->_menu_tree_node($v['items'], ($level == 0 ? $tab - 1 : $tab + 1), $add_submenu_tab, $parent_path . $k . '/', $level + 1);
                        if ($items != '') {
                            if ($level == 0) {
                                $this->submenu .= $items;
                            } else {
                                $ret .= $tab0 . '<div>' . $items . $tab0 . '</div>';
                                $is_spacer = true;
                            }
                        }
                    }
                }
            }
        }
        return $ret;
    }

    function menu_right($params, &$smarty)
    {
        $tab = (isset($params['tab']) ? intval($params['tab']) : 0);
        return $this->_menu_tree_node($this->all->map->tree_right, $tab);
    }

    function pagination_pane($params, &$smarty)
    {
        if (!isset($params['pagination']) || $params['pagination']['pages_count'] < 2) {
            return '';
        }
        $tab = (isset($params['tab']) ? intval($params['tab']) : 0);
        $pagination =& $params['pagination'];
        $tab0 = $this->nl($tab);
        $tab1 = $this->nl($tab + 1);
        $ret = '';
        $i = max(1, $pagination['page'] - floor(($pagination['pages_count_max'] - 1) / 2));
        $c = min($pagination['pages_count'] + 1, $i + $pagination['pages_count_max']);
        if ($c > $pagination['pages_count']) {
            $i = max(1, $pagination['pages_count'] - $pagination['pages_count_max'] + 1);
        }
        $ret .= $tab1 . ($pagination['page'] > 1 ? '<a class="prev" href="' . $this->all->uri($pagination['link_prefix'], 1, 'page', $pagination['page'] - 1) . '">' . $this->all->io->output(73) . '</a>' : '<span>' . $this->all->io->output(79) . '</span>');
        for (; $i < $c; $i++) {
            $ret .= $tab1 . ($i == $pagination['page'] ? '<b>' . $i . '</b>' : '<a href="' . $this->all->uri($pagination['link_prefix'], 1, 'page', $i) . '">' . $i . '</a>');
        }
        if ($pagination['page'] < $pagination['pages_count']) {
            $ret .= $tab1 . '<a class="next" href="' . $this->all->uri($pagination['link_prefix'], 1, 'page', $pagination['page'] + 1) . '">' . $this->all->io->output(74) . '</a>';
        }
        return $tab0 . '<div class="pagination">' . $ret . $tab0 . '</div>';
    }

}

?>