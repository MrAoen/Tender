<?php

/*
*/

class cAll
{
    var
        $db,
        $auth,
        $map,
        $io,
        $tpl,
        $prj,
        $mail,
        $admin_abs_home,
        $admin_rel_home,
        $tmp_dir,
        $img_composite_path,
        $img_convert_path,
        $zip_path,
        $frontend_abs_home,
        $frontend_css_dir,
        $frontend_rw_dir,
        $frontend_srv_rw_dir,
        $prefix,
        $display_errors,
        $max_execution_time,
        $page_uri,
        $page_query,
        $path,
        $ip,
        $vclose,
        $write_log;

    function cAll()
    {
        $this->finclude('config/all.cfg.php', $this);
        ini_set('display_errors', $this->display_errors);
        ini_set('max_execution_time', $this->max_execution_time);
        $this->vclose = array();
        $this->page_uri = $_SERVER['REQUEST_URI'];
        $this->page_query = ($_SERVER['QUERY_STRING'] == '' ? '' : '?' . $_SERVER['QUERY_STRING']);
        $this->path = array();
        $this->ip = preg_replace('/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}).*/', '$1', substr((empty($_SERVER['REMOTE_ADDR']) ? (empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? (empty($_SERVER['HTTP_CLIENT_IP']) ? '' : $_SERVER['HTTP_CLIENT_IP']) : $_SERVER['HTTP_X_FORWARDED_FOR']) : $_SERVER['REMOTE_ADDR']), 0, 500));
        if ($this->ip == '' || $this->ip == '::1') {
            $this->ip = '0.0.0.0';
        }
        $query = explode('/', preg_replace(array('/\?.*$/', '/[^a-z0-9\-\_\.\/]+/'), array('', ''), strtolower(substr($this->page_uri, 0, 1000))));
        $e = explode('/', preg_replace(array('/^\/+/', '/\/+$/'), array('', ''), $this->admin_rel_home));
        $i = 0;
        foreach ($query as $q) {
            if (!($q == '' || (isset($e[$i]) && $e[$i] == $q))) {
                $this->path[] = $q;
                ++$i;
            }
        }
        $this->finclude('libraries/db.lib.php', $this);
        $this->finclude('libraries/auth.lib.php', $this);
        $this->finclude('libraries/map.lib.php', $this);
        $this->finclude('libraries/io.lib.php', $this);
        $this->finclude('libraries/tpl.lib.php', $this);
        $this->finclude('libraries/prj.lib.php', $this);
        $this->finclude('libraries/mail.lib.php', $this);
        $this->db = new cDataBase($this);
        $this->auth = new cAuthorization($this);
        $this->map = new cMap($this);
        $this->io = new cIO($this);
        $this->tpl = new cTemplates($this);
        $this->prj = new cProject($this);
        $this->mail = new cMail($this);
        session_start();
        $this->db->connect();
        if ($this->auth->login()) {
            $this->append_log($this->auth->admin['id']);
        } else {
            $this->append_log('-');
        }
        $this->map->tree_create();
        $get_params_error = $this->map->get_params();
        if (!($get_params_error == 0 && is_file($this->map->params['filename']))) {
            if ($this->auth->logged_in) {
                $this->redirect('index/');
            } else {
                $this->session_set_var('login_redirect', $this->page_uri);
                if ($this->path != array('login')) {
                    $this->redirect('login/');
                }
            }
        }
        $this->tpl->smarty_init_after_login();
        $this->finclude($this->map->params['filename'], $this);
        $this->close();
    }

    function finclude($filename, &$caller, $inc_type = 0)
    {
        switch ($inc_type) {
            case 0:
                require_once($filename);
                break;
            case 1:
                require($filename);
                break;
            case 2:
                include_once($filename);
                break;
            case 3:
                include($filename);
                break;
        }
    }

