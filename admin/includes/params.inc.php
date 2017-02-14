<?php

/*
*/

class cParams
{
    var
        $all,
        $action,
        $table_name = 'params',
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
        'chapterid' => array(
            'type' => 'integer',
            'default' => 0,
            'input_format' => 4,
            'input_html' => '',
        ),
        'paramname' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 50,
            'mask' => '/^[0-9a-zA-Z_]+$/',
            'unique' => true,
            'err_msg_req' => 1320,
            'err_msg_mask' => 1321,
            'err_msg_unique' => 1322,
            'input_size' => 65,
            'input_caption' => 1318,
            'input_hint' => 1319,
        ),
        'paramvalue' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 10000,
            'err_msg_length' => 1325,
            'input_format' => 3,
            'input_size' => 80,
            'input_rows' => 7,
            'input_caption' => 1323,
            'input_hint' => 1324,
        ),
        'srvnotes' => array(
            'type' => 'string',
            'required' => true,
            'default' => '',
            'maxlength' => 10000,
            'err_msg_req' => 1328,
            'err_msg_length' => 1329,
            'input_format' => 3,
            'input_size' => 49,
            'input_rows' => 3,
            'input_caption' => 1326,
            'input_hint' => 1327,
        ),
    ),
        $messages = array(
        'add_succeed' => 1307,
        'add_failed' => 1308,
        'edit_succeed' => 1309,
        'remove_succeed' => 1310,
        'remove_failed' => 1311,
    );

    function cParams(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
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
        $this->all->io->after_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        $this->items['lastchangets']['default'] = time();
        $this->items['lastchangeadminid']['default'] = $this->all->auth->admin['id'];
        switch ($this->action) {
            case 'add':
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
        $this->all->tpl->add_js_global('removec', "'" . addslashes($this->all->io->output(1306)) . "'");
        $cheader = array(
            '0' => array(
                'caption' => 1330,
                'hint' => 1331,
            ),
            '1' => array(
                'caption' => 1332,
                'hint' => 1333,
            ),
            '2' => array(
                'caption' => '&nbsp;',
            ),
        );
        $ctree = array();
        $this->all->db->query($req, 1301);
        $chapterid = -1;
        while ($res = $this->all->db->fetch_assoc($req)) {
            $href = $this->all->uri('params/' . $res['id'] . '/');
            $fields = array(
                '0' => array(
                    'caption' => $this->all->io->str_cut($res['srvnotes'], 200),
                    'hint' => 1304,
                    'col_params' => array('class' => 'spacer-left'),
                ),
                '1' => array(
                    'type' => 'text',
                    'caption' => $this->all->io->str_cut($res['paramvalue'], 50),
                    'col_params' => array('class' => 'spacer-left'),
                ),
                '2' => array(
                    'type' => 'remove_btn',
                ),
            );
            $ctree[] = array(
                'fields' => $fields,
                'href' => $href,
            );
        }
        $this->all->db->free_result($req);
        $ptools = array(
            'add' => array(
                'content' => $this->all->io->output(1313),
                'href' => $this->all->uri('params/add/'),
            ),
        );
        $smarty->assign('pane_title', $this->all->io->output((count($ctree) > 0 ? 1301 : 1312)));
        $smarty->assign_by_ref('list_headers', $cheader);
        $smarty->assign_by_ref('list_values', $ctree);
        $smarty->assign_by_ref('pane_tools', $ptools);
        $smarty->display('params_list.tpl');
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
        $smarty->assign('pane_title', $this->all->io->output(($this->action == 'add' ? 1303 : 1304)));
        $smarty->assign('last_change_info', ($this->action == 'add' ? '' : $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid'])));
        $smarty->assign('form_action', $this->all->uri('params/' . ($this->action == 'edit' ? $this->all->map->params['id'] : $this->action) . '/'));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $smarty->assign_by_ref('fields', $valtpl);
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('params_form.tpl');
    }

    function after_add(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            $this->all->map->tree_refresh();
        }
        return true;
    }

    function after_edit(&$values_i, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            $this->all->map->tree_refresh();
        }
        return true;
    }

}

$params = new cParams($caller);
?>