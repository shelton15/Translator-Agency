<?php
/**
 * Requires the "PHP Email Form" library
 * The "PHP Email Form" library is available only in the pro version of the template
 * The library should be uploaded to: vendor/php-email-form/php-email-form.php
 * For more info and help: https://bootstrapmade.com/php-email-form/
 * Retouched by shelton15
 */

// Requires the "PHP Email Form" library
require_once 'vendor/php-email-form/php-email-form.php';

// Replace contact@example.com with your real receiving email address
$receiving_email_address = 'mignonitor@gmail.com';

if (file_exists($php_email_form = 'vendor/php-email-form/php-email-form.php')) {
    include($php_email_form);
} else {
    die('Unable to load the "PHP Email Form" Library!');
}

$contact = new PHP_Email_Form();
$contact->ajax = true;

$contact->to = $receiving_email_address;
$contact->from_name = $_POST['name'];
$contact->from_email = $_POST['email'];
$contact->subject = $_POST['subject'];

// Uncomment below code if you want to use SMTP to send emails. You need to enter your correct SMTP credentials
/*
$contact->smtp = array(
    'host' => 'example.com',
    'username' => 'example',
    'password' => 'pass',
    'port' => '587'
);
*/

$contact->add_message($_POST['name'], 'From');
$contact->add_message($_POST['email'], 'Email');
$contact->add_message($_POST['message'], 'Message', 10);

// Validate and sanitize form input
$validation_errors = $contact->validate(array('name', 'email', 'subject', 'message'));
if (!empty($validation_errors)) {
    // Handle validation errors
    echo json_encode(['success' => false, 'errors' => $validation_errors]);
    exit;
}

// Send the email
if ($contact->send()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to send email. Please try again later.']);
}