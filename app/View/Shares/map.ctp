<!-- Action bar -->
<?php echo $this->element('action-bar'); ?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>

<div class="container">
    <div id="div-share-search-google-map" style="width: 100%; height: 300px;">
        
    </div>
</div>

<script>
    var map;
    function initialize() {
      var mapOptions = {
        zoom: 8,
        center: new google.maps.LatLng(-34.397, 150.644)
      };
      map = new google.maps.Map(document.getElementById('div-share-search-google-map'),
          mapOptions);
    }

    <?php foreach ($response['results'] as $share) : ?>
        
    var myLatlng = new google.maps.LatLng(<?php echo $share['latitude']; ?>, <?php echo $share['longitude']; ?>);
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: 'Hello World!'
    });
        
    <?php endforeach; ?>

    google.maps.event.addDomListener(window, 'load', initialize);
</script>