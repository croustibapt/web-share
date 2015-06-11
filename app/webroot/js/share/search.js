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

    $scope.bounds = null;

    $scope.shareTypeCategories = {};
    $scope.shareTypeCategory = "-1";
    $scope.shareType = "-1";

    $scope.shares = [];

    //
    $http.get(webroot + 'api/share_type_categories/get')
    .success(function(data, status, headers, config) {
        //
        getShareTypeCategories($scope, data);
    })
    .error(function(data, status, headers, config) {
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
            jsonData['types'] = [];

            //Loop on types
            for (var i = 0; i < types.length; i++) {
                var typeId = types[i];

                if (Number.isInteger(typeId)) {
                    jsonData['types'][i] = typeId;
                }
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
    $scope.search = function(shareTypeCategory, shareType, page, date, bounds) {
        //Store values
        $scope.shareTypeCategory = shareTypeCategory;
        $scope.shareType = shareType;
        $scope.page = page;
        $scope.date = date;
        $scope.bounds = bounds;

        var types = getTypesWithShareType($scope.shareType, $scope.shareTypeCategory, $scope.shareTypeCategories);

        var startDate = null;
        var endDate = null;

        if (date == 'day') {
            startDate = moment().startOf('day').unix();
            endDate = moment().endOf('day').unix();
        } else if (date == 'week') {
            startDate = moment().startOf('week').unix();
            endDate = moment().endOf('week').unix();
        } else if (date == 'month') {
            startDate = moment().startOf('month').unix();
            endDate = moment().endOf('month').unix();
        }

        //Create JSON data
        var jsonData = $scope.createSearchJson(startDate, endDate, types);

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
        $scope.search($scope.shareTypeCategory, $scope.shareType, 1, $scope.date, $scope.bounds);
    };

    //
    $scope.onShareTypeChanged = function() {
        $scope.search($scope.shareTypeCategory, $scope.shareType, 1, $scope.date, $scope.bounds);
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

function initialize(shareTypeCategory, shareType, date, neLatitude, neLongitude, swLatitude, swLongitude) {
    map = new google.maps.Map(document.getElementById('div-share-search-google-map'), null);

    var sw = new google.maps.LatLng(swLatitude, swLongitude);
    var ne = new google.maps.LatLng(neLatitude, neLongitude);
    var mapBounds = new google.maps.LatLngBounds(sw, ne);
    map.fitBounds(mapBounds);

    google.maps.event.addListener(map, 'idle', function() {
        var searchResultsDiv = $('#div-search-results');
        var searchScope = angular.element(searchResultsDiv).scope();

        searchScope.$apply(function() {
            //Restart search from page 1
            searchScope.search(shareTypeCategory, shareType, 1, date, map.getBounds());
        });
    });
}