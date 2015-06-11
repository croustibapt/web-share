/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Create SearchController
app.controller('SearchController', ['$scope', '$http', function($scope, $http) {
    $scope.date = 'all';
    $scope.startDate = null;
    $scope.endDate = null;
    $scope.types = null;
    $scope.shareTypeCategory = 0;
    $scope.shareType = 0;
    
    $scope.shareTypeCategories = [];
    $scope.shares = [];
    
    //$scope.shareTypeCategories['all'] = [];
    
    $http.get(webroot + 'api/share_type_categories/get').
    success(function(data, status, headers, config) {
        $scope.shareTypeCategories.push({"label": "all", "share_type_category_id": -1, "share_types": []});
        
        for (var shareTypeCategoryIndex in data.results) {
            var shareTypeCategory = data.results[shareTypeCategoryIndex];
            shareTypeCategory['share_types'].unshift({"label": "all", "share_type_category_id": shareTypeCategory.share_type_category_id, "share_type_id": -1});
            
            $scope.shareTypeCategories.push(shareTypeCategory);
        }
        
        console.log($scope.shareTypeCategories);
    }).
    error(function(data, status, headers, config) {
        console.log(data);
    });

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
    };
    
    //
    $scope.onShareTypeCategoryChanged = function() {
        console.log($scope.shareTypeCategory);
        
        if ($scope.shareTypeCategory === 'all') {
            $scope.types = null;
        } else {
            $scope.types = [];

            var shareTypes = $scope.shareTypeCategories[$scope.shareTypeCategory];

            for (var shareTypeId in shareTypes) {
                $scope.types.push(shareTypeId);
            }
        }

        //loadShares(<?php echo $page; ?>, $scope.startDate, $scope.endDate, $scope.types);
    };

    //
    $scope.onShareTypeChanged = function() {
        console.log($scope.shareType);
    };

    //
    $scope.formatShareTypeCategory = function (shareTypeCategory) {
        return shareTypeCategory;
    };
}]);

function searchHandleResponse(response) {
    var searchResultsDiv = $('#div-search-results');
    var searchScope = angular.element(searchResultsDiv).scope();

    searchScope.$apply(function(){
        searchScope.handleResponse(response);
    });
}