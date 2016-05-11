<?php

require_once '../../swiftmailer/lib/swift_required.php';
require_once 'db.php';

$db = openDB(); // from db.php

$statement = $db->prepare("SELECT email, message FROM emails WHERE date_time <= NOW()"); // get all emails with timestamps older than now
$statement->execute();
$rows = $statement->fetchAll(PDO::FETCH_ASSOC); // return query as associative array


$email_auth = simplexml_load_file("../../email.xml"); // stored my email credentials in an xml file on the server so you can't read it here in plain text
if(!$email_auth){
    die("could not load email for sending");
}

$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
  ->setUsername($email_auth->username)
  ->setPassword($email_auth->password);

$mailer = Swift_Mailer::newInstance($transport);

foreach($rows as $r)
{   
    $message = Swift_Message::newInstance('Test Subject')
        ->setFrom(array('abc@example.com' => 'mailer.php'))
        ->setTo(array($r['email']))
        ->setBody($r['message']);
    
    $result = $mailer->send($message);
}
        
$db = null;


//CITATION: the above script, using the Swift library, was adapted from source code found here:

    //http://stackoverflow.com/questions/712392/send-email-using-the-gmail-smtp-server-from-a-php-page