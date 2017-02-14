<?php

/*
*/

class cImg
{
    var
        $all,
        $action,
        $table_name = 'img',
        $db_ident,
        $readonly = false,
        $counts = array(
        'default' => 10,
        'required' => 0,
    ),
        $items = array(
        'ismark' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'lastchangets' => array(
            'type' => 'integer',
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'lastchangeadminid' => array(
            'type' => 'integer',
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'srvnotes' => array(
            'type' => 'string',
            'add_only' => true,
            'default' => '',
            'input_format' => 4,
            'input_html' => '',
        ),
        'ownerid' => array(
            'type' => 'integer',
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'ord' => array(
            'type' => 'integer',
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'img_l' => array(
            'type' => 'file',
            'default_ignore' => 3006,
            'input_size' => 45,
            'input_caption' => 3000,
            'input_hint' => 3001,
            'browse_hint' => 3002,
            'clear_hint' => 3003,
            'existing_hint' => 3004,
            'temp_hint' => 3005,
            'remove_actions' => ' onclick="imgLRemoveClick(this)"',
            'remove_temp_actions' => ' onclick="imgLRemoveClick(this)"',
        ),
        'stamp_img_l' => array(
            'type' => 'integer',
            'in_db' => false,
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 3007,
            'input_hint' => 3008,
        ),
        'img_s' => array(
            'type' => 'file',
            'default_ignore' => 3016,
            'input_size' => 45,
            'input_caption' => 3010,
            'input_hint' => 3011,
            'browse_hint' => 3012,
            'clear_hint' => 3013,
            'existing_hint' => 3014,
            'temp_hint' => 3015,
            'remove_actions' => ' onclick="imgSRemoveClick(this)"',
        ),
        'stamp_img_s' => array(
            'type' => 'integer',
            'in_db' => false,
            'default' => 0,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 3017,
            'input_hint' => 3018,
        ),
        'make_main_img' => array(
            'type' => 'integer',
            'in_db' => false,
            'default' => 0,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 3024,
            'input_hint' => 3025,
            'input_actions' => ' onclick="makeMainImg(this)"',
        ),
        'title' => array(
            'type' => 'string',
            'multilingual' => true,
            'default' => '',
            'maxlength' => 500,
            'proc_strip_tags' => '',
            'err_msg_mask' => 3022,
            'input_size' => 65,
            'input_caption' => 3020,
            'input_hint' => 3021,
        ),
    ),
        $messages = array(),
        $owner,
        $make_main_img = false,
        $main_img = false,
        $images = array(
        array(
            'key' => 'l',
            'size_alert' => 3009,
            'allow_crop' => false,
        ),
        array(
            'key' => 's',
            'size_alert' => 3019,
            'allow_crop' => true,
        ),
    );

    function cImg(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
    {
        $this->all =& $all;
        $this->owner =& $parent_objects[0];
        ini_set('max_execution_time', $this->owner->max_execution_time);
        $this->table_name = $params['table_name'];
        if (isset($params['main_img'])) {
            $this->make_main_img = true;
            $this->items['make_main_img']['input_caption'] = $params['main_img']['input_caption'];
            $this->items['make_main_img']['input_hint'] = $params['main_img']['input_hint'];
        } else {
            unset($this->items['make_main_img']);
        }
        $this->all->io->before_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        $this->action = $this->owner->action;
        if (isset($this->all->map->params['id'])) {
            $this->db_ident = "`ownerid` = '" . intval($this->all->map->params['id']) . "' ORDER BY `ord`";
        }
        $this->all->io->after_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        $this->items['img_l']['max_file_size'] = $this->owner->img_l['max_file_size'];
        $this->items['img_l']['allowed_filetypes'] = $this->owner->img_l['allowed_filetypes'];
        $this->items['img_l']['filename_prefix'] = $this->owner->img_l['filename_prefix'];
        $this->items['img_s']['max_file_size'] = $this->owner->img_s['max_file_size'];
        $this->items['img_s']['allowed_filetypes'] = $this->owner->img_s['allowed_filetypes'];
        $this->items['img_s']['filename_prefix'] = $this->owner->img_s['filename_prefix'];
        $this->items['lastchangets']['default'] = time();
        $this->items['lastchangeadminid']['default'] = $this->all->auth->admin['id'];
    }

    function after_init(&$critical_error, &$alerts, $display_errors, $sent, $reset, $parent_values)
    {
        if (!$sent) {
            return true;
        }
        $values =& $parent_values[0]['items']['img'];
        for ($i = 0; $i < $this->counts['current']; $i++) {
            $valitm =& $values[$i]['items'];
            foreach ($this->images as $image) {
                if (isset($valitm['img_' . $image['key']]['temp_filename']) && (!($s = getimagesize($valitm['img_' . $image['key']]['temp_filename'])) || $s[0] < $this->owner->{'img_' . $image['key']}['min_width'] || $s[1] < $this->owner->{'img_' . $image['key']}['min_height'] || ($k = $s[0] / $s[1]) > $this->owner->{'img_' . $image['key']}['max_width'] / $this->owner->{'img_' . $image['key']}['min_height'] || $k < $this->owner->{'img_' . $image['key']}['min_width'] / $this->owner->{'img_' . $image['key']}['max_height'])) {
                    $critical_error = true;
                    if ($display_errors) {
                        $alerts[] = array(
                            'type' => 'err_msg_mask',
                            'params' => array('warning', $image['size_alert'], array('%minw%', '%maxw%', '%minh%', '%maxh%'), array($this->owner->{'img_' . $image['key']}['min_width'], $this->owner->{'img_' . $image['key']}['max_width'], $this->owner->{'img_' . $image['key']}['min_height'], $this->owner->{'img_' . $image['key']}['max_height'])),
                        );
                    }
                }
            }
            if ($critical_error) {
                break;
            }
            $temp_files = false;
            for ($j = 1; $j < count($this->images); $j++) {
                $imgsk = 'img_' . $this->images[$j]['key'];
                if (!isset($valitm[$imgsk]['temp_filename'])) {
                    for ($k = 0; $k < $j; $k++) {
                        $imglk = 'img_' . $this->images[$k]['key'];
                        if (isset($valitm[$imglk]['temp_filename'])) {
                            $tmp = tempnam($this->all->tmp_dir, $this->owner->img_all_filename_prefix . $this->images[$j]['key'] . '_');
                            if (!copy($valitm[$imglk]['temp_filename'], $tmp)) {
                                return false;
                            }
                            $orig = preg_replace('/(?:_\w{1,2})?(\.\w{1,4})$/', '_' . $this->images[$j]['key'] . '$1', $valitm[$imglk]['temp_orig_filename']);
                            $ext = $valitm[$imglk]['temp_extension'];
                            $valitm[$imgsk]['temp_orig_filename'] = $orig;
                            $valitm[$imgsk]['temp_filename'] = $tmp;
                            $valitm[$imgsk]['temp_extension'] = $ext;
                            $valitm[$imgsk]['future_filename'] = $this->all->io->temp_file_add_update($this->owner->img_all_filename_prefix . $this->images[$j]['key'] . '_', $orig, $tmp, $ext, $this->all->frontend_rw_dir . '/', $temp_file_id);
                            $valitm[$imgsk]['temp_file_id'] = $temp_file_id;
                            if (!is_array($temp_files)) {
                                $temp_files = $this->all->session_get_var('temp_files', false);
                                if ($temp_files !== false) {
                                    $temp_files = unserialize($temp_files);
                                }
                                if (!is_array($temp_files)) {
                                    $temp_files = array();
                                }
                            }
                            $temp_files['img_0_' . $imgsk . '_' . $i] = $temp_file_id;
                            $temp_file_id = 0;
                            break;
                        }
                    }
                }
            }
            if ($this->make_main_img && $valitm['make_main_img'] == 1) {
                foreach ($this->images as $image) {
                    if (isset($valitm['img_' . $image['key']]['temp_filename']) || isset($valitm['img_' . $image['key']]['existing_filename'])) {
                        $this->main_img = $valitm['img_' . $image['key']];
                        break;
                    }
                }
            }
            if (is_array($temp_files)) {
                $this->all->session_set_var('temp_files', serialize($temp_files));
            }
            if (empty($valitm['img_s']['temp_filename']) && (empty($valitm['img_s']['existing_filename']) || !empty($valitm['img_s']['existing_remove']))) {
                $values[$i]['is_ready4db'] = false;
            }
        }
        return true;
    }

    function before_add(&$values_i, &$remove_on_fail, &$remove_on_success)
    {
        $values_i['items']['ownerid'] = $values_i['parent_values'][0]['id'];
        return true;
    }

    function before_edit(&$values_i, &$remove_on_fail, &$remove_on_success)
    {
        $values_i['items']['ownerid'] = $values_i['parent_values'][0]['id'];
        return true;
    }

    function after_add(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        return $this->resize_image($values_i, $remove_on_fail, $remove_on_success, $succeed);
    }

    function resize_image(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if (!$succeed) {
            return true;
        }
        $valitm =& $values_i['items'];
        foreach ($this->images as $v) {
            $k = $v['key'];
            if (isset($valitm['img_' . $k]['temp_filename'])) {
                if (!($s = getimagesize($valitm['img_' . $k]['temp_filename']))) {
                    return false;
                }
                if (!($valitm['stamp_img_' . $k] == 1 || $s[0] < $this->owner->{'img_' . $k}['min_width'] || $s[0] > $this->owner->{'img_' . $k}['max_width'] || $s[1] < $this->owner->{'img_' . $k}['min_height'] || $s[1] > $this->owner->{'img_' . $k}['max_height'])) {
                    continue;
                }
                $m = min($this->owner->{'img_' . $k}['max_width'] / $s[0], $this->owner->{'img_' . $k}['max_height'] / $s[1]);
                $img_new = array(
                    array(
                        'width' => ($m < 1 ? ($v['allow_crop'] ? max(floor($s[0] * $m), $this->owner->{'img_' . $k}['min_width']) : floor($s[0] * $m)) : $s[0]),
                        'height' => ($m < 1 ? ($v['allow_crop'] ? max(floor($s[1] * $m), $this->owner->{'img_' . $k}['min_height']) : floor($s[1] * $m)) : $s[1]),
                        'quality' => $this->owner->{'img_' . $k}['quality'],
                    ),
                );
                if ($valitm['stamp_img_' . $k] == 1) {
                    $img_new[0]['stamp_file'] = $this->owner->{'img_' . $k}['stamp_filename'];
                    $img_new[0]['stamp_align'] = $this->owner->{'img_' . $k}['stamp_align'];
                    $img_new[0]['stamp_padding'] = $this->owner->{'img_' . $k}['stamp_padding'];
                }
                $res = $this->all->io->resize_image($valitm['img_' . $k]['temp_filename'], $valitm['img_' . $k]['temp_extension'], $this->owner->img_all_filename_prefix . $k . '_', false, $img_new, $ddd);
                if ($res) {
                    $remove_on_fail[] = array('filename' => $res[0]);
                }
                if (!($res && copy($res[0], $this->all->frontend_rw_dir . '/' . $valitm['img_' . $k]['future_filename']))) {
                    return false;
                }
                $remove_on_success[] = array('filename' => $res[0]);
            }
        }
        return true;
    }

    function after_edit(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        return $this->resize_image($values_i, $remove_on_fail, $remove_on_success, $succeed);
    }

    function output_form()
    {
        $smarty =& $this->all->tpl->smarty;
        $this->all->tpl->add_js_file('js/img.js');
        $smarty->assign('img_form_counts', $this->counts['tpl']['current']);
        $img_fields = array();
        $js_items = '';
        $img_visible = 0;
        for ($i = 0, $c = count($this->parent_objects[0]->values[0]['items']['img']); $i < $c; $i++) {
            $values =& $this->parent_objects[0]->values[0]['items']['img'][$i];
            $valitm =& $values['items'];
            $valtpl =& $values['tpl']['items'];
            $exfl = (
            $valtpl['img_l']['existing_file'] == '' && $valtpl['img_l']['temp_file'] == '' &&
            $valtpl['img_s']['existing_file'] == '' && $valtpl['img_s']['temp_file'] == '' ?
                false : true
            );
            $valtpl['number'] = $i + 1;
            $img_fields[] =& $valtpl;
            $js_items .= ($js_items == '' ? '' : ', ') . ($exfl ? '1' : '0');
            if ($exfl) {
                ++$img_visible;
            }
        }
        for ($i = $img_visible; $i < $this->owner->img_visible_count; $i++) {
            if (($j = strpos($js_items, '0')) !== false) {
                $js_items{$j} = '1';
            }
        }
        $this->all->tpl->add_js_global('show_img_fields', '[' . $js_items . ']');
        $img_title_keys = array();
        $js_lang_available = '';
        foreach ($this->all->io->lang_available_frontend as $k => $v) {
            $img_title_keys[] = 'title_' . $k;
            $js_lang_available .= ($js_lang_available == '' ? "'" : ", '") . $k . "'";
        }
        $this->all->tpl->add_js_global('lang_available', '[' . $js_lang_available . ']');
        $this->all->tpl->add_js_bottom('imgCreate();');
        if ($this->make_main_img) {
            $this->all->tpl->add_js_bottom('makeMainImg();');
        }
        $smarty->assign('img_title_keys', $img_title_keys);
        $smarty->assign('add_img_link_title', $this->all->io->output(3023));
        $smarty->assign('img_fields', $img_fields);
    }

}

?>