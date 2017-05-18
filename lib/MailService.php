<?php


namespace MissionNext\lib;


class MailService {

    public $fromName = "MissionNext";
    public $from;
    public $contentType = 'text/plain';
    public $charset;

    public function __construct(){
        add_filter('wp_mail_from', array($this, 'hookFrom'));
        add_filter('wp_mail_from_name', array($this, 'hookFromName'));
        add_filter('wp_mail_content_type', array($this, 'hookContentType'));
        add_filter('wp_mail_charset', array($this, 'hookCharset'));
    }

    public function send($to, $subject, $message, $headers = '', $attachments = array(), $reset = true){
        $status = wp_mail($to, $subject, $message, $headers, $attachments );

        if($reset){
            $this->reset();
        }

        return $status;
    }

    public function hookFromName($name){
        return $this->fromName ? $this->fromName : $name;
    }
    public function hookFrom($email){
        return $this->from ? $this->from : $email;
    }
    public function hookContentType($content_type){
        return $this->contentType ? $this->contentType : $content_type;
    }
    public function hookCharset($charset){
        return $this->charset ? $this->charset : $charset;
    }

    public function reset(){
        $this->from = null;
        $this->fromName = null;
        $this->contentType = null;
        $this->charset = null;
    }

} 