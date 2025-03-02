<?php
// File path to the commands
$file_path = '/../../log/commands.txt';

// Recipient email address
$to = 'mail@example.com';

// Email subject
$subject = 'Command Log Update';

// Function to send the email
function sendMail($to, $subject, $message) {
    $headers = "From: mail@example.com\r\n" .
               "Reply-To: mail@example.com\r\n" .
               "X-Mailer: PHP/" . phpversion();

    return mail($to, $subject, $message, $headers);
}

// Check if the file exists and is readable
if (file_exists($file_path) && is_readable($file_path)) {
    // Get the file content
    $file_content = trim(file_get_contents($file_path));

    // Only send email if file is not empty
    if (!empty($file_content)) {
        $message = "The following commands were logged:\n\n" . $file_content;

        if (sendMail($to, $subject, $message)) {
            echo "Email sent successfully.";
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "No commands to send. Email not sent.";
    }
} else {
    echo "Error: File does not exist or is not readable.";
}
?>

