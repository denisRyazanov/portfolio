<?php
$secretKey = "RECAPTCHA_SECRET_KEY";
$token = $_POST['recaptcha_response'];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$token}");
$captcha_success = json_decode($verify);
if(!$captcha_success->success || $captcha_success->score < 0.5){die("Spam detekován!");}
$name=htmlspecialchars($_POST['name']);
$email=htmlspecialchars($_POST['email']);
$phone=htmlspecialchars($_POST['phone']);
$message=htmlspecialchars($_POST['message']);
$attachment=$_FILES['attachment'];
$to="EMAIL@example.com";
$subject="Nová zpráva z webu";
$boundary=md5(time());
$headers="MIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"{$boundary}\"\r\nFrom: $email\r\n";
$body="--{$boundary}\r\nContent-Type: text/plain; charset=utf-8\r\n\r\nJméno: $name\nEmail: $email\nTelefon: $phone\n\nZpráva:\n$message\n";
if($attachment['size']>0){
$fileData=chunk_split(base64_encode(file_get_contents($attachment['tmp_name'])));
$fileName=$attachment['name'];
$body.="--{$boundary}\r\nContent-Type: application/octet-stream; name=\"{$fileName}\"\r\nContent-Disposition: attachment; filename=\"{$fileName}\"\r\nContent-Transfer-Encoding: base64\r\n\r\n{$fileData}\r\n";
}
$body.="--{$boundary}--";
mail($to,$subject,$body,$headers);
echo "OK";
?>