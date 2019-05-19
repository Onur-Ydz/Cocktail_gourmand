<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

//Check for empty fields
if(empty($_POST['name'])      ||
empty($_POST['email'])     ||
empty($_POST['phone'])     ||
empty($_POST['message'])   ||
!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
{
  echo "No arguments Provided!";
  return false;
}

// Instantiation and passing `true` enables exceptions
$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));

try{
  $mail = new PHPMailer(true);
  //$mail->SMTPDebug = 3; // DEBUG PURPOSE
  $mail->isSMTP();
  $mail->setLanguage('fr'); // SET MESSAGES IN FRENCH

  // TODO CHANGE CONFIG
  $mail->Host = 'localhost'; // TODO CHANGE CONFIG FOR PROD
  $mail->Port = 1025;// TODO CHANGE CONFIG FOR PROD
  $mail->SMTPAuth = false; // TODO CHANGE CONFIG FOR PROD
  $mail->SMTPSecure = false;// TODO CHANGE CONFIG FOR PROD
  $mail->SMTPAutoTLS = false;// TODO CHANGE CONFIG FOR PROD


  $mail->setFrom('contact@cocktailgourmand.fr'); // Add a sender
  $mail->addAddress($email_address); // Add a recipient
  $mail->isHTML(true); // Set email format to HTML
  $mail->Subject = 'Contact de ' . $name; // Set email subject
  $mail->Body    = 'Message de '.$name.' : '.$message .' <br/> Phone :'.$phone; // Set email content
  $mail->CharSet = 'utf-8';

  //send the message, check for errors
  $data = [];
  if (!$mail->send()) {
    $data['status'] = 'error';
    $data['message'] =  "Désolé ". $name .", Il y a eu une erreur lors de l'envoi du mail :" . $mail->ErrorInfo;
  } else {
    $data['status'] = 'success';
    $data['message'] =  "Merci " . $name. ". Votre message a bien été envoyé";
  }

}catch (Exception $e){
  $data['status'] = 'error';
  $data['message'] =  "Désolé ". $name .", Il y a eu une erreur lors de l'envoi du mail, veuillez ressayer ultérieurement";
}

echo json_encode($data);
