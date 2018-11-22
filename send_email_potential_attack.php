<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//Load composer's autoloader
// require 'vendor/autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                      // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = '8gag.web.sec@gmail.com';                 // SMTP username
    $mail->Password = file_get_contents('password.txt');                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('account-verify@8gag.com', '8gag.com');
    $mail->addAddress('adoantal@gmail.com');     // Add a recipient
    $mail->addAddress('k0kukavica@gmail.com');
    $mail->addAddress('sasa.labusova@gmail.com');
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = '8gag.com fishy activity';
    $mail->Body    = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Email account activation</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
    </head>
    <body>
        <section>
            <div id="container">
                <p>Dear admins of 8gag,<br><br>there has been some strange activity going on at your domain.<br><br>
                    Details of the activity:<br><br>
                    Time: '.$time_of_attack.'<br>
                    Description: '.$attack_description.'<br>
                    IP address: '.$currentIp.'<br>
                    User id: '.$_SESSION['userId'].'<br>
                    User email: '.$_SESSION['userEmail'].'<br>
                    Username: '.$_SESSION['userUsername'].'<br>
                </p>
                <p>This mentioned user is now also banned. We encourage you to investigate this matter further.</p>
                <p>For more details please look into your activity log in the database, or log in to your account and check the activity log page.</p>
                <p>Best regards,<br>8gag team.</p>
            </div>    
        </section>   
    </body>
    </html>';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    echo '<br><br> Also exception:'.$e;
}

