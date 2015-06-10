/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Create SearchController
app.controller('SearchController', ['$scope', function($scope) {
    $scope.shares = [];

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
}]);

function searchHandleResponse(response) {
    var searchResultsDiv = $('#div-search-results');
    var searchScope = angular.element(searchResultsDiv).scope();

    searchScope.$apply(function(){
        searchScope.handleResponse(response);
    });
}