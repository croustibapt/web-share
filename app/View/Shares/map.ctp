<!-- Action bar -->
<?php echo $this->element('action-bar'); ?>

<div class="container">
    <div id="div-share-search-google-map" style="width: 100%; height: 500px;">
        
    </div>
</div>

<script>
    var map;
    var markers = [];
    var shares = [];
    
    function addMarker(share) {
        var myLatlng = new google.maps.LatLng(share.latitude, share.longitude);
        
        var marker = new MarkerWithLabel({
            position: myLatlng,
            map: map,
            title: share.title,
            labelContent: '<div class="img-circle text-center" style="display: table; width: 32px; height: 32px; background-color: #2ecc71;"><i class="fa fa-folder" style="display: table-cell; vertical-align: middle; color: #ffffff; font-size: 20px;"></i></div>',
            labelAnchor: new google.maps.Point(16, 16),
            icon: ' '
            /*icon: {
                path: fontawesome.markers.FOLDER,
                scale: 0.5,
                strokeWeight: 0.0,
                strokeColor: '#ffffff',
                strokeOpacity: 1,
                fillColor: '#2ecc71',
                fillOpacity: 1.0,
            },*/
        });
        markers.push(marker);
        shares.push(share);

        google.maps.event.addListener(marker, 'click', function() {
            var infowindow = new google.maps.InfoWindow({
                content: marker.getTitle()
            });
            infowindow.open(map, marker);
        });
    }
    
    function clearMarkers() {
        for (var i = 0; i < markers.length; i++ ) {
            markers[i].setMap(null);
        }
        markers.length = 0;
        shares.length = 0;
    }
    
    function loadShares() {
        var bounds = map.getBounds();
        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();

        var jsonData =
        '   {' +
        '       "region": [' +
        '           {' +
        '               "latitude": "' + ne.lat() + '",' +
        '               "longitude": "' + sw.lng() + '"' +
        '           },' +
        '           {' +
        '               "latitude": "' + ne.lat() + '",' +
        '               "longitude": "' + ne.lng() + '"' +
        '           },' +
        '           {' +
        '               "latitude": "' + sw.lat() + '",' +
        '               "longitude": "' + ne.lng() + '"' +
        '           },' +
        '           {' +
        '               "latitude": "' + sw.lat() + '",' +
        '               "longitude": "' + sw.lng() + '"' +
        '           }' +
        '       ]' +
        '   }';

        //console.log(jsonData);

        var url = webroot + 'api/share/search';
        //console.log(url);

        //Load share
        $(function() {
            $.ajax({
                url: url,
                method: 'POST',
                data: jsonData,
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
            center: new google.maps.LatLng(43.594484, 1.447947)
        };
        map = new google.maps.Map(document.getElementById('div-share-search-google-map'), mapOptions);
          
        google.maps.event.addListener(map, 'idle', function() {
            clearMarkers();
            loadShares();
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>