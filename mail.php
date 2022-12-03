<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php'; // Only file you REALLY need
require 'PHPMailer-master/src/Exception.php'; // If you want to debug
require 'vendor1/autoload.php';
ob_start();
if(isset($_GET['msg'])){
  echo $_GET['msg'];
  exit;
}
$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->SMTPAuth = true; // There was a syntax error here (SMPTAuth)
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Mailer = "smtp";
    $mail->Port = 465;
    $mail->Username = "zetaipos@gmail.com";
    $mail->Password = "gxtuaxtbtyxcwyog";
    // $mail->addAttachment("ipos_user_manual.pdf");

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    //Recipients
    $mail->setFrom('zetaipos@gmail.com', 'Zeta');
    $mail->addAddress('easn.aliamir@gmail.com', 'IPOS');     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    $mail->addBCC('easnamirali@gmail.com');


    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Query From Contact Form Skyrides.com';
    $mail->Body    = '<html>
						<head>
							<title>Contact Form Message</title>
						</head>
						<body>
						<table border="1" cellpadding="2" cellspacing="0" align="left" >
							<tr style="text-align: left; padding-left: 10px;">
								<th width="250">Name:</th>
								<td width="250">Amir Ali</td>

							</tr>
							<tr style="text-align: left; padding-left: 10px;">
								<th>Email Id:</th>
								<td>gdfg</td>
							</tr>
							<tr style="text-align: left; padding-left: 10px;">
								<th>Subject:</th>
								<td>dgdfgdf</td>
							</tr>
							<tr style="text-align: left; padding-left: 10px;">
								<th>Message/Query:</th>
								<td>gvdfgfgd</td>
							</tr>
						</table>
						</body>
						</html>';
    

    $mail->send();
    //echo "Mail Sent!!";
  
  header('Location: mail.php?msg=Message Sent Successfully');
} catch (Exception $e) {
    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  
    header('Location: mail.php?msg=Message could not be sent. Mailer Error: {'.$mail->ErrorInfo.'}');
}
ob_end_flush();
?>