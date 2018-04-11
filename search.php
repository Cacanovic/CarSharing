<?php
session_start();
include("connection.php");
$errors="";

//define all error mesages
$missingDeparture='<p><strong>Please Enter your departure! </strong></p>';
$invalidDeparture='<p><strong>Please Enter a valid departure! </strong></p>';
$missingDestination='<p><strong>Please Enter your destination! </strong></p>';
$invalidDestination='<p><strong>Please Enter a valid  destination! </strong></p>';

$departure=$_POST["departure"];
$destination=$_POST["destination"];



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


//ako postoji greske ispisati greske 
if($errors) {
	$resultMessage="<div class='alert alert-danger'>$errors</div>";
	echo $resultMessage;
	exit;
}

// postavljanje radiusa za pretrazivanje 
$searchRadius=10;
//longitude out od range
$departureLngOutOfRange=false;
$destinationLngOutOfRange=false;

//minimum and maximum za departure Longitude
// deg2rad() se koristi kod konvertovanja stepena u radius sto je u ovom slucajo i potrbno
$deltaLongitudeDeparture=$searchRadius*360/(24901*cos(deg2rad($departureLatitude)));


$minLongitudeDeparture=$departureLongitude-$deltaLongitudeDeparture;
//provjeravamo da li je manja od - 180 stepeni
if($minLongitudeDeparture<-180){
	$departureLngOutOfRange=true;
	$minLongitudeDeparture+=360;
}

$maxLongitudeDeparture=$departureLongitude+$deltaLongitudeDeparture;
//provjeravamo da li je manja od 180 stepeni
if($maxLongitudeDeparture > 180){
	$departureLngOutOfRange=true;
	$maxLongitudeDeparture-=360;
}




//minimum and maximum za destination  Longitude
// deg2rad() se koristi kod konvertovanja stepena u radius sto je u ovom slucajo i potrbno
$deltaLongitudeDestination=$searchRadius*360/(24901*cos(deg2rad($destinationLatitude)));


$minLongitudeDestination=$destinationLongitude-$deltaLongitudeDestination;
//provjeravamo da li je manja od - 180 stepeni
if($minLongitudeDestination<-180){
	$destinationLngOutOfRange=true;
	$minLongitudeDestination+=360;
}

$maxLongitudeDestination=$destinationLongitude+$deltaLongitudeDestination;
//provjeravamo da li je manja od 180 stepeni
if($maxLongitudeDestination > 180){
	$destinationLngOutOfRange=true;
	$maxLongitudeDestination-=360;
}

//min max departure Latitude

$deltaLatitudeDeparture=$searchRadius*180/12430;
$minLatitudeDeparture=$departureLatitude-$deltaLatitudeDeparture;
if($minLatitudeDeparture<-90){
	$minLatitudeDeparture=-90;
}

$maxLatitudeDeparture=$departureLatitude+$deltaLatitudeDeparture;
if($minLatitudeDeparture>90){
	$minLatitudeDeparture=90;
}


//min max destination Latitude

$deltaLatitudeDestination=$searchRadius*180/12430;
$minLatitudeDestination=$destinationLatitude-$deltaLatitudeDestination;
if($minLatitudeDestination<-90){
	$minLatitudeDestination=-90;
}

$maxLatitudeDestination=$destinationLatitude+$deltaLatitudeDestination;
if($minLatitudeDestination>90){
	$minLatitudeDestination =90;
}

//build query

$sql="SELECT * FROM carsharetrips WHERE ";

//departure longitude
if($departureLngOutOfRange){
	$sql.=" ((departureLongitude > $minLongitudeDeparture) OR (departureLongitude < $maxLongitudeDeparture)) ";
}else{
	$sql.= " (departureLongitude BETWEEN $minLongitudeDeparture AND $maxLongitudeDeparture)";
}

//departure longitude
$sql.= " AND (departureLatitude BETWEEN $minLatitudeDeparture AND $maxLatitudeDeparture) ";


