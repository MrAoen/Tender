<?php

/*
*/

class cMap
{
    var
        $tree_left,
        $tree_right,
        $params,
        $items = array(),
        $all;

    function cMap(&$all)
    {
        $this->all =& $all;
        $this->all->finclude('config/map.cfg.php', $this);
        $this->tree_left = array();
        $this->tree_right = array();
        $this->params = array();
    }

    function tree_refresh()
    {
        $this->tree_create();
        $this->_tree_refresh($this->tree_left);
        $this->_tree_refresh($this->tree_right);
    }

    function tree_create()
    {
        $path =& $this->all->path;
        $this->tree_left = array();
        $this->tree_right = array();

        /* all { */
        $this->tree_left['login'] = array(
            'permissions' => -2,
            'page_title' => 300,
            'page_caption' => 300,
            'filename' => 'includes/login.inc.php',
        );
        $this->tree_left['logout'] = array(
            'permissions' => -1,
            'filename' => 'includes/logout.inc.php',
        );
        $this->tree_left['index'] = array(
            'permissions' => -1,
            'menu_title' => 1900,
            'page_title' => 1900,
            'page_caption' => 1900,
            'filename' => 'includes/index.inc.php',
            'items' => array(
                'intro' => array(
                    'permissions' => -1,
                    'param_name' => 'intro',
                ),
            ),
        );
        /* all } */

        if (!$this->all->auth->logged_in) {
            return;
        }
        $remove_params = array(
            'onclick' => "return rmc('" . addslashes($this->all->io->output(30)) . "')",
        );
        $remove_row_params = array(
            'class' => 'remove',
        );
        $admin =& $this->all->auth->admin;
        $lang = $this->all->io->lang_active;

        /* tenders { */
        $this->tree_left['tenders'] = array(
            'permissions' => 190,
            'menu_title' => 3900,
            'page_title' => 3901,
            'page_caption' => 3901,
            'filename' => 'includes/tenders.inc.php',
        );
        if (isset($path[0]) && $path[0] == 'tenders') {
            $this->tree_left['tenders']['items'] = array();
            $items =& $this->tree_left['tenders']['items'];
            $items['add'] = array(
                'permissions' => 190,
                'menu_title' => 3902,
                'page_title' => 3903,
                'page_caption' => 3903,
                'param_name' => 'action',
            );
            $items['params'] = array(
                'permissions' => 200,
                'menu_title' => 4100,
                'page_title' => 4100,
                'page_caption' => 4100,
                'filename' => 'includes/params_tenders.inc.php',
            );

            /* cities { */
            $items['cities'] = array(
                'permissions' => 90,
                'menu_title' => 4300,
                'page_title' => 4301,
                'page_caption' => 4301,
                'filename' => 'includes/cities.inc.php',
            );
            if (isset($path[1]) && $path[1] == 'cities') {
                $items['cities']['items'] = array(
                    'add' => array(
                        'permissions' => 90,
                        'menu_title' => 4302,
                        'page_title' => 4303,
                        'page_caption' => 4303,
                        'param_name' => 'action',
                    ),
                );
                $ritems =& $items['cities']['items'];
                if (isset($path[2]) && $path[2] != 'add') {
                    $this->all->db->query($req, 4300, '%id%', intval($path[2]));
                    if ($res = $this->all->db->fetch_assoc($req)) {
                        $this->items['city'] = $res;
                        $ritems['s0001'] = array(
                            'permissions' => 90,
                            'menu_item_type' => 1,
                        );
                        $ps = strval($res['id']);
                        $ritems[$ps] = array(
                            'permissions' => 90,
                            'menu_title' => $this->all->io->str_cut($res['title_' . $this->all->io->lang_active], 30),
                            'page_title' => 4304,
                            'page_caption' => 4304,
                            'param_name' => 'id',
                            'items' => array(
                                'remove' => array(
                                    'permissions' => 90,
                                    'menu_title' => 4307,
                                    'param_name' => 'action',
                                    'menu_link_params' => $remove_params,
                                    'menu_row_params' => $remove_row_params,
                                ),
                            ),
                        );
                    }
                    $this->all->db->free_result($req);
                }
            }
            /* cities } */

            $items['xls'] = array(
                'permissions' => 200,
                'menu_title' => 'Скачать в Excel',
                'page_title' => 'Скачать тендеры в Excel',
                'page_caption' => 'Скачать тендеры в Excel',
                'filename' => 'includes/tenders_xls.inc.php',
            );
            if (isset($path[2]) && $path[1] == 'xls') {
                $items['xls']['items'] = array(
                    strval($path[2]) => array(
                        'permissions' => 200,
                        'param_name' => 'action',
                    ),
                );
            }
            if (isset($path[1]) && !in_array($path[1], array('add', 'params', 'xls', 'mailings'))) {
                $this->all->db->query($req, 3900, '%id%', intval($path[1]));
                if ($res = $this->all->db->fetch_assoc($req)) {
                    $this->items['tender'] = $res;
                    $items['s0001'] = array(
                        'permissions' => 190,
                        'menu_item_type' => 1,
                    );
                    $ps = strval($res['id']);
                    $items[$ps] = array(
                        'permissions' => 190,
                        'menu_title' => 'Тендер #' . $res['number'],
                        'page_title' => 3904,
                        'page_caption' => 3904,
                        'param_name' => 'id',
                        'items' => array(
                            'remove' => array(
                                'permissions' => 190,
                                'menu_title' => 3905,
                                'param_name' => 'action',
                                'menu_link_params' => $remove_params,
                                'menu_row_params' => $remove_row_params,
                            ),
                        ),
                    );
                }
                $this->all->db->free_result($req);
            }
        }
        /* tenders } */

        /* users { */
        $this->tree_left['users'] = array(
            'permissions' => 190,
            'menu_title' => 3400,
            'page_title' => 3401,
            'page_caption' => 3401,
            'filename' => 'includes/users.inc.php',
        );
        if (isset($path[0]) && $path[0] == 'users') {
            $this->tree_left['users']['items'] = array();
            $items =& $this->tree_left['users']['items'];
            $items['add'] = array(
                'permissions' => 190,
                'menu_title' => 3402,
                'page_title' => 3403,
                'page_caption' => 3403,
                'param_name' => 'action',
            );
            $items['params'] = array(
                'permissions' => 200,
                'menu_title' => 3700,
                'page_title' => 3700,
                'page_caption' => 3700,
                'filename' => 'includes/params_users.inc.php',
            );
            $items['xls'] = array(
                'permissions' => 200,
                'menu_title' => 'Скачать в Excel',
                'page_title' => 'Скачать перевозчиков в Excel',
                'page_caption' => 'Скачать перевозчиков в Excel',
                'filename' => 'includes/users_xls.inc.php',
            );
            if (isset($path[2]) && $path[1] == 'xls') {
                $items['xls']['items'] = array(
                    strval($path[2]) => array(
                        'permissions' => 200,
                        'param_name' => 'action',
                    ),
                );
            }
            if (isset($path[1]) && !in_array($path[1], array('add', 'params', 'xls', 'mailings'))) {
                $this->all->db->query($req, 3400, '%id%', intval($path[1]));
                if ($res = $this->all->db->fetch_assoc($req)) {
                    $this->items['user'] = $res;
                    $items['s0001'] = array(
                        'permissions' => 190,
                        'menu_item_type' => 1,
                    );
                    $ps = strval($res['id']);
                    $items[$ps] = array(
                        'permissions' => 190,
                        'menu_title' => $this->all->io->str_cut($res['name'], 70),
                        'page_title' => 3404,
                        'page_caption' => 3404,
                        'param_name' => 'id',
                        'items' => array(
                            'remove' => array(
                                'permissions' => 190,
                                'menu_title' => 3405,
                                'param_name' => 'action',
                                'menu_link_params' => $remove_params,
                                'menu_row_params' => $remove_row_params,
                            ),
                        ),
                    );
                }
                $this->all->db->free_result($req);
            }
        }
        /* users } */

        /* commodities { */
        $this->tree_left['commodities'] = array(
            'permissions' => 180,
            'menu_title' => 5400,
            'page_title' => 5401,
            'page_caption' => 5401,
            'filename' => 'includes/commodities.inc.php',
        );
        if (isset($path[0]) && $path[0] == 'commodities') {
            $this->tree_left['commodities']['items'] = array();
            $items =& $this->tree_left['commodities']['items'];
            $items['add-chapter'] = array(
                'permissions' => 180,
                'menu_title' => 5524,
                'page_title' => 5525,
                'page_caption' => 5525,
                'param_name' => 'action',
            );
            if (isset($path[1]) && !in_array($path[1], array('add-item', 'add-chapter'))) {
                $this->all->db->query($req, 5400, '%id%', intval($path[1]));
                if ($res = $this->all->db->fetch_assoc($req)) {
                    $this->items['commodity'] = $res;
                    $items['s0001'] = array(
                        'permissions' => 180,
                        'menu_item_type' => 1,
                    );
                    $ps = strval($res['id']);
                    $items[$ps] = array(
                        'permissions' => 180,
                        'menu_title' => $this->all->io->str_cut($res['title_' . $this->all->io->lang_active], 30),
                        'page_title' => ($res['isfolder'] == 0 ? 5404 : 5526),
                        'page_caption' => ($res['isfolder'] == 0 ? 5404 : 5526),
                        'param_name' => 'id',
                        'items' => array(),
                    );
                    if ($res['ord'] > 1) {
                        $items[$ps]['items']['up'] = array(
                            'permissions' => 180,
                            'menu_title' => 5495,
                            'param_name' => 'action',
                        );
                    }
                    $this->all->db->query($reqc, 5409);
                    if (($resc = $this->all->db->fetch_row($reqc)) && $res['ord'] < $resc[0]) {
                        $items[$ps]['items']['down'] = array(
                            'permissions' => 180,
                            'menu_title' => 5496,
                            'param_name' => 'action',
                        );
                    }
                    $this->all->db->free_result($reqc);
                    $items[$ps]['items']['remove'] = array(
                        'permissions' => 180,
                        'menu_title' => 5405,
                        'param_name' => 'action',
                        'menu_link_params' => $remove_params,
                        'menu_row_params' => $remove_row_params,
                    );
                }
                $this->all->db->free_result($req);
            }
        }
        /* commodities } */

        /* files { */
        if (count($path) > 1 && $path[0] == 'preview') {
            $ps = strval($path[1]);
            $this->tree_left['preview'] = array(
                'permissions' => -1,
                'page_title' => 500,
                'filename' => 'includes/preview.inc.php',
                'items' => array(
                    $ps => array(
                        'permissions' => -1,
                        'param_name' => 'action',
                    ),
                ),
            );
            if (isset($path[2])) {
                $this->tree_left['preview']['items'][$ps]['items'] = array(
                    strval($path[2]) => array(
                        'permissions' => -1,
                        'param_name' => 'action',
                    ),
                );
            }
        } else if (count($path) > 1 && $path[0] == 'temp') {
            $ps = strval($path[1]);
            $this->tree_left['temp'] = array(
                'permissions' => -1,
                'page_title' => 501,
                'filename' => 'includes/preview.inc.php',
                'items' => array(
                    $ps => array(
                        'permissions' => -1,
                        'param_name' => 'action',
                    ),
                ),
            );
            if (isset($path[2])) {
                $this->tree_left['temp']['items'][$ps]['items'] = array(
                    strval($path[2]) => array(
                        'permissions' => -1,
                        'param_name' => 'action',
                    ),
                );
            }
        }
        /* files } */

        /* admins { */
        $this->tree_right['admins'] = array(
            'permissions' => 0,
            'menu_title' => 700,
            'page_title' => 701,
            'page_caption' => 701,
            'filename' => 'includes/admins.inc.php',
        );
        if (isset($path[0]) && $path[0] == 'admins') {
            $this->tree_right['admins']['items'] = array(
                'add' => array(
                    'permissions' => 1,
                    'menu_title' => 702,
                    'page_title' => 703,
                    'page_caption' => 703,
                    'param_name' => 'action',
                ),
            );
            $items =& $this->tree_right['admins']['items'];
            if (isset($path[1]) && $path[1] != 'add') {
                $this->all->db->query($req, 702, '%id%', intval($path[1]));
                if ($res = $this->all->db->fetch_assoc($req)) {
                    $this->items['admin'] = $res;
                    $items['s0001'] = array(
                        'permissions' => 1,
                        'menu_item_type' => 1,
                    );
                    $ps = strval($res['id']);
                    $items[$ps] = array(
                        'permissions' => 1,
                        'menu_title' => $this->all->io->str_cut($this->all->io->user_longname($res['fname'], $res['sname'], $res['lname']), 30),
                        'page_title' => 704,
                        'page_caption' => 704,
                        'param_name' => 'id',
                        'items' => array(
                            'remove' => array(
                                'permissions' => 1,
                                'menu_title' => 705,
                                'param_name' => 'action',
                                'menu_link_params' => $remove_params,
                                'menu_row_params' => $remove_row_params,
                            ),
                        ),
                    );
                }
                $this->all->db->free_result($req);
            }
        }
        /* admins } */

        /* texts { */
        $this->tree_right['texts'] = array(
            'permissions' => 30,
            'menu_title' => 1500,
            'page_title' => 1501,
            'page_caption' => 1501,
            'filename' => 'includes/texts.inc.php',
        );
        if (isset($path[0]) && $path[0] == 'texts') {
            $this->tree_right['texts']['items'] = array(
                'add' => array(
                    'permissions' => 30,
                    'menu_title' => 1502,
                    'page_title' => 1503,
                    'page_caption' => 1503,
                    'param_name' => 'action',
                ),
            );
            $items =& $this->tree_right['texts']['items'];
            if (isset($path[1]) && $path[1] != 'add') {
                $this->all->db->query($req, 1500, '%id%', intval($path[1]));
                if ($res = $this->all->db->fetch_assoc($req)) {
                    $this->items['text'] = $res;
                    $items['s0001'] = array(
                        'permissions' => 30,
                        'menu_item_type' => 1,
                    );
                    $ps = strval($res['id']);
                    $items[$ps] = array(
                        'permissions' => 30,
                        'menu_title' => $this->all->io->str_cut($res['text_' . $this->all->io->lang_active], 30),
                        'page_title' => 1504,
                        'page_caption' => 1504,
                        'param_name' => 'id',
                        'items' => array(
                            'remove' => array(
                                'permissions' => 30,
                                'menu_title' => 1505,
                                'param_name' => 'action',
                                'menu_link_params' => $remove_params,
                                'menu_row_params' => $remove_row_params,
                            ),
                        ),
                    );
                }
                $this->all->db->free_result($req);
            }
        }
        /* texts } */

        /* params { */
        $this->tree_right['params'] = array(
            'permissions' => 20,
            'menu_title' => 1300,
            'page_title' => 1301,
            'page_caption' => 1301,
            'filename' => 'includes/params.inc.php',
        );
        if (isset($path[0]) && $path[0] == 'params') {
            $this->tree_right['params']['items'] = array(
                'add' => array(
                    'permissions' => 20,
                    'menu_title' => 1302,
                    'page_title' => 1303,
                    'page_caption' => 1303,
                    'param_name' => 'action',
                ),
            );
            $items =& $this->tree_right['params']['items'];
            if (isset($path[1]) && $path[1] != 'add') {
                $this->all->db->query($req, 1300, '%id%', intval($path[1]));
                if ($res = $this->all->db->fetch_assoc($req)) {
                    $this->items['param'] = $res;
                    $items['s0001'] = array(
                        'permissions' => 20,
                        'menu_item_type' => 1,
                    );
                    $ps = strval($res['id']);
                    $items[$ps] = array(
                        'permissions' => 20,
                        'menu_title' => $this->all->io->str_cut($res['srvnotes'], 30),
                        'page_title' => 1304,
                        'page_caption' => 1304,
                        'param_name' => 'id',
                        'items' => array(
                            'remove' => array(
                                'permissions' => 20,
                                'menu_title' => 1305,
                                'param_name' => 'action',
                                'menu_link_params' => $remove_params,
                                'menu_row_params' => $remove_row_params,
                            ),
                        ),
                    );
                }
                $this->all->db->free_result($req);
            }
        }
        /* params } */

        /* templates { */
        $this->tree_right['templates'] = array(
            'permissions' => 10,
            'menu_title' => 1100,
            'page_title' => 1101,
            'page_caption' => 1101,
            'filename' => 'includes/templates.inc.php',
        );
        /* templates } */

        if ($admin['typeid'] == 10 && isset($path[0]) && isset($this->tree_right[$path[0]])) {
            $this->tree_left['news']['menu_title'] = 206;
            foreach ($this->tree_left as $k => $v) {
                if ($k !== 'news') {
                    unset($this->tree_left[$k]);
                }
            }
        } else {
            if ($admin['typeid'] == 30) {
                unset($this->tree_left['index']['menu_title']);
            }
            $this->tree_right['admins']['menu_title'] = 204;
            $this->tree_right['admins']['page_title'] = 205;
            $this->tree_right['admins']['page_caption'] = 205;
            foreach ($this->tree_right as $k => $v) {
                if ($k !== 'admins') {
                    unset($this->tree_right[$k]);
                }
            }
        }
    }

