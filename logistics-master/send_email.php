<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $userMessage = $_POST["message"];

    $to = "roberttocu09@gmail.com";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/html\r\n";

    $email_body = "
    <p><strong>First Name:</strong> $fname</p>
    <p><strong>Last Name:</strong> $lname</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Subject:</strong> $subject</p>
    <p><strong>Message:</strong> $userMessage</p>
    ";

    $success = mail($to, $subject, $email_body, $headers);

    if ($success) {
        $message = "Your message has been sent successfully.";
    } else {
        $message = "There was a problem sending your message. Please try again later.";
    }
}
?>
