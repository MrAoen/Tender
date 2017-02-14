<?php

/*
*/

class cCities
{
    var
        $all,
        $action,
        $table_name = 'cities',
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
        'title' => array(
            'type' => 'string',
            'required' => true,
            'multilingual' => true,
            'default' => '',
            'maxlength' => 30,
            'unique' => true,
            'proc_strip_tags' => '',
            'err_msg_req' => 4314,
            'err_msg_unique' => 4315,
            'input_size' => 65,
            'input_caption' => 4312,
            'input_hint' => 4313,
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
        'add_succeed' => 4329,
        'add_failed' => 4330,
        'edit_succeed' => 4331,
        'remove_succeed' => 4332,
        'remove_failed' => 4333,
    ),
        $ones_per_page,
        $pages_count_max;

    function cCities(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
    {
        $this->all =& $all;
        $this->all->finclude('config/cities.cfg.php', $this);
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
        $this->all->tpl->add_js_global('removec', "'" . addslashes($this->all->io->output(4316)) . "'");
        $cheader = array(
            '0' => array(
                'caption' => 4334,
                'hint' => 4335,
            ),
        );
        $ctree = array();
        $this->all->tpl->pagination_init($pagination, $this->ones_per_page, $this->pages_count_max, 'tenders/cities/', $req, 4303, '%lang%', $this->all->io->lang_active);
        $i = 0;
        $rows = $this->all->db->num_rows($req);
        while ($res = $this->all->db->fetch_assoc($req)) {
            $href = $this->all->uri('tenders/cities/' . $res['id'] . '/');
            $fields = array(
                '0' => array(
                    'caption' => $res['title'],
                    'hint' => 4336,
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
                'content' => $this->all->io->output(4325),
                'href' => $this->all->uri('tenders/cities/add/'),
            ),
        );
        $smarty->assign('pane_title', $this->all->io->output((count($ctree) > 0 ? 4301 : 4327)));
        $smarty->assign_by_ref('list_headers', $cheader);
        $smarty->assign_by_ref('list_values', $ctree);
        $smarty->assign_by_ref('pagination', $pagination);
        $smarty->assign_by_ref('pane_tools', $ptools);
        $smarty->display('cities_list.tpl');
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
        $smarty->assign('pane_title', $this->all->io->output(($this->action == 'add' ? 4303 : 4304)));
        $smarty->assign('last_change_info', ($this->action == 'add' ? '' : $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid'])));
        $smarty->assign('form_action', $this->all->uri('tenders/cities/' . ($this->action == 'edit' ? $this->all->map->params['id'] : $this->action) . '/'));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $valtpl['title_keys'] = array();
        foreach ($this->all->io->lang_available_frontend as $k => $v) {
            $valtpl['title_keys'][] = 'title_' . $k;
        }
        $smarty->assign_by_ref('fields', $valtpl);
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('cities_form.tpl');
    }

    function after_remove($ident, &$remove_on_fail, &$remove_on_success, $succeed)
    {
        if ($succeed) {
            $this->all->map->tree_refresh();
        }
        return true;
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

$cities = new cCities($caller);
?>