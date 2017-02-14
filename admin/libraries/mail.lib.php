<?php

/*
*/

class cMail
{
    var
        $line_width_max,
        $line_ending,
        $all;

    function cMail(&$all)
    {
        $this->all =& $all;
        $this->all->finclude('config/mail.cfg.php', $this);
    }

    function wrap($s, $endings_count = 1, $spacer_length = 0)
    {
        $spacer = str_repeat(' ', $spacer_length);
        return wordwrap(str_replace("\n", "\n" . $spacer, $s), $this->line_width_max - $spacer_length, $this->line_ending . $spacer, false) . str_repeat($this->line_ending, $endings_count);
    }

    function send_plaintext($recepients, $subject, $message, $sender, $attachments = array())
    {
        switch ($this->line_ending) {
            case "\r\n":
                $message = str_replace(array("\r", "\n"), array('', "\r\n"), $message);
                break;
            case "\n":
                $message = str_replace("\r", '', $message);
                break;
        }
        if (count($attachments) == 0) {
            return mail(
                $recepients,
                $this->encode_subj($subject),
                $this->encode_body($message),
                'From: ' . $this->encode_sender($sender) .
                $this->line_ending . 'MIME-Version: 1.0' .
                $this->line_ending . 'Content-Type: text/plain; charset=windows-1251' .
                $this->line_ending . 'Content-Transfer-Encoding: quoted-printable'
            );
        } else {
            $mime_boundary = '--' . md5($subject . $recepients . time());
            $attachments_enc = '';
            foreach ($attachments as $attachment) {
                $attachments_enc .=
                    $this->line_ending . $this->line_ending . '--' . $mime_boundary .
                    $this->line_ending . 'Content-Type: ' . $this->get_mime_type($this->all->io->get_file_ext($attachment['orig'])) . ';' .
                    $this->line_ending . "\t" . 'name="' . $attachment['orig'] . '"' .
                    $this->line_ending . 'Content-Transfer-Encoding: base64' .
                    $this->line_ending . 'Content-Disposition: attachment;' .
                    $this->line_ending . "\t" . 'filename="' . $attachment['orig'] . '"' .
                    $this->line_ending . $this->line_ending . chunk_split(base64_encode(file_get_contents($this->all->frontend_rw_dir . '/' . $attachment['saved'])));
            }
            switch ($this->line_ending) {
                case "\r\n":
                    $attachments_enc = str_replace(array("\r", "\n"), array('', "\r\n"), $attachments_enc);
                    break;
                case "\n":
                    $attachments_enc = str_replace("\r", '', $attachments_enc);
                    break;
            }
            return mail(
                $recepients,
                $this->encode_subj($subject),
                '--' . $mime_boundary .
                $this->line_ending . 'Content-Type: text/plain; charset=windows-1251' .
                $this->line_ending . 'Content-Transfer-Encoding: quoted-printable' .
                $this->line_ending . $this->line_ending . $this->encode_body($message) .
                $attachments_enc .
                $this->line_ending . $this->line_ending . '--' . $mime_boundary . '--',
                'From: ' . $this->encode_sender($sender) .
                $this->line_ending . 'MIME-Version: 1.0' .
                $this->line_ending . 'Content-Type: multipart/mixed;' .
                $this->line_ending . "\t" . 'boundary="' . $mime_boundary . '"'
            );
        }
        return true;
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

    function encode_sender($s)
    {
        return (($i = strrpos($s, '<')) && $s{--$i} == ' ' ? $this->encode_subj(substr($s, 0, $i)) . substr($s, $i) : $s);
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

}

?>