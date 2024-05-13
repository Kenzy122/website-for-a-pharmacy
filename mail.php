<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

$alert = '';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pharmatrust_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    try{
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO form_data (first_name, last_name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $subject, $message);

        // Set parameters and execute
        $first_name = $name;
        $last_name = $_POST['c_lname']; // Assuming you have a last name field in your form
        $stmt->execute();

        // Send email
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'example@gmail.com'; //Add mail to send request
        $mail->Password = ''; // Add mail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = '587';

        $mail->setFrom('example@gmail.com'); //Add mail to send request
        $mail->addAddress('example@gmail.com'); //Add mail to recieve request

        $mail->isHTML(true);
        $mail->Subject = 'Message Received From Contact:'. $name;
        $mail->Body = "Name: $name  <br>Email: $email <br>Subject: $subject <br>Message: $message";

        $mail->send();
        $alert = "<div class='alert-success'><span>Message Sent! Thanks for contacting us.</span></div>";

    } catch (Exception $e){
        $alert = "<div class='alert-error'><span>'.$e->getMessage().'</span></div>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