//destination longitude
if($destinationLngOutOfRange){
	$sql.=" AND ((destinationLongitude > $minLongitudeDestination) OR (destinationLongitude < $maxLongitudeDestination)) ";
}else{
	$sql.= " AND (destinationLongitude BETWEEN $minLongitudeDestination AND $maxLongitudeDestination)";
}

//destination LAtitude

$sql.= " AND (destinationLatitude BETWEEN $minLatitudeDestination AND $maxLatitudeDestination ) ";


$result=mysqli_query($link,$sql);
if(!$result){
	echo "ERROR: Unable to execute :$sql. ".mysqli_error($link);
	exit;
}
if(mysqli_num_rows($result)==0){
	echo "<div class='alert alert-info noresults'>There are no journeys matching your search!</div>";
	exit;
}
//poruka da imamo trips 
echo "<div class='alert alert-info journeysummary'>From $departure to $destination <br>
Closest Yourneys: </div>";

  //pocetak diva u kome ce biti prikazana putovanja
echo "<div id='tripResults'>";

//petalja za niz dobijen iz baze

while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		//GET TRIP DETAils

	// check frequency
	if($row['regular']=='N'){
		//za one-off putovanja provjeriti datum da nije proslo
		$frequency="One-off journey. ";
		$time=$row['date']." at ".$row['time'].".";
		//provjera datuma
		$source=$row['date'];
		//ovdje parsiramo datum
		$tripDate=DateTime::createFromFormat('D d M, Y',$source);
		$today=date('D d M, Y');
		$todayDate=DateTime::createFromFormat('D d M, Y',$source);

		if($tripDate<$todayDate){
			// nastavi dalje u petlji
			continue;
		}


	}else{
		$frequency="Regular";

		$array = [];
		$weekdays=['monday'=>'Mon','tuesday'=>'Tue','wednesday'=>'Wed','thursday'=>'Thu','friday'=>'Fri','saturday'=>'Sat','sunday'=>'Sun'];
		foreach($weekdays as $key=>$value){
			if($row[$key]==1){
				array_push($array, $value);
			}
		}  		

		$time=implode("-",$array)." at ".$row['time'].".";


	}
	$tripDeparture=$row['departure'];
	$tripDestination=$row['destination'];
	$price=$row['price'];
	$seatsavailable=$row['seatsavailable'];


	//get user_id
	$person_id=$row['user_id'];

	$sql2="SELECT * FROM users WHERE user_id= {$person_id} LIMIT 1 ";
	$result2=mysqli_query($link,$sql2);

	if(!$result2){
	echo "ERROR: Unable to execute :$sql. ".mysqli_error($link);
	exit;
	}

	$row2=mysqli_fetch_array($result2,MYSQLI_ASSOC);
	$firstname=$row2['first_name'];
	$gender=$row2['gender'];
	$moreinformation=$row2['moreinformation'];
	$picture=$row2['profilepicture'];
	if($_SESSION['user_id']){
		$phonenumber=$row2['phonenumber'];		
	}else{
		$phonenumber="Please sign up! Only members have access to contact information. ";
	}

	//ispisati putovanje
	echo "
		<h4 class='row'>
			<div class='col-sm-2'>
				<div class='driver'> $firstname </div>
				<div> <img class='profile' src='$picture'></div>
			</div>

			<div class='col-sm-8 journey' >
				<div><span class='departure'>Departure:</span> $tripDeparture.</div>
				<div><span class='destination'>Destination:</span> $tripDestination.</div>
				<div class='time'> $time </div>
				<div class='frequency'> $frequency </div>
			</div>

			<div class='col-sm-2 journey2' >
				<div class='price'>$$price</div>
				<div class='perseat'> Per Seat </div>
				<div class='seatsavailable'> $seatsavailable left </div>
			</div>
		</h4>
		
		<div class='moreinfo'>
			<div>
				<div>Gender: $gender</div>
				<div>&#9742 Telephone: $phonenumber</div>
			</div>
			<div class='aboutme'>About me: $moreinformation</div>
		</div>

	";






}





//kraj diva za putovanja
echo "</div>";








?>