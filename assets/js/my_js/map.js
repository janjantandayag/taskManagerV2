function initializeMap(){
    var address = $('#input_address').val();
    var api_key = "AIzaSyCaKkGtt5kGENzzcd_EJTjHao0CXuwOqXc";
    var target = document.getElementById('map-container');

    $.get('https://maps.googleapis.com/maps/api/geocode/json' , { 'address' : address , 'key' : api_key } , function(data){
        if(data.status === 'OK'){                
            lat = data.results[0].geometry.location.lat;
            lang = data.results[0].geometry.location.lng;
            displayMap(lat,lang,target);
        } else {
            $(target).html('<p style="text-align:center;margin-top:50px;border:1px solid #000;padding:10px"><strong>Sorry!</strong> Address not found!</p>');
        }
    }, 'json');
}

function displayMap(lat,lang,target){
    var center = {lat: lat, lng: lang};
    var map = new google.maps.Map(target, {
      zoom: 17,
      center: center
    });

    var marker = new google.maps.Marker({
      position: center,
      map: map
    });
}