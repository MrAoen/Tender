<?php

/*
*/

class cAdmins
{
    var
        $all,
        $action,
        $table_name = 'admins',
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
        'lastin' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'lastip' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'permissions' => array(
            'type' => 'string',
            'add_only' => true,
            'default' => '',
            'input_format' => 4,
            'input_html' => '',
        ),
        'typeid' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 10,
            'input_format' => 4,
            'input_html' => '',
        ),
        'fname' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 255,
            'proc_strip_tags' => '',
            'err_msg_req' => 716,
            'input_size' => 65,
            'input_caption' => 708,
            'input_hint' => 709,
        ),
        'sname' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'proc_strip_tags' => '',
            'err_msg_req' => 717,
            'input_size' => 65,
            'input_caption' => 710,
            'input_hint' => 711,
        ),
        'lname' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'proc_strip_tags' => '',
            'err_msg_mask' => 726,
            'input_size' => 65,
            'input_caption' => 714,
            'input_hint' => 715,
        ),
        'emails' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'mask' => '/^[0-9a-zA-Z\\-_\\.]+@[0-9a-zA-Z\\-_]+\\.[0-9a-zA-Z\\-_\\.]+(\\,[0-9a-zA-Z\\-_\\.]+@[0-9a-zA-Z\\-_]+\\.[0-9a-zA-Z\\-_\\.]+)*$/',
            'err_msg_req' => 718,
            'err_msg_mask' => 719,
            'input_size' => 65,
            'input_caption' => 712,
            'input_hint' => 713,
        ),
        'notify' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 769,
            'input_hint' => 770,
        ),
        'login' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 20,
            'mask' => '/^[0-9a-zA-Z]+$/',
            'unique' => true,
            'err_msg_req' => 734,
            'err_msg_mask' => 735,
            'err_msg_unique' => 724,
            'input_size' => 65,
            'input_caption' => 732,
            'input_hint' => 733,
        ),
        'old_password' => array(
            'type' => 'string',
            'in_db' => false,
            'edit_only' => true,
            'default' => '',
            'maxlength' => 50,
            'imask' => '/[^0-9a-zA-Z]/',
            'err_msg_mask' => 731,
            'input_format' => 5,
            'confirm' => false,
            'input_size' => 65,
            'input_caption' => 729,
            'input_hint' => 730,
        ),
        'password' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 50,
            'imask' => '/[^0-9a-zA-Z]/',
            'err_msg_req' => 740,
            'err_msg_mask' => 741,
            'err_msg_conf' => 742,
            'input_format' => 5,
            'input_size' => 65,
            'input_caption' => 736,
            'input_hint' => 737,
            'conf_caption' => 738,
            'conf_hint' => 739,
        ),
        'srvnotes' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 10000,
            'err_msg_length' => 725,
            'input_format' => 3,
            'input_size' => 49,
            'input_rows' => 3,
            'input_caption' => 706,
            'input_hint' => 707,
        ),
        'params' => array(
            'type' => 'string',
            'add_only' => true,
            'default' => '',
            'input_format' => 4,
            'input_html' => '',
        ),
    ),
        $messages = array(
        'add_succeed' => 720,
        'add_failed' => 723,
        'edit_succeed' => 748,
        'remove_succeed' => 721,
        'remove_failed' => 722,
    ),
        $is_admin = true;

    function cAdmins(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
    {
        $this->all =& $all;
        $this->all->io->before_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        if (isset($this->all->map->params['action'])) {
            $this->action = $this->all->map->params['action'];
        }
        if (isset($this->all->map->params['id'])) {
            $this->all->map->params['id'] = intval($this->all->map->params['id']);
            if (!isset($this->action)) {
                $this->action = 'edit';
            }
            $this->db_ident = "`id` = '" . $this->all->map->params['id'] . "'";
        }
        if (!$this->all->auth->check_permissions(1)) {
            $this->is_admin = false;
            $this->all->map->params['id'] = intval($this->all->auth->admin['id']);
            $this->action = 'edit';
            $this->db_ident = "`id` = '" . $this->all->map->params['id'] . "'";
            unset(
                $this->items['typeid'],
                $this->items['fname'],
                $this->items['sname'],
                $this->items['lname'],
                $this->items['emails'],
                $this->items['login'],
                $this->items['srvnotes']
            );
        }
        $this->all->io->after_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        $this->items['lastchangets']['default'] = time();
        $this->items['lastchangeadminid']['default'] = $this->all->auth->admin['id'];
        if ($this->is_admin) {
            $this->items['permissions']['default'] = str_repeat('0', 300);
            $this->items['params']['default'] = serialize(array());
            $this->items['typeid']['options'] = array(
                array(
                    'title' => $this->all->io->output(756),
                    'hint' => $this->all->io->output(753),
                    'value' => '10',
                ),
                array(
                    'title' => $this->all->io->output(757),
                    'hint' => $this->all->io->output(754),
                    'value' => '20',
                ),
            );
        }
        switch ($this->action) {
            case 'add':
                $this->items['password']['required'] = true;
            case 'edit':
                if ($this->all->io->save_object_values($this) && $this->is_admin) {
                    $this->output_list();
                } else {
                    $this->output_form();
                }
                break;
            case 'remove':
                $this->all->io->remove_object($this);
                $this->output_list();
                break;
            default:
                $this->output_list();
        }
    }

    function output_list()
    {
        $smarty =& $this->all->tpl->smarty;
        $this->all->tpl->add_js_file('js/all.js');
        $this->all->tpl->add_js_global('removec', "'" . addslashes($this->all->io->output(727)) . "'");
        $cheader = array(
            '0' => array(
                'caption' => 758,
                'hint' => 759,
            ),
            '1' => array(
                'caption' => 760,
                'hint' => 761,
            ),
            '2' => array(
                'caption' => 762,
                'hint' => 763,
            ),
        );
        $ctree = array();
        $this->all->db->query($req, 701);
        $typeid = -1;
        while ($res = $this->all->db->fetch_assoc($req)) {
            if ($typeid != $res['typeid']) {
                $fields = array(
                    '0' => array(
                        'type' => 'text',
                        'caption' => $this->all->io->output($res['typeid'] == 10 ? 764 : ($res['typeid'] == 20 ? 765 : 772)),
                        'col_params' => array('class' => 'header-arrow'),
                    ),
                );
                $ctree[] = array(
                    'fields' => $fields,
                    'nomouseover' => true,
                );
                $typeid = $res['typeid'];
            }
            $href = $this->all->uri('admins/' . $res['id'] . '/');
            $fields = array(
                '0' => array(
                    'caption' => $this->all->io->user_longname($res['fname'], $res['sname'], $res['lname']),
                    'hint' => 746,
                    'col_params' => array('class' => 'spacer-left'),
                ),
                '1' => array(
                    'type' => 'text',
                    'caption' => ($res['lastin'] == 0 ? $this->all->io->output(766) : $this->all->io->date_ts2text($res['lastin'])),
                ),
                '2' => array(
                    'caption' => str_replace(',', ', ', $res['emails']),
                    'href' => 'mailto:' . $res['emails'],
                    'hint' => 728,
                ),
                '3' => array(
                    'type' => 'remove_btn',
                ),
            );
            if ($res['lastin'] == 0) {
                $fields['1']['hint'] = 767;
            }
            $ctree[] = array(
                'fields' => $fields,
                'href' => $href,
            );
        }
        $this->all->db->free_result($req);
        $ptools = array(
            'add' => array(
                'content' => $this->all->io->output(744),
                'href' => $this->all->uri('admins/add/'),
            ),
        );
        $smarty->assign('pane_title', $this->all->io->output((count($ctree) > 0 ? 701 : 743)));
        $smarty->assign_by_ref('list_headers', $cheader);
        $smarty->assign_by_ref('list_values', $ctree);
        $smarty->assign_by_ref('pane_tools', $ptools);
        $smarty->display('admins_list.tpl');
    }

    function output_form()
    {
        if (!$this->all->tpl->init_object_values($this)) {
            if ($this->is_admin) {
                $this->output_list();
            }
            return;
        }
        $smarty =& $this->all->tpl->smarty;
        $values =& $this->values[0];
        $valitm =& $values['items'];
        $valtpl =& $values['tpl']['items'];
        $this->all->tpl->add_js_file('js/all.js');
        $this->all->tpl->add_js_file('js/admins.js');
        $this->all->tpl->add_js_global('admin_pw_short', "'" . addslashes($this->all->io->output(747)) . "'");
        $this->all->tpl->add_js_global('admin_pw_conf', "'" . addslashes($this->all->io->output(750)) . "'");
        $js_validation = ($this->action == 'edit' ? 'admVldEdit()' : 'admVld()');
        $smarty->assign('js_validation', $js_validation);
        $readonly_fields = false;
        if ($this->action == 'edit' && ($res = $this->all->tpl->get_admin_info(intval($this->all->map->params['id']), '`lastin`, `lastip`' . ($this->is_admin ? '' : ', `typeid`, `fname`, `sname`, `lname`, `emails`, `login`')))) {
            $valitm['lastin'] = $res['lastin'];
            $valitm['lastip'] = $res['lastip'];
            if (!$this->is_admin) {
                $readonly_fields = array(
                    'typeid' => array(
                        'caption' => 751,
                        'hint' => 752,
                        'value' => $this->all->io->output($res['typeid'] == 10 ? 756 : ($res['typeid'] == 20 ? 757 : 755)),
                    ),
                    'fname' => array(
                        'caption' => 708,
                        'hint' => 709,
                        'value' => $res['fname'],
                    ),
                    'sname' => array(
                        'caption' => 710,
                        'hint' => 711,
                        'value' => $res['sname'],
                    ),
                    'lname' => array(
                        'caption' => 714,
                        'hint' => 715,
                        'value' => $res['lname'],
                    ),
                    'emails' => array(
                        'caption' => 712,
                        'hint' => 713,
                        'value' => $res['emails'],
                    ),
                    'login' => array(
                        'caption' => 732,
                        'hint' => 733,
                        'value' => $res['login'],
                    ),
                );
                foreach ($readonly_fields as $k => $v) {
                    $readonly_fields[$k]['caption'] = $this->all->io->output($v['caption']);
                    $readonly_fields[$k]['hint'] = $this->all->io->output($v['hint']);
                }
            }
        }
        $smarty->assign('page_action', $this->action);
        $smarty->assign('pane_title', $this->all->io->output(($this->action == 'add' ? 703 : 704)));
        $smarty->assign('last_change_info', ($this->action == 'add' ? '' : $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid'])));
        $smarty->assign('last_login', (empty($valitm['lastin']) ? '' : $this->all->io->output(768, array('%date%', '%ip%'), array($this->all->io->date_ts2text($valitm['lastin']), long2ip($valitm['lastip'])))));
        $smarty->assign('form_action', $this->all->uri('admins/' . ($this->is_admin ? ($this->action == 'edit' ? $this->all->map->params['id'] : $this->action) . '/' : '')));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $smarty->assign('is_admin', $this->is_admin);
        $smarty->assign_by_ref('fields', $valtpl);
        $smarty->assign_by_ref('readonly_fields', $readonly_fields);
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('admins_form.tpl');
    }

    function after_init(&$critical_error, &$alerts, $display_errors, $sent, $reset, $parent_values)
    {
        if (!$sent) {
            return true;
        }
        if ($this->action == 'edit') {
            $no_old_pw = empty($_POST['old_password_0']);
            $no_new_pw = empty($_POST['password_0']);
            if ($no_old_pw && $no_new_pw) {
                $this->items['password']['in_db'] = false;
            } else {
                if ($no_old_pw || $no_new_pw) {
                    $critical_error = true;
                    if ($display_errors) {
                        $alerts[] = array(
                            'type' => 'err_msg_req',
                            'params' => array('warning', 750),
                        );
                    }
                } else if ($this->all->auth->admin['password'] != $this->values[0]['items']['old_password']) {
                    $critical_error = true;
                    if ($display_errors) {
                        $alerts[] = array(
                            'type' => 'err_msg_mask',
                            'params' => array('warning', 749),
                        );
                    }
                }
            }
        }
        if (!empty($_POST['password_0']) && strlen($_POST['password_0']) < 8) {
            $critical_error = true;
            if ($display_errors) {
                $alerts[] = array(
                    'type' => 'err_msg_mask',
                    'params' => array('warning', 747),
                );
            }
        }
        return true;
    }

    function before_remove($ident, &$remove_on_fail, &$remove_on_success)
    {
        if ($this->all->map->params['id'] == $this->all->auth->admin['id']) {
            $this->all->tpl->add_sys_alert('error', 745);
            return false;
        }
        return true;
    }

    function after_remove($ident, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            $id = $this->all->map->params['id'];
            foreach (array(
                         'admins',
                         'texts',
                         'params',
                         'templates',
                         'chapters_news',
                         'news',
                         'news_img',
                         'commodities',
                         'commodity_img',
                         'users',
                         'admin_user_params',
                     ) as $v) {
                $this->all->db->query($req, 704, array('%t%', '%id%'), array($v, $id));
            }
            $this->all->map->tree_refresh();
        }
        return true;
    }

    function after_add(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            $this->set_permissions(10, $values_i['id']);
            $this->all->map->tree_refresh();
        }
        return true;
    }

    function set_permissions($typeid, $aid)
    {
        switch ($typeid) {
            case '10':
                $p = '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111';
                break;
            case '20':
                $p = '100000000000000000000000000000000000000000000000000000000000000000000000000000001000000000000000000010000000001000000000100000000010000000001000000000100000000010000000001000000000000000000010000000001000000000100000000010000000001000011000100000000010000000001000000000100000000010000000001000000000';
                break;
        }
        $this->all->db->query($req, 703, array('%id%', '%perms%'), array($aid, $p));
    }

    function after_edit(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            $valitm =& $values_i['items'];
            if ($this->is_admin) {
                $this->set_permissions(10, $values_i['id']);
            }
            if ($values_i['id'] == $this->all->auth->admin['id'] && !empty($valitm['password'])) {
                $this->all->auth->login((empty($valitm['login']) ? $this->all->auth->admin['login'] : $valitm['login']), $valitm['password']);
            }
            $this->all->map->tree_refresh();
        }
        return true;
    }

}

$admins = new cAdmins($caller);
?>