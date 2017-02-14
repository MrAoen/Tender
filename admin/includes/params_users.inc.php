<?php

/*
*/

class cParamsUsers
{
    var
        $all,
        $action,
        $table_name = 'admin_user_params',
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
        'ord' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Номер п/п',
            'input_hint' => 3740,
        ),
        'addts' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Дата регистрации',
            'input_hint' => 3741,
        ),
        'addip' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'IP регистрации',
            'input_hint' => 3742,
        ),
        'lastin' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Дата последнего входа',
            'input_hint' => 3743,
        ),
        'lastip' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'IP последнего входа',
            'input_hint' => 3744,
        ),
        'company' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Компания',
            'input_hint' => 3745,
        ),
        'name' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'ФИО',
            'input_hint' => 3746,
        ),
        'city' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Город',
            'input_hint' => 3747,
        ),
        'phone' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Телефон',
            'input_hint' => 3748,
        ),
        'email' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Email',
            'input_hint' => 3749,
        ),
        'status' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Статус компании',
            'input_hint' => 3750,
        ),
        'ones_per_page' => array(
            'type' => 'integer',
            'required' => true,
            'default' => '30',
            'options' => array(),
            'err_msg_req' => 20,
            'input_format' => 1,
            'input_caption' => 'Компаний на странице',
            'input_hint' => 3751,
        ),
    ),
        $messages = array(
        'add_succeed' => 3729,
        'add_failed' => 3730,
        'edit_succeed' => 3731,
        'remove_succeed' => 3732,
        'remove_failed' => 3733,
    );

    function cParamsUsers(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
    {
        $this->all =& $all;
        $this->all->io->before_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        $this->action = 'edit';
        $this->db_ident = '`id` = 1';
        $this->all->io->after_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        $this->items['lastchangets']['default'] = time();
        $this->items['lastchangeadminid']['default'] = $this->all->auth->admin['id'];
        $this->items['ones_per_page']['options'] = array(
            array(
                'title' => '10',
                'value' => '10',
            ),
            array(
                'title' => '20',
                'value' => '20',
            ),
            array(
                'title' => '30',
                'value' => '30',
            ),
            array(
                'title' => '50',
                'value' => '50',
            ),
            array(
                'title' => '100',
                'value' => '100',
            ),
            array(
                'title' => '200',
                'value' => '200',
            ),
            array(
                'title' => '500',
                'value' => '500',
            ),
            array(
                'title' => '1000',
                'value' => '1000',
            ),
        );
        $this->all->io->save_object_values($this);
        $this->output_form();
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
        $smarty->assign('pane_title', $this->all->io->output(($this->action == 'add' ? 3703 : 3704)));
        $smarty->assign('last_change_info', ($this->action == 'add' ? '' : $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid'])));
        $smarty->assign('form_action', $this->all->uri('users/params/'));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $smarty->assign_by_ref('fields', $valtpl);
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('params_users_form.tpl');
    }

}

$params_users = new cParamsUsers($caller);
?>