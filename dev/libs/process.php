<?php

//Retrieve form data. 
//GET - user submitted data using AJAX
//POST - in case user does not support javascript, we'll use POST instead
$name = ($_GET['firstname']) ? $_GET['firstname'] : $_POST['firstname'];
$lastname = ($_GET['lastname']) ? $_GET['lastname'] : $_POST['lastname'];
$email = ($_GET['email']) ?$_GET['email'] : $_POST['email'];
$options = ($_GET['options']) ?$_GET['options'] : $_POST['options'];
$comment = ($_GET['comment']) ?$_GET['comment'] : $_POST['comment'];

$name = stripslashes($name);
$lastname = stripslashes($lastname);
$comment = stripslashes($comment);

//flag to indicate which method it uses. If POST set it to 1
if ($_POST) $post=1;

//Simple server side validation for POST data, of course, you should validate the email
if (!$name) $errors[count($errors)] = 'Please enter your first name.';
if (!$lastname) $errors[count($errors)] = 'Please enter your last name.';
if (!$email) $errors[count($errors)] = 'Please enter your email.';
if (!$options) $errors[count($errors)] = 'Please enter your email.';
if (!$comment) $errors[count($errors)] = 'Please enter your message.'; 

//if the errors array is empty, send the mail
if (!$errors) {

	//recipient - YOUR EMAIL.. or whatever
	$to = 'Your Name <feedback@gatha.info>';
	/*$to = 'Your Name <dmanuell08@gmail.com>';*/	
	//sender - from the form
	$from = $name . ' <' . $email . '>';
	
	//subject and the html message
	$subject = 'Comment from ' . $name;
	$message = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head></head>
	<body>
	<table>
		<tr><td>Last Name</td><td></td><td>' . $lastname . '</td></tr>
		<tr><td>Name</td><td></td><td>' . $name . '</td></tr>
		<tr><td>Email</td><td></td><td>' . $email . '</td></tr>
		<tr><td>Subject</td><td></td><td>' . $options . '</td></tr>
		<tr><td>Comment</td><td></td><td>' . nl2br($comment) . '</td></tr>
	</table>
	</body>
	</html>';

	//send the mail
	$result = sendmail($to, $subject, $message, $from);
	
	//if POST was used, display the message straight away
	if ($_POST) {
		if ($result) echo 'Thank you! We have received your Message';
		else echo 'Sorry, unexpected error. Please try again later';
		
	//else if GET was used, return the boolean value so that 
	//ajax script can react accordingly
	//1 means success, 0 means failed
	} else {
		echo $result;
	}

//if the errors array has values
} else {
	//display the errors message
	for ($i=0; $i<count($errors); $i++) echo $errors[$i] . '<br/>';
	echo '<a href="index.html">Back</a>';
	exit;
}


//Simple mail function with HTML header
function sendmail($to, $subject, $message, $from) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= 'From: ' . $from . "\r\n";
	
	$result = mail($to,$subject,$message,$headers);
	
	if ($result) return 1;
	else return 0;
}

?>