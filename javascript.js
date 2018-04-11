//Ajax Call for the sign up form 
//Once the form is submitted
$("#signupform").submit(function(event){ 
     //show spinner
    $("#spinner").show();
    //hide results
    $("#signupmessage").hide();
     //prevent default php processing
     event.preventDefault();
     //collect user inputs
     var datatopost = $(this).serializeArray();
     //    console.log(datatopost);
     //send them to signup.php using AJAX
     $.ajax({
        url: "signup.php",
        type: "POST",
        data: datatopost,
         success: function(data){
            if(data){
                $("#spinner").hide();
                $("#signupmessage").html(data);
                 $("#signupmessage").slideDown();
            }
        },
        error: function(){
            $("#spinner").hide();
            $("#signupmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
            $("#signupmessage").slideDown();
        }
    
      });

});

//Ajax Call for the login form
//Once the form is submitted
$("#loginform").submit(function(event){ 
     //show spinner
    $("#spinner").show();
    //hide results
    $("#loginmessage").hide();
     //prevent default php processing
     event.preventDefault();
     //collect user inputs
     var datatopost = $(this).serializeArray();
     //    console.log(datatopost);
     //send them to login.php using AJAX
     $.ajax({
        url: "login.php",
        type: "POST",
        data: datatopost,
        success: function(data){
           
            if(data == "success"){
                window.location = "mainpageloggedin.php";
            }else{
                $("#spinner").hide();
                $('#loginmessage').html(data); 
                $("#loginmessage").slideDown();  
            }
        },
        error: function(){
             $("#spinner").hide();
            $("#loginmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
            $("#loginmessage").slideDown();  
        }
    
    });

});


//Ajax Call for the forgot password form
//Once the form is submitted
$("#forgotpasswordform").submit(function(event){ 
    //show spinner
    $("#spinner").show();
    //hide results
    $("#forgotpasswordmessage").hide();
     //prevent default php processing
     event.preventDefault();
     //collect user inputs
     var datatopost = $(this).serializeArray();
     //    console.log(datatopost);
     //send them to signup.php using AJAX
     $.ajax({
        url: "forgot-password.php",
        type: "POST",
        data: datatopost,
        success: function(data){
             $("#spinner").hide();
            $('#forgotpasswordmessage').html(data);
            $("#forgotpasswordmessage").slideDown(); 
        },
        error: function(){
             $("#spinner").hide();
            $("#forgotpasswordmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
            $("#forgotpasswordmessage").slideDown(); 
        }
    
     });

});

var data;

//submit searh formu
$("#searchForm").submit(function(event){
    //show spinner
    $("#spinner").show();
    //hide results
    $("#searchResults").fadeOut();
    event.preventDefault();
    data = $(this).serializeArray();
    getSearchDepartureCoordinates();

});


//create a geocoder object to use geocode future of google maps
var geocoder=new google.maps.Geocoder();

//define functions 
function getSearchDepartureCoordinates(){
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
                getSearchDestinationCoordinates();
            }else{
                getSearchDestinationCoordinates();
            }
        }
    );
}

function getSearchDestinationCoordinates(){
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
                submitSearchRequest();
            }else{
                submitSearchRequest();
            }
        }
    );  
}

// send to search.php

function submitSearchRequest(){

    $.ajax({
        url: "search.php",
        type: "POST",
        data: data,
        success: function(returnedData){
             //hide spinner
             $("#spinner").hide();
             //show results
             $("#searchResults").fadeIn();

             $("#searchResults").html(returnedData);
             //accordian je za animaciju na div da se spusta za more info
             $("#tripResults").accordion({
                active:false,
                collapsible: true,
                heightStyle: "content",
                icons: false
             });
             //show results
             $("#searchResults").fadeIn();

        },
        error: function(){
            //hide spinner
            $("#spinner").hide();
            $("#searchResults").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
             //show results
             $("#searchResults").fadeIn();
        }
    
    });

}