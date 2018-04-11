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

$departure=$_POST["departure"];
$destination=$_POST["destination"];
$price=$_POST["price"];
$seatsavailable=$_POST["seatsavailable"];

if(isset($_POST["regular"])){
	$regular=$_POST["regular"];
}else{
	$regular="";
}

$date=$_POST["date"];
$time=$_POST["time"];
if(isset($_POST["monday"])){
	$monday=$_POST["monday"];
}else{
	$monday="";
}
if(isset($_POST["tuesday"])){
	$tuesday=$_POST["tuesday"];
}else{
	$tuesday="";
}
if(isset($_POST["wednesday"])){
	$wednesday=$_POST["wednesday"];
}else{
	$wednesday="";
}
if(isset($_POST["thursday"])){
	$thursday=$_POST["thursday"];
}else{
	$thursday="";
}
if(isset($_POST["friday"])){
	$friday=$_POST["friday"];
}else{
	$friday="";
}
if(isset($_POST["saturday"])){
	$saturday=$_POST["saturday"];
}else{
	$saturday="";
}
if(isset($_POST["sunday"])){
	$sunday=$_POST["sunday"];
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
		//query za regularan 
		 // $sql="INSERT INTO carsharetrips ('user_id', 'departure' ,'departureLongitude','departureLatitude' ,'destination','destinationLongitude' ,'destinationLatitude' ,'price' ,'seatsavailable' ,'regular' ,'monday' ,'tuesday' ,'wednesday' ,'thursday' ,'friday' ,'saturday' ,'sunday' ,'time') VALUES ('{$user_id}', '{$departure}', '{$departureLongitude}', '{$departureLatitude}', '{$destination}', '{$destinationLongitude}', '{$destinationLatitude}', '{$price}', '{$seatsavailable}', '{$regular}', '{$monday}', '{$tuesday}', '{$wednesday}', '{$thursday}', '{$friday}', '{$saturday}', '{$sunday}', '{$time}')";

	 $sql="INSERT INTO carsharetrips  VALUES (null , '{$user_id}', '{$departure}', '{$departureLongitude}', '{$departureLatitude}', '{$destination}', '{$destinationLongitude}', '{$destinationLatitude}', '{$price}', '{$seatsavailable}', '{$regular}', '' , '{$time}', '{$monday}', '{$tuesday}', '{$wednesday}', '{$thursday}', '{$friday}', '{$saturday}', '{$sunday}')";

	}else{
		//query za one-off 
		// $sql="INSERT INTO carsharetrips ('user_id', 'departure' ,'departureLongitude','departureLatitude' ,'destination','destinationLongitude' ,'destinationLatitude' ,'price' ,'seatsavailable' ,'regular' ,'date' ,'time') VALUES ('$user_id', '$departure', '$departureLongitude', 'departureLatitude', '$destination', '$destinationLongitude', '$destinationLatitude', '$price', '$seatsavailable', '$regular', '$date', '$time')";

		$sql="INSERT INTO carsharetrips  VALUES (null , '{$user_id}', '{$departure}', '{$departureLongitude}', '{$departureLatitude}', '{$destination}', '{$destinationLongitude}', '{$destinationLatitude}', '{$price}', '{$seatsavailable}', '{$regular}', '{$date}' , '{$time}', '', '', '', '', '', '', '')";

	}
	$result=mysqli_query($link,$sql);
	//provjera 
	if(!$result){
		echo "<div class='alert alert-danger '>There was an error! The trip could not be added to the database!!!</div>";
	}
}









?>