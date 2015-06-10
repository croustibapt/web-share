<div class="content" style="height: 100%; position: relative;">
    <div style="float: left; width: 50%; height: 100%; overflow-y: scroll; overflow-x: hidden;">
        <!-- Action bar -->
        <?php echo $this->element('action-bar'); ?>

        <div id="div-search-results" ng-controller="SearchController" class="row" style="padding: 30px;">
            <div ng-repeat="share in shares">
                <?php echo $this->element('share-card'); ?>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php echo $this->element('pagination'); ?>
    </div>
    <div style="margin-left: 50%; width: 50%; height: 100%;">
        <div id="div-share-search-google-map" style="width: 100%; height: 100%;">

        </div>
    </div>
</div>

<script>
    //Google maps
    var map;
    var markers = [];

    function addMarker(share) {
        var myLatlng = new google.maps.LatLng(share.latitude, share.longitude);
        var iconClass = getMarkerIcon(share['share_type_category']['label'], share['share_type']['label'])
        var iconColor = getIconColor(share['share_type_category']['label']);

        var marker = new MarkerWithLabel({
            position: myLatlng,
            map: map,
            title: share.title,
            labelContent: '<div class="img-circle text-center" style="border: 4px solid white; background-color: ' + iconColor + '; display: table; min-width: 40px; width: 40px; min-height: 40px; height: 40px;"><i class="' + iconClass + '" style="display: table-cell; vertical-align: middle; color: #ffffff; font-size: 18px;"></i></div>',
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
    }

    function loadShares(page, startDate, endDate, types) {        
        //Create JSON data
        var jsonData = createSearchJson(page, startDate, endDate, types);

        //Load share
        $(function() {
            $.ajax({
                url: webroot + 'api/share/search',
                method: 'POST',
                data: JSON.stringify(jsonData),
                dataType: 'json'
            })
            .done(function(response) {
                console.log(response);

                //Results
                searchHandleResponse(response);
                
                //Pagination
                paginationHandleResponse(response);

                clearMarkers();
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
            //Zoom
            <?php if ($searchZoom != NULL) : ?>
            zoom: <?php echo $searchZoom; ?>,
            <?php else : ?>
            zoom: 8,
            <?php endif; ?>

            //Center
            <?php if (($searchLatitude != NULL) && ($searchLongitude != NULL)) : ?>
            center: new google.maps.LatLng(<?php echo $searchLatitude; ?>, <?php echo $searchLongitude; ?>)
            <?php else : ?>
            center: new google.maps.LatLng(43.594484, 1.447947)
            <?php endif; ?>
        };
        map = new google.maps.Map(document.getElementById('div-share-search-google-map'), mapOptions);

        google.maps.event.addListener(map, 'idle', function() {
            $('.hidden-search-zoom').val(map.getZoom());
            $('.hidden-search-latitude').val(map.getCenter().lat());
            $('.hidden-search-longitude').val(map.getCenter().lng());

            loadShares(<?php echo $page; ?>);
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

    $(document).on("click", ".a-search-pagination" , function() {
        console.log('click');
        var page = $(this).attr('page');
        loadShares(page);
    });

    //
    $(document).on("click", ".div-share-card" , function() {
        var shareId = $(this).attr('share-id');
        window.location.href = webroot + "share/details/" + shareId;
    });
</script>