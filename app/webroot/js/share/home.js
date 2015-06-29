/**
 * Created by bleguelvouit on 11/06/15.
 */

/**
 *
 * @param inputId
 */
function initializeHome(inputId) {
    //Create HomeController
    app.controller('HomeController', ['$scope', '$http', function($scope, $http) {
        $scope.shareTypeCategories = {};
        $scope.shareTypeCategory = "-1";
        $scope.shareType = "-1";

        $scope.viewPort = '';
        $scope.autocomplete = null;

        $scope.initializeAutocompleteInput = function(inputId) {
            //Prepare wanted types
            var types = {
                types: ['geocode']
            };

            var input = document.getElementById(inputId);

            //Create the autocomplete object, restricting the search to geographical location types.
            $scope.autocomplete = new google.maps.places.Autocomplete(input, types);

            //
            google.maps.event.addListener($scope.autocomplete, 'place_changed', function() {
                var place = $scope.autocomplete.getPlace();
                //console.log(place);

                //If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    //console.log(place.geometry.viewport);

                    //
                    var jsonViewport = JSON.stringify(place.geometry.viewport);

                    //
                    $scope.viewport = encodeURI(jsonViewport);
                }
            });
        };

        //
        $scope.geolocate = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });

                    $scope.autocomplete.setBounds(circle.getBounds());
                });
            }
        };

        //
        $scope.onShareTypeCategoryChanged = function() {
            //
            $scope.shareType = '-1';
        };

        //
        $scope.formatShareTypeCategory = function(shareTypeCategory) {
            return getShareTypeCategoryLabel(shareTypeCategory);
        };

        //
        $scope.formatShareType = function(shareTypeCategory, shareType) {
            return getShareTypeLabel(shareTypeCategory, shareType);
        };

        //
        $http.get(webroot + 'api/share_type_categories/get')
        .success(function (data, status, headers, config) {
            //
            getShareTypeCategories($scope, data);
        })
        .error(function (data, status, headers, config) {
            console.log(data);
        });

        //
        $scope.initializeAutocompleteInput(inputId);
    }]);
}
