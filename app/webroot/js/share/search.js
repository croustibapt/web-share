/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Create SearchController
app.controller('SearchController', ['$scope', '$http', function($scope, $http) {
    $scope.page = 1;
    $scope.total_pages = 1;

    $scope.date = 'all';
    $scope.startDate = null;
    $scope.endDate = null;
    $scope.types = null;
    $scope.shareTypeCategory = "-1";
    $scope.shareType = "-1";
    $scope.bounds = null;
    
    $scope.shareTypeCategories = [];
    $scope.shares = [];

    //
    $http.get(webroot + 'api/share_type_categories/get').
    success(function(data, status, headers, config) {
        $scope.shareTypeCategories[-1] = {
            "label": "all",
            "share_type_category_id": -1,
            "share_types": []
        };
        
        for (var shareTypeCategoryIndex in data.results) {
            var shareTypeCategory = data.results[shareTypeCategoryIndex];

            var shareTypes = shareTypeCategory['share_types'];
            shareTypeCategory['share_types'] = {};
            shareTypeCategory['share_types'][-1] = {
                "label": "all",
                "share_type_category_id": shareTypeCategory.share_type_category_id,
                "share_type_id": -1
            };

            for (var shareTypeIndex in shareTypes) {
                var shareType = shareTypes[shareTypeIndex];
                shareTypeCategory['share_types'][shareType.share_type_id] = shareType;
            }

            $scope.shareTypeCategories[shareTypeCategory.share_type_category_id] = shareTypeCategory;
        }
        
        //console.log($scope.shareTypeCategories);
    }).
    error(function(data, status, headers, config) {
        console.log(data);
    });

    /**
     *
     * @param num
     * @returns {Array}
     */
    $scope.getNumber = function(num) {
        return new Array(num);
    };

    /**
     *
     * @param page
     * @param startDate
     * @param endDate
     * @param types
     * @param bounds
     * @returns {*}
     */
    $scope.createSearchJson = function() {
        var jsonData = {};

        //Page
        jsonData['page'] = $scope.page;

        //Start date
        if ($scope.startDate) {
            jsonData['start'] = $scope.startDate;
        }

        //End date
        if ($scope.endDate) {
            jsonData['end'] = $scope.endDate;
        }

        //Types
        if ($scope.types) {
            //Create types array
            jsonData['types'] = [];

            //Loop on types
            for (var i = 0; i < $scope.types.length; i++) {
                jsonData['types'][i] = $scope.types[i];
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
    }

    /**
     *
     * @param page
     * @param startDate
     * @param endDate
     * @param types
     * @param bounds
     */
    $scope.search = function(page, startDate, endDate, types, bounds) {
        //Store values
        $scope.page = page;
        $scope.startDate = startDate;
        $scope.endDate = endDate;
        $scope.types = types;
        $scope.bounds = bounds;

        //Create JSON data
        var jsonData = $scope.createSearchJson();

        //
        $http.post(webroot + 'api/share/search', jsonData).
        success(function(data, status, headers, config) {
            console.log(data);

            //Results
            $scope.handleResponse(data);
        }).
        error(function(data, status, headers, config) {
            console.log(data);
        });
    }

    /**
     * Method used to handle the Ajax response
     * @param response
     */
    $scope.handleResponse = function(response) {
        //Handle pagination
        $scope.page = parseInt(response.page);
        $scope.total_pages = parseInt(response.total_pages);

        //Clear markers on map
        clearMarkers();

        //Handle shares
        var shares = response.results;
        $scope.shares = [];

        for (var i = 0; i < shares.length; i++) {
            var share = shares[i];

            //Add to map
            addMarker(share);

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
    };
    
    //
    $scope.onShareTypeCategoryChanged = function() {
        //
        if ($scope.shareTypeCategory == -1) {
            $scope.types = null;
        } else {
            $scope.types = [];

            var shareTypes = $scope.shareTypeCategories[$scope.shareTypeCategory]['share_types'];

            for (var shareTypeId in shareTypes) {
                $scope.types.push(shareTypeId);
            }
        }

        $scope.search(1, $scope.startDate, $scope.endDate, $scope.types);
    };

    //
    $scope.onShareTypeChanged = function() {
        //
        if ($scope.shareType == -1) {
            $scope.types = [];

            var shareTypes = $scope.shareTypeCategories[$scope.shareTypeCategory]['share_types'];

            for (var shareTypeId in shareTypes) {
                $scope.types.push(shareTypeId);
            }
        } else {
            $scope.types = [$scope.shareType];
        }

        $scope.search(1, $scope.startDate, $scope.endDate, $scope.types);
    };

    //
    $scope.formatShareTypeCategory = function (shareTypeCategory) {
        return shareTypeCategory;
    };
}]);

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

function initialize(zoom, latitude, longitude) {
    var mapOptions = {
        zoom: zoom,
        center: new google.maps.LatLng(latitude, longitude)
    };
    map = new google.maps.Map(document.getElementById('div-share-search-google-map'), mapOptions);

    google.maps.event.addListener(map, 'idle', function() {
        var searchResultsDiv = $('#div-search-results');
        var searchScope = angular.element(searchResultsDiv).scope();

        searchScope.$apply(function() {
            //Restart search from page 1
            searchScope.search(1, searchScope.startDate, searchScope.endDate, searchScope.types, map.getBounds());
        });
    });
}