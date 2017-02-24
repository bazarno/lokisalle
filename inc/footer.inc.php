<footer>
<div class="conteneur">
<?= date('Y') ?> - Tous droits reservés.
</div>
</footer>
<script src="<?php echo RACINE_SITE; ?>js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?php echo RACINE_SITE; ?>js/bootstrap.min.js"></script>

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
</body>
</html>
