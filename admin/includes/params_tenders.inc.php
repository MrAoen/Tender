<?php

/*
*/

class cParamsTenders
{
    var
        $all,
        $action,
        $table_name = 'admin_tender_params',
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
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Номер',
            'input_hint' => 4140,
        ),
        'startts' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Дата начала',
            'input_hint' => 4141,
        ),
        'length' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Продолжительность',
            'input_hint' => 4142,
        ),
        'loadingcity' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Город погрузки',
            'input_hint' => 4143,
        ),
        'loadingaddress' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Адрес погрузки',
            'input_hint' => 4144,
        ),
        'loadingts' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Дата погрузки',
            'input_hint' => 4145,
        ),
        'course' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Точки доставки',
            'input_hint' => 4146,
        ),
        'volume' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Объем',
            'input_hint' => 4147,
        ),
        'body' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Дополнительно',
            'input_hint' => 4148,
        ),
        'pricestart' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Начальная цена',
            'input_hint' => 4149,
        ),
        'pricewin' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Призовая цена',
            'input_hint' => 4150,
        ),
        'pricecurrent' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Предложенная цена',
            'input_hint' => 4151,
        ),
        'lastuser' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Победитель',
            'input_hint' => 4152,
        ),
        'propositions' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Предложения',
            'input_hint' => 4153,
        ),
        'iscomplete' => array(
            'type' => 'integer',
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'err_msg_mask' => 20,
            'input_format' => 3,
            'input_caption' => 'Завершен',
            'input_hint' => 4154,
        ),
        'ones_per_page' => array(
            'type' => 'integer',
            'required' => true,
            'default' => '30',
            'options' => array(),
            'err_msg_req' => 20,
            'input_format' => 1,
            'input_caption' => 'Тендеров на странице',
            'input_hint' => 4155,
        ),
    ),
        $messages = array(
        'add_succeed' => 4129,
        'add_failed' => 4130,
        'edit_succeed' => 4131,
        'remove_succeed' => 4132,
        'remove_failed' => 4133,
    );

    function cParamsTenders(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
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
        $smarty->assign('pane_title', $this->all->io->output(($this->action == 'add' ? 4103 : 4104)));
        $smarty->assign('last_change_info', ($this->action == 'add' ? '' : $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid'])));
        $smarty->assign('form_action', $this->all->uri('tenders/params/'));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $smarty->assign_by_ref('fields', $valtpl);
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('params_tenders_form.tpl');
    }

}

$params_tenders = new cParamsTenders($caller);
?>