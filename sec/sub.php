<?php session_start();
//If the form is submitted
/*if(isset($_POST['submit'])) {

	//Check to make sure that the name field is not empty
	if(trim($_POST['name']) == '') {
		$hasError = true;
	} else {
		$name = trim($_POST['name']);
	}

	//Check to make sure sure that a valid email address is submitted
	if(trim($_POST['email']) == '')  {
		$hasError = true;
	} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}
	//$type = trim($_POST['inquiry_type']);
	$comments = trim($_POST['comments']);
	//If there is no error, send the email
	if(!isset($hasError)) {
		$emailTo = 'brendon@nwpro.org'; //Put your own email address here
		$body = "Name: $name \n\nEmail: $email \n\nComments:\n $comments";
		$headers = 'From: My Site <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

		mail($emailTo, $body, $headers);
		$emailSent = true;
	}
}*/
/*require_once('../scripts/recaptchalib.php');
  $privatekey = "6Lfk-eESAAAAAJtbefgSRuT88u52JY3TqIupm3F0";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);*/

  /*if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
   echo "The reCAPTCHA wasn't entered correctly. Go back and try it again.";// .
         //"(reCAPTCHA said: " . $resp->error . ")"; 
		//$formok = false;
		//$errors = $resp->error;
		die();
  } else {
    // Your code here to handle a successful verification*/

	if( isset($_POST) ){
		/*if (!$resp->is_valid) {
		    // What happens when the CAPTCHA was entered incorrectly
		   echo "The reCAPTCHA wasn't entered correctly. Go back and try it again.";
		die();
	} else {*/
	   //form validation vars
	   $formok = true;
	   //$errors = array();
	   //$errors = header('HTTP', true, 500);
	   $errors = "";

	   //submission data
	   $ipaddress = $_SERVER['REMOTE_ADDR'];
	   $date = date('m/d/Y');
	   $time = date('h:i:s');

	   //form data
	   $name = $_POST['name'];
	   $email = $_POST['email'];
	   $telephone = $_POST['telephone'];
	   $inquiry = $_POST['inquiry_type'];
	   $event_date = $_POST['event_date'];
	   $comments = $_POST['comments'];
	   $question = $_POST['question'];
		
		/*if (!$resp->is_valid) {
		    // What happens when the CAPTCHA was entered incorrectly
		   //echo "The reCAPTCHA wasn't entered correctly. Go back and try it again.";
			$formok = false;
	        $errors[] = "The reCAPTCHA wasn't entered correctly";
			//$caperror = "The reCAPTCHA wasn't entered correctly";
		}*/
	   //validate form data

	   //validate name is not empty
	   if(empty($name)){
	       $formok = false;
	       //$errors[] = "You have not entered a name";
		   $errors .= "You have not entered a name...<br>";
	   }

	   //validate email address is not empty
	   if(empty($email)){
	       $formok = false;
	       //$errors[] = "You have not entered an email address";
		   $errors .= "You have not entered an email address...<br>";
	   //validate email address is valid
	   }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	       $formok = false;
	       //$errors[] = "You have not entered a valid email address";
		   $errors .= "You have not entered a valid email address...<br>";
	   }
	   if(empty($question)) {
	       $formok = false;
	       //$errors[] = "You have not entered a name";
		   $errors .= "You have not answered the security question...<br>";
	   }elseif($question != '5') {
	       $formok = false;
	       //$errors[] = "You have not entered a name";
		   $errors .= "Security answer is not correct...<br>";
	   }
	   //validate message is not empty
	   /*if(empty($comments)){
	       $formok = false;
	       $errors[] = "You have not entered a message";
	   }
	   //validate message is greater than 20 characters
	   elseif(strlen($comments) < 20){
	       $formok = false;
	       $errors[] = "Your message must be greater than 20 characters";
	   }*/

	   //send email if all is ok
	   if($formok){
	       $headers = "From: F5 Contact Form" . "\r\n";
	       $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	       $emailbody = "<p>You have received a new message from the F5 contact form:</p>
	                     <p><strong>Name: </strong> {$name} </p>
	                     <p><strong>Email Address: </strong> {$email} </p>
	                     <p><strong>Telephone: </strong> {$telephone} </p>
	                     <p><strong>Event Type: </strong> {$inquiry} </p>
						 <p><strong>Date of Event: </strong> {$event_date} </p>
	                     <p><strong>Message: </strong> {$comments} </p>
	                     <p>This message was sent from the IP Address: {$ipaddress} on {$date} at {$time}</p>";

	       mail("mitch@foremanproductionsinc.com","F5 Event Inquiry",$emailbody,$headers);
		   
		   echo "Thanks for contacting us, $name!<br>We will get back to you ASAP!";
		   //echo "Name: "+$name+""+"Email: "+$email;
		   
	   }
	   echo $errors;
	   //echo "Thanks for contacting us! <br />We will get back to you ASAP!";
	   //what we need to return back to our form
	   $returndata = array(
	       'posted_form_data' => array(
	           'name' => $name,
	           'email' => $email,
	           'telephone' => $telephone,
	           'inquiry_type' => $inquiry,
			   'event_date' => $event_date,
	           'comments' => $comments
	       ),
	       'form_ok' => $formok,
	       'errors' => $errors
	   ); 

	   //if this is not an ajax request
	   if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'){
	       //set session variables
	       session_start();
	       $_SESSION['cf_returndata'] = $returndata;

	       //redirect back to form
	       header('location: ' . $_SERVER['HTTP_REFERER']);
	   }
//	}
}
?>