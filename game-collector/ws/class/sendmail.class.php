<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(1);

include_once 'db.class.php';
require_once('PHPMailer/class.phpmailer.php');

class Sendmail {
    private $error_message = null;
    private $fromMail = "hadougame@gmail.com";
    private $fromName = "Hadougame App";
    private $toMail = "hadougame@gmail.com";
    private $toName = "Hadougame App";
    private $subject = "";
    private $body = "";
    private $altBody = "";
    
    public function __construct(){
        $this->phpmailer = new PHPMailer();
    }

    public function setFrom($email, $name) {
        $this->fromMail = $email;
        $this->fromName = $name;
    }

    public function setTo($email, $name) {
        // Limpa todos os enderecos
        $this->phpmailer->ClearAddresses();

        $this->toMail = $email;
        $this->toName = $name;

        // Adiciona o email ao objeto
        //$this->phpmailer->AddAddress($this->toMail, $this->toName);
        $this->phpmailer->AddAddress($this->toMail, $this->toName);
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function setAltBody($body) {
        $this->altBody = $body;
    }

    public function setSubject($subject) {
        $accents = 'ÁÍÓÚÉÄÏÖÜËÀÌÒÙÈÃÕÂÎÔÛÊáíóúéäïöüëàìòùèãõâîôûêÇç'; 
        $subject = preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $subject ) );
        $this->subject = $subject;
    }

    public function send() {
        $result = false;
        try {
            $mail = $this->phpmailer;

            $mail->IsSMTP();
            $mail->Host = "localhost";

            //$mail->SMTPAuth = true;     // turn on SMTP authentication
            //$mail->Username = "jswan";  // SMTP username
            //$mail->Password = "secret"; // SMTP password

            $mail->From = $this->fromMail;
            $mail->FromName = $this->fromName;

            //$mail->WordWrap = 50;                                 // set word wrap to 50 characters
            //$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
            //$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
            
            $mail->IsHTML(true);                                  // set email format to HTML

            $mail->Subject = $this->subject;
            $mail->Body    = $this->body;
            $mail->AltBody = $this->altBody;

            if(!$mail->Send())
            {
               throw new Exception($mail->ErrorInfo);
            }
            
            $result =  true;
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }
        return $result;
    }

    public function getError() {
        return $this->error_message;
    }
}