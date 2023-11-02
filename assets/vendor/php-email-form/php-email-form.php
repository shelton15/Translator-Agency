<?php

class PHP_Email_Form
{
    /**
     * Summary of ajax
     * @var 
     */
    public $ajax = true;
    public $to;
    public $from_name;
    public $from_email;
    public $subject;
    public $smtp;

    private $fields = array();
    private $errors = array();

    public function add_message($value, $label, $length = 0)
    {
        if ($length > 0 && strlen($value) > $length) {
            $this->errors[] = "The field $label exceeds the maximum length of $length characters.";
        }

        $this->fields[$label] = $value;
    }

    public function validate($required_fields)
    {
        foreach ($required_fields as $field) {
            if (empty($this->fields[$field])) {
                $this->errors[] = "The $field field is required.";
            }
        }

        return $this->errors;
    }

    public function send()
    {
        $message = '';
        foreach ($this->fields as $label => $value) {
            $message .= "$label: $value\n";
        }

        $headers = "From: $this->from_name <$this->from_email>" . PHP_EOL;
        $headers .= "Reply-To: $this->from_email" . PHP_EOL;
        $headers .= "MIME-Version: 1.0" . PHP_EOL;
        $headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;

        if (!empty($this->smtp)) {
            $smtp_host = $this->smtp['host'];
            $smtp_username = $this->smtp['username'];
            $smtp_password = $this->smtp['password'];
            $smtp_port = $this->smtp['port'];

            ini_set('SMTP', $smtp_host);
            ini_set('smtp_port', $smtp_port);
            ini_set('sendmail_from', $this->from_email);

            $headers .= "X-Mailer: PHP/" . phpversion() . PHP_EOL;

            $params = "-f " . $this->from_email;
            $additional_parameters = "-r " . $this->from_email;

            if (!mail($this->to, $this->subject, $message, $headers, $additional_parameters)) {
                $this->errors[] = "Failed to send email using SMTP.";
                return false;
            }
        } else {
            if (!mail($this->to, $this->subject, $message, $headers)) {
                $this->errors[] = "Failed to send email using mail() function.";
                return false;
            }
        }

        return true;
    }
}