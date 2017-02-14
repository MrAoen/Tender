<?php

/*
*/

class cUsersXls
{
    var
        $all,
        $userStatus;

    function cUsersXls(&$all)
    {
        $this->all =& $all;
        $smarty =& $this->all->tpl->smarty;
        $admin =& $this->all->auth->admin;
        $this->userStatus = array(
            '1' => $this->all->io->output(3432),
            '2' => $this->all->io->output(3434),
            '3' => $this->all->io->output(3436),
            '4' => $this->all->io->output(3438),
        );
        if (isset($this->all->map->params['action']) && file_exists('tmp/' . $this->all->map->params['action'])) {
            $real_filename = 'tmp/' . $this->all->map->params['action'];
            $this->all->close();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Length: ' . filesize($real_filename));
            header('Content-Transfer-Encoding: binary');
            header('Content-Disposition: inline; filename=' . $this->all->map->params['action']);
            readfile($real_filename);
            exit();
        }
        $this->all->tpl->add_js_file('js/all.js');
        $this->all->finclude('libraries/excelwriter.lib.php', $this);
        $f = 'users_' . date('Y-m-d_H-i-s') . '.xls';
        $excel = new ExcelWriter('tmp/' . $f);
        $smarty->assign('hidden_arc', $this->all->uri('users/xls/' . $f));
        if ($excel == false) {
            $this->all->tpl->add_sys_alert('warning', 'Ошибка записи файла');
            return false;
        }
        $excel->writeLine(array(
            'ID',
            'Зарегистрирован',
            'Рег. IP',
            'Последний вход',
            'Последний IP',
            'Компания',
            'ФИО',
            'Город',
            'Телефон',
            'Email',
            'Статус',
        ));
        $this->all->db->query($req, 3409);
        while ($v = $this->all->db->fetch_assoc($req)) {
            $excel->writeLine(array(
                $v['id'],
                date('d.m.Y H:i:s', intval($v['addts'])),
                long2ip(intval($v['addip'])),
                ($v['lastin'] == 0 ? '' : date('d.m.Y H:i:s', intval($v['lastin']))),
                long2ip(intval($v['lastip'])),
                $v['company'],
                $v['name'],
                $v['city'],
                $v['phone'],
                $v['email'],
                $this->userStatus[strval($v['status'])],
            ));
        }
        $this->all->db->free_result($req);
        $excel->close();
        $smarty->display('users_xls.tpl');
    }

}

$users_xls = new cUsersXls($caller);
?>