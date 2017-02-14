<?php

/*
*/

class cTendersXls
{
    var
        $all;

    function cTendersXls(&$all)
    {
        $this->all =& $all;
        $smarty =& $this->all->tpl->smarty;
        $admin =& $this->all->auth->admin;
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
        $f = 'tenders_' . date('Y-m-d_H-i-s') . '.xls';
        $excel = new ExcelWriter('tmp/' . $f);
        $smarty->assign('hidden_arc', $this->all->uri('tenders/xls/' . $f));
        if ($excel == false) {
            $this->all->tpl->add_sys_alert('warning', 'Ошибка записи файла');
            return false;
        }
        $excel->writeLine(array(
            'Номер',
            'Завершен',
            'Дата начала',
            'Продолжительность',
            'Город погрузки',
            'Адрес погрузки',
            'Дата погрузки',
            'Точки доставки',
            'Объем груза',
            'Дополнительно',
            'Начальная цена',
            'Призовая цена',
            'Предложенная цена',
            'Перевозчик',
            'Предложения',
        ));
        $this->all->db->query($req, 3909, '%lang%', $this->all->io->lang_active);
        while ($v = $this->all->db->fetch_assoc($req)) {
            $v['course'] = unserialize($v['course']);
            $course = array();
            foreach ($v['course'] as $r) {
                $course[] = $r['citytitle'] . ', ' . $r['address'] . ', ' . date('d.m.Y H:i', $v['datets']);
            }
            $excel->writeLine(array(
                $v['number'],
                ($v['iscomplete'] == 1 ? 'да' : 'нет'),
                ($v['startts'] == 0 ? '' : date('d.m.Y H:i', intval($v['startts']))),
                $v['length'],
                ($v['city'] == '' ? '-' : $v['city']),
                ($v['loadingaddress'] == '' ? '-' : $v['loadingaddress']),
                ($v['loadingts'] == 0 ? '' : date('d.m.Y H:i', intval($v['loadingts']))),
                implode(' -- ', $course),
                ($v['volume'] == '' ? '' : $v['volume'] . ' т'),
                ($v['body'] == '' ? '-' : $v['body']),
                ($v['pricestart'] == 0 ? '-' : $v['pricestart'] . ' Лари'),
                ($v['pricewin'] == 0 ? '-' : $v['pricewin'] . ' Лари'),
                ($v['pricecurrent'] == 0 ? '-' : $v['pricecurrent'] . ' Лари'),
                ($v['lastuser'] == '' ? '-' : $v['lastuser']),
                ($v['propositions'] == 0 ? '-' : $v['propositions']),
            ));
        }
        $this->all->db->free_result($req);
        $excel->close();
        $smarty->display('tenders_xls.tpl');
    }

}

$tenders_xls = new cTendersXls($caller);
?>