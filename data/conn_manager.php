<?php

$data = file_get_contents("php://input");

$objData = json_decode($data);

$event = $objData->event;
if($event == "login"){
$email = $objData->loginemail;
$pwd = $objData->loginpassword;

include 'conn.php';
$query = "select count(*) from customers where email='".$email."' and password='".$pwd."'";
$rs = mysql_query($query);
$row = mysql_fetch_row($rs);
$count = $row[0];

if($count > 0){
echo "success";
}else{
echo "failure";
}
}

// signup 

if($event == "signup"){
$name = $objData->name;
$phone = $objData->phone;
$email = $objData->email;
$address = $objData->address;
$password = $objData->password;

include 'conn.php';

$query = "INSERT INTO customers (`cid`, `name`, `email`, `address`, `phone`, `password`) VALUES (NULL, '$name', '$email', '$address', '$phone', '$password')";
$result = mysql_query($query);

if($result){
echo "success";
}else{
echo "failure";
}
}



// add message 

if($event == "addMessage"){
$senderId = $objData->senderId;
$receiverId = $objData->receiverId;
$msgContent = $objData->msgContent;
$msgContentEncoded = json_encode($msgContent);
$msgContentEncoded = substr($msgContentEncoded,1);
$msgContentEncoded = substr($msgContentEncoded,0,strlen($msgContentEncoded)-1);
include 'conn.php';

$query = "INSERT INTO `MJCHAT_MESSAGES`(`SENDER`, `RECEIVER`, `CONTENT`) VALUES ('$senderId','$receiverId','$msgContentEncoded')";
$result = mysql_query($query);

if($result){
echo "success";
}else{
echo "failure";
}
}



// get messages

if($event == "getMessages"){

$senderId = $objData->senderId;
$receiverId = $objData->receiverId;

include 'conn.php';

    $query = "SELECT `MESSAGEID`, `SENDER`, `RECEIVER`, `SENTDATE`, `STATUS`, `CONTENT` FROM `MJCHAT_MESSAGES` WHERE (SENDER='$senderId' AND RECEIVER ='$receiverId') OR (RECEIVER='$senderId' AND SENDER ='$receiverId')";

$result=mysql_query($query);
$json = array();
while($row = mysql_fetch_array($result))     
 {
    $json[]= array(
       'messageId' => $row['MESSAGEID'],
     'senderId' => $row['SENDER'],
     'receiverId' => $row['RECEIVER'],
	 'sentdate' => $row['SENTDATE'],
	 'content' => $row['CONTENT'],
    );
}

$jsonstring = json_encode($json);
 echo $jsonstring;


}


// get users
if($event == "getUsers"){

include 'conn.php';

$query = "SELECT `USERID`, `USERNAME`, `DISPLAYNAME`, `PASSWORD`, `GENDER`, `EMAILID`, `MOBILE`,`STATUSMSG`, `AVATARNAME`, `CREATEDON` FROM `MJCHAT_USERS` WHERE 1";
$result=mysql_query($query);
$json = array();
while($row = mysql_fetch_array($result))     
 {
    $json[]= array(
     'userid' => $row['USERID'],
     'username' => $row['USERNAME'],
	 'status' => $row['STATUSMSG'],
	 'avatar' => $row['AVATARNAME'],
     'displayname' => $row['DISPLAYNAME']
    );
}
$jsonstring = json_encode($json);
 echo $jsonstring;
}




?>
