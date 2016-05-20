<?php

require_once '../../swiftmailer/lib/swift_required.php';
require_once 'db.php';

$db = openDB(); // from db.php
if(!$db)
{
    die("failed to open $dbname database");
}

// get all emails with timestamps older than now that have not been sent
$statement = $db->prepare("SELECT email_id, email, message FROM emails WHERE date_time <= NOW() AND sent = 0");
$statement->execute();
$rows = $statement->fetchAll(PDO::FETCH_ASSOC); // return query as associative array


$email_auth = simplexml_load_file("../../email.xml"); // stored my email credentials in an xml file on the server so you can't read it here in plain text
if(!$email_auth)
{
    $db=null;
    die("could not load email for sending");
}

$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
  ->setUsername($email_auth->username)
  ->setPassword($email_auth->password);
if(!$transport)
{
    $db=null;
    die("failed to create email transport");
}

$mailer = Swift_Mailer::newInstance($transport);
if(!$mailer)
{
    $db=null;
    die("failed to connect to email transport");
}

foreach($rows as $r)
{   
    $message = Swift_Message::newInstance('Test Subject')
        ->setFrom(['abc@example.com' => 'mailer.php'])
        ->setTo($r['email'])
        ->setBody($r['message']);
    
    $result = $mailer->send($message);
    if($result)
    {
        $email_id = $r['email_id'];
        $statement = $db->prepare("UPDATE emails SET sent = 1 WHERE email_id = $email_id;");
        $statement->execute();
    }
}
        
$db = null;


//CITATION: the above script, using the Swift library, was adapted from source code found here:

    //http://stackoverflow.com/questions/712392/send-email-using-the-gmail-smtp-server-from-a-php-page
