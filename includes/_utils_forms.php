<?php
/*
*/

/*$smtp_server = 'mail.caucasianpet.com';
$smtp_username = 'preforma@caucasianpet.com';
$smtp_passwd = 'QbZ5w5sW';*/
$smtp_server = 'mail.retal.kz';
$smtp_username = 'info@retal.kz';
$smtp_passwd = '89nb8Jc3Ti';

function turing_check($turing)
{
    return (
        $turing != ''
        && isset($_SESSION['DPturing'])
        && $_SESSION['DPturing'] == $turing
    );
}

function get_template($template_name)
{
    db__query($req, "SELECT `%value%` FROM `%prefix%templates` LIMIT 1", '%value%', $template_name);
    //$res = mysql_fetch_row($req);
    $res = $req->fetch_row();
    //mysql_free_result($req);
    $result = ($res ? $res[0] : '');
    $req->free_result();
    return $result;
}

class cMail
{
    var
        $line_width_max = 75,
        $line_ending = "\n",
        $frontend_rw_dir = 'di';

    function cMail()
    {
    }

    function encode_sender($s)
    {
        return (($i = strrpos($s, '<')) && $s{--$i} == ' ' ? $this->encode_subj(substr($s, 0, $i)) . substr($s, $i) : $s);
    }

    function encode_subj($s)
    {
        return '=?windows-1251?Q?' . str_replace(array("\r", "=\n"), array('', ''), $this->bit8($s)) . '?=';
    }

    function bit8($s)
    {
        $s = preg_replace('/[^\x21-\x3C\x3E-\x7E\x09\x20]/e', 'sprintf("=%02x", ord("$0"));', $s);
        preg_match_all('/.{1,73}([^=]{0,3})?/', $s, $a);
        return implode('=' . "\n", $a[0]);
    }

    function encode_body($s)
    {
        return $this->bit8($s);
    }

    function wrap($s, $endings_count = 1, $spacer_length = 0)
    {
        $spacer = str_repeat(' ', $spacer_length);
        return wordwrap(str_replace("\n", "\n" . $spacer, $s), $this->line_width_max - $spacer_length, $this->line_ending . $spacer, false) . str_repeat($this->line_ending, $endings_count);
    }

    function get_mime_type($ext)
    {
        switch ($ext) {
            case 'jpg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'doc':
                return 'application/msword';
            case 'xls':
                return 'application/vnd.ms-excel';
            case 'pdf':
                return 'application/pdf';
            case 'zip':
                return 'application/zip';
            case 'rar':
                return 'application/rar';
            case 'gz':
                return 'application/x-gzip';
            case 'tar':
                return 'application/x-tar';
            case 'avi':
                return 'video/x-msvideo';
            case 'mpeg':
                return 'video/mpeg';
            case 'mpg':
                return 'video/mpeg';
            case 'mov':
                return 'video/quicktime';
            case 'qt':
                return 'video/quicktime';
            default:
                return 'application/zip';
        }
    }

    function get_file_ext($filename)
    {
        return strtolower(substr(strrchr($filename, '.'), 1, 10));
    }

    function send_plaintext($recepients, $subject, $message, $sender, $attachments = array())
    {
        global $smtp_mail;
        $smtp_mail->add_text($message);
        $smtp_mail->build_message('win');
        $smtp_mail->send($recepients, $sender, $subject);
        $smtp_mail->clearAll();
        return true;
    }

}

function _HMAC_MD5($key, $data)
{
    if (strlen($key) > 64) {
        $key = pack('H32', md5($key));
    }

    if (strlen($key) < 64) {
        $key = str_pad($key, 64, chr(0));
    }

    $k_ipad = substr($key, 0, 64) ^ str_repeat(chr(0x36), 64);
    $k_opad = substr($key, 0, 64) ^ str_repeat(chr(0x5C), 64);

    $inner = pack('H32', md5($k_ipad . $data));
    $digest = md5($k_opad . $inner);

    return $digest;
}

