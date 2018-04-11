var map;
var directionsDisplay;
//set map opetions center of the map
var myLatLng={lat:44.9, lng:18.8};
var mapOptions={
	center:myLatLng,
	zoom:10,
	mapTypeId:google.maps.MapTypeId.ROADMAP
}
// create autocomplete objects
//autocomplete za pocetna dva inputa
var input1=document.getElementById("departure");
var input2=document.getElementById("destination");
var input3=document.getElementById("departure2");
var input4=document.getElementById("destination2");
var options={
	types:['(cities)']
}
var autocomplete1= new google.maps.places.Autocomplete(input1,options);
var autocomplete2= new google.maps.places.Autocomplete(input2,options);
var autocomplete3= new google.maps.places.Autocomplete(input3,options);
var autocomplete4= new google.maps.places.Autocomplete(input4,options);

//kreiranje  DirectionService objekta da bi smo mogli koristiti route metodu
var directionsService=new google.maps.DirectionsService();

//onload:
google.maps.event.addDomListener(window, 'load', initialize);

//initialize: draw map in div googleMap
function initialize(){
	//CREATE DIRECTIONS RENDERER OBJECT JER NAM treba da prikaz rute na mapi
	directionsDisplay= new google.maps.DirectionsRenderer();
	//create map
	 map=new google.maps.Map(document.getElementById("googleMap"), mapOptions);
	 // bind directionRenderer to the map
	 directionsDisplay.setMap(map);
}

//calculate the route when selecting autocomlete
//event koji se dodaje na inpute u zagradi opcije
//kada izaberemo grad i onda imamo taj event koji na promjenu lokacije 
//izvrsava funkciju calcRoute
google.maps.event.addListener(autocomplete1,'place_changed',calcRoute);
google.maps.event.addListener(autocomplete2,'place_changed',calcRoute);


//calcRoute funckija
function calcRoute(){
	var start=$('#departure').val();
	var end=$('#destination').val();
	var request={
		origin:start,
		destination:end,
		travelMode:google.maps.DirectionsTravelMode.DRIVING
	};
	if(start && end){
		directionsService.route(request, function(response, status){
			if(status==google.maps.DirectionsStatus.OK){
				//iscrtavanje rute na mapi
				directionsDisplay.setDirections(response);
			}
		});
	}else{
		initialize();
	}
}






























