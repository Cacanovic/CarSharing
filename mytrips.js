//definisanje varijabli
var data;
var trip;
var departureLongitude;
var departureLatitude;
var destinationLongitude;
var destinationLatitude;

//get trips
getTrips();


//create a geocoder object to use geocode future of google maps
var geocoder=new google.maps.Geocoder();


$(function(){
      //fix map
      $("#addtripmodal").on('shown.bs.modal',function(){
      	google.maps.event.trigger(map,"resize");
      });
});

//hide all date time checkboxes
$('.regular').hide();
$('.one-off').hide();


var myRadio = $('input[name="regular"]');
myRadio.click(function(){
	if($(this).is(':checked')){
		if($(this).val()=='Y'){
			$('.one-off').hide();
			$('.regular').show();
		}else{
			$('.regular').hide();
			$('.one-off').show();
		}
	}
	//console.log(myRadio);
});
$('.regular2').hide();
$('.one-off2').hide();

var myRadio2 = $('input[name="regular2"]');
myRadio2.click(function(){
	if($(this).is(':checked')){
		if($(this).val()=='Y'){
			$('.one-off2').hide();
			$('.regular2').show();
		}else{
			$('.regular2').hide();
			$('.one-off2').show();
		}
	}
});

//calendar
$('input[name="date"],input[name="date2"]').datepicker({
	numberOfMonths: 1,
	showAnim: "fadeIn",
	dateFormat: "D d M, yy",
	minDate: +1,
	maxDate: "+12M",
	showWeek: true
});

//click on create trip

$("#addtripform").submit(function(event){
	$("#spinner").show();
	$("#addtripmessage").hide();
	event.preventDefault();
	//izvlacenje vrijednosti iz svih inputa
	data= $(this).serializeArray();
	getAddTripDepartureCoordinates();

});

//define functions 
function getAddTripDepartureCoordinates(){
	geocoder.geocode(
		{
		'address': document.getElementById("departure").value 
		},
		function(results,status){
			if(status == google.maps.GeocoderStatus.OK){
				//console.log(results);
				departureLongitude = results[0].geometry.location.lng();
				departureLatitude = results[0].geometry.location.lat();
				//dodavanje u niz sa svim podacima iz forme
				data.push({name:'departureLongitude',value:departureLongitude});
				data.push({name:'departureLatitude',value:departureLatitude});
				getAddTripDestinationCoordinates();
			}else{
				getAddTripDestinationCoordinates();
			}
		}
	);
}

function getAddTripDestinationCoordinates(){
	geocoder.geocode(
		{
		'address': document.getElementById("destination").value 
		},
		function(results,status){
			if(status == google.maps.GeocoderStatus.OK){
				//console.log(results);
				destinationLongitude = results[0].geometry.location.lng();
				destinationLatitude = results[0].geometry.location.lat();
				//dodavanje u niz sa svim podacima iz forme
				data.push({name:'destinationLongitude',value:destinationLongitude});
				data.push({name:'destinationLatitude',value:destinationLatitude});
				submitAddTripRequest();
			}else{
				submitAddTripRequest();
			}
		}
	);	
}

// send to addtrips.php

