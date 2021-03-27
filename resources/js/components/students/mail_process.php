<?php
$type=$_REQUEST["member_type"];
$name=$_REQUEST["name"];
$gender=$_REQUEST["gender"];
$state=$_REQUEST["state"];
$nationality=$_REQUEST["nationality"];
$phone=$_REQUEST["phone"];
$religion=$_REQUEST["religion"];
$occupation=$_REQUEST["occupation"];
$kills=$_REQUEST["skills"];
$volunteer=$_REQUEST["volunteer"];
$email=$_REQUEST["email"];










$to = "info@thegreenchild.org";
$subject = "New Registration";

$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<h3>The following person has just registered with the details below</h3>
<p>Type: ".$type."</p>
<p>Name: ".$name."</p>
<p>Gender:".$gender." </p>
<p>Email:".$email." </p>
<p>Phone Number: ".$phone." </p>
<p>State of Origin: ".$state."</p>
<p>Nationality: ".$nationality."</p>
<p>Religion: ".$religion."</p>
<p>Occupation: ".$occupation."</p>
<p>Skill:  ".$kills."</p>
<p>Volunteer Type: ".$volunteer."</p>
</body>
</html>
";


// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: .' .$email. "\r\n";
$headers .= 'Cc: greenchild.org' . "\r\n";


$message_status= mail($to,$subject,$message,$headers);
if($message){
      $message="<html>
      <div><p>message sent successfully</p><br>
      <a href='index.html'>Back Home</a>
      </div> </html>";
    echo($message);

}
