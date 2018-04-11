<?php
session_start();
include("connection.php");


$user_id=$_SESSION['user_id'];
//filename
$name=$_FILES["picture"]["name"];
//extenzija slike
$extension=pathinfo($name,PATHINFO_EXTENSION);

$tmp_name=$_FILES["picture"]["tmp_name"];

$fileError=$_FILES['picture']['error'];

$permanentDestination='profilepicture/' . md5(time()) . '.'.$extension;

if (move_uploaded_file($tmp_name, $permanentDestination)) {
	$sql="UPDATE users SET profilepicture='$permanentDestination' WHERE user_id='$user_id'";
	$result=mysqli_query($link, $sql);
	if(!$result){
		echo "<div class='alert alert-danger'>Unable to update profile picture</div>";
	}
}else{
		echo "<div class='alert alert-danger'>Unable to update profile picture</div>";
	}

if ($fileError>0) {
	echo "<div class='alert alert-danger'>Unable to update profile picture! Error Code: $fileError  </div>";
}
?>