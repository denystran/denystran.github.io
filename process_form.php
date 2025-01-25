<?php

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php'; // Đảm bảo đường dẫn chính xác nếu tải thủ công

// reCAPTCHA v3 Secret Key
$recaptcha_secret = '6LeClbgqAAAAAC8s64nqLNYkkQ00X5cxIUN8RmVe';

// Lấy token reCAPTCHA từ form
$recaptcha_response = $_POST['g-recaptcha-response'];

// Xác minh reCAPTCHA với Google
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
$response_keys = json_decode($response, true);

if (!$response_keys['success'] || $response_keys['score'] < 0.5) {
    echo json_encode(['success' => false, 'message' => 'Captcha verification failed.']);
    exit;
}

// Lấy dữ liệu form
$fullname = htmlspecialchars($_POST['fullname']);
$email = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);

// Kiểm tra dữ liệu
if (empty($fullname) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill all the fields.']);
    exit;
}

// Gửi email với PHPMailer
$mail = new PHPMailer(true);

try {
    // Cấu hình SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Thay bằng SMTP server của bạn
    $mail->SMTPAuth = true;
    $mail->Username = 'duc.tran0502@hcmut.edu.vn'; // Thay bằng email của bạn
    $mail->Password = 'hybj cdau xayz vvky'; // Thay bằng mật khẩu hoặc App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Mã hóa TLS
    $mail->Port = 587; // Cổng SMTP (587 cho TLS, 465 cho SSL)

    // Cấu hình người gửi & người nhận
    $mail->setFrom($email, $fullname); // Người gửi
    $mail->addAddress('duc.tran0502@hcmut.edu.vn', 'Tran Minh Duc'); // Email người nhận

    // Nội dung email
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Message';
    $mail->Body    = "<h4>Contact Details</h4>
                      <p><strong>Name:</strong> $fullname</p>
                      <p><strong>Email:</strong> $email</p>
                      <p><strong>Message:</strong><br>$message</p>";
    $mail->AltBody = "Name: $fullname\nEmail: $email\nMessage:\n$message";

    // Gửi email
    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
}
?>
