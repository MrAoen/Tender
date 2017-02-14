<?php

/*
*/

class cIndex
{
    var
        $all;

    function cIndex(&$all)
    {
        $this->all =& $all;
        $smarty =& $this->all->tpl->smarty;
        $admin =& $this->all->auth->admin;
        $this->all->tpl->add_js_file('js/all.js');
        $is_intro = isset($this->all->map->params['intro']);
        $is_stats = true;
        $smarty->assign('is_intro', $is_intro);
        $smarty->assign('is_stats', $is_stats);
        if ($is_intro) {
            $smarty->assign('pane_title', $this->all->io->output(1901));
            $smarty->assign('auth_info', $this->all->io->output(1902, array('%name%', '%access%'), array($this->all->io->user_longname($admin['fname'], $admin['sname'], $admin['lname']), $this->all->io->output($admin['typeid'] == 10 ? 1903 : ($admin['typeid'] == 20 ? 1904 : 1915)))));
            $smarty->assign('lastin_info', ($admin['lastin'] == 0 ? '' : $this->all->io->output(1905, array('%lastin%', '%ip%'), array($this->all->io->date_ts2text($admin['lastin']), long2ip($admin['lastip'])))));
            $smarty->assign('logout_notify', $this->all->io->output(1906, '%link%', $this->all->uri('logout/')));
        }
        if ($is_stats) {
            $cheader = array(
                '0' => array(
                    'caption' => 1909,
                    'hint' => 1910,
                ),
                '1' => array(
                    'caption' => 1911,
                    'hint' => 1912,
                ),
                '2' => array(
                    'caption' => 1913,
                    'hint' => 1914,
                ),
            );
            $ctree = array();
            $this->all->db->query($req, 1901);
            $cache = $this->all->db->fetch_assoc($req);
            $this->all->db->free_result($req);
            if (!$cache) {
                $this->all->fexit(1);
            }
            $cache['value'] = unserialize($cache['value']);
            $log = (is_array($cache['value']) ? $cache['value'] : array());
            $addtsmax = intval($cache['lastchangets']);
            $this->all->db->query($req, 1900, '%tsmin%', $addtsmax);
            $update_cache = $this->all->db->num_rows($req);
            while ($res = $this->all->db->fetch_assoc($req)) {
                $addtsmax = max($addtsmax, $res['addts']);
                $y = date('Y', $res['addts']);
                $m = date('m', $res['addts']);
                $res['ip'] = strval($res['ip']);
                if (!isset($log[$y])) {
                    $log[$y] = array('all' => 0);
                }
                ++$log[$y]['all'];
                if (!isset($log[$y][$m])) {
                    $log[$y][$m] = array('all' => 0, 'unique' => 0);
                }
                ++$log[$y][$m]['all'];
                if (!isset($log[$y][$m][$res['ip']])) {
                    $log[$y][$m][$res['ip']] = 0;
                    ++$log[$y][$m]['unique'];
                } else if (!(date('Ym', $res['last_in']) == $y . $m)) {
                    ++$log[$y][$m]['unique'];
                }
                ++$log[$y][$m][$res['ip']];
            }
            $this->all->db->free_result($req);
            if ($update_cache) {
                $this->all->db->query($req, 1902,
                    array(
                        '%id%',
                        '%lastchangets%',
                        '%value%',
                    ),
                    array(
                        $cache['id'],
                        $addtsmax,
                        serialize($log),
                    )
                );
            }
            foreach (array_reverse($log, true) as $y => $log_y) {
                if ($y == 'all') {
                    continue;
                }
                $fields = array(
                    '0' => array(
                        'type' => 'text',
                        'caption' => $y . $this->all->io->output(1908),
                    ),
                    '2' => array(
                        'type' => 'text',
                        'caption' => strval($log_y['all']),
                    ),
                );
                $items = array();
                foreach (array_reverse($log_y, true) as $m => $log_m) {
                    if ($m == 'all' || $m == 'unique') {
                        continue;
                    }
                    $mfields = array(
                        '0' => array(
                            'type' => 'text',
                            'caption' => $this->all->io->output(34 + intval($m)),
                        ),
                        '1' => array(
                            'type' => 'text',
                            'caption' => strval($log_m['unique']),
                        ),
                        '2' => array(
                            'type' => 'text',
                            'caption' => strval($log_m['all']),
                        ),
                    );
                    $items[] = array(
                        'fields' => $mfields,
                        'nomouseover' => true,
                    );
                }
                $ctree[] = array(
                    'fields' => $fields,
                    'nomouseover' => true,
                    'items' => $items,
                );
            }
            $smarty->assign('stats_title', $this->all->io->output(1907));
            $smarty->assign_by_ref('list_headers', $cheader);
            $smarty->assign_by_ref('list_values', $ctree);
        }
        $smarty->display('index.tpl');
    }

}

$index = new cIndex($caller);
?>