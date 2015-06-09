<?php
    /*echo $startDate;
    echo $endDate;*/
    //pr($types);
?>

<script>
    //Create SearchController
    app.controller('SearchController', ['$scope', function($scope) {
        $scope.shares = [];
        $scope.page = 0;
        $scope.total_pages = 0;

        $scope.getNumber = function(num) {
            return new Array(num);
        }

        //Method used to handle the Ajax response
        $scope.handleResponse = function(response) {
            //Handle shares
            var shares = response.results;
            $scope.shares = [];

            for (var i = 0; i < shares.length; i++) {
                var share = shares[i];

                var shareColor = getIconColor(share.share_type_category.label);
                share.share_color = shareColor;
                
                //Share icon
                var shareIcon = getMarkerIcon(share.share_type_category.label, share.share_type.label);
                share.share_icon = shareIcon;

                var htmlDate = share.event_date;
                var eventDate = new Date(htmlDate);
                var isoEventDate = eventDate.toISOString();

                var momentDay = moment(isoEventDate).format('dddd D MMMM', 'fr');
                share.moment_day = momentDay;
                
                var momentHour = moment(isoEventDate).format('LT', 'fr');
                share.moment_hour = momentHour;
                
                var momentModifiedTimeAgo = moment(isoEventDate).fromNow();
                share.moment_modified_time_ago = momentModifiedTimeAgo;
                
                var totalPlaces = parseInt(share.places) + 1;
                var participationCount = parseInt(share.participation_count) + 1;
                var placesLeft = totalPlaces - participationCount;
                share.places_left = placesLeft;
                
                var percentage = (participationCount * 100) / totalPlaces;
                share.percentage = percentage;
                
                var price = parseFloat(share.price);
                share.round_price = price.toFixed(1);
                
                //Details link
                var detailsLink = webroot + 'users/details/' + share.user.external_id;
                share.details_link = detailsLink;
        
                $scope.shares.push(share);
            }

            //Create pagination
            $scope.page = parseInt(response.page);
            $scope.total_pages = parseInt(response.total_pages);
        };
    }]);
</script>

<div id="div-angular" ng-controller="SearchController" class="content" style="height: 100%; position: relative;">
    <div style="float: left; width: 50%; height: 100%; overflow-y: scroll; overflow-x: hidden;">
        <!-- Action bar -->
        <?php echo $this->element('action-bar'); ?>

        <div id="div-search-results" class="row" style="padding: 30px;">
            <div ng-repeat="share in shares">
                <?php echo $this->element('share-card'); ?>
            </div>
            <?php echo $this->element('pagination'); ?>
        </div>

        <div id="div-search-pagination">

        </div>
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
        var bounds = map.getBounds();
        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();

        var jsonData =
            '   {' +
            '       "page": "' + page + '",';

        //Start date
        if (startDate != null) {
            jsonData +=
                '   "start": "' + startDate + '",';
        }

        //End date
        if (endDate != null) {
            jsonData +=
                '   "end": "' + endDate + '",';
        }

        //Types
        if (types != null) {
            jsonData +=
                '   "types": [';

            //Loop on types
            for (var i = 0; i < types.length; i++) {
                var shareTypeId = types[i];

                if (i > 0) {
                    jsonData += ', ' + shareTypeId;
                } else {
                    jsonData += shareTypeId;
                }
            }

            jsonData +=
                '   ],';
        }

        //Region
        jsonData +=
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

        //
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

                var angularDiv = $('#div-angular');
                var scope = angular.element(angularDiv).scope();

                scope.$apply(function(){
                    scope.handleResponse(response);
                });

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