function submitAddTripRequest(){
	$.ajax({
	    url: "addtrips.php",
	    type: "POST",
	    data: data,
	    success: function(returnedData){
	        if(returnedData){
	        	$("#spinner").hide();
	        	$("#addtripmessage").html(returnedData);
	        	$("#addtripmessage").slideDown();
	        }else{
	        	//hide modal
	        	$("#addtripmodal").modal('hide');
	        	//reset form
	        	$("#addtripform")[0].reset();
	        	//hide regular and on-off element
	        	$(".regular").hide();
	        	$(".one-off").hide();
	        	//load trips
	        	getTrips();
	        }
	    },
	    error: function(){
	    	$("#spinner").hide();
	        $("#addtripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
	        $("#addtripmessage").slideDown();
	    }
	
	});

}
//funckija za popunjavanje edit forme sa podacima vracenim iz baze
function formatModal( ){
	$("#departure2").val(trip['departure']);
	$("#destination2").val(trip['destination']);
	$("#price2").val(trip['price']);
	$("#seatsavailable2").val(trip['seatsavailable']);
	//ako je putovanje regular
	if(trip['regular']=="Y"){
		//radio button biramo
		$("#yes2").prop('checked', true);
		
		// skraceni oblik if else naredbe
		$("#monday2").prop('checked', trip['monday']=="1" ? true : false);
		$("#tuesday2").prop('checked', trip['tuesday']=="1" ? true : false);
		$("#wednesday2").prop('checked', trip['wednesday']=="1" ? true : false);
		$("#thursday2").prop('checked', trip['thursday']=="1" ? true : false);
		$("#friday2").prop('checked', trip['friday']=="1" ? true : false);
		$("#saturday2").prop('checked', trip['saturday']=="1" ? true : false);
		$("#sunday2").prop('checked', trip['sunday']=="1" ? true : false);
		$("input[name='time2']").val(trip['time']);
		$('.one-off2').hide(); 
		$('.regular2').show(); 
	}else{

		$("#no2").prop('checked', true);
		$("input[name='date2']").val(trip['date']);
		$("input[name='time2']").val(trip['time']);
		$('.regular2').hide(); 
		$('.one-off2').show(); 
	}

}
function getEditTripDepartureCoordinates(){
	geocoder.geocode(
		{
		'address': document.getElementById("departure2").value 
		},
		function(results,status){
			if(status == google.maps.GeocoderStatus.OK){
				//console.log(results);
				departureLongitude = results[0].geometry.location.lng();
				departureLatitude = results[0].geometry.location.lat();
				//dodavanje u niz sa svim podacima iz forme
				data.push({name:'departureLongitude',value:departureLongitude});
				data.push({name:'departureLatitude',value:departureLatitude});
				getEditTripDestinationCoordinates();
			}else{
				getEditTripDestinationCoordinates();
			}
		}
	);

}
function getEditTripDestinationCoordinates(){
	geocoder.geocode(
			{
			'address': document.getElementById("destination2").value 
			},
			function(results,status){
				if(status == google.maps.GeocoderStatus.OK){
					//console.log(results);
					destinationLongitude = results[0].geometry.location.lng();
					destinationLatitude = results[0].geometry.location.lat();
					//dodavanje u niz sa svim podacima iz forme
					data.push({name:'destinationLongitude',value:destinationLongitude});
					data.push({name:'destinationLatitude',value:destinationLatitude});
					submitEditTripRequest();
				}else{
					submitEditTripRequest();
				}
			}
		);	

}
function submitEditTripRequest(){
	$.ajax({
	    url: "updatetrips.php",
	    type: "POST",
	    data: data,
	    success: function(returnedData){
	        if(returnedData){
	        	$("#spinner").hide();
	        	$("#edittripmessage").html(returnedData);
	        	$("#edittripmessage").slideDown();
	        }else{
	        	//hide modal
	        	$("#edittripmodal").modal('hide');
	        	//reset form
	        	$("#edittripform")[0].reset();
	        	//load trips
	        	getTrips();
	        }

	    },
	    error: function(){
	        $("#spinner").hide();
	        $("#edittripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
	        $("#edittripmessage").slideDown();
	    }

	});

}
//getTrips
function getTrips(){
	 //show spinner
    $("#spinner").show();
	$.ajax({
	    url: "gettrips.php",
	    data: data,
	    success: function(returnedData){
	    	$("#spinner").hide();
	    	$("#myTrips").hide();
	        $("#myTrips").html(returnedData);
	         $("#myTrips").fadeIn();

	    },
	    error: function(){
	    	$("#spinner").hide();
	    	$("#myTrips").hide();
	        $("#myTrips").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
	         $("#myTrips").fadeIn();
	    }
	
	});

}

// klik on edit dugme kod putovanja
$("#edittripmodal").on('show.bs.modal', function(event){
	$("#edittripmessage").empty();
	//button which opened the modal
	var invoker=$(event.relatedTarget);


	//ajax call to get details of trip

	$.ajax({
	    url: "gettripdetails.php",
	    method:"POST",
	    data: {trip_id : invoker.data('trip_id')},
	    success: function(returnedData){

	    		if(returnedData=="error"){
	    			 $("#edittripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
	    		}else{
			        trip=JSON.parse(returnedData);
			       // console.log(trip);
			        // funkcija koja ce popuniti formu koristeci JSON parsirane podatke
			        formatModal();	    			
	    		}
	    },
	    error: function(){
	        $("#edittripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
	        
	    }
	
	});

	$("#edittripform").submit(function(event){
		$("#spinner").show();
		$("#edittripmessage").hide();
		// $("#edittripmessage").empty();
		event.preventDefault();
		data=$(this).serializeArray();
		//dodavanje u niz trip_id
		data.push({name:'trip_id',value:invoker.data('trip_id')});
		getEditTripDepartureCoordinates();
	});

	//brisanje putovanja
	$("#deleteTrip").click(function(){
		$("#spinner").show();
		$("#edittripmessage").hide();
		$.ajax({
		    url: "deletetrips.php",
		    method:"POST",
		    data: {trip_id : invoker.data('trip_id')},
		    success: function(returnedData){

		    		$("#spinner").hide();
		    		if(returnedData){
		    			 $("#edittripmessage").html("<div class='alert alert-danger'>The trip could not be deleted!!!</div>");	
		    			$("#edittripmessage").slideDown();
		    		}else{
		    			$("#edittripmodal").modal('hide');
		    			getTrips();
		    		}
		    },
		    error: function(){
		    	$("#spinner").hide();
		        $("#edittripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
		        $("#edittripmessage").slideDown();
		    }
		
		});

	});



});

















