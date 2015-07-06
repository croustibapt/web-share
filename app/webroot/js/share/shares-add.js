/**
 * Method used to initialize the AddController
 * @param googleMapDivId GoogleMap div identifier
 * @param autocompleteDivId Autocomplete address input identifier
 * @param autocompleteInputId Autocomplete address div identifier
 * @param latitude Start map latitude
 * @param longitude Start map longitude
 */
function initializeAdd(googleMapDivId, autocompleteDivId, autocompleteInputId, latitude, longitude) {
    /**
     * AddController
     */
    app.controller('AddController', ['$scope', '$http', function($scope, $http) {
        $scope.shareTypes = {};
        $scope.shareTypes[""] = {
                "label": "Type",
                "share_type_category_id": "",
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

        //Share position
        $scope.latitude = latitude;
        $scope.longitude = longitude;

        $scope.map = null;

        /**
         * Method used to format a passed share type cateogory
         * @param shareTypeCategory Share type category to format
         * @returns The corresponding formatted share type category
         */
        $scope.formatShareTypeCategory = function(shareTypeCategory) {
            return getShareTypeCategoryLabel(shareTypeCategory);
        };

        /**
         * Method used to format a passed share type
         * @param shareTypeCategory Corresponding share type category
         * @param shareType Share type to format
         * @returns The corresponding formatted share type
         */
        $scope.formatShareType = function(shareTypeCategory, shareType) {
            return getShareTypeLabel(shareTypeCategory, shareType);
        };

        /**
         * Method used to update the share location
         */
        $scope.updateLatitudeLongitude = function(marker) {
            //"Force" update
            $scope.$apply(function() {
                $scope.latitude = marker.getPosition().lat();
                $scope.longitude = marker.getPosition().lng();
            });
        }

        /**
         * Method called to create the GoogleMap
         * @param googleMapDivId GoogleMap div identifier
         * @param autocompleteDivId Autocomplete address input identifier
         * @param autocompleteInputId Autocomplete address div identifier
         */
        $scope.createGoogleMap = function(googleMapDivId, autocompleteDivId, autocompleteInputId) {
            //Create map
            var mapOptions = {
                panControl: false,
                zoomControl: true,
                scaleControl: true,
                streetViewControl: false,
                scrollwheel: false,
                zoom: 8,
                center: new google.maps.LatLng($scope.latitude, $scope.longitude)
            }
            $scope.map = new google.maps.Map(document.getElementById(googleMapDivId), mapOptions);

            //Add search box
            var divSearch = document.getElementById(autocompleteDivId);
            $scope.map.controls[google.maps.ControlPosition.TOP_CENTER].push(divSearch);

            var marker = null;

            //Configure autocomplete control
            var inputSearch = document.getElementById(autocompleteInputId);
            var autocomplete = new google.maps.places.Autocomplete(inputSearch);

            //Add autocomplete listener
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();

                //If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    console.log(place.geometry.viewport);
                    $scope.map.fitBounds(place.geometry.viewport);
                } else {
                    $scope.map.setCenter(place.geometry.location);
                    $scope.map.setZoom(17);  // Why 17? Because it looks good.
                }

                //Update marker position
                marker.setPosition(place.geometry.location);

                //Update share position
                $scope.updateLatitudeLongitude(marker);
            });

            //Center map
            if (!geolocate($scope)) {
                //Arbitraty fit
                $scope.map.fitBounds(getStartBounds());
            }

            //Add idle listener
            google.maps.event.addListener($scope.map, 'idle', function() {
                if (marker == null) {
                    //Create the initial marker
                    marker = new google.maps.Marker({
                        position: $scope.map.getCenter(),
                        map: $scope.map,
                        title: 'Hello World!',
                        draggable: true
                    });

                    //Update share position
                    $scope.updateLatitudeLongitude(marker);

                    //Update share position when the marker is released
                    google.maps.event.addListener(marker, 'dragend', function() {
                        //Update share position
                        $scope.updateLatitudeLongitude(marker);
                    });
                }
            });
        };

        /**
         * Method used to initialize the AddController
         * @param googleMapDivId GoogleMap div identifier
         * @param autocompleteDivId Autocomplete address input identifier
         * @param autocompleteInputId Autocomplete address div identifier
         */
        $scope.initialize = function(googleMapDivId, autocompleteDivId, autocompleteInputId) {
            //Get all share types
            $http.get(webroot + 'api/share_type_categories/get')
            .success(function(data, status, headers, config) {
                //Handle JSON response
                getShareTypes($scope, data);
            })
            .error(function(data, status, headers, config) {
                console.log(data);
            });

            //Create the GoogleMap
            $scope.createGoogleMap(googleMapDivId, autocompleteDivId, autocompleteInputId);
        };

        //Initialize the AddController
        $scope.initialize(googleMapDivId, autocompleteDivId, autocompleteInputId);
    }]);
}
