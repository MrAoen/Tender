<?php

/*
*/

class cProject
{
    var
        $enable_zip_archives,
        $day_length,
        $overdue_critical,
        $graph,
        $all;

    function cProject(&$all)
    {
        $this->all =& $all;
        $this->all->finclude('config/prj.cfg.php', $this);
    }

}

?>