class SMTP_Mail
{
    var $socket = null;
    var $timeout = 10.0;

    var $port = 25;
    var $host = '';

    var $user = '';
    var $pass = '';

    var $from = '';
    var $mail_to = '';

    var $mime = '';
    var $headers = '';
    var $html = '';
    var $multipart = '';
    var $plain_text = '';

    var $parts = array();

    var $auth_methods = array('DIGEST-MD5', 'CRAM-MD5', 'LOGIN', 'PLAIN');

    var $smtp_params = array();
    var $line_ending = '\r\n';

    function SMTP_Mail($host = "localhost", $user = "free", $pass = "pass")
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;

        $this->clearAll();
    }

    function clearAll()
    {
        $this->html = '';
        $this->plain_text = '';
        $this->multipart = '';
        $this->headers = '';
        $this->mime = '';
        $this->parts = array();
    }

    function send($to, $from, $subject)
    {
        $this->from = $from;
        $this->mail_to = $to;
        $this->subject = $subject;

        $this->connect();
        $this->parse_response(220);

        $this->sendHello();
        $this->auth();
        $this->sendFrom();
        $this->sendTo();
        $this->sendData();
        $this->sendQuit();
    }

    function connect()
    {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errmsg, $this->timeout);
        if (!$this->socket)
            $this->error($php_errormsg);

        stream_set_blocking($this->socket, true);
    }

    function error($errmsg)
    {
        die("<font color='red'><b>Ошибка:</b> $errmsg</font><br>");
    }

    function parse_response($_code)
    {
        $lines = $this->readLine();
        $this->smtp_params = array();

        foreach ($lines as $line) {
            $code = (int)substr($line, 0, 3);
            $text = substr($line, 4);
            $text = trim($text);

            if ($code > 400)
                $this->error($text);

            if ($code != $_code)
                $this->error('Неожиданный ответ сервера');


            $params = explode(' ', $text);
            foreach ($params as $param)
                $this->smtp_params[] = trim($param);
        }
    }

    function readLine()
    {
        if (!$this->socket)
            $this->error('Не установлено соединение');

        $text = fread($this->socket, 4096);
        $text = trim($text);
        $line = explode("\n", $text);

        if (count($line) == 1 && substr($text, 3, 1) == "-")
            return $this->readLine();

        return $line;
    }

    function sendHello()
    {

        $this->put('EHLO', $this->host);//$_SERVER['SERVER_NAME']);
        $this->parse_response(250);
    }

    function put($command, $arguments = '')
    {
        if ($arguments == '')
            fwrite($this->socket, "$command\r\n");
        else
            fwrite($this->socket, "$command $arguments\r\n");
    }

    function auth()
    {
        if (array_search('AUTH', $this->smtp_params) === false)
            return;

        foreach ($this->auth_methods as $method) {
            if (!array_search($method, $this->smtp_params))
                continue;

            switch ($method) {
                case 'LOGIN':
                    $this->auth_PLANE();
                    break;
                case 'CRAM-MD5':
                    $this->auth_CRAM_MD5();
                    return;
                case 'PLAIN':
                    $this->auth_PLANE();
                    return;
                case 'DIGEST-MD5':
                    break;
            }
        }
    }

    function auth_PLANE()
    {
        $this->put('AUTH', 'LOGIN');
        $this->parse_response(334);

        $auth_str = base64_encode($this->user);
        $this->put($auth_str);
        $this->parse_response(334);

        $auth_str = base64_encode($this->pass);
        $this->put($auth_str);
        $this->parse_response(235);
    }

    function auth_CRAM_MD5()
    {
        $this->put('AUTH', 'CRAM-MD5');
        $this->parse_response(334);

        $key = base64_decode($this->smtp_params[0]);
        $auth_str = base64_encode($this->user . ' ' . _HMAC_MD5($this->pass, $key));
        $this->put($auth_str);
        $this->parse_response(235);
    }

    function sendFrom()
    {
        $this->put('MAIL', "FROM: $this->from");
        $this->parse_response(250);
    }

    function sendTo()
    {
        $this->put('RCPT', "TO: $this->mail_to");
        $this->parse_response(250);
    }

    function sendData()
    {
        $this->put('DATA');
        $this->parse_response(354);

        $this->headers .= "To: $this->mail_to\r\nFrom: $this->from\r\nSubject: $this->subject\r\n";
        $text = "$this->headers\r\n$this->mime\r\n.";

        $this->put($text);
        $this->parse_response(250);
    }

    function sendQuit()
    {
        $this->put('QUIT');
    }

    function add_html($html = "")
    {
        $this->html .= $html;
    }

    function add_text($text = '')
    {
        $this->plain_text .= $text;
    }

    function build_message($kod)
    {
        $boundary = "=_" . md5(uniqid(time()));
        $this->headers .= "MIME-Version: 1.0\r\n";
        $this->headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
        $this->multipart = "";
        $this->multipart .= "This is a MIME encoded message.\r\n\r\n";
        $this->build_html($boundary, $kod);
        $this->build_text($boundary, $kod);
        for ($i = (count($this->parts) - 1); $i >= 0; $i--)
            $this->multipart .= "--$boundary\r\n" . $this->build_part($i);
        $this->mime = "$this->multipart--$boundary--\r\n";
    }

    function build_html($orig_boundary, $kod)
    {
        if ($this->html == '')
            return;

        $this->multipart .= "--$orig_boundary\r\n";
        if ($kod == 'w' || $kod == 'win' || $kod == 'windows-1251') $kod = 'windows-1251';
        else $kod = 'koi8-r';
        $this->multipart .= "Content-Type: text/html; charset=$kod\r\n";
        $this->multipart .= "Content-Transfer-Encoding: Quot-Printed\r\n\r\n";
        $this->multipart .= "$this->html\r\n\r\n";
    }

    function build_text($orig_boundary, $kod)
    {
        if ($this->plain_text == '')
            return;

        $this->multipart .= "--$orig_boundary\r\n";
        if ($kod == 'w' || $kod == 'win' || $kod == 'windows-1251') $kod = 'windows-1251';
        else $kod = 'koi8-r';
        $this->multipart .= "Content-Type: text/plain; charset=$kod\r\n";
        $this->multipart .= "Content-Transfer-Encoding: Quot-Printed\r\n\r\n";
        $this->multipart .= $this->plain_text . "\r\n\r\n";
    }

    function build_part($i)
    {
        $message_part = "";
        $message_part .= "Content-Type: " . $this->parts[$i]["c_type"];
        if ($this->parts[$i]["name"] != "")
            $message_part .= "; name = \"" . $this->parts[$i]["name"] . "\"\n";
        else
            $message_part .= "\n";
        $message_part .= "Content-Transfer-Encoding: base64\n";
        $message_part .= "Content-Disposition: attachment; filename = \"" .
            $this->parts[$i]["name"] . "\"\n\n";
        $message_part .= chunk_split(base64_encode($this->parts[$i]["body"])) . "\n";
        return $message_part;
    }

    function add_attachment($path = "", $name = "", $c_type = "application/octet-stream")
    {
        if (!file_exists($path . $name)) {
            print "File $path.$name dosn't exist.";
            return;
        }
        $fp = fopen($path . $name, "r");
        if (!$fp) {
            print "File $path.$name coudn't be read.";
            return;
        }
        $file = fread($fp, filesize($path . $name));
        fclose($fp);
        $this->parts[] = array("body" => $file, "name" => $name, "c_type" => $c_type);
    }
}

;

global $smtp_mail;
$smtp_mail = new SMTP_Mail($smtp_server, $smtp_username, $smtp_passwd);

?>