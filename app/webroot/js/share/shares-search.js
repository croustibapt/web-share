/**
 * Method used to initialize the SearchController
 * @param autocompleteInputId GoogleMap address autocomplete identifier
 * @param googleMapDivId GoogleMap div identifier
 * @param placeId GoogleMap place identifier
 * @param shareTypeCategory Start share type category
 * @param shareType Start share type
 * @param period Start period
 */
function initializeSharesSearch(autocompleteInputId, googleMapDivId, shareTypeCategory, shareType, period, placeId, latitude, longitude, zoom) {
    /**
     * SearchController
     */
    app.controller('SharesSearchController', ['$scope', '$http', function($scope, $http) {
        $scope.page = null;
        $scope.total_pages = 0;
        $scope.total_results = 0;
        $scope.results_count = 0;
        $scope.results_by_page = 10;

        $scope.period = period;

        $scope.address = '';
        $scope.placeId = placeId;
        $scope.bounds = null;
        $scope.latitude = latitude;
        $scope.longitude = longitude;
        $scope.zoom = zoom;

        $scope.autocomplete = null;

        $scope.shareTypeCategories = {};
        $scope.shareTypeCategory = shareTypeCategory;
        $scope.shareType = shareType;

        $scope.shares = {};
        
        $scope.map = null;
        $scope.firstIdle = true;
        $scope.markers = {};
        $scope.infoWindow = new google.maps.InfoWindow({
            /*disableAutoPan: true,*/
            maxWidth: 250
        });

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
        $scope.createSearchJson = function(startDate, endDate, types, page) {
            var jsonData = {};

            //Page
            jsonData['page'] = page;

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
            //Update page
            $scope.page = null;

            //Simply call the search method
            $scope.search(page);
        };

        $scope.refreshUrl = function() {
            var url = webroot + 'shares/search?period=' + $scope.period +
                '&share_type_category=' + $scope.shareTypeCategory +
                '&share_type=' + $scope.shareType +
                '&place_id=' + $scope.placeId +
                '&lat=' + $scope.latitude +
                '&lng=' + $scope.longitude +
                '&zoom=' + $scope.zoom;

            url = encodeURI(url);
            console.log(url);

            window.history.pushState(null, null, url);
        };

        /**
         * Method used to search for shares
         * @param shareTypeCategory Wanted share type category
         * @param shareType Wanted share type
         * @param page Wanted page
         * @param date Wanted period
         * @param bounds Wanted lat/long bounds
         */
        $scope.search = function(page) {
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
            var jsonData = $scope.createSearchJson(startDate, endDate, types, page);

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
         * Method called to clear all the current shares
         */
        $scope.clearShares = function(newShares) {
            var shareIds = [];
            for (var newShareId in newShares) {
                var newShare = newShares[newShareId];
                shareIds.push(newShare.share_id);
            }

            for (var shareId in $scope.shares) {
                if (shareIds.indexOf(shareId) == -1) {
                    var marker = $scope.markers[shareId];
                    marker.setMap(null);

                    delete $scope.shares[shareId];
                    delete $scope.markers[shareId];
                }
            }
        };

        $scope.updateShare = function(share) {
            var shareId = share.share_id;
            $scope.shares[shareId] = share;

            var marker = $scope.markers[shareId];
            if (marker != null) {
                marker.setPosition(new google.maps.LatLng(share.latitude, share.longitude));
                marker.share = share;
                marker.setTitle(share.title);
                //marker.setIcon('../img/' + icon);

                if ($scope.infoWindow.share_id == shareId) {
                    $scope.infoWindow.setContent($scope.createMarkerHtmlContent(share));
                }
            }
        };

        /**
         * Method used to handle the search Ajax response
         * @param response The JSON response
         */
        $scope.handleSearchResponse = function(response) {
            //Handle pagination
            $scope.total_pages = parseInt(response.total_pages);
            $scope.total_results = parseInt(response.total_results);
            $scope.results_count = response.results.length;
            $scope.page = parseInt(response.page);

            //Handle shares
            var shares = response.results;

            //Clear markers on map
            $scope.clearShares(shares);

            for (var i = 0; i < shares.length; i++) {
                var share = shares[i];

                formatShare(share);

                if (!$scope.shares[share.share_id]) {
                    //Add to map
                    $scope.addMarker(share);
                    $scope.shares[share.share_id] = share;
                } else {
                    //Update content
                    $scope.updateShare(share);
                }
            }
        };

        /**
         * Method called when the current selected period changed.
         */
        $scope.onPeriodChanged = function() {
            //Reset page
            $scope.page = 1;

            //And update results
            $scope.search();

            //Refresh URL (for History)
            $scope.refreshUrl();
        };

        /**
         * Method called when the current selected share type category changed.
         */
        $scope.onShareTypeCategoryChanged = function() {
            //Reset current share type category
            $scope.shareType = '-1';

            //Reset page
            $scope.page = 1;

            //And update results
            $scope.search();

            //Refresh URL (for History)
            $scope.refreshUrl();
        };

        /**
         * Method called when the current selected share type changed.
         */
        $scope.onShareTypeChanged = function() {
            //Reset page
            $scope.page = 1;

            //And update results
            $scope.search();

            //Refresh URL (for History)
            $scope.refreshUrl();
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
            showShareDetails(shareId);
        };

        /**
         * Method used to make a marker bounce
         * @param shareId Corresponding share identifier
         */
        $scope.bounceMarker = function(shareId) {
            var marker = $scope.markers[shareId];
            marker.setZIndex(1000);
            marker.setIcon('../img/markers/40/marker-selected.png');
        };

        /**
         * Method used to stop the marker animation
         * @param shareId Corresponding share identifier
         */
        $scope.cancelBounceMarker = function(shareId) {
            var marker = $scope.markers[shareId];

            var share = $scope.shares[shareId];

            marker.setZIndex(null);
            marker.setIcon('../img/markers/40/marker-' + share.share_type_category.label + '-' + share.share_type.label + '.png');
        };

        $scope.getShareMarkerImage = function(shareTypeCategoryLabel, shareTypeLabel) {
            return getShareMarkerImage(shareTypeCategoryLabel, shareTypeLabel);
        };

        $scope.createMarkerHtmlContent = function(share) {
            //Places label
            var placesLabel = null;
            if (share.places_left > 1) {
                placesLabel = '<span class="text-info">' + share.places_left + ' places</span>';
            } else if (share.places_left > 0) {
                placesLabel = '<span class="text-warning">' + '1 place</span>';
            } else {
                placesLabel = '<span class="text-success">Complet</span>';
            }

            //Create the window html content
            var contentHtml =
                '<div class="info-window-div" share-id="' + share.share_id + '">' +
                '   <p class="text-capitalize text-muted info-window-date-p">' + share.moment_day + '</p>' +
                '   <p class="text-capitalize line-clamp line-clamp-1 info-window-type-p" style="color: ' + share.share_color + ';">' +
                '       <span class="info-window-type-category-span">' + share.share_type_category_label + '</span> / ' + '<span class="share-card-type-span">' + share.share_type_label + '</span>' +
                '   </p>' +
                '   <p class="info-window-title-p line-clamp line-clamp-3">' +
                share.title +
                '   </p>' +
                '   <p class="info-window-places-price-p">' +
                placesLabel +
                '<span class="pull-right text-muted"><strong class="text-info">' + share.formatted_price + '€</strong> / pers.</span>' +
                '   </p>' +
                '</div>';

            return contentHtml;
        };

        /**
         * Method called to add a marker on the map
         * @param share Corresponding share
         */
        $scope.addMarker = function(share) {
            //Create the marker
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(share.latitude, share.longitude),
                map: $scope.map,
                share: share,
                title: share.title,
                icon: '../img/markers/40/marker-' + share.share_type_category.label + '-' + share.share_type.label + '.png'
            });

            //Add marker to array
            $scope.markers[share.share_id] = marker;

            //Add a click listener on the marker
            google.maps.event.addListener(marker, 'click', function() {
                //Get the corresponding share
                var share = marker.share;
                marker.setZIndex(1000);

                //Create the window
                $scope.infoWindow.setContent($scope.createMarkerHtmlContent(share));
                $scope.infoWindow.share_id = share.share_id;

                //And open it
                $scope.infoWindow.open($scope.map, marker);
            });
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

        $scope.onMapReady = function(place) {
            if (place == null) {
                if (($scope.latitude != null) && ($scope.longitude != null) && ($scope.zoom != null)) {
                    console.log('place null => lat lng');

                    //Center on position
                    $scope.map.setCenter(new google.maps.LatLng($scope.latitude, $scope.longitude));

                    //And zoom
                    $scope.map.setZoom($scope.zoom);
                } else {
                    //Arbitraty fit
                    $scope.map.fitBounds(getStartBounds());

                    //And then, try to locate the user
                    geolocate($scope, function(position) {
                        //Center on position
                        $scope.map.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
                    });
                }
            } else {
                if (($scope.latitude != null) && ($scope.longitude != null) && ($scope.zoom != null)) {
                    console.log('place not null => lat lng');

                    //Center on position
                    $scope.map.setCenter(new google.maps.LatLng($scope.latitude, $scope.longitude));

                    //And zoom
                    $scope.map.setZoom($scope.zoom);
                } else {
                    console.log('place not null => place');

                    $scope.centerMapOnPlace(place);
                }
            }

            //Add idle listener
            google.maps.event.addListener($scope.map, 'idle', function() {
                //"Force" update
                $scope.$apply(function() {
                    console.log('idle');
                    //Store bounds
                    $scope.bounds = $scope.map.getBounds();

                    //And position
                    $scope.latitude = $scope.map.getCenter().lat();
                    $scope.longitude = $scope.map.getCenter().lng();
                    $scope.zoom = $scope.map.getZoom();

                    //Reset page
                    $scope.page = 1;

                    //And update results
                    $scope.search();

                    if ($scope.firstIdle) {
                        $scope.firstIdle = false;
                    } else {
                        //Refresh URL (for History)
                        $scope.refreshUrl();
                    }
                });
            });

            google.maps.event.addListener($scope.map, 'click', function() {
                console.log('map clicked');
                $scope.infoWindow.close();
            });
        };

        $scope.createGoogleMap = function(autocompleteInputId, googleMapDivId) {
            console.log(googleMapDivId);

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
            $scope.autocomplete = new google.maps.places.Autocomplete(autocompleteInput);
            google.maps.event.addListener($scope.autocomplete, 'place_changed', function() {
                //Get the place
                var place = $scope.autocomplete.getPlace();

                //Save new values
                $scope.placeId = place.place_id;
                $scope.address = place.formatted_address;

                //Center on it
                $scope.centerMapOnPlace(place);
            });

            //Get place if exists
            if ($scope.placeId !== '') {
                var request = {
                    placeId: $scope.placeId
                };

                var service = new google.maps.places.PlacesService($scope.map);
                service.getDetails(request, function(place, status) {
                    //"Force" update
                    $scope.$apply(function() {
                        //Save address
                        $scope.address = place.formatted_address;
                    });

                    $scope.onMapReady(place);
                });
            } else {
                $scope.onMapReady(null);
            }
        };

        /**
         * Method used to initialize the SearchController
         * @param autocompleteInputId GoogleMap address autocomplete identifier
         * @param googleMapDivId GoogleMap div identifier
         * @param placeId GoogleMap place identifier
         */
        $scope.initialize = function(autocompleteInputId, googleMapDivId) {
            console.log('initializeWithPlaceId');

            //Create the GoogleMap
            google.maps.event.addDomListener(window, 'load', $scope.createGoogleMap(autocompleteInputId, googleMapDivId));
            
            //And get all the share type categories
            $scope.getShareTypeCategories();
        };

        //Initialize the controller
        $scope.initialize(autocompleteInputId, googleMapDivId);
    }]);
}