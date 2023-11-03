<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "mignonitor@gmail.com"; // Replace with your email address

    // Fetching and sanitizing form data
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $subject = sanitizeInput($_POST['subject']);
    $message = sanitizeInput($_POST['message']);

    // Validating form data
    $validation_errors = array();

    if (empty($name)) {
        $validation_errors[] = "Name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validation_errors[] = "Invalid email address.";
    }

    if (empty($subject)) {
        $validation_errors[] = "Subject is required.";
    }

    if (empty($message)) {
        $validation_errors[] = "Message is required.";
    }

    // If there are validation errors, return the errors as a response
    if (!empty($validation_errors)) {
        $response = array(
            'status' => 'error',
            'message' => $validation_errors
        );
        echo json_encode($response);
        exit;
    }

    // Create the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Subject: $subject\n";
    $email_content .= "Message:\n$message\n";

    // Set the email headers
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    if (mail($to, $subject, $email_content, $headers)) {
        $response = array(
            'status' => 'success',
            'message' => 'Your message has been sent. Thank you!'
        );
        echo json_encode($response);
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to send the email. Please try again later.'
        );
        echo json_encode($response);
    }
}

// Function to sanitize form input data
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
?>