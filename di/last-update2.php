<?php
//date_default_timezone_set('Asia/Almaty');

//@mysql_connect('localhost','cpet_preforma','Dx0oV335');
$db_link = new mysqli('localhost', 'retalkz', '89nb8Jc3Ti', 'retalkz_tender');

//$db_link = new mysqli('localhost','root','demon','retalkz_tender');
//mysql_select_db('cpet_preforma');

$time = '1461040200';


//$sql_tender=mysql_query("SELECT * FROM dptenders WHERE iscomplete='0'");
$sql_tender = $db_link->query("SELECT * FROM dptenders WHERE iscomplete='0'", MYSQLI_STORE_RESULT);
//while($rowdp=mysql_fetch_array($sql_tender))
while ($rowdp = $sql_tender->fetch_array()) {
    $shedareba = (60 * $rowdp['length']);

    if (time() > $shedareba) {
        //mysql_query("UPDATE dptenders SET iscomplete='1' WHERE number='".$rowdp['number']."'");
        $db_link->query("UPDATE dptenders SET iscomplete='1' WHERE number='" . $rowdp['number'] . "'");
    }

}
$sql_tender->free_result();

$numberi = intval($_GET['number']);
if (!empty($numberi)) {
//mysql_query("UPDATE dptenders SET iscomplete='1' WHERE number='$numberi'");
    $db_link->query("UPDATE dptenders SET iscomplete='1' WHERE number='$numberi'");

}

?>