<?php

/*
*/

class cAuthorization
{
    var
        $logged_in,
        $admin,
        $allowed_filetypes,
        $all;

    function cAuthorization(&$all)
    {
        $this->all =& $all;
        $this->all->finclude('config/auth.cfg.php', $this);
        $this->admin = false;
        $this->logged_in = false;
    }

    function login($login = '', $password = '', $update_last_in = true)
    {
        if ($login == '' || $password == '') {
            $login = $this->all->session_get_var('login', '');
            $password = $this->all->session_get_var('password', '');
        }
        $this->all->db->query($req, 1, array('%login%', '%pw%'), array($login, $password));
        $res = $this->all->db->fetch_assoc($req);
        $this->all->db->free_result($req);
        if ($res) {
            $this->admin = $res;
            $this->admin['params'] = unserialize($this->admin['params']);
            $this->all->session_set_var('login', $res['login']);
            $this->all->session_set_var('password', $res['password']);
            if ($update_last_in) {
                $this->all->db->query($req, 2, array('%id%', '%lastin%', '%lastip%'), array($res['id'], time(), ip2long($this->all->ip)));
            }
            $this->logged_in = true;
        } else {
            $this->all->session_set_var('login', '');
            $this->all->session_set_var('password', '');
            $this->admin = false;
            $this->logged_in = false;
        }
        return $this->logged_in;
    }

    function check_permissions($permissions)
    {
        return ($permissions == -2 || ($permissions == -1 && $this->logged_in) || ($permissions >= 0 && $permissions < strlen($this->admin['permissions']) && $this->admin['permissions']{$permissions} == '1') ? true : false);
    }

    function set_param($id, $value)
    {
        $params =& $this->admin['params'];
        if (!(isset($params[$id]) && $params[$id] == $value)) {
            $params[$id] = $value;
            $s = serialize($params);
            $this->all->db->query($req, 3, array('%params%', '%id%'), array($s, $this->admin['id']));
        }
    }

}

?>