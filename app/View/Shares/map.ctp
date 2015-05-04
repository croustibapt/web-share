<!-- Action bar -->
<?php echo $this->element('action-bar'); ?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>

<div class="container">
    <div id="div-share-search-google-map" style="width: 100%; height: 300px;">
        
    </div>
</div>

<script>
    var map;
    var markers = [];
    
    function addMarker(share) {
        var myLatlng = new google.maps.LatLng(share.latitude, share.longitude);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'Hello World!'
        });
        markers.push(marker);
    }
    
    function clearMarkers() {
        for (var i = 0; i < markers.length; i++ ) {
            markers[i].setMap(null);
        }
        markers.length = 0;
    }
    
    function loadShares() {
        //Load share
        $(function() {
            $.ajax({
                url: webroot + 'api/share/search',
                method: 'POST',
                /*data: {
                    id : menuId
                },*/
                dataType: 'json'
            })
            .done(function(response) {
                console.log(response);

                var results = response['results'];
                for (var i = 0; i < results.length; i++) {
                    var share = results[i];
                    addMarker(share);
                }
            })
            .fail(function(jqXHR, textStatus) {
                console.log(jqXHR);
            });
        });
    }
    
    function initialize() {
        var mapOptions = {
            zoom: 8,
            center: new google.maps.LatLng(-34.397, 150.644)
        };
        map = new google.maps.Map(document.getElementById('div-share-search-google-map'), mapOptions);
          
        google.maps.event.addListener(map, 'idle', function() {
            clearMarkers();
            loadShares();
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>