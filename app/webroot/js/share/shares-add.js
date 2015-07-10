/**
 * Method used to initialize the AddController
 * @param googleMapDivId GoogleMap div identifier
 * @param autocompleteDivId Autocomplete address input identifier
 * @param autocompleteInputId Autocomplete address div identifier
 * @param latitude Start map latitude
 * @param longitude Start map longitude
 */
function initializeSharesAdd(googleMapDivId, autocompleteDivId, autocompleteInputId, shareTypeId) {
    /**
     * AddController
     */
    app.controller('SharesAddController', ['$scope', '$http', function($scope, $http) {
        $scope.shareTypes = {};
        $scope.shareTypes[""] = {
                "label": "Type",
                "share_type_category_id": "",
                "share_type_id": ""
            };
        $scope.shareType = shareTypeId;

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

        //Waiting time
        $scope.waitingTime = "";
        $scope.waitingTimes = {};

        $scope.waitingTimes[""] = "Waiting time";
        $scope.waitingTimes["15"] = "15 min";
        $scope.waitingTimes["30"] = "30 min";
        $scope.waitingTimes["45"] = "45 min";

        //Share position
        $scope.latitude = null;
        $scope.longitude = null;

        $scope.map = null;
        $scope.marker = null;

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
        $scope.updateLatitudeLongitude = function() {
            //"Force" update
            $scope.$apply(function() {
                $scope.latitude = $scope.marker.getPosition().lat();
                $scope.longitude = $scope.marker.getPosition().lng();
            });
        }

        $scope.moveMarker = function(position, move) {
            //Check if we need to create it
            if ($scope.marker == null) {
                //Create the initial marker
                $scope.marker = new google.maps.Marker({
                    position: position,
                    map: $scope.map,
                    title: 'Hello World!',
                    draggable: true
                });

                //Update share position when the marker is released
                google.maps.event.addListener($scope.marker, 'dragend', function() {
                    //Update share position
                    $scope.updateLatitudeLongitude();
                });
            } else if (move) {
                //Update marker position
                $scope.marker.setPosition(position);

                //Update share position
                $scope.updateLatitudeLongitude();
            }
        }

        $scope.onMapReady = function() {
            //Arbitraty fit
            $scope.map.fitBounds(getStartBounds());

            //And then, try to locate the user
            geolocate($scope, function(position) {
                //Move marker
                var position = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                $scope.moveMarker(position, true);
            });

            //Add idle listener
            google.maps.event.addListener($scope.map, 'idle', function() {
                var position = $scope.map.getCenter();
                $scope.moveMarker(position, false);
            });
        };

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

            //Configure autocomplete control
            var inputSearch = document.getElementById(autocompleteInputId);
            var autocomplete = new google.maps.places.Autocomplete(inputSearch);

            //Add autocomplete listener
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();

                //If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    $scope.map.fitBounds(place.geometry.viewport);
                } else {
                    $scope.map.setCenter(place.geometry.location);
                    $scope.map.setZoom(17);  // Why 17? Because it looks good.
                }

                //Move marker
                $scope.moveMarker(place.geometry.location, true);
            });

            $scope.onMapReady();
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
