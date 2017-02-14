<?php

/*
*/

class cPTemplates
{
    var
        $all,
        $action,
        $table_name = 'templates',
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
        'registration_sender' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1139,
            'input_size' => 86,
            'input_caption' => 1136,
            'input_hint' => 1137,
        ),
        'registration_subj' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1130,
            'input_size' => 86,
            'input_caption' => 1128,
            'input_hint' => 1129,
        ),
        'registration' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 100000,
            'err_msg_length' => 1131,
            'input_format' => 3,
            'input_size' => 75,
            'input_rows' => 15,
            'input_caption' => 1103,
            'input_hint' => 1107,
        ),
        'notifyregistration_sender' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1139,
            'input_size' => 86,
            'input_caption' => 1136,
            'input_hint' => 1137,
        ),
        'notifyregistration_subj' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1130,
            'input_size' => 86,
            'input_caption' => 1128,
            'input_hint' => 1129,
        ),
        'notifyregistration' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 100000,
            'err_msg_length' => 1131,
            'input_format' => 3,
            'input_size' => 75,
            'input_rows' => 15,
            'input_caption' => 1106,
            'input_hint' => 1107,
        ),
        'recover_conf_sender' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1139,
            'input_size' => 86,
            'input_caption' => 1136,
            'input_hint' => 1137,
        ),
        'recover_conf_subj' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1130,
            'input_size' => 86,
            'input_caption' => 1128,
            'input_hint' => 1129,
        ),
        'recover_conf' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 100000,
            'err_msg_length' => 1131,
            'input_format' => 3,
            'input_size' => 75,
            'input_rows' => 15,
            'input_caption' => 1104,
            'input_hint' => 1107,
        ),
        'recover_sender' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1139,
            'input_size' => 86,
            'input_caption' => 1136,
            'input_hint' => 1137,
        ),
        'recover_subj' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1130,
            'input_size' => 86,
            'input_caption' => 1128,
            'input_hint' => 1129,
        ),
        'recover' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 100000,
            'err_msg_length' => 1131,
            'input_format' => 3,
            'input_size' => 75,
            'input_rows' => 15,
            'input_caption' => 1105,
            'input_hint' => 1107,
        ),
        'tenderwin_sender' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1139,
            'input_size' => 86,
            'input_caption' => 1136,
            'input_hint' => 1137,
        ),
        'tenderwin_subj' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1130,
            'input_size' => 86,
            'input_caption' => 1128,
            'input_hint' => 1129,
        ),
        'tenderwin' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 100000,
            'err_msg_length' => 1131,
            'input_format' => 3,
            'input_size' => 75,
            'input_rows' => 15,
            'input_caption' => 1169,
            'input_hint' => 1107,
        ),
        'tenderlost_sender' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1139,
            'input_size' => 86,
            'input_caption' => 1136,
            'input_hint' => 1137,
        ),
        'tenderlost_subj' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 500,
            'err_msg_length' => 1130,
            'input_size' => 86,
            'input_caption' => 1128,
            'input_hint' => 1129,
        ),
        'tenderlost' => array(
            'type' => 'string',
            'default' => '',
            'maxlength' => 100000,
            'err_msg_length' => 1131,
            'input_format' => 3,
            'input_size' => 75,
            'input_rows' => 15,
            'input_caption' => 1170,
            'input_hint' => 1107,
        ),
    ),
        $messages = array(
        'edit_succeed' => 1108,
    ),
        $templates = array(
        'registration',
        'notifyregistration',
        'recover_conf',
        'recover',
        'tenderwin',
        'tenderlost',
    );

    function cPTemplates(&$all, $params = array(), $parent_objects = array(), $parent_item_names = array())
    {
        $this->all =& $all;
        $this->all->io->before_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        $this->action = 'edit';
        $this->db_ident = "`id` = 0";
        $this->all->io->after_init_object_declaration($this, $params, $parent_objects, $parent_item_names);
        $this->items['lastchangets']['default'] = time();
        $this->items['lastchangeadminid']['default'] = $this->all->auth->admin['id'];
        foreach ($this->templates as $v) {
            $this->items[$v]['input_size'] = $this->all->mail->line_width_max;
        }
        $this->all->io->save_object_values($this);
        $this->output_form();
    }

    function output_form()
    {
        if (!$this->all->tpl->init_object_values($this)) {
            return;
        }
        $smarty =& $this->all->tpl->smarty;
        $values =& $this->values[0];
        $valitm =& $values['items'];
        $valtpl =& $values['tpl']['items'];
        $this->all->tpl->add_js_file('js/all.js');
        $js_validation = '';
        $smarty->assign('js_validation', $js_validation);
        $smarty->assign('page_action', $this->action);
        $smarty->assign('pane_title', $this->all->io->output(1109));
        $smarty->assign('last_change_info', $this->all->tpl->last_change_info($valitm['lastchangets'], $valitm['lastchangeadminid']));
        $smarty->assign('form_action', $this->all->uri('templates/'));
        $smarty->assign('form_counts', $this->counts['tpl']['current']);
        $smarty->assign('submit_title', $this->all->io->output(28));
        $smarty->assign_by_ref('fields', $valtpl);
        $template_keys = array();
        foreach ($this->templates as $v) {
            $template_keys[] = array(
                'sender' => $v . '_sender',
                'subj' => $v . '_subj',
                'body' => $v,
            );
        }
        $smarty->assign_by_ref('template_keys', $template_keys);
        $smarty->assign('replacements', array(
            'caption' => $this->all->io->output(1110),
            'hint' => $this->all->io->output(1150),
            'registration' => array(
                '%дата%' => $this->all->io->output(1111),
                '%дата_сокр%' => $this->all->io->output(1112),
                '%время%' => $this->all->io->output(1113),
                '%организация%' => $this->all->io->output(1146),
                '%фио%' => $this->all->io->output(1152),
                '%email%' => $this->all->io->output(1154),
                '%пароль%' => $this->all->io->output(1155),
                '%ссылка_подтв%' => $this->all->io->output(1156),
                '%ссылка_логин%' => $this->all->io->output(1157),
            ),
            'notifyregistration' => array(
                '%дата%' => $this->all->io->output(1111),
                '%дата_сокр%' => $this->all->io->output(1112),
                '%время%' => $this->all->io->output(1113),
                '%а_фамилия%' => $this->all->io->output(1114),
                '%а_имя%' => $this->all->io->output(1115),
                '%а_отчество%' => $this->all->io->output(1116),
                '%а_email%' => $this->all->io->output(1149),
                '%о_орг%' => $this->all->io->output(1164),
                '%о_фио%' => $this->all->io->output(1165),
                '%о_город%' => $this->all->io->output(1166),
                '%о_телефон%' => $this->all->io->output(1167),
                '%о_email%' => $this->all->io->output(1168),
            ),
            'recover_conf' => array(
                '%дата%' => $this->all->io->output(1111),
                '%дата_сокр%' => $this->all->io->output(1112),
                '%время%' => $this->all->io->output(1113),
                '%фио%' => $this->all->io->output(1158),
                '%организация%' => $this->all->io->output(1159),
                '%email%' => $this->all->io->output(1160),
                '%ссылка_подтв%' => $this->all->io->output(1161),
            ),
            'recover' => array(
                '%дата%' => $this->all->io->output(1111),
                '%дата_сокр%' => $this->all->io->output(1112),
                '%время%' => $this->all->io->output(1113),
                '%фио%' => $this->all->io->output(1158),
                '%организация%' => $this->all->io->output(1159),
                '%email%' => $this->all->io->output(1160),
                '%пароль%' => $this->all->io->output(1162),
                '%ссылка_логин%' => $this->all->io->output(1157),
            ),
            'tenderwin' => array(
                '%дата%' => $this->all->io->output(1111),
                '%дата_сокр%' => $this->all->io->output(1112),
                '%время%' => $this->all->io->output(1113),
                '%организация%' => $this->all->io->output(1159),
                '%фио%' => $this->all->io->output(1158),
                '%email%' => $this->all->io->output(1160),
                '%тендер_сокр%' => $this->all->io->output(1171),
                '%тендер_полн%' => $this->all->io->output(1172),
            ),
            'tenderlost' => array(
                '%дата%' => $this->all->io->output(1111),
                '%дата_сокр%' => $this->all->io->output(1112),
                '%время%' => $this->all->io->output(1113),
                '%организация%' => $this->all->io->output(1159),
                '%фио%' => $this->all->io->output(1158),
                '%email%' => $this->all->io->output(1160),
                '%тендер_сокр%' => $this->all->io->output(1171),
                '%тендер_полн%' => $this->all->io->output(1172),
            ),
        ));
        $this->all->tpl->add_js_bottom('winonload("RgEv(' . (empty($js_validation) ? '' : "'mform', '" . $js_validation . "'") . '); VF(' . (empty($js_validation) ? '' : "'mform', 0, '" . $js_validation . "'") . ');");');
        $smarty->display('templates_form.tpl');
    }

}

$ptemplates = new cPTemplates($caller);
?>