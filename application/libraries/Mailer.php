<?php

class Mailer extends PHPMailer\PHPMailer\PHPMailer
{
	public function __construct()
	{
		log_message('Debug', 'PHPMailer class is loaded.');

		require_once('vendor/phpmailer/phpmailer/src/PHPMailer.php');
		require_once('vendor/phpmailer/phpmailer/src/SMTP.php');

		parent::__construct();

		$this->isSMTP();                                      // Set mailer to use SMTP
        $this->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $this->SMTPAuth = true;                               // Enable SMTP authentication
        $this->Username = 'inmorima@gmail.com';                 // SMTP username
        $this->Password = 'solymaia2018';                           // SMTP password
        $this->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->Port = 587;  
	}

}

?>