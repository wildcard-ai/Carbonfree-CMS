<?php
if(isset($_POST['submit'])) {
  $to = "admin@localhost";
  $subject = "Mail from localhost Contact Form";
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];
  
  $headers = "From: " . $name . " <" . $email . ">\r\n";
  $headers .= "Reply-To: " . $email . "\r\n";
  
  mail($to, $subject, $message, $headers);
  
  echo "Your email was sent successfully.";
} else {
  echo "An error occurred. Please try again.";
}
?>