    function append_log($s = '')
    {
        if (!$this->write_log) {
            return;
        }
        list($usec, $sec) = explode(' ', microtime());
        $splitter = "\t";
        $ending = "\r\n";
        $log = date('Y-m-d H:i:s') . (strlen($usec) > 1 && $usec{0} == '0' ? substr($usec, 1) : $usec) . $splitter . $this->page_uri . $splitter . $this->ip . $splitter . $s . $ending;
        if (($f = @fopen($this->tmp_dir . '/log.txt', 'ab'))) {
            @fwrite($f, $log);
            @fclose($f);
        }
    }

    function redirect($uri, $ad = 2, $k = '', $v = '')
    {
        $uri = $this->uri($uri, $ad, $k, $v);
        $this->close();
        if (!headers_sent()) {
            @header('location: ' . $uri);
        }
        exit(
            '<html>' .
            '<head>' .
            '<meta http-equiv="refresh" content="1; url=' . $uri . '" />' .
            '<meta http-equiv="expires" content="Thu, 01 Jan 1970 02:00:00 GMT" />' .
            '<meta http-equiv="cache-control" content="no-cache" />' .
            '<meta http-equiv="pragma" content="no-cache" />' .
            '<script language="javascript" type="text/javascript">' .
            "<!--\n" .
            'window.location.href = "' . $uri . '";' .
            "//-->\n" .
            '</script>' .
            '</head>' .
            '<body>' .
            '<layer name="da">' .
            '<div id="da">' .
            '<a href="' . $uri . '">' . $uri . '</a>' .
            '</div>' .
            '</layer>' .
            '<script language="javascript" type="text/javascript">' .
            "<!--\n" .
            'var d = document, n = "da", s = d.getElementById ? d.getElementById(n).style : d.all ? d.all[n].style : d.layers ? d.layers[n] : null;' .
            'if (s)' .
            '{' .
            's.visibility = navigator.appName == "Netscape" && !d.getElementById ? "hide" : "hidden";' .
            's.display = "none";' .
            '}' .
            "//-->\n" .
            '</script>' .
            '</body>' .
            '</html>'
        );
    }

    function uri($uri, $ad = 1, $k = '', $v = '')
    {
        if ($ad == 1) {
            $uri = $this->admin_rel_home . $uri;
        } else if ($ad == 2) {
            $uri = $this->admin_abs_home . $uri;
        }
        if ($k == '') {
            return $uri;
        }
        $q = strpos($uri, '?');
        if ($q === false) {
            $q = strpos($uri, '#');
            return ($q === false ? $uri . '?' . $k . '=' . $v : substr($uri, 0, $q) . '?' . $k . '=' . $v . substr($uri, $q));
        }
        if (strpos($uri, '&' . $k . '=', $q + 1) === false && substr($uri, $q + 1, strlen($k) + 1) != $k . '=') {
            $q = strpos($uri, '#', $q + 1);
            return ($q === false ? $uri . '&' . $k . '=' . $v : substr($uri, 0, $q) . '&' . $k . '=' . $v . substr($uri, $q));
        }
        return preg_replace('/([\\?\\&])' . $k . '=[^\\&\\#]*/', '$1' . $k . '=' . $v, $uri);
    }

    function close()
    {
        foreach ($this->vclose as $k => $v) {
            $this->$k->close();
        }
    }

    function session_set_var($k, $v)
    {
        $_SESSION[$this->prefix . $k] = $v;
    }

    function add_close_handler($k)
    {
        if (!isset($this->vclose[$k])) {
            $this->vclose[$k] = true;
        }
    }

    function remove_close_handler($k)
    {
        if (isset($this->vclose[$k])) {
            unset($this->vclose[$k]);
        }
    }

    function fexit($id = '###')
    {
        //echo '###-'.$id;
        //return;
        $this->redirect('error.php');
    }

    function session_get_var($k, $default = false)
    {
        return (isset($_SESSION[$this->prefix . $k]) ? $_SESSION[$this->prefix . $k] : $default);
    }

    function get_param($param_name)
    {
        $this->db->query($req, 13, '%param%', $param_name);
        $res = $this->db->fetch_assoc($req);
        $this->db->free_result($req);
        return ($res ? $res['value'] : false);
    }

    function set_param($param, $value)
    {
        $this->db->query($req, 14, array('%param%', '%value%'), array($param, $value));
    }

}

?>