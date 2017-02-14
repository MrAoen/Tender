<?php

/*
*/

class cUsers
{
    var
        $all,
        $action,
        $table_name = 'users',
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
        'addts' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'addip' => array(
            'type' => 'integer',
            'add_only' => true,
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
        'conf' => array(
            'type' => 'string',
            'add_only' => true,
            'default' => '',
            'input_format' => 4,
            'input_html' => '',
        ),
        'remember' => array(
            'type' => 'integer',
            'add_only' => true,
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'status' => array(
            'type' => 'integer',
            'default' => 3,
            'options' => array(),
            'err_msg_mask' => 3431,
            'input_format' => 2,
            'input_caption' => 3429,
            'input_hint' => 3430,
        ),
        'company' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 255,
            'proc_strip_tags' => '',
            'err_msg_req' => 3408,
            'input_size' => 65,
            'input_caption' => 3406,
            'input_hint' => 3407,
        ),
        'name' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 255,
            'proc_strip_tags' => '',
            'err_msg_req' => 3411,
            'input_size' => 65,
            'input_caption' => 3409,
            'input_hint' => 3410,
        ),
        'city' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 255,
            'proc_strip_tags' => '',
            'err_msg_req' => 3414,
            'input_size' => 65,
            'input_caption' => 3412,
            'input_hint' => 3413,
        ),
        'phone' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 255,
            'proc_strip_tags' => '',
            'err_msg_req' => 3417,
            'input_size' => 65,
            'input_caption' => 3415,
            'input_hint' => 3416,
        ),
        'email' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 500,
            'mask' => '/^[0-9a-zA-Z\\-_\\.]+@[0-9a-zA-Z\\-_]+\\.[0-9a-zA-Z\\-_\\.]+$/',
            'err_msg_req' => 3420,
            'err_msg_mask' => 3421,
            'input_size' => 65,
            'input_caption' => 3418,
            'input_hint' => 3419,
        ),
        'password' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 50,
            'imask' => '/[^0-9a-zA-Z]/',
            'err_msg_req' => 3426,
            'err_msg_mask' => 3427,
            'err_msg_conf' => 3428,
            'input_format' => 5,
            'input_size' => 65,
            'input_caption' => 3422,
            'input_hint' => 3423,
            'conf_caption' => 3424,
            'conf_hint' => 3425,
        ),
        'srvnotes' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 10000,
            'err_msg_length' => 3457,
            'input_format' => 3,
            'input_size' => 49,
            'input_rows' => 3,
            'input_caption' => 3455,
            'input_hint' => 3456,
        ),
    ),
        $messages = array(
        'add_succeed' => 3460,
        'add_failed' => 3461,
        'edit_succeed' => 3462,
        'remove_succeed' => 3463,
        'remove_failed' => 3464,
    ),
        $pages_count_max,
        $params;

    function cUsers(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
    {
        $this->all =& $all;
        $this->all->finclude('config/users.cfg.php', $this);
        $this->all->db->query($req, 3406);
        $this->params = $this->all->db->fetch_assoc($req);
        $this->all->db->free_result($req);
        if (!$this->params) {
            $this->all->fexit(3);
        }
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
        $this->items['lastchangets']['default'] = time();
        $this->items['lastchangeadminid']['default'] = $this->all->auth->admin['id'];
        $this->items['addts']['default'] = time();
        $this->items['conf']['default'] = substr(md5(rand() . time()), 0, 16);
        $this->items['status']['options'] = array(
            '1' => array(
                'title' => $this->all->io->output(3432),
                'hint' => $this->all->io->output(3433),
                'value' => '1',
            ),
            '2' => array(
                'title' => $this->all->io->output(3434),
                'hint' => $this->all->io->output(3435),
                'value' => '2',
            ),
            '3' => array(
                'title' => $this->all->io->output(3436),
                'hint' => $this->all->io->output(3437),
                'value' => '3',
            ),
            '4' => array(
                'title' => $this->all->io->output(3438),
                'hint' => $this->all->io->output(3439),
                'value' => '4',
            ),
        );
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
        $this->all->tpl->add_js_file('js/users.js');
        $this->all->tpl->add_js_global('removec', "'" . addslashes($this->all->io->output(3466)) . "'");
        $user_fields = array(
            'ord' => array(
                'nodb' => true,
                'caption' => '№',
                'hint' => '№',
            ),
            'addts' => array(
                'caption' => 'Зарегистрирован',
                'hint' => 'Зарегистрирован',
            ),
            'addip' => array(
                'caption' => 'Рег. IP',
                'hint' => 'Рег. IP',
            ),
            'lastin' => array(
                'caption' => 'Последний вход',
                'hint' => 'Последний вход',
            ),
            'lastip' => array(
                'caption' => 'Последний вход с IP',
                'hint' => 'Последний вход с IP',
            ),
            'company' => array(
                'caption' => 'Перевозчик',
                'hint' => 'Название компании',
            ),
            'name' => array(
                'caption' => 'Контактное лицо',
                'hint' => 'ФИО контактного лица',
            ),
            'city' => array(
                'caption' => 'Город',
                'hint' => 'Город компании-перевозчика',
            ),
            'phone' => array(
                'caption' => 'Телефон',
                'hint' => 'Телефон',
            ),
            'email' => array(
                'caption' => 'Email',
                'hint' => 'Email',
            ),
            'status' => array(
                'caption' => 'Статус',
                'hint' => 'Статус',
            ),
        );
        $cheader = array();
        $search_params = array('%fields%', '%ord%');
        $search_values = array('`id`, `status`', $this->params['orderby']);
        $j = 0;
        foreach ($user_fields as $k => $v) {
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
        $list_cols_width = str_repeat('"' . round(100 / ($j - 1)) . '%", ', $j - 1) . '"1"';
        $ctree = array();
        $this->all->tpl->pagination_init($pagination, $this->params['ones_per_page'], $this->pages_count_max, 'users/', $req, 3405, $search_params, $search_values);
        $i = ($pagination['page'] - 1) * $pagination['items_per_page'];
        while ($res = $this->all->db->fetch_assoc($req)) {
            $href = $this->all->uri('users/' . $res['id'] . '/');
            ++$i;
            $j = 0;
            $fields = array();
            foreach ($user_fields as $k => $v) {
                if (!empty($this->params[$k])) {
                    $fields[strval($j)] = array(
                        'caption' => (empty($res[$k]) ? '&mdash;' : $res[$k]),
                        'hint' => 3458,
                    );
                    switch ($k) {
                        case 'ord':
                            $fields[strval($j)]['caption'] = strval($i);
                            $fields[strval($j)]['col_params'] = array('class' => 'spacer-right');
                            break;
                        case 'addts':
                        case 'lastin':
                            $fields[strval($j)]['caption'] = ($res[$k] > 0 ? $this->all->io->date_ts2str($res[$k], false, false) : $this->all->io->output(3467));
                            $fields[strval($j)]['col_params'] = array('class' => 'spacer-right');
                            break;
                        case 'addip':
                        case 'lastip':
                            $fields[strval($j)]['caption'] = ($res[$k] > 0 ? long2ip($res[$k]) : '&mdash;');
                            $fields[strval($j)]['col_params'] = array('class' => 'spacer-right');
                            break;
                        case 'email':
                            $fields[strval($j)]['href'] = 'mailto:' . $res['email'];
                            break;
                        case 'status':
                            $fields[strval($j)]['caption'] = $this->items['status']['options'][strval($res['status'])]['title'];
                            break;
                    }
                    ++$j;
                }
            }
            $fields[strval($j++)] = array(
                'type' => 'remove_btn',
                'href' => $href . 'remove/' . ($pagination['page'] > 1 ? '?page=' . $pagination['page'] : ''),
            );
            if ($res['status'] == 2) {
                $fields['0']['col_params'] = array('class' => 'li0');
            }
            $ctree[] = array(
                'fields' => $fields,
                'href' => $href,
                'row_params' => ($res['status'] == 1 || $res['status'] == 4 ? array('class' => 'temp') : ($res['status'] == 3 ? false : array('class' => 'new'))),
            );
        }
        $this->all->db->free_result($req);
        $ptools = array(
            'add' => array(
                'content' => $this->all->io->output(3459),
                'href' => $this->all->uri('users/add/'),
            ),
        );
        $this->all->tpl->add_js_bottom("winonload('userListLoaded();');");
        $smarty->assign('pane_title', $this->all->io->output((count($ctree) > 0 ? 3401 : 3465)));
        $smarty->assign('list_cols_width', $list_cols_width);
        $smarty->assign_by_ref('list_headers', $cheader);
        $smarty->assign_by_ref('list_values', $ctree);
        $smarty->assign_by_ref('pagination', $pagination);
        $smarty->assign_by_ref('pane_tools', $ptools);
        $smarty->display('users_list.tpl');
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
        $js_validation = false;
        $smarty->assign('js_validation', $js_validation);
        $smarty->assign('page_action', $this->action);
        $smarty->assign('pane_title', $this->all->io->output(($this->action == 'add' ? 3403 : 3404)));
        $smarty->assign('last_change_info', ($this->action == 'add' ? '' : $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid'], $this->all->io->output(80))));
        $smarty->assign('form_action', $this->all->uri('users/' . ($this->action == 'edit' ? $this->all->map->params['id'] : $this->action) . '/'));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $res =& $this->all->map->items['user'];
        $readonly_fields = array(
            'addts' => array(
                'caption' => 'Зарегистрирован',
                'hint' => 'Зарегистрирован',
                'value' => $this->all->io->date_ts2text($res['addts']),
            ),
            'addip' => array(
                'caption' => 'с IP',
                'hint' => 'с IP',
                'value' => ($res['addip'] == 0 ? '&mdash;' : long2ip($res['addip'])),
            ),
            'lastin' => array(
                'caption' => 'Время последнего входа',
                'hint' => 'Время последнего входа',
                'value' => ($res['lastin'] == 0 ? '&mdash;' : $this->all->io->date_ts2text($res['lastin'])),
            ),
            'lastip' => array(
                'caption' => 'Последний вход с IP',
                'hint' => 'Последний вход с IP',
                'value' => ($res['lastip'] == 0 ? '&mdash;' : long2ip($res['lastip'])),
            ),
        );
        $smarty->assign_by_ref('readonly_fields', $readonly_fields);
        $smarty->assign_by_ref('fields', $valtpl);
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('users_form.tpl');
    }

    function after_init(&$critical_error, &$alerts, $display_errors, $sent, $reset, $parent_values)
    {
        if (!$sent) {
            return true;
        }
        if ($this->action == 'edit') {
            if (empty($_POST['password_0'])) {
                $this->items['password']['in_db'] = false;
            }
        }
        if (!empty($_POST['password_0']) && strlen($_POST['password_0']) < 8) {
            $critical_error = true;
            if ($display_errors) {
                $alerts[] = array(
                    'type' => 'err_msg_mask',
                    'params' => array('warning', 3440),
                );
            }
        }
        return true;
    }

    function after_add(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        return $this->after_add_edit($values_i, $remove_on_fail, $remove_on_success, $succeed);
    }

    function after_add_edit(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            $this->all->map->tree_refresh();
        }
        return true;
    }

    function after_edit(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        return $this->after_add_edit($values_i, $remove_on_fail, $remove_on_success, $succeed);
    }

}

$users = new cUsers($caller);
?>