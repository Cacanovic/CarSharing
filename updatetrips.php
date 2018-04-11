<?php
session_start();
include('connection.php');
$errors="";


//define all error mesages
$missingDeparture='<p><strong>Please Enter your departure! </strong></p>';
$invalidDeparture='<p><strong>Please Enter a valid departure! </strong></p>';
$missingDestination='<p><strong>Please Enter your destination! </strong></p>';
$invalidDestination='<p><strong>Please Enter a valid  destination! </strong></p>';
$missingPrice='<p><strong>Please Enter a price per seat! </strong></p>';
$invalidPrice='<p><strong>Please Enter  a valid price per seat using numbers only! </strong></p>';
$missingSeatsavailable='<p><strong>Please select the number of available seats! </strong></p>';
$invalidSeatsavailable='<p><strong>The number of available seats should contain digits only! </strong></p>';
$missingFrequency='<p><strong>Please select a frequency!! </strong></p>';
$missingDays='<p><strong>Please select at least one weekday !! </strong></p>';
$missingDate='<p><strong>Please choose a date for your trip!! </strong></p>';
$missingTime='<p><strong>Please choose a time for your trip!! </strong></p>';

//get all inputs
$trip_id=$_POST["trip_id"];
$departure=$_POST["departure2"];
$destination=$_POST["destination2"];
$price=$_POST["price2"];
$seatsavailable=$_POST["seatsavailable2"];

if(isset($_POST["regular2"])){
	$regular=$_POST["regular2"];
}else{
	$regular="";
}

$date=$_POST["date2"];
$time=$_POST["time2"];
if(isset($_POST["monday2"])){
	$monday=$_POST["monday2"];
}else{
	$monday="";
}
if(isset($_POST["tuesday2"])){
	$tuesday=$_POST["tuesday2"];
}else{
	$tuesday="";
}
if(isset($_POST["wednesday2"])){
	$wednesday=$_POST["wednesday2"];
}else{
	$wednesday="";
}
if(isset($_POST["thursday2"])){
	$thursday=$_POST["thursday2"];
}else{
	$thursday="";
}
if(isset($_POST["friday2"])){
	$friday=$_POST["friday2"];
}else{
	$friday="";
}
if(isset($_POST["saturday2"])){
	$saturday=$_POST["saturday2"];
}else{
	$saturday="";
}
if(isset($_POST["sunday2"])){
	$sunday=$_POST["sunday2"];
}else{
	$sunday="";
}



//provjera departure 
if(empty($departure)){
	$errors.=$missingDeparture;
}else{
	//check coordinates
	if(!isset($_POST["departureLatitude"]) or !isset($_POST["departureLongitude"])){
		$errors.=$invalidDeparture;
	}else{
		$departureLatitude=$_POST["departureLatitude"];
		$departureLongitude=$_POST["departureLongitude"];
		$departure=filter_var($departure, FILTER_SANITIZE_STRING);
	}
}
//provjera destination 
if(empty($destination)){
	$errors.=$missingDestination;
}else{
	//check coordinates
	if(!isset($_POST["destinationLatitude"]) or !isset($_POST["destinationLongitude"])){
		$errors.=$invalidDestination;
	}else{
		$destinationLatitude=$_POST["destinationLatitude"];
		$destinationLongitude=$_POST["destinationLongitude"];
		$destination=filter_var($destination, FILTER_SANITIZE_STRING);
	}
}

//provjera cijene
if(empty($price)){
	$errors.=$missingPrice;	
	//provjera da samo sadrzi brojeve
}elseif(preg_match('/\D/',$price)){
	$errors.=$invalidPrice;
}else{
	$price=filter_var($price, FILTER_SANITIZE_STRING);
}
//provjera cijene
if(empty($seatsavailable)){
	$errors.=$missingSeatsavailable;	
	//provjera da samo sadrzi brojeve
}elseif(preg_match('/\D/',$seatsavailable)){
	$errors.=$invalidSeatsavailable;
}else{
	$seatsavailable=filter_var($seatsavailable, FILTER_SANITIZE_STRING);
}


//frequency regular one-off3w
if(empty($regular)){
	$errors.=$missingFrequency;
}elseif($regular=="Y"){
	if(empty($monday) && empty($tuesday) && empty($wednesday) && empty($thursday) && empty($friday) && empty($saturday) && empty($sunday)){
		$errors.=$missingDays;
	}
	if(empty($time)){
		$errors.=$missingTime;
	}

}else{
	if(empty($date)){
		$errors.=$missingDate;
	}
	if(empty($time)){
		$errors.=$missingTime;
	}
}

//ako postoji greske ispisati greske 
if($errors) {
	$resultMessage="<div class='alert alert-danger'>$errors</div>";
	echo $resultMessage;
}else{
	//nema greska pripremanje varijabli za query
	$departure=mysqli_real_escape_string($link,$departure);
	$destination=mysqli_real_escape_string($link,$destination);
	$user_id=$_SESSION['user_id'];
	if($regular=="Y"){
		
	  $sql="UPDATE carsharetrips SET `departure`='".$departure."', `departureLongitude` = ".$departureLongitude.", `departureLatitude`=".$departureLatitude.", `destination` = '".$destination."', `destinationLongitude` = ".$destinationLongitude.", `destinationLatitude` = ".$destinationLatitude.", `price` = '".$price."',`seatsavailable` = '".$seatsavailable."', `regular` = '".$regular."', `time` = '".$time."', `monday`='".$monday."', `tuesday` = '".$tuesday."', `wednesday`='".$wednesday."', `thursday` ='".$thursday."',`friday`='".$friday."', `saturday`='".$saturday."',`sunday`='".$sunday."' WHERE `trip_id`=".$trip_id. " ;";

	}else{
	

		$sql="UPDATE carsharetrips SET `departure`='".$departure."', `departureLongitude` = ".$departureLongitude.", `departureLatitude`=".$departureLatitude.", `destination` = '".$destination."', `destinationLongitude` = ".$destinationLongitude.", `destinationLatitude` = ".$destinationLatitude.", `price` = '".$price."',`seatsavailable` = '".$seatsavailable."', `regular` = '".$regular."', `time` = '".$time."', `date` = '".$date."' WHERE `trip_id`=".$trip_id. " ;";

	}
	
	$result=mysqli_query($link,$sql);
	//provjera 
	if(!$result){
		echo mysqli_connect_error($link) ."<div class='alert alert-danger '>There was an error! The trip could not be updated to the database!!!</div>";
	}
}









?>