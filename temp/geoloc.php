
    <style>
      #map {
        height: 400px;
        width:400px;
      }


    </style>


    <input id="address" type="textbox" value="sydney">
    <input id="submit" type="button" value="voir le plan">
    <div id="map"></div>
<script>
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 16,
    center: {lat: 48.853, lng: 2.35}
  });
  var geocoder = new google.maps.Geocoder();

  document.getElementById('submit').addEventListener('click', function() {
    geocodeAddress(geocoder, map);
  });
}

function geocodeAddress(geocoder, resultsMap) {
  var address = document.getElementById('address').value;
  geocoder.geocode({'address': address}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      resultsMap.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
        map: resultsMap,
        position: results[0].geometry.location
      });
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYOi0FBxyzE8ysxQMe1OMfP78qUf10Pt8&signed_in=true&callback=initMap"
        async defer></script>
