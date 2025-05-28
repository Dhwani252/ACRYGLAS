<?php
// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = sanitize_input($_POST["company_name"] ?? "");
    $contact_person = sanitize_input($_POST["contact_person"] ?? "");
    $address = sanitize_input($_POST["address"] ?? "");
    $country = sanitize_input($_POST["country"] ?? "");
    $phone = sanitize_input($_POST["phone"] ?? "");
    $fax = sanitize_input($_POST["fax"] ?? "");
    $email = filter_var($_POST["email"] ?? "", FILTER_SANITIZE_EMAIL);
    $comment = sanitize_input($_POST["comment"] ?? "");

    if (
        empty($company_name) ||
        empty($contact_person) ||
        empty($phone) ||
        empty($email) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        http_response_code(400);
        echo "Invalid input. Please fill in all required fields correctly.";
        exit;
    }

    // Prepare email body
    $body = "You have received a new inquiry with the following details:\n\n";
    $body .= "Company Name: $company_name\n";
    $body .= "Contact Person: $contact_person\n";
    $body .= "Address: $address\n";
    $body .= "Country: $country\n";
    $body .= "Phone: $phone\n";
    $body .= "Fax: $fax\n";
    $body .= "Email: $email\n";
    $body .= "Comments:\n$comment\n";

    // SMTP settings
    $smtpHost = 'smtp.example.com';  
    $smtpUsername = 'dhwanis205@gmail.com'; 
    $smtpPassword = 'rgln ppug qrnd bmob';
    $smtpPort = 587; 
    $smtpSecure = 'tls'; 

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dhwanis205@gmail.com';
        $mail->Password = 'rgln ppug qrnd bmob'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom($smtpUsername, 'Vision Acryglas Website');
        $mail->addAddress('dhwanis205@gmail.com');  
        $mail->addReplyTo($email, $contact_person);

        // Content
        $mail->Subject = 'New Inquiry from Vision Acryglas Website';
        $mail->Body = $body;

        $mail->send();
        echo "Success";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    http_response_code(405);
    echo "Method not allowed.";
}
?>