    function _tree_refresh(&$tree_items)
    {
        foreach ($this->all->path as $p) {
            $ps = strval($p);
            if (!(isset($tree_items[$ps]) && $this->all->auth->check_permissions($tree_items[$ps]['permissions']))) {
                break;
            }
            $tree_items[$ps]['menu_active'] = true;
            if (!isset($tree_items[$ps]['items'])) {
                break;
            }
            $tree_items =& $tree_items[$ps]['items'];
        }
    }

    function get_params()
    {
        $this->params = array(
            'filename' => '',
        );
        $error_left = $this->_get_params($this->tree_left);
        $error_right = $this->_get_params($this->tree_right);
        return ($error_left < 2 && $error_right < 2 ? ($error_left == 1 && $error_right == 1 ? 1 : 0) : 2);
    }

    function _get_params(&$tree_items)
    {
        $error = 0;
        $a = array();
        foreach ($this->all->path as $p) {
            $ps = strval($p);
            if (isset($tree_items[$ps])) {
                $tree_item =& $tree_items[$ps];
                if ($this->all->auth->check_permissions($tree_item['permissions'])) {
                    $tree_item['menu_active'] = true;
                    if (isset($tree_item['page_title'])) {
                        $this->all->tpl->add_title((is_string($tree_item['page_title']) ? $tree_item['page_title'] : $this->all->io->output($tree_item['page_title'])));
                    }
                    if (isset($tree_item['page_caption'])) {
                        $this->all->tpl->add_caption((is_string($tree_item['page_caption']) ? $tree_item['page_caption'] : $this->all->io->output($tree_item['page_caption'])));
                    }
                    if (isset($tree_item['param_name'])) {
                        $this->params[$tree_item['param_name']] = $ps;
                    }
                    if (!empty($tree_item['filename'])) {
                        $this->params['filename'] = $tree_item['filename'];
                    }
                    if (isset($tree_item['items'])) {
                        $tree_items =& $tree_item['items'];
                    } else {
                        $tree_items =& $a;
                    }
                } else {
                    $error = 2;
                    break;
                }
            } else {
                $error = 1;
                break;
            }
        }
        return $error;
    }

}

?>