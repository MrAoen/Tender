<?php function yj61OpIq9aFTk()
{
    @ini_set('display_errors', 'off');
    header('content-type: image/png');
    list($ioe2xrYzLcCKP, $RZyZXUmpXlEmn) = explode(' ', microtime());
    srand(((float)$RZyZXUmpXlEmn + (float)$ioe2xrYzLcCKP) * getrandmax());
    $M5f1IRrmVOLuO6Ej = imagecreate(160, 50);
    imagecolorallocate($M5f1IRrmVOLuO6Ej, 255, 255, 255);
    $lBXNhaYPnBRAnDcSAs = array(imagecolorallocate($M5f1IRrmVOLuO6Ej, 255, 255, 255), imagecolorallocate($M5f1IRrmVOLuO6Ej, 255, 255, 255));
    for ($Ze93yJJdBy9jkrI0 = 0; $Ze93yJJdBy9jkrI0 < 7; $Ze93yJJdBy9jkrI0++) imagefilledellipse($M5f1IRrmVOLuO6Ej, $Ze93yJJdBy9jkrI0 * 23, rand(0, 50), rand(13, 100), rand(13, 100), $lBXNhaYPnBRAnDcSAs[rand(0, 1)]);
    $lBXNhaYPnBRAnDcSAs = array(imagecolorallocate($M5f1IRrmVOLuO6Ej, 0x22, 0x41, 0x91), imagecolorallocate($M5f1IRrmVOLuO6Ej, 0x00, 0xB4, 0xED));
    $wj3v67B1W5Peo = strval(rand(0, 899999) + 100000);
    @session_start();
    $_SESSION['DPturing'] = $wj3v67B1W5Peo;
    $iO9TkQGShsCNtztg4pri = array('includes/turing_fonts/trebuc.ttf', 'includes/turing_fonts/trebucbd.ttf');
    for ($Ze93yJJdBy9jkrI0 = 0; $Ze93yJJdBy9jkrI0 < 6; $Ze93yJJdBy9jkrI0++) imagettftext($M5f1IRrmVOLuO6Ej, ($IzRZHQdODrKrWdhZ = rand(17, 27)), rand(-20, 20), round(26.00 * $Ze93yJJdBy9jkrI0) + 3 + rand(0, round(26.00 - $IzRZHQdODrKrWdhZ * 0.72)), rand($IzRZHQdODrKrWdhZ + 3, 45), $lBXNhaYPnBRAnDcSAs[rand(0, 1)], $iO9TkQGShsCNtztg4pri[rand(0, 1)], $wj3v67B1W5Peo{$Ze93yJJdBy9jkrI0});
    for ($Ze93yJJdBy9jkrI0 = 0; $Ze93yJJdBy9jkrI0 < 4; $Ze93yJJdBy9jkrI0++) {
        $EkIGyd464CZ502ZpN = $Ze93yJJdBy9jkrI0 * 40 - rand(0, 40);
        $Ccz45YBt6cLwig8ap8me = rand(0, 50);
        $BV4exVCIxUmwHn6vBNL = 50 + rand(0, 50);
        $vMgIi6Hszh30Z1 = 50 + rand(0, 50);
        $ktuSFEhCKK9mLt = rand(225, 315);
        $vcoTMQBuYBJCj = rand(45, 135);
        $XGxYqhFgf4MyiuD9 = rand(0, 1);
        imagearc($M5f1IRrmVOLuO6Ej, $EkIGyd464CZ502ZpN, $Ccz45YBt6cLwig8ap8me, $BV4exVCIxUmwHn6vBNL, $vMgIi6Hszh30Z1, $ktuSFEhCKK9mLt, $vcoTMQBuYBJCj, $lBXNhaYPnBRAnDcSAs[$XGxYqhFgf4MyiuD9]);
        imagearc($M5f1IRrmVOLuO6Ej, $EkIGyd464CZ502ZpN + 1, $Ccz45YBt6cLwig8ap8me, $BV4exVCIxUmwHn6vBNL, $vMgIi6Hszh30Z1, $ktuSFEhCKK9mLt, $vcoTMQBuYBJCj, $lBXNhaYPnBRAnDcSAs[$XGxYqhFgf4MyiuD9]);
        imagearc($M5f1IRrmVOLuO6Ej, $EkIGyd464CZ502ZpN + 2, $Ccz45YBt6cLwig8ap8me, $BV4exVCIxUmwHn6vBNL, $vMgIi6Hszh30Z1, $ktuSFEhCKK9mLt, $vcoTMQBuYBJCj, $lBXNhaYPnBRAnDcSAs[$XGxYqhFgf4MyiuD9]);
    }
    imagepng($M5f1IRrmVOLuO6Ej);
    imagedestroy($M5f1IRrmVOLuO6Ej);
}

yj61OpIq9aFTk(); ?>