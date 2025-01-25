
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $secretKey = "6LeClbgqAAAAAC8s64nqLNYkkQ00X5cxIUN8RmVe";
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $response = file_get_contents("$verifyUrl?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if ($responseKeys["success"] && $responseKeys["score"] >= 0.5) {
        // Gửi email
        $to = "duc.tran0502@hcmut.edu.vn";
        $subject = "New Contact Form Submission";
        $body = "Name: $fullname\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email\r\n";

        if (mail($to, $subject, $body, $headers)) {
            echo json_encode(["success" => true, "message" => "Mail sent successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to send mail."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "reCAPTCHA verification failed."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
