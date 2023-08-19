<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $phone = $_POST["phone"];
    $job = $_POST["job"];
    $description = $_POST["description"];

    // Handle file upload (CV)
    if (isset($_FILES["cv"])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["cv"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($_FILES["cv"]["tmp_name"], $targetFilePath);
    }

    // Send an email with the form data and CV attachment
    $to = "roberttocu09@gmail.com";
    $subject = "New Job Application";
    $message = "Nume: $lname\nPrenume: $fname\nNumar de telefon: $phone\nPostul dorit: $job\nMesaj:\n$description";
    $headers = "From: $fname $lname";

    // Add the CV as an attachment
    $file_attached = false;
    if (isset($_FILES["cv"])) {
        $file_attached = true;
        $file_name = $_FILES["cv"]["name"];
        $file_path = $_FILES["cv"]["tmp_name"];
        $file_type = $_FILES["cv"]["type"];
        $file_size = $_FILES["cv"]["size"];
        $file_content = file_get_contents($file_path);
        $file_base64 = base64_encode($file_content);

        $boundary = md5(time());
        $headers .= "\r\nMIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n\r\n";
        $message = "This is a multi-part message in MIME format.\r\n\r\n" .
                   "--" . $boundary . "\r\n" .
                   "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n" .
                   "Content-Transfer-Encoding: 7bit\r\n\r\n" .
                   $message . "\r\n\r\n" .
                   "--" . $boundary . "\r\n" .
                   "Content-Disposition: attachment; filename=\"" . $file_name . "\"\r\n" .
                   "Content-Type: " . $file_type . "; name=\"" . $file_name . "\"\r\n" .
                   "Content-Transfer-Encoding: base64\r\n\r\n" .
                   chunk_split($file_base64) . "\r\n\r\n" .
                   "--" . $boundary . "--";
    }

    if (mail($to, $subject, $message, $headers)) {
        // Set a flag to indicate successful submission
        $message = "Your application has been submitted successfully.";
    } else {
        // Handle email sending error
        $message = "There was an error submitting your application. Please try again later.";
    }

    if (mail($to, $subject, $message, $headers)) {
        // Set a flag to indicate successful submission
        $submission_success = true;
    } else {
        // Handle email sending error
        $submission_success = false;
    }
    
}
?>
