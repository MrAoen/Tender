<?php

/*
*/

class cTexts
{
    var
        $all,
        $action,
        $table_name = 'texts',
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
            'required' => true,
            'default' => 0,
            'options' => array(),
            'err_msg_req' => 1517,
            'input_format' => 1,
            'input_caption' => 1515,
            'input_hint' => 1516,
        ),
        'text' => array(
            'type' => 'string',
            'multilingual' => true,
            'default' => '',
            'maxlength' => 100000,
            'err_msg_length' => 1520,
            'input_format' => 3,
            'input_size' => 49,
            'input_rows' => 3,
            'input_caption' => 1518,
            'input_hint' => 1519,
        ),
        'srvnotes' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 10000,
            'err_msg_length' => 1523,
            'input_format' => 3,
            'input_size' => 49,
            'input_rows' => 3,
            'input_caption' => 1521,
            'input_hint' => 1522,
        ),
    ),
        $messages = array(
        'add_succeed' => 1507,
        'add_failed' => 1508,
        'edit_succeed' => 1509,
        'remove_succeed' => 1510,
        'remove_failed' => 1511,
    );

    function cTexts(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
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
        $this->items['chapterid']['options'] = array(
            '0' => array(
                'title' => $this->all->io->output(1526),
                'value' => '0',
            ),
            '30' => array(
                'title' => $this->all->io->output(1529),
                'value' => '30',
            ),
            '50' => array(
                'title' => $this->all->io->output(1531),
                'value' => '50',
            ),
        );
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
        $this->all->tpl->add_js_global('removec', "'" . addslashes($this->all->io->output(1506)) . "'");
        $cheader = array(
            '0' => array(
                'caption' => $this->all->io->output(1524, '%lang%', $this->all->io->lang_active),
                'hint' => 1525,
            ),
        );
        $ctree = array();
        $this->all->db->query($req, 1501, '%lang%', $this->all->io->lang_active);
        $chapterid = -1;
        while ($res = $this->all->db->fetch_assoc($req)) {
            if ($chapterid != $res['chapterid']) {
                $fields = array(
                    '0' => array(
                        'type' => 'text',
                        'caption' => $this->items['chapterid']['options'][strval($res['chapterid'])]['title'],
                        'col_params' => array('class' => 'header-arrow'),
                    ),
                );
                $ctree[] = array(
                    'fields' => $fields,
                    'nomouseover' => true,
                );
                $chapterid = $res['chapterid'];
            }
            $href = $this->all->uri('texts/' . $res['id'] . '/');
            $fields = array(
                '0' => array(
                    'caption' => $this->all->io->str_cut(($res['value'] == '' ? $res['srvnotes'] : $res['value']), 200),
                    'hint' => 1504,
                    'col_params' => array('class' => 'spacer-left'),
                ),
                '1' => array(
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
                'content' => $this->all->io->output(1513),
                'href' => $this->all->uri('texts/add/'),
            ),
        );
        $smarty->assign('pane_title', $this->all->io->output((count($ctree) > 0 ? 1501 : 1512)));
        $smarty->assign_by_ref('list_headers', $cheader);
        $smarty->assign_by_ref('list_values', $ctree);
        $smarty->assign_by_ref('pane_tools', $ptools);
        $smarty->display('texts_list.tpl');
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
        $smarty->assign('pane_title', $this->all->io->output(($this->action == 'add' ? 1503 : 1504)));
        $smarty->assign('last_change_info', ($this->action == 'add' ? '' : $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid'])));
        $smarty->assign('form_action', $this->all->uri('texts/' . ($this->action == 'edit' ? $this->all->map->params['id'] : $this->action) . '/'));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $valtpl['text_keys'] = array();
        foreach ($this->all->io->lang_available_frontend as $k => $v) {
            $valtpl['text_keys'][] = 'text_' . $k;
        }
        $smarty->assign_by_ref('fields', $valtpl);
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('texts_form.tpl');
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

$texts = new cTexts($caller);
?>