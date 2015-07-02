/**
 * Created by bleguelvouit on 11/06/15.
 */

/**
 *
 * @param inputId
 */
function initializeAdd() {
    //Create HomeController
    app.controller('AddController', ['$scope', '$http', function($scope, $http) {
        $scope.shareTypes = {};
        $scope.shareTypes[""] = {
                "label": "Type",
                "share_type_category_id": "-1",
                "share_type_id": ""
            };
        $scope.shareType = "";

        //Event time
        $scope.eventTime = "";
        $scope.eventTimes = {};

        $scope.eventTimes[""] = "Heure";
        for (var i = 0; i < 24; i++) {
            var hour = pad(i, 2);

            $scope.eventTimes['' + hour + ':00:00'] = '' + hour + ':00';
            $scope.eventTimes['' + hour + ':15:00'] = '' + hour + ':15';
            $scope.eventTimes['' + hour + ':30:00'] = '' + hour + ':30';
            $scope.eventTimes['' + hour + ':45:00'] = '' + hour + ':45';
        }

        //
        $scope.formatShareTypeCategory = function(shareTypeCategory) {
            return getShareTypeCategoryLabel(shareTypeCategory);
        };

        //
        $scope.formatShareType = function(shareTypeCategory, shareType) {
            return getShareTypeLabel(shareTypeCategory, shareType);
        };

        $scope.createGoogleMaps = function() {
            //Create map
            var mapOptions = {
                panControl: false,
                zoomControl: true,
                scaleControl: true,
                streetViewControl: false,
                scrollwheel: false,
                zoom: 8,
                center: new google.maps.LatLng(-34.397, 150.644)
            }
            var addMap = new google.maps.Map(document.getElementById('div-share-add-google-map'), mapOptions);

            //Add search box
            var divSearch = document.getElementById('div-search-address');
            addMap.controls[google.maps.ControlPosition.TOP_CENTER].push(divSearch);

            var marker = null;

            //Configure autocomplete control
            var inputSearch = document.getElementById('input-search-address');
            var autocomplete = new google.maps.places.Autocomplete(inputSearch);

            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();

                //If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    console.log(place.geometry.viewport);
                    addMap.fitBounds(place.geometry.viewport);
                } else {
                    addMap.setCenter(place.geometry.location);
                    addMap.setZoom(17);  // Why 17? Because it looks good.
                }

                marker.setPosition(place.geometry.location);
                updateLatitudeLongitude();
            });

            //Center on wanted bounds
            /*var sw = new google.maps.LatLng(swLatitude, swLongitude);
             var ne = new google.maps.LatLng(neLatitude, neLongitude);
             var mapBounds = new google.maps.LatLngBounds(sw, ne);
             console.log(mapBounds);
             map.fitBounds(mapBounds);*/

            function updateLatitudeLongitude() {
                $('#hidden-share-add-latitude').val(marker.getPosition().lat());
                $('#hidden-share-add-longitude').val(marker.getPosition().lng());
            }

            //Add idle listener
            google.maps.event.addListener(addMap, 'idle', function() {
                if (marker == null) {
                    marker = new google.maps.Marker({
                        position: addMap.getCenter(),
                        map: addMap,
                        title: 'Hello World!',
                        draggable: true
                    });
                    updateLatitudeLongitude();

                    google.maps.event.addListener(marker, 'dragend', function() {
                        updateLatitudeLongitude();
                    });
                }
            });
        };

        //
        $scope.onShareTypeChanged = function() {
            console.log($scope.shareType);
        };

        //
        $scope.initialize = function() {
            //
            $http.get(webroot + 'api/share_type_categories/get')
            .success(function(data, status, headers, config) {
                //
                getShareTypes($scope, data);

                console.log($scope.shareTypes);
            })
            .error(function(data, status, headers, config) {
                console.log(data);
            });

            //
            $scope.createGoogleMaps();
        };

        $scope.initialize();
    }]);
}
