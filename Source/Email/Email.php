<?php


namespace Source\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailException;

/**
 * Description of Email
 *
 * @author edem <edem.fbc@gmail.com>
 * @package Source\Email
 */
class Email 
{
    /** @var array */
    private $data;
    
    /** @var PHPMailer */
    private $mail;
    
    /** @var string */
    private $message;
    
    /**
     * Email Construct
     */
    public function __construct() 
    {
        $this->mail = new PHPMailer(true);
        $this->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mail->isSMTP();
        $this->mail->setLanguage(CONF_MAIL_OPTION_LANG);
        $this->mail->isHTML(CONF_MAIL_OPTION_HTML);
        $this->mail->SMTPAuth = CONF_MAIL_OPTION_AUTH;
        $this->mail->SMTPSecure = CONF_MAIL_OPTION_SECURE;
        $this->mail->CharSet = CONF_MAIL_OPTION_CHARSET;
        $this->mail->Host = CONF_MAIL_HOST;
        $this->mail->Username = CONF_MAIL_USER;
        $this->mail->Password = CONF_MAIL_PASS;
        $this->mail->Port = CONF_MAIL_PORT;
    }
    
    /**
     * @param string $subject
     * @param string $body
     * @param string $recipient
     * @param string $recipientName
     * @return Email
     */
    public function bootstrap(string $subject, string $body, string $recipient, string $recipientName): Email
    {
        $this->data = new \stdClass();
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_email = $recipient;
        $this->data->recipient_name = $recipientName;
        return $this;
    }
    
    /**
     * @param string $filePath
     * @param string $fileName
     * @return Email
     */
    public function attach(string $filePath, string $fileName): Email
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }
    
    
    /**
     * @param string $from
     * @param string $fromName
     * @return bool
     */
    public function send(string $from = CONF_MAIL_SENDER["address"], string $fromName = CONF_MAIL_SENDER["name"]): bool
    {
        if (empty($this->data)) {
            $this->message = "Erro ao enviar, por favor verifique os dados!";
            return false;
        }

        if (!is_email($this->data->recipient_email)) {
            $this->message = "O e-mail de destinatário não é válido!";
            return false;
        }

        if (!is_email($from)) {
            $this->message = "O e-mail de remetente não é válido!";
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);
            $this->mail->setFrom($from, $fromName);

            // verifica anexos
            if (!empty($this->data->attach)) {
                foreach ($this->data->attach as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }

            $this->mail->send();
            return true;
        } catch (MailException $excepetion) {
            $this->message = $excepetion->getMessage();
            return false;
        }
    }
    
    /**
     * @return PHPMailer
     */
    public function mail(): PHPMailer
    {
        return $this->mail;
    }
    
    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
}
