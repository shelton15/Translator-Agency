<?php

class PHP_Email_Form {
  public $to;
  public $from_name;
  public $from_email;
  public $subject;
  public $smtp;

  public $ajax = false;

  private $message = array();

  public function add_message($content, $label = '', $newline = true) {
    if ($newline) {
      $content = nl2br($content);
    }
    $this->message[] = array('content' => $content, 'label' => $label);
  }

  public function send() {
    $message_content = '';
    foreach ($this->message as $item) {
      $label = $item['label'];
      $content = $item['content'];
      $message_content .= "<strong>{$label}:</strong><br>{$content}<br><br>";
    }

    $headers = "From: {$this->from_name} <{$this->from_email}>";

    if (!empty($this->smtp)) {
      $this->send_smtp($message_content, $headers);
    } else {
      $this->send_mail($message_content, $headers);
    }

    if ($this->ajax) {
      return 'success';
    }
  }

  private function send_smtp($message_content, $headers) {
    // Implement SMTP sending here using provided credentials
    // Example code:
    /*
    $smtp_host = $this->smtp['host'];
    $smtp_username = $this->smtp['username'];
    $smtp_password = $this->smtp['password'];
    $smtp_port = $this->smtp['port'];

    // Use appropriate SMTP library or functions to send the email
    // Example code using PHPMailer library:
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = $smtp_host;
    $mail->Port = $smtp_port;
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;

    $mail->SetFrom($this->from_email, $this->from_name);
    $mail->AddAddress($this->to);

    $mail->Subject = $this->subject;
    $mail->MsgHTML($message_content);

    if (!$mail->Send()) {
      if ($this->ajax) {
        return 'error';
      }
    } else {
      if ($this->ajax) {
        return 'success';
      }
    }
    */
  }

  private function send_mail($message_content, $headers) {
    $message = "<html><body>{$message_content}</body></html>";
    $headers .= "\r\nContent-Type: text/html; charset=ISO-8859-1";

    if (mail($this->to, $this->subject, $message, $headers)) {
      if ($this->ajax) {
        return 'success';
      }
    } else {
      if ($this->ajax) {
        return 'error';
      }
    }
  }
}

?>