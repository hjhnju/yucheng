<?php
require_once dirname(__FILE__) . '/PHPMailer/PHPMailerAutoload.php';

/**
 * $to      = 'someone@ex.com';
 * $subject = '修改邮箱地址';
 * $body    = <<<HTML
 *     html-content
 * HTML;
 *
 * Base_Mailer::getInstance()->send($to, $subject, $body);
 *
 */ 
class Base_Mailer {

    private static $instance = null;

    //PHPMailer类
    private $mail = null;

    /**
     * 获取单例
     */
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new Base_Mailer();
        }
        return self::$instance;
    }

    private function __construct(){
        $this->mail = new PHPMailer();
    }

    /**
     * 发送系统邮件（noreply@xingjiaodai.com)
     * @param   $to '接受人地址'
     * @param   $subject '邮件标题'
     * @param   $body '邮件内容，支持html'
     * @return  boolean
     */
    public function send($to, $subject, $body){
        if(!$this->mail){
            return false;
        }

        //$this->mail->SMTPDebug = 3;                        // Enable verbose debug output
        $this->mail->CharSet    ="UTF-8";
        $this->mail->isSMTP();                               // Set mailer to use SMTP
        $this->mail->Host       = 'smtp.exmail.qq.com';      // Specify main and backup SMTP servers
        $this->mail->SMTPAuth   = true;                      // Enable SMTP authentication
        $this->mail->Username   = 'noreply@xingjiaodai.com'; // SMTP username
        $this->mail->Password   = 'XjdNoreply@2014';         // SMTP password
        $this->mail->SMTPSecure = 'ssl';                     // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port       = 465;                       // TCP port to connect to

        $this->mail->From     = 'noreply@xingjiaodai.com';
        $this->mail->FromName = '兴教贷';
        // $this->mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $this->mail->addAddress($to);               // Name is optional
        // $this->mail->addReplyTo('info@example.com', 'Information');
        // $this->mail->addCC('cc@example.com');
        // $this->mail->addBCC('bcc@example.com');

        // $this->mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $this->mail->isHTML(true);                                  // Set email format to HTML

        $this->mail->Subject = $subject;
        $this->mail->Body    = $body;
        // $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$this->mail->send()) {
            // echo 'Message could not be sent.';
            Base_Log::error(array(
                'msg'     => $this->mail->ErrorInfo,
                'to'      => $to,
                'subject' => $subject,
                'body'    => $body,
            ));
            return false;
        } else {
            Base_Log::notice(array(
                'msg'     => 'success',
                'to'      => $to,
                'subject' => $subject,
                'body'    => $body,
            ));
            return true;
        }
    }
}
