<?php

/*
*/

class cDataBase
{
    var
        $prefix,
        $all,
        $host,
        $user,
        $pass,
        $name,
        $link;

    function cDataBase(&$all)
    {
        $this->all =& $all;
        $this->all->finclude('config/db.cfg.php', $this);
    }

    function connect()
    {
        $this->all->add_close_handler('db');
        if (!empty($this->link)) {
            return;
        }
        //$this->link = mysql_connect($this->host, $this->user, $this->pass) or $this->all->fexit();

        $this->link = new mysqli($this->host, $this->user, $this->pass, $this->name) or $this->all->fexit(4);
        //mysql_select_db($this->name) or $this->all->fexit();
        if ($this->link->connect_error) {
            $this->all->fexit(5);
        }
        $this->query($req, 4);
    }

    function query(&$req, $id, $params = array(), $values = array())
    {
        //$req = mysql_query($this->query_prepare($this->queries[strval($id)], $params, $values), $this->link) or $this->all->fexit();
        $req = $this->link->query($this->query_prepare($this->queries[strval($id)], $params, $values), MYSQLI_STORE_RESULT) or $this->all->fexit($this->query_prepare($this->queries[strval($id)], $params, $values));
    }

    function query_prepare(&$query, &$params, &$values)
    {
        if (!is_array($values)) {
            $params = array($params);
            $values = array($values);
        }
        $params[] = '%prefix%';
        $values[] = $this->prefix;
        for ($i = 0; $i < count($values); $i++) {
            $values[$i] = $this->escape($values[$i]);
        }
        return str_replace($params, $values, $query);
    }

    function escape($s)
    {
        //return mysql_real_escape_string($s, $this->link);
        return $this->link->real_escape_string($s);
    }

    function close()
    {
        $this->all->remove_close_handler('db');
        if (!empty($this->link)) {
            //mysql_close($this->link);
            $this->link->close();
            $this->link = false;
        }
    }

    function transaction_begin()
    {
        $this->squery($req, 'BEGIN');
    }

    function squery(&$req, $query, $params = array(), $values = array())
    {
        //$req = mysql_query($this->query_prepare($query, $params, $values), $this->link) or $this->all->fexit();
        $req = $this->link->query($this->query_prepare($query, $params, $values), MYSQLI_STORE_RESULT) or $this->all->fexit(7);
    }

    function transaction_rollback()
    {
        $this->squery($req, 'ROLLBACK');
    }

    function transaction_commit()
    {
        $this->squery($req, 'COMMIT');
    }

    function fetch_assoc(&$req)
    {
        //return mysql_fetch_assoc($req);
        return $req->fetch_assoc();
    }

    function fetch_row(&$req)
    {
        //return mysql_fetch_row($req);
        return $req->fetch_row();
    }

    function num_rows(&$req)
    {
        //return mysql_num_rows($req);
        return $req->num_rows;
    }

    function free_result(&$req)
    {
        //return mysql_free_result($req);
        return $req->free_result();
    }

    function affected_rows()
    {
        //return mysql_affected_rows($this->link);
        return $this->link->affected_rows;
    }

    function insert_id()
    {
        //return mysql_insert_id($this->link);
        return $this->link->insert_id;
    }

}

?>