<?php

/*
*/

class cTenders
{
    var
        $all,
        $action,
        $table_name = 'tenders',
        $db_ident,
        $readonly = false,
        $items = array(
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
        'number' => array(
            'type' => 'integer',
            'required' => true,
            'default' => 1,
            'min' => 1,
            'unique' => true,
            'err_msg_req' => 3910,
            'err_msg_unique' => 3911,
            'input_size' => 20,
            'input_caption' => 3908,
            'input_hint' => 3909,
        ),
        'iscomplete' => array(
            'type' => 'integer',
            'default' => 0,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 3906,
            'input_hint' => 3907,
        ),
        'startts' => array(
            'type' => 'datetime',
            'required' => true,
            'has_time' => true,
            'has_seconds' => false,
            'default' => 0,
            'err_msg_req' => 3918,
            'err_msg_req_y' => 3919,
            'err_msg_req_m' => 3920,
            'err_msg_req_d' => 3921,
            'err_msg_mask' => 3922,
            'err_msg_mask_y' => 3923,
            'err_msg_mask_m' => 3924,
            'err_msg_mask_d' => 3925,
            'input_caption' => 3912,
            'input_hint' => 3913,
            'button_hint' => 3914,
        ),
        'length' => array(
            'type' => 'integer',
            'required' => true,
            'default' => 60,
            'min' => 1,
            'err_msg_req' => 3928,
            'input_size' => 20,
            'input_caption' => 3926,
            'input_hint' => 3927,
        ),
        'loadingcityid' => array(
            'type' => 'integer',
            'default' => 124,
            'min' => 1,
            'options' => array(),
            'err_msg_mask' => 3931,
            'input_format' => 1,
            'input_caption' => 3929,
            'input_hint' => 3930,
        ),
        'loadingaddress' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 500,
            'proc_strip_tags' => '',
            'err_msg_req' => 3935,
            'input_size' => 65,
            'input_caption' => 3933,
            'input_hint' => 3934,
        ),
        'loadingts' => array(
            'type' => 'datetime',
            'required' => true,
            'has_time' => true,
            'has_seconds' => false,
            'default' => 0,
            'default_ignore_y' => 3915,
            'default_ignore_m' => 3916,
            'default_ignore_d' => 3917,
            'default_ignore_h' => 3947,
            'default_ignore_i' => 3948,
            'err_msg_req' => 3939,
            'err_msg_req_y' => 3940,
            'err_msg_req_m' => 3941,
            'err_msg_req_d' => 3942,
            'err_msg_mask' => 3943,
            'err_msg_mask_y' => 3944,
            'err_msg_mask_m' => 3945,
            'err_msg_mask_d' => 3946,
            'input_caption' => 3936,
            'input_hint' => 3937,
            'button_hint' => 3938,
        ),
        'course' => array(
            'type' => 'string',
            'default' => '',
            'input_format' => 4,
            'input_html' => '',
        ),
        'volume' => array(
            'type' => 'string',
            'required' => true,
            'default' => 0,
            'min' => 1,
            'err_msg_req' => 3982,
            'input_size' => 20,
            'input_caption' => 3980,
            'input_hint' => 3981,
        ),
        'pricestart' => array(
            'type' => 'integer',
            'required' => true,
            'default' => 0,
            'min' => 1,
            'err_msg_req' => 3985,
            'input_size' => 20,
            'input_caption' => 3983,
            'input_hint' => 3984,
        ),
        'pricewin' => array(
            'type' => 'integer',
            'required' => true,
            'default' => 0,
            'min' => 1,
            'err_msg_req' => 3988,
            'input_size' => 20,
            'input_caption' => 3986,
            'input_hint' => 3987,
        ),
        'body' => array(
            'type' => 'string',
            'multilingual' => true,
            'default' => '',
            'maxlength' => 500,
            'proc_strip_tags' => '',
            'err_msg_req' => 3991,
            'input_size' => 65,
            'input_caption' => 3989,
            'input_hint' => 3990,
        ),
        'lastuserid' => array(
            'type' => 'integer',
            'default' => 0,
            'options' => array(),
            'err_msg_mask' => 3994,
            'input_format' => 1,
            'input_caption' => 3992,
            'input_hint' => 3993,
        ),
        'pricecurrent' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'propositions' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'srvnotes' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 10000,
            'err_msg_length' => 3969,
            'input_format' => 3,
            'input_size' => 49,
            'input_rows' => 3,
            'input_caption' => 3967,
            'input_hint' => 3968,
        ),
    ),
        $messages = array(
        'add_succeed' => 3972,
        'add_failed' => 3973,
        'edit_succeed' => 3974,
        'remove_succeed' => 3975,
        'remove_failed' => 3976,
    ),
        $pages_count_max,
        $params;

    function cTenders(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
    {
        $this->all =& $all;
        $this->all->finclude('config/tenders.cfg.php', $this);
        $this->all->db->query($req, 3906);
        $this->params = $this->all->db->fetch_assoc($req);
        $this->all->db->free_result($req);
        if (!$this->params) {
            $this->all->fexit(2);
        }
        $this->params['loadingcityid'] = $this->params['loadingcity'];
        $this->params['lastuserid'] = $this->params['lastuser'];
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
        $this->all->io->after_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        if ($this->action != 'remove') {
            $this->items['lastchangets']['default'] = time();
            $this->items['lastchangeadminid']['default'] = $this->all->auth->admin['id'];
            $this->all->db->query($req, 3901);
            if ($res = $this->all->db->fetch_assoc($req)) {
                $this->items['number']['default'] = max(1, $res['max']);
            }
            $this->all->db->free_result($req);
            $this->items['startts']['default'] = mktime(13, 0, 0, date('n'), date('j') + (date('G') < 15 ? 0 : 1));

            $this->items['loadingcityid']['options']['0'] = array(
                'title' => $this->all->io->output(3932),
                'value' => '0',
            );
            $this->all->db->query($req, 3902, '%lang%', $this->all->io->lang_active);
            while ($res = $this->all->db->fetch_assoc($req)) {
                $this->items['loadingcityid']['options'][strval($res['id'])] = array(
                    'title' => $res['title'],
                    'value' => $res['id'],
                );
            }
            $this->all->db->free_result($req);

            $res = (isset($this->all->map->params['id']) ? unserialize($this->all->map->items['tender']['course']) : false);
            $res = (empty($res) ? false : $res);
            for ($i = 1; $i <= 10; $i++) {
                $n = 13800 + $i * 100;
                for ($j = 3949; $j <= 3966; $j++) {
                    $this->all->io->voutput[strval($n - 3900 + $j)] = str_replace('%n%', $i, $this->all->io->voutput[strval($j)]);
                }
                $this->items['coursecityid' . $i] = array(
                    'type' => 'integer',
                    'in_db' => false,
                    'default' => ($res && isset($res[strval($i)]) ? $res[strval($i)]['cityid'] : 0),
                    'options' => $this->items['loadingcityid']['options'],
                    'err_msg_mask' => $n + 51,
                    'input_format' => 1,
                    'input_caption' => $n + 49,
                    'input_hint' => $n + 50,
                );
                $this->items['courseaddress' . $i] = array(
                    'type' => 'string',
                    'in_db' => false,
                    'default' => ($res && isset($res[strval($i)]) ? $res[strval($i)]['address'] : ''),
                    'maxlength' => 500,
                    'proc_strip_tags' => '',
                    'err_msg_req' => $n + 55,
                    'input_size' => 65,
                    'input_caption' => $n + 53,
                    'input_hint' => $n + 54,
                );
                $this->items['coursets' . $i] = array(
                    'type' => 'datetime',
                    'in_db' => false,
                    'has_time' => true,
                    'has_seconds' => false,
                    'default' => ($res && isset($res[strval($i)]) ? $res[strval($i)]['datets'] : 0),
                    'err_msg_req' => $n + 59,
                    'err_msg_req_y' => $n + 60,
                    'err_msg_req_m' => $n + 61,
                    'err_msg_req_d' => $n + 62,
                    'err_msg_mask' => $n + 63,
                    'err_msg_mask_y' => $n + 64,
                    'err_msg_mask_m' => $n + 65,
                    'err_msg_mask_d' => $n + 66,
                    'input_caption' => $n + 56,
                    'input_hint' => $n + 57,
                    'button_hint' => $n + 58,
                );
            }

            $this->items['lastuserid']['options']['0'] = array(
                'title' => $this->all->io->output(3995),
                'value' => '0',
            );
            $this->all->db->query($req, 3903);
            while ($res = $this->all->db->fetch_assoc($req)) {
                $this->items['lastuserid']['options'][strval($res['id'])] = array(
                    'title' => $res['company'],
                    'value' => $res['id'],
                );
            }
            $this->all->db->free_result($req);
        }
        switch ($this->action) {
            case 'add':
                $this->items['password']['required'] = true;
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
            default:
                $this->output_list();
        }
    }

    function output_list()
    {
        $smarty =& $this->all->tpl->smarty;
        $this->all->tpl->add_js_file('js/all.js');
        $this->all->tpl->add_js_file('js/tenders.js');
        $this->all->tpl->add_js_global('removec', "'" . addslashes($this->all->io->output(3978)) . "'");
        $tender_fields = array(
            'number' => array(
                'caption' => '�',
                'hint' => '����� �������',
            ),
            'startts' => array(
                'caption' => '���� ������',
                'hint' => '���� ������ �������',
            ),
            'length' => array(
                'caption' => '�����������������',
                'hint' => '����������������� �������',
            ),
            'loadingcityid' => array(
                'caption' => '����� ��������',
                'hint' => '����� ��������',
            ),
            'loadingaddress' => array(
                'caption' => '����� ��������',
                'hint' => '����� ��������',
            ),
            'loadingts' => array(
                'caption' => '���� ��������',
                'hint' => '���� �������� �� ��������',
            ),
            'course' => array(
                'caption' => '����� ��������',
                'hint' => '����� ��������',
            ),
            'volume' => array(
                'caption' => '����� �����',
                'hint' => '����� �����',
            ),
            'body_ru' => array(
                'caption' => '�������������',
                'hint' => '�������������� �������� �������',
            ),
            'pricestart' => array(
                'caption' => '��������� ����',
                'hint' => '��������� ����',
            ),
            'pricewin' => array(
                'caption' => '�������� ����',
                'hint' => '�������� ����',
            ),
            'pricecurrent' => array(
                'caption' => '������������ ����',
                'hint' => '������� ����������� ������������ ���� (� �.�. � ������� ���������)',
            ),
            'lastuserid' => array(
                'caption' => '����������',
                'hint' => '��������-����������, ���������� ������',
            ),
            'propositions' => array(
                'caption' => '�����������',
                'hint' => '���������� �����������',
            ),
            'iscomplete' => array(
                'caption' => '��������',
                'hint' => '������ ��������?',
            ),
        );
        $cheader = array();
        $search_params = array('%fields%', '%ord%');
        $search_values = array('`id`, `iscomplete`, `startts`, `length`', $this->params['orderby']);
        $j = 0;
        foreach ($tender_fields as $k => $v) {
            if (!empty($this->params[$k])) {
                if (empty($v['nodb'])) {
                    $search_values[0] .= ', `' . $k . '`';
                }
                $cheader[strval($j++)] = $v;
            }
        }
        $cheader[strval($j++)] = array(
            'caption' => '&nbsp;',
        );
        $list_cols_width = str_repeat('"1", ', $j - 1) . '"1"';
        $ctree = array();
        $this->all->tpl->pagination_init($pagination, $this->params['ones_per_page'], $this->pages_count_max, 'tenders/', $req, 3905, $search_params, $search_values);
        $i = ($pagination['page'] - 1) * $pagination['items_per_page'];
        while ($res = $this->all->db->fetch_assoc($req)) {
            $href = $this->all->uri('tenders/' . $res['id'] . '/');
            ++$i;
            $j = 0;
            $fields = array();
            foreach ($tender_fields as $k => $v) {
                if (!empty($this->params[$k])) {
                    $fields[strval($j)] = array(
                        'caption' => (empty($res[$k]) ? '&mdash;' : $res[$k]),
                        'hint' => 3970,
                    );
                    switch ($k) {
                        case 'startts':
                        case 'loadingts':
                            $fields[strval($j)]['caption'] = ($res[$k] > 0 ? $this->all->io->date_ts2str($res[$k], true, false) : $this->all->io->output(3979));
                            break;
                        case 'length':
                            $fields[strval($j)]['caption'] .= ' ���';
                            $fields[strval($j)]['col_params'] = array('class' => 'spacer-right');
                            break;
                        case 'loadingcityid':
                            $fields[strval($j)]['caption'] = $this->items[$k]['options'][strval($res[$k])]['title'];
                            break;
                        case 'lastuserid':
                            $fields[strval($j)]['caption'] = ($res['iscomplete'] == 1 && $res['lastuserid'] > 0 ? $this->items[$k]['options'][strval($res[$k])]['title'] : '&mdash;');
                            break;
                        case 'course':
                            $res[$k] = unserialize($res[$k]);
                            if (!empty($res[$k])) {
                                $fields[strval($j)]['caption'] = '';
                                foreach ($res[$k] as $r) {
                                    $fields[strval($j)]['caption'] .= ($fields[strval($j)]['caption'] == '' ? '' : '&nbsp;&rarr; ') . $r['citytitle'];
                                }
                            }
                            break;
                        case 'volume':
                            $fields[strval($j)]['caption'] .= ' �';
                            $fields[strval($j)]['col_params'] = array('class' => 'spacer-right');
                            break;
                        case 'pricestart':
                        case 'pricewin':
                        case 'pricecurrent':
                            if ($res[$k] > 0) {
                                $fields[strval($j)]['caption'] .= ' KZT';
                            }
                            $fields[strval($j)]['col_params'] = array('class' => 'spacer-right');
                            break;
                        case 'propositions':
                            $fields[strval($j)]['col_params'] = array('class' => 'spacer-right');
                            break;
                        case 'iscomplete':
                            $fields[strval($j)]['caption'] = ($res[$k] == 1 ? '��' : '���');
                            $fields[strval($j)]['col_params'] = array('class' => 'spacer-right');
                            break;
                    }
                    ++$j;
                }
            }
            $fields[strval($j++)] = array(
                'type' => 'remove_btn',
                'href' => $href . 'remove/' . ($pagination['page'] > 1 ? '?page=' . $pagination['page'] : ''),
            );
            $ctree[] = array(
                'fields' => $fields,
                'href' => $href,
                'row_params' => ($res['iscomplete'] == 1 && date('Ymd', $res['startts']) < date('Ymd') ? array('class' => 'temp') : false),
            );
        }
        $this->all->db->free_result($req);
        $ptools = array(
            'add' => array(
                'content' => $this->all->io->output(3971),
                'href' => $this->all->uri('tenders/add/'),
            ),
        );
        $this->all->tpl->add_js_bottom("winonload('tenderListLoaded();');");
        $smarty->assign('pane_title', $this->all->io->output((count($ctree) > 0 ? 3901 : 3977)));
        $smarty->assign('list_cols_width', $list_cols_width);
        $smarty->assign_by_ref('list_headers', $cheader);
        $smarty->assign_by_ref('list_values', $ctree);
        $smarty->assign_by_ref('pagination', $pagination);
        $smarty->assign_by_ref('pane_tools', $ptools);
        $smarty->display('tenders_list.tpl');
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
        $this->all->tpl->add_js_file('js/tenders.js');
        $js_validation = false;
        $this->all->tpl->add_js_bottom("winonload(\"vCalendars.startts_0_calendar.onHide = 'VF(" . (empty($js_validation) ? '' : "\\\\'mform\\\\',0,\\\\'" . $js_validation . "\\\\'") . ");';\");");
        $smarty->assign('js_validation', $js_validation);
        $smarty->assign('page_action', $this->action);
        $smarty->assign('pane_title', $this->all->io->output(($this->action == 'add' ? 3903 : 3904)));
        $smarty->assign('last_change_info', ($this->action == 'add' ? '' : $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid'], $this->all->io->output(80))));
        $smarty->assign('form_action', $this->all->uri('tenders/' . ($this->action == 'edit' ? $this->all->map->params['id'] : $this->action) . '/'));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $res = (isset($this->all->map->items['tender']) ? $this->all->map->items['tender'] : array(
            'propositions' => 0,
            'pricecurrent' => 0,
            'course' => serialize(array()),
        ));
        $readonly_fields = array(
            'propositions' => array(
                'caption' => '�����������',
                'hint' => '���������� �����������',
                'value' => $res['propositions'],
            ),
            'pricecurrent' => array(
                'caption' => '������������ ����',
                'hint' => '������� ����������� ������������ ���� (� �.�. � ������� ���������)',
                'value' => $res['pricecurrent'],
            ),
        );
        $proplist = array(
            'caption' => '������ �����������',
            'hint' => '����������� ������������',
            'items' => array(),
        );
        $res['course'] = unserialize($res['course']);
        $this->all->tpl->add_js_global('show_course_fields', max(1, count($res['course'])));
        $this->all->tpl->add_js_bottom('courseInit();');
        if (isset($this->all->map->params['id'])) {
            $this->all->db->query($req, 3904, '%tid%', $this->all->map->params['id']);
            while ($res = $this->all->db->fetch_assoc($req)) {
                $res['date'] = $this->all->io->date_ts2str($res['datets']);
                $proplist['items'][] = $res;
            }
            $this->all->db->free_result($req);
        }
        $valtpl['body_keys'] = array();
        foreach ($this->all->io->lang_available_frontend as $k => $v) {
            $valtpl['body_keys'][] = 'body_' . $k;
        }
        $valtpl['course_keys'] = array();
        for ($i = 1; $i <= 10; $i++) {
            $valtpl['course_keys'][strval($i)] = array(
                'coursecityid' => 'coursecityid' . $i,
                'courseaddress' => 'courseaddress' . $i,
                'coursets' => 'coursets' . $i,
            );
        }
        $smarty->assign_by_ref('readonly_fields', $readonly_fields);
        $smarty->assign_by_ref('proplist', $proplist);
        $smarty->assign_by_ref('fields', $valtpl);
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('tenders_form.tpl');
    }

    function after_remove($ident, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            $this->update_lastchangets();
        }
        return true;
    }

    function update_lastchangets()
    {
        file_put_contents($this->all->frontend_rw_dir . '/last-update', time());
    }

    function after_init(&$critical_error, &$alerts, $display_errors, $sent, $reset, $parent_values)
    {
        if (!$sent) {
            return true;
        }
        $values =& $this->values[0];
        $valitm =& $values['items'];
        $valitm['course'] = array();
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($valitm['coursecityid' . $i]) && isset($this->items['loadingcityid']['options'][strval($valitm['coursecityid' . $i])])) {
                $valitm['course'][strval($i)] = array(
                    'cityid' => $valitm['coursecityid' . $i],
                    'citytitle' => $this->items['loadingcityid']['options'][strval($valitm['coursecityid' . $i])]['title'],
                    'address' => $valitm['courseaddress' . $i],
                    'datets' => $valitm['coursets' . $i],
                );
            }
        }
        $valitm['course'] = serialize($valitm['course']);
        return true;
    }

    function after_add(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        return $this->after_add_edit($values_i, $remove_on_fail, $remove_on_success, $succeed);
    }

    function after_add_edit(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            $this->update_lastchangets();
            $this->all->map->tree_refresh();
        }
        return true;
    }

    function after_edit(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        return $this->after_add_edit($values_i, $remove_on_fail, $remove_on_success, $succeed);
    }

}

$tenders = new cTenders($caller);
?>