<?php
require 'phpqrcode/qrlib.php';
require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "irc_db";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all required fields are posted
    if (isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['institution'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $institution = $_POST['institution'];

        // Generate QR Code with attendance URL
        $qrData = "http://localhost/web/web/markattendance.php?name=$name&email=$email&phone=$phone&institution=$institution";
        $qrFilePath = 'qrcodes/' . $email . '.png';
        QRcode::png($qrData, $qrFilePath);

        // Insert data into database
        $sql = "INSERT INTO registrations (name, email, phone, institution)
                VALUES ('$name', '$email', '$phone', '$institution')";

        if ($conn->query($sql) === TRUE) {
            // Send email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'nawodparanagama754@gmail.com'; // Your Gmail address
                $mail->Password = 'dfve gdfr gjbu zeyo'; // App password or Gmail password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email settings
                $mail->setFrom('conference@domain.com', 'Conference Team');
                $mail->addAddress($email, $name); // Recipient's email and name

                // Attach QR code
                $mail->addAttachment($qrFilePath);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Conference Registration Confirmation';
                $mail->Body = "Dear $name,<br><br>Thank you for registering for the International Research Conference 2024. We have attached your personal check-in QR code.Kindly ensure that you do not share this QR code with anyone else.<br><br> We looked forward to welcoming you at the event!<br><br>Best regards,<br>Conference Team";

                $mail->send();
                echo "Dear $name, Your Registration successful! QR code has been sent to your email.Please check your email address";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Missing required fields.";
    }
}

$conn->close();
?>
