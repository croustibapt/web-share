/**
 * Method used to initialize the HomeController
 * @param autocompleteInputId Identifier used to link the GoogleMap autocomplete input
 */
function initializeHome(autocompleteInputId) {
    /**
     * Home controller
     */
    app.controller('HomeController', ['$scope', '$http', function($scope, $http) {
        //All available share type categories
        $scope.shareTypeCategories = {};
        //Current selected share type category
        $scope.shareTypeCategory = "-1";
        //Current selected share type
        $scope.shareType = "-1";

        //Viewport model
        $scope.viewPort = '';

        //GoogleMaps address autocomplete input
        $scope.autocomplete = null;

        /**
         * Method called to link the GoogleMap autocomplete input
         * @param autocompleteInputId Identifier used to link the GoogleMap autocomplete input
         */
        $scope.initializeAutocompleteInput = function(autocompleteInputId) {
            //Prepare wanted types
            var types = {
                types: ['geocode']
            };

            //Get corresponding input
            var input = document.getElementById(autocompleteInputId);

            //Create the autocomplete object, restricting the search to geographical location types
            $scope.autocomplete = new google.maps.places.Autocomplete(input, types);

            //Add listener (for place changes) to update the current viewport
            google.maps.event.addListener($scope.autocomplete, 'place_changed', function() {
                //Get current place
                var place = $scope.autocomplete.getPlace();

                //If the place has a geometry
                if (place.geometry.viewport) {
                    //Convert the viewport to JSON format
                    var jsonViewport = JSON.stringify(place.geometry.viewport);

                    //And update the model
                    $scope.viewport = encodeURI(jsonViewport);
                }
            });
        };

        /**
         * Method called to use the browser location
         */
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

        /**
         * Method called when the current selected share type category changed.
         */
        $scope.onShareTypeCategoryChanged = function() {
            //
            $scope.shareType = '-1';
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
         * Method used to get all the share types categories
         */
        $scope.getShareTypeCategories = function() {
            //
            $http.get(webroot + 'api/share_type_categories/get')
            .success(function (data, status, headers, config) {
                //
                getShareTypeCategories($scope, data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        }

        /**
         * Method called to initialize the HomeController
         * @param autocompleteInputId Identifier used to link the GoogleMap autocomplete input
         */
        $scope.initialize = function(autocompleteInputId) {
            //Get all share type categories
            $scope.getShareTypeCategories();

            //Initialize the GoogleMap autocomplete input
            $scope.initializeAutocompleteInput(autocompleteInputId);
        }

        //Initialize the controller
        $scope.initialize(autocompleteInputId);
    }]);
}
