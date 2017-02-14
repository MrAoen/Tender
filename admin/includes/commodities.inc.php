<?php

/*
*/

class cCommodities
{
    var
        $all,
        $action,
        $table_name = 'commodities',
        $db_ident,
        $readonly = false,
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
        'isfolder' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 1,
            'input_format' => 4,
            'input_html' => '',
        ),
        'haschilds' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'parentid' => array(
            'type' => 'integer',
            'required' => true,
            'default' => 0,
            'options' => array(),
            'err_msg_req' => 5509,
            'input_format' => 1,
            'input_caption' => 5507,
            'input_hint' => 5508,
            'input_actions' => ' onchange="parentChange()"',
        ),
        'ord' => array(
            'type' => 'integer',
            'required' => true,
            'default' => 0,
            'options' => array(),
            'match_options' => false,
            'err_msg_req' => 5499,
            'input_format' => 1,
            'input_caption' => 5497,
            'input_hint' => 5498,
        ),
        'level' => array(
            'type' => 'integer',
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'ispublished' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 5503,
            'input_hint' => 5504,
        ),
        'publishchilds' => array(
            'type' => 'integer',
            'in_db' => false,
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 5572,
            'input_hint' => 5573,
        ),
        'isprotected' => array(
            'type' => 'integer',
            'default' => 0,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 5408,
            'input_hint' => 5409,
        ),
        'title' => array(
            'type' => 'string',
            'required' => true,
            'multilingual' => true,
            'default' => '',
            'maxlength' => 500,
            'proc_strip_tags' => '',
            'err_msg_req' => 5429,
            'input_size' => 65,
            'input_caption' => 5427,
            'input_hint' => 5428,
        ),
        'keywords' => array(
            'type' => 'string',
            'multilingual' => true,
            'default' => '',
            'maxlength' => 1000,
            'proc_strip_tags' => '',
            'err_msg_req' => 5432,
            'input_size' => 65,
            'input_caption' => 5430,
            'input_hint' => 5431,
        ),
        'description' => array(
            'type' => 'string',
            'multilingual' => true,
            'default' => '',
            'maxlength' => 500,
            'proc_strip_tags' => '',
            'err_msg_req' => 5435,
            'input_size' => 65,
            'input_caption' => 5433,
            'input_hint' => 5434,
        ),
        'body' => array(
            'type' => 'string',
            'multilingual' => true,
            'default' => '',
            'maxlength' => 1000000,
            'err_msg_req' => 5441,
            'err_msg_length' => 5442,
            'input_format' => 7,
            'input_size' => 80,
            'input_rows' => 15,
            'input_caption' => 5439,
            'input_hint' => 5440,
        ),
        'url' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 30,
            'mask' => '/^[a-z_\-0-9]+$/',
            'err_msg_req' => 5491,
            'err_msg_mask' => 5492,
            'input_size' => 65,
            'input_caption' => 5489,
            'input_hint' => 5490,
        ),
        'srvnotes' => array(
            'type' => 'string',
            'add_only' => true,
            'default' => '',
            'input_format' => 4,
            'input_html' => '',
        ),
    ),
        $messages = array(
        'add_succeed' => 5460,
        'add_failed' => 5461,
        'edit_succeed' => 5462,
        'remove_succeed' => 5463,
        'remove_failed' => 5464,
    ),
        $parentid,
        $isfolder = 1,
        $id = 0,
        $max_execution_time,
        $commodities_per_page,
        $pages_count_max;

    function cCommodities(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
    {
        $this->all =& $all;
        $this->all->finclude('config/commodities.cfg.php', $this);
        $this->all->io->before_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        if (isset($this->all->map->params['action'])) {
            $this->action = $this->all->map->params['action'];
        }
        if (isset($this->all->map->params['id'])) {
            $this->id = $this->all->map->params['id'] = intval($this->all->map->params['id']);
            if (!isset($this->action)) {
                $this->action = 'edit';
            }
            $this->db_ident = "`id` = '" . $this->id . "'";
        }
        $this->all->io->after_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        $this->parentid = (isset($this->all->map->items['commodity']) ? $this->all->map->items['commodity']['parentid'] : 0);
        if ($this->action != 'remove') {
            $this->items['lastchangets']['default'] = time();
            $this->items['lastchangeadminid']['default'] = $this->all->auth->admin['id'];
            if ($this->isfolder) {
                $item_titles = array(
                    'parentid' => array(
                        'err_msg_req' => 5529,
                        'input_caption' => 5527,
                        'input_hint' => 5528,
                    ),
                    'ord' => array(
                        'err_msg_req' => 5532,
                        'input_caption' => 5530,
                        'input_hint' => 5531,
                    ),
                    'ispublished' => array(
                        'input_caption' => 5533,
                        'input_hint' => 5534,
                    ),
                    'title' => array(
                        'err_msg_req' => 5539,
                        'input_caption' => 5537,
                        'input_hint' => 5538,
                    ),
                    'keywords' => array(
                        'err_msg_req' => 5644,
                        'input_caption' => 5642,
                        'input_hint' => 5643,
                    ),
                    'description' => array(
                        'err_msg_req' => 5647,
                        'input_caption' => 5645,
                        'input_hint' => 5646,
                    ),
                    'body' => array(
                        'err_msg_req' => 5637,
                        'err_msg_length' => 5638,
                        'input_caption' => 5635,
                        'input_hint' => 5636,
                    ),
                    'url' => array(
                        'err_msg_req' => 5516,
                        'err_msg_mask' => 5517,
                        'input_caption' => 5514,
                        'input_hint' => 5515,
                    ),
                );
                foreach ($item_titles as $k => $v) {
                    $this->items[$k] = array_merge($this->items[$k], $v);
                }
                $this->messages = array(
                    'add_succeed' => 5563,
                    'add_failed' => 5564,
                    'edit_succeed' => 5565,
                    'remove_succeed' => 5566,
                    'remove_failed' => 5567,
                );
            }
            $this->items['parentid']['options'][] = array(
                'title' => $this->all->io->output(5510),
                'value' => '0',
            );
            $this->get_parents_tree($this->items['parentid']['options'], 0, $this->id);
            $this->items['ord']['options'][] = array(
                'title' => $this->all->io->output($this->isfolder ? 5518 : 5500),
                'value' => '1',
            );
            $this->all->db->query($req, 5410, array('%lang%', '%id%', '%pid%'), array($this->all->io->lang_active, $this->id, $this->parentid));
            while ($res = $this->all->db->fetch_assoc($req)) {
                $this->items['ord']['options'][] = array(
                    'title' => $this->all->io->str_cut($res['title'], 60),
                    'value' => $res['ord_next'],
                );
            }
            $this->all->db->free_result($req);
        }
        switch ($this->action) {
            case 'add-chapter':
                $this->action = 'add';
            case 'edit':
                if ($this->all->io->save_object_values($this)) {
                    $this->output_list();
                } else {
                    $this->output_form();
                }
                break;
            case 'remove':
                $this->all->io->remove_object($this);
                $this->output_list();
                break;
            case 'up':
                $this->move_up();
                $this->output_list();
                break;
            case 'down':
                $this->move_down();
                $this->output_list();
                break;
            default:
                $this->output_list();
        }
    }

    function get_parents_tree(&$parentitems, $parentid, $excludeid)
    {
        $this->all->db->query($req, 5411, array('%lang%', '%pid%', '%eid%'), array($this->all->io->lang_active, $parentid, $excludeid));
        if ($this->all->db->num_rows($req) > 0 && empty($parentitems)) {
            $parentitems = array();
        }
        while ($res = $this->all->db->fetch_assoc($req)) {
            $parentitems[] = array(
                'title' => $this->all->io->str_cut($res['title'], 60),
                'value' => $res['id'],
            );
            $this->get_parents_tree($parentitems[count($parentitems) - 1]['items'], $res['id'], $excludeid);
        }
        $this->all->db->free_result($req);
    }

    function output_list()
    {
        $smarty =& $this->all->tpl->smarty;
        $this->all->tpl->add_js_file('js/all.js');
        $this->all->tpl->add_js_global('removec', "'" . addslashes($this->all->io->output(5468)) . "'");
        $cheader = array(
            '0' => array(
                'caption' => 5471,
                'hint' => $this->all->io->output(5472, '%lang%', $this->all->io->lang_active),
            ),
        );
        $ctree = array();
        $this->output_tree_items($ctree, 0, 0);
        $ptools = array(
            'add_chapter' => array(
                'content' => $this->all->io->output(5574),
                'href' => $this->all->uri('commodities/add-chapter/'),
            ),
        );
        $smarty->assign('pane_title', $this->all->io->output((count($ctree) > 0 ? 5401 : 5467)));
        $smarty->assign('list_cols_width', '"100%", "1", "1", "1"');
        $smarty->assign_by_ref('list_headers', $cheader);
        $smarty->assign_by_ref('list_values', $ctree);
        $smarty->assign_by_ref('pagination', $pagination);
        $smarty->assign_by_ref('pane_tools', $ptools);
        $smarty->display('commodities_list.tpl');
    }

    function output_tree_items(&$ctree, $parentid, $level)
    {
        $this->all->db->query($req, 5405, array('%lang%', '%pid%'), array($this->all->io->lang_active, $parentid));
        $i = 0;
        $rows = $this->all->db->num_rows($req);
        while ($res = $this->all->db->fetch_assoc($req)) {
            $href = $this->all->uri('commodities/' . $res['id'] . '/');
            $fields = array(
                '0' => array(
                    'caption' => $res['title'],
                    'hint' => 5476,
                    'col_params' => array('class' => ($res['isfolder'] == 1 ? 'header-arrow li' . $level : 'li' . $level)),
                ),
            );
            $j = 0;
            if ($res['ord'] > 1) {
                $fields[strval(++$j)] = array(
                    'type' => 'up_btn',
                );
            } else {
                ++$j;
            }
            if (++$i < $rows) {
                $fields[strval(++$j)] = array(
                    'type' => 'down_btn',
                );
            } else {
                ++$j;
            }
            $fields[strval(++$j)] = array(
                'type' => 'remove_btn',
            );
            $ctree[] = array(
                'fields' => $fields,
                'href' => $href,
                //'row_params' => ($res['ispublished'] == 1 ? ($res['isnew'] == 1 ? array('class' => 'new-product') : false) : array('class' => 'temp' . ($res['isnew'] == 1 ? ' new-product' : ''))),
                'row_params' => ($res['ispublished'] == 1 ? (false) : array('class' => 'temp' . (''))),
            );
            if ($res['isfolder'] == 1) {
                $this->output_tree_items($ctree, $res['id'], $level + 1);
            }
        }
        $this->all->db->free_result($req);
    }

    function output_form()
    {
        if (!$this->all->tpl->init_object_values($this)) {
            $this->output_list();
            return;
        }
        $smarty =& $this->all->tpl->smarty;
        $values =& $this->values[0];
        $valitm =& $values['items'];
        $valtpl =& $values['tpl']['items'];
        $this->all->tpl->add_js_file('js/all.js');
        $this->all->tpl->add_js_file('js/commodities.js');
        $js_validation = false;
        $smarty->assign('js_validation', $js_validation);
        $smarty->assign('page_action', $this->action);
        $smarty->assign('pane_title', $this->all->io->output(($this->action == 'add' ? ($this->isfolder ? 5525 : 5403) : ($this->isfolder ? 5526 : 5404))));
        $smarty->assign('last_change_info', ($this->action == 'add' ? '' : $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid'])));
        if (!$this->isfolder && isset($this->all->map->items['commodity'])) {
            $res =& $this->all->map->items['commodity'];
        }
        $smarty->assign('form_action', $this->all->uri('commodities/' . ($this->action == 'edit' ? $this->id : ($this->isfolder ? 'add-chapter' : 'add-item')) . '/'));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $js_commodities_ord = '';
        $parentid = -1;
        $js_ord = '';
        $exids = ($this->id > 0 ? array($this->id) : array());
        $this->all->db->query($req, 5418, array('%lang%', '%id%'), array($this->all->io->lang_active, $this->id));
        while ($res = $this->all->db->fetch_assoc($req)) {
            if (in_array($res['parentid'], $exids)) {
                if ($res['isfolder'] == 1) {
                    $exids[] = $res['id'];
                }
            } else {
                if (!($parentid == $res['parentid'] || $js_ord == '')) {
                    $js_commodities_ord .= ($js_commodities_ord == '' ? "'p" : ", 'p") . $parentid . "' : [" . $js_ord . ']';
                    $js_ord = '';
                }
                $js_ord .= ($js_ord == '' ? '[' : ', [') . $res['ord_next'] . ", '" . addslashes($this->all->io->str_cut($res['title'], 80)) . "']";
                $parentid = $res['parentid'];
            }
        }
        $this->all->db->free_result($req);
        if ($js_ord != '') {
            $js_commodities_ord .= ($js_commodities_ord == '' ? "'p" : ", 'p") . $parentid . "' : [" . $js_ord . ']';
        }
        $this->all->tpl->add_js_global('commodities_ord', '{' . $js_commodities_ord . '}');
        $valtpl['title_keys'] = array();
        $valtpl['keywords_keys'] = array();
        $valtpl['description_keys'] = array();
        $valtpl['body_keys'] = array();
        foreach ($this->all->io->lang_available_frontend as $k => $v) {
            $valtpl['body_keys'][] = array('body' => 'body_' . $k);
            $valtpl['title_keys'][] = 'title_' . $k;
            $valtpl['keywords_keys'][] = 'keywords_' . $k;
            $valtpl['description_keys'][] = 'description_' . $k;
        }
        $smarty->assign_by_ref('fields', $valtpl);
        $smarty->assign('isfolder', $this->isfolder);
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('commodities_form.tpl');
    }

    function move_up()
    {
        return $this->move(-1);
    }

    function move($n)
    {
        $c = $this->all->io->move_tree_items($this->table_name, $this->id, 'ord', $this->all->map->items['commodity']['ord'], $this->all->map->items['commodity']['ord'] + $n, 'parentid', $this->parentid, $this->parentid);
        if ($c == 3) {
            $this->all->tpl->add_sys_alert('error', ($this->isfolder ? 5570 : 5501));
            return false;
        }
        $this->all->tpl->add_sys_alert('status', ($this->isfolder ? 5571 : 5502));
        $this->all->map->tree_refresh();
        return true;
    }

    function move_down()
    {
        return $this->move(2);
    }

    function after_init(&$critical_error, &$alerts, $display_errors, $sent, $reset, $parent_values)
    {
        if (!$sent) {
            return true;
        }
        $values =& $this->values[0];
        $valitm =& $values['items'];
        if ($valitm['parentid'] > 0) {
            $this->all->db->query($req, 5417, '%id%', $valitm['parentid']);
            if ($res = $this->all->db->fetch_assoc($req)) {
                $valitm['level'] = $res['level'] + 1;
            }
            $this->all->db->free_result($req);
        }
        return true;
    }

    function before_remove($ident, &$remove_on_fail, &$remove_on_success)
    {
        $this->all->io->move_tree_items_remove($this->table_name, 'ord', $this->all->map->items['commodity']['ord'], 'parentid', $this->parentid);
        return true;
    }

    function after_remove($ident, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            if ($this->isfolder) {
                $this->remove_childs($this->id);
            }
            $this->update_haschilds($this->parentid);
            $this->all->map->tree_refresh();
        }
        return true;
    }

    function remove_childs($parentid)
    {
        $this->all->db->query($req, 5412, '%pid%', $parentid);
        while ($res = $this->all->db->fetch_assoc($req)) {
            $this->remove_childs($res['id']);
            $this->all->db->query($reqc, 5430, '%id%', $res['id']);
        }
        $this->all->db->free_result($req);
        $this->all->db->query($req, 5413, '%pid%', $parentid);
    }

    function update_haschilds($parentid)
    {
        if ($parentid == 0) {
            return;
        }
        $this->all->db->query($req, 5427, '%pid%', $parentid);
        $res = $this->all->db->fetch_assoc($req);
        $this->all->db->free_result($req);
        $this->all->db->query($req, 5428, array('%id%', '%haschilds%'), array($parentid, ($res ? 1 : 0)));
    }

    function before_add(&$values_i, &$remove_on_fail, &$remove_on_success)
    {
        $valitm =& $values_i['items'];
        if ($this->isfolder && !$this->check_unique_url($valitm['url'], $valitm['parentid'], 0, $valitm['level'])) {
            return false;
        }
        $c = $this->all->io->move_tree_items_add($this->table_name, 'ord', $valitm['ord'], 'parentid', $valitm['parentid']);
        if ($c == 3) {
            $this->all->tpl->add_sys_alert('error', ($this->isfolder ? 5570 : 5501));
            return false;
        }
        return true;
    }

    function check_unique_url($url, $parentid, $excludeid, $level)
    {
        $this->all->db->query($req, 5414, array('%url%', '%pid%', '%eid%'), array($url, $parentid, $excludeid));
        $res = $this->all->db->fetch_assoc($req);
        $this->all->db->free_result($req);
        if ($res) {
            $this->all->db->query($req, 5416, array('%lang%', '%id%'), array($this->all->io->lang_active, $parentid));
            $ttl = $this->all->db->fetch_assoc($req);
            $this->all->db->free_result($req);
            $this->all->tpl->add_sys_alert('warning', 5493, array('%value%', '%title%'), array($url, ($ttl ? $ttl['title'] : '')));
            return false;
        }
        return true;
    }

    function after_add(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        return $this->after_add_edit($values_i, $remove_on_fail, $remove_on_success, $succeed);
    }

    function after_add_edit(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if (!$succeed) {
            return true;
        }
        $valitm =& $values_i['items'];
        $this->update_haschilds($this->parentid);
        $this->update_haschilds($valitm['parentid']);
        $this->all->map->tree_refresh();
        return true;
    }

    function before_edit(&$values_i, &$remove_on_fail, &$remove_on_success)
    {
        $valitm =& $values_i['items'];
        if ($this->isfolder && !$this->check_unique_url($valitm['url'], $valitm['parentid'], $values_i['id'], $valitm['level'])) {
            return false;
        }
        $c = $this->all->io->move_tree_items($this->table_name, $values_i['id'], 'ord', $this->all->map->items['commodity']['ord'], $valitm['ord'], 'parentid', $this->parentid, $valitm['parentid'], true);
        if ($c == 3) {
            $this->all->tpl->add_sys_alert('error', ($this->isfolder ? 5570 : 5501));
            return false;
        }
        if ($this->parentid == $valitm['parentid'] && $valitm['ord'] > $this->all->map->items['commodity']['ord']) {
            --$valitm['ord'];
        }
        return true;
    }

    function after_edit(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        $valitm =& $values_i['items'];
        if ($this->isfolder && $valitm['level'] != $this->all->map->items['commodity']['level']) {
            $this->update_child_level($values_i['id'], $valitm['level'] + 1);
        }
        if ($valitm['ispublished'] == 1) {
            $pid = $valitm['parentid'];
            while ($pid > 0) {
                $this->all->db->query($req, 5421, '%id%', $pid);
                if ($res = $this->all->db->fetch_assoc($req)) {
                    if ($res['ispublished'] == 0) {
                        $this->all->db->query($reqc, 5422, '%id%', $pid);
                    }
                    $pid = $res['parentid'];
                }
                $this->all->db->free_result($req);
            }
        }
        if ($this->isfolder && ($valitm['ispublished'] == 0 || $valitm['publishchilds'] == 1)) {
            $this->setpublish_childs($values_i['id'], $valitm['ispublished']);
        }
        return $this->after_add_edit($values_i, $remove_on_fail, $remove_on_success, $succeed);
    }

    function update_child_level($parentid, $level)
    {
        $this->all->db->query($req, 5419, '%pid%', $parentid);
        while ($res = $this->all->db->fetch_assoc($req)) {
            $this->update_child_level($res['id'], $level + 1);
        }
        $this->all->db->free_result($req);
        $this->all->db->query($req, 5420, array('%pid%', '%level%'), array($parentid, $level));
    }

    function setpublish_childs($parentid, $ispublished)
    {
        $this->all->db->query($req, 5423, '%pid%', $parentid);
        while ($res = $this->all->db->fetch_assoc($req)) {
            $this->setpublish_childs($res['id'], $ispublished);
        }
        $this->all->db->free_result($req);
        $this->all->db->query($req, 5424, array('%pid%', '%pub%'), array($parentid, $ispublished));
    }

}

$commodities = new cCommodities($caller);
?>