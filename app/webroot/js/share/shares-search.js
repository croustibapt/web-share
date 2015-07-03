/**
 * Method used to initialize the SearchController
 * @param autocompleteInputId GoogleMap address autocomplete identifier
 * @param googleMapDivId GoogleMap div identifier
 * @param placeId GoogleMap place identifier
 * @param shareTypeCategory Start share type category
 * @param shareType Start share type
 * @param period Start period
 */
function initializeSearch(autocompleteInputId, googleMapDivId, placeId, shareTypeCategory, shareType, period) {
    /**
     * SearchController
     */
    app.controller('SearchController', ['$scope', '$http', function($scope, $http) {
        $scope.page = 1;
        $scope.total_pages = 1;
        $scope.total_results = 0;
        $scope.results_count = 0;

        $scope.period = period;

        $scope.address = '';
        $scope.bounds = null;

        $scope.shareTypeCategories = {};
        $scope.shareTypeCategory = shareTypeCategory;
        $scope.shareType = shareType;

        $scope.shares = [];
        
        $scope.map = null;
        $scope.markers = {};

        /**
         * Method used to get an array from a number (used in pagination)
         * @param number Number to transform
         * @returns An array of <number> elements
         */
        $scope.getNumberArray = function(number) {
            return new Array(number);
        };

        /**
         *
         * @param startDate
         * @param endDate
         * @param types
         * @returns {*}
         */
        $scope.createSearchJson = function(startDate, endDate, types) {
            var jsonData = {};

            //Page
            jsonData['page'] = $scope.page;

            //Start date
            if (startDate) {
                jsonData['start'] = startDate;
            }

            //End date
            if (endDate) {
                jsonData['end'] = endDate;
            }

            //Types
            if (types) {
                //Create types array
                var realTypes = [];

                //Loop on types
                for (var i = 0; i < types.length; i++) {
                    var typeId = types[i];

                    if (parseInt(typeId) >= 0) {
                        realTypes[i] = typeId;
                    }
                }

                if (realTypes.length > 0) {
                    jsonData['types'] = realTypes;
                }
            }

            //Region
            if ($scope.bounds != null) {
                var ne = $scope.bounds.getNorthEast();
                var sw = $scope.bounds.getSouthWest();

                //Create region array
                jsonData['region'] = [];

                jsonData['region'][0] = {};
                jsonData['region'][0]['latitude'] = ne.lat();
                jsonData['region'][0]['longitude'] = sw.lng();

                jsonData['region'][1] = {};
                jsonData['region'][1]['latitude'] = ne.lat();
                jsonData['region'][1]['longitude'] = ne.lng();

                jsonData['region'][2] = {};
                jsonData['region'][2]['latitude'] = sw.lat();
                jsonData['region'][2]['longitude'] = ne.lng();

                jsonData['region'][3] = {};
                jsonData['region'][3]['latitude'] = sw.lat();
                jsonData['region'][3]['longitude'] = sw.lng();
            }
            console.log(jsonData);

            return JSON.stringify(jsonData);
        };

        /**
         * Method used to show a specific search page
         * @param page Wanted page
         */
        $scope.showPage = function(page) {
            //Simply call the search method
            $scope.search($scope.shareTypeCategory, $scope.shareType, page, $scope.period, $scope.bounds);
        };

        /**
         * Method used to search for shares
         * @param shareTypeCategory Wanted share type category
         * @param shareType Wanted share type
         * @param page Wanted page
         * @param date Wanted period
         * @param bounds Wanted lat/long bounds
         */
        $scope.search = function(shareTypeCategory, shareType, page, period, bounds) {
            //Store values
            $scope.shareTypeCategory = shareTypeCategory;
            $scope.shareType = shareType;
            $scope.page = page;
            $scope.period = period;
            $scope.bounds = bounds;

            //Handle types
            var types = getTypesWithShareType($scope.shareType, $scope.shareTypeCategory, $scope.shareTypeCategories);

            //Handle period
            var startDate = moment().unix();
            var endDate = null;

            if ($scope.period == 'day') {
                endDate = moment().endOf('day').unix();
            } else if ($scope.period == 'week') {
                endDate = moment().endOf('week').unix();
            } else if ($scope.period == 'month') {
                endDate = moment().endOf('month').unix();
            }

            //Create JSON data
            var jsonData = $scope.createSearchJson(startDate, endDate, types);

            //Search for shares
            $http.post(webroot + 'api/share/search', jsonData)
            .success(function(data, status, headers, config) {
                console.log(data);

                //Handle the JSON response
                $scope.handleSearchResponse(data);
            })
            .error(function(data, status, headers, config) {
                console.log(data);
            });
        };

        /**
         * Method used to handle the search Ajax response
         * @param response The JSON response
         */
        $scope.handleSearchResponse = function(response) {
            //Handle pagination
            $scope.page = parseInt(response.page);
            $scope.total_pages = parseInt(response.total_pages);
            $scope.total_results = parseInt(response.total_results);
            $scope.results_count = response.results.length;

            //Clear markers on map
            $scope.clearMarkers();

            //Handle shares
            var shares = response.results;
            $scope.shares = [];

            for (var i = 0; i < shares.length; i++) {
                var share = shares[i];

                //Add to map
                $scope.addMarker(share);

                //Share type category label
                var shareTypeCategoryLabel = getShareTypeCategoryLabel(share.share_type_category.label);
                share.share_type_category_label = shareTypeCategoryLabel;

                //Share type label
                var shareTypeLabel = getShareTypeLabel(share.share_type_category.label, share.share_type.label);
                share.share_type_label = shareTypeLabel;

                //Share color
                var shareColor = getIconColor(share.share_type_category.label);
                share.share_color = shareColor;

                //Share icon
                var shareIcon = getMarkerIcon(share.share_type_category.label, share.share_type.label);
                share.share_icon = shareIcon;

                //Event date
                var eventDate = new Date(share.event_date);
                var isoEventDate = eventDate.toISOString();
                var momentDay = moment(isoEventDate).format('dddd D MMMM', 'fr');
                share.moment_day = momentDay;

                //Event time
                if (share.event_time != null) {
                    var eventTime = new Date(share.event_date + ' ' + share.event_time);
                    var isoEventTime = eventTime.toISOString();
                    var momentHour = moment(isoEventTime).format('LT', 'fr');
                    share.moment_hour = momentHour;
                }

                //Modified
                var modifiedDate = new Date(share.modified);
                var isoModifiedDate = modifiedDate.toISOString();
                var momentModifiedTimeAgo = moment(isoModifiedDate).fromNow();
                share.moment_modified_time_ago = momentModifiedTimeAgo;

                //Places left
                var totalPlaces = parseInt(share.places) + 1;
                var participationCount = parseInt(share.participation_count) + 1;
                var placesLeft = totalPlaces - participationCount;
                share.places_left = placesLeft;

                var percentage = (participationCount * 100) / totalPlaces;
                share.percentage = percentage;

                //Formatted price
                var price = parseFloat(share.price);
                share.formatted_price = numeral(price).format('0.0a');

                //Details link
                var detailsLink = webroot + 'users/details/' + share.user.external_id;
                share.details_link = detailsLink;

                $scope.shares.push(share);
            }
        };

        /**
         * Method called when the current selected period changed.
         */
        $scope.onPeriodChanged = function() {
            $scope.search($scope.shareTypeCategory, $scope.shareType, 1, $scope.period, $scope.bounds);
        };

        /**
         * Method called when the current selected share type category changed.
         */
        $scope.onShareTypeCategoryChanged = function() {
            //Reset current share type category
            $scope.shareType = '-1';

            //Re-search
            $scope.search($scope.shareTypeCategory, $scope.shareType, 1, $scope.period, $scope.bounds);
        };

        /**
         * Method called when the current selected share type changed.
         */
        $scope.onShareTypeChanged = function() {
            //Re-search
            $scope.search($scope.shareTypeCategory, $scope.shareType, 1, $scope.period, $scope.bounds);
        };

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
         * Method called to show a specific share details page
         * @param shareId Corresponding share identifier
         */
        $scope.showShareDetails = function(shareId) {
            //Simply change window location
            window.location.href = webroot + "share/details/" + shareId;
        };

        /**
         * Method used to make a marker bounce
         * @param shareId Corresponding share identifier
         */
        $scope.bounceMarker = function(shareId) {
            var marker = $scope.markers[shareId];
            marker.setZIndex(1000);
            marker.setAnimation(google.maps.Animation.BOUNCE);
        };

        /**
         * Method used to stop the marker animation
         * @param shareId Corresponding share identifier
         */
        $scope.cancelBounceMarker = function(shareId) {
            var marker = $scope.markers[shareId];
            marker.setZIndex(null);
            marker.setAnimation(null);
        };

        /**
         * Method called to add a marker on the map
         * @param share Corresponding share
         */
        $scope.addMarker = function(share) {
            //Get marker icon
            var icon = getShareMarkerImage(share['share_type_category']['label'], share['share_type']['label']);

            //Create the marker
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(share.latitude, share.longitude),
                map: $scope.map,
                share: share,
                title: share.title,
                icon: '../img/' + icon
            });

            //Add marker to array
            $scope.markers[share.share_id] = marker;

            //Add a click listener on the marker
            google.maps.event.addListener(marker, 'click', function() {
                //Get the corresponding share
                var share = marker.share;

                //Create the window html content
                var contentHtml =
                    '<div class="row" style="margin: 0px;">' +
                    '   <div class="col-md-2 text-center" style="padding-right: 0px;">' +
                    '       <span style="font-size: 24px; color: ' + share.share_color + ';"><i class="' + share.share_icon + '"></i></span>' +
                    '   </div>' +
                    '   <div class="col-md-10">' +
                    '       <span class="text-capitalize">' +
                                share.moment_day +
                    '       </span>' +
                    '       <p>' +
                                share.title +
                    '       </p>'
                    '   </div>' +
                    '</div>'

                    ;

                //Create the window
                var infowindow = new google.maps.InfoWindow({
                    content: contentHtml
                });

                //And open it
                infowindow.open($scope.map, marker);
            });
        };

        /**
         * Method called to clear all the map markers
         */
        $scope.clearMarkers = function() {
            for (var shareId in $scope.markers) {
                var marker = $scope.markers[shareId];
                marker.setMap(null);
            }
            $scope.markers = {};
        };

        /**
         * Method used to center the map on a specific place
         * @param place Place to center on
         */
        $scope.centerMapOnPlace = function(place) {
            //If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                $scope.map.fitBounds(place.geometry.viewport);
            } else {
                $scope.map.setCenter(place.geometry.location);
                $scope.map.setZoom(17);  // Why 17? Because it looks good.
            }
        };

        /**
         * Method used to create the search google map
         * @param autocompleteInputId GoogleMap address autocomplete identifier
         * @param googleMapDivId GoogleMap div identifier
         * @param placeId GoogleMap place identifier
         */
        $scope.createGoogleMap = function(autocompleteInputId, googleMapDivId, placeId) {
            //Create map
            var mapOptions = {
                panControl: false,
                zoomControl: false,
                scaleControl: true,
                streetViewControl: false
            };
            $scope.map = new google.maps.Map(document.getElementById(googleMapDivId), mapOptions);

            //Add search box
            var autocompleteInput = document.getElementById(autocompleteInputId);

            //Configure autocomplete control
            var autocomplete = new google.maps.places.Autocomplete(autocompleteInput);
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                //Get the place
                var place = autocomplete.getPlace();

                //Center on it
                $scope.centerMapOnPlace(place);

                //window.history.pushState("object or string", "Title", "/new-url");
            });

            //Add idle listener
            google.maps.event.addListener($scope.map, 'idle', function() {
                //"Force" update
                $scope.$apply(function() {
                    //Restart search from page 1
                    $scope.search($scope.shareTypeCategory, $scope.shareType, 1, $scope.period, $scope.map.getBounds());
                });
            });

            //Center map
            if (placeId) {
                var request = {
                    placeId: placeId
                };
                var service = new google.maps.places.PlacesService($scope.map);

                service.getDetails(request, function(place, status) {
                    //"Force" update
                    $scope.$apply(function() {
                        //Save address
                        $scope.address = place.formatted_address;

                        //And center on it
                        $scope.centerMapOnPlace(place);
                    });
                });
            } else {
                //Arbitraty center
                $scope.map.setCenter(new google.maps.LatLng(43.5958736, 1.4672682));
                $scope.map.setZoom(13);
            }
        };

        /**
         * Method used to get all the share type categories
         */
        $scope.getShareTypeCategories = function() {
            //
            $http.get(webroot + 'api/share_type_categories/get')
            .success(function(data, status, headers, config) {
                //
                getShareTypeCategories($scope, data);
            })
            .error(function(data, status, headers, config) {
                console.log(data);
            });
        };

        /**
         * Method used to initialize the SearchController
         * @param autocompleteInputId GoogleMap address autocomplete identifier
         * @param googleMapDivId GoogleMap div identifier
         * @param placeId GoogleMap place identifier
         */
        $scope.initialize = function(autocompleteInputId, googleMapDivId, placeId) {
            //Create the GoogleMap
            google.maps.event.addDomListener(window, 'load', $scope.createGoogleMap(autocompleteInputId, googleMapDivId, placeId));
            
            //And get all the share type categories
            $scope.getShareTypeCategories();
        };

        //Initialize the controller
        $scope.initialize(autocompleteInputId, googleMapDivId, placeId);
    }]);
}