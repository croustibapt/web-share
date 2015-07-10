/**
 * Method used to initialize the Details controller
 * @param shareId Corresponding share identifier
 * @param shareUserExternalId Corresponding user external identifier
 * @param textAreaId Comment text area identifier
 * @param divGoogleMapId GoogleMap div identifier
 */
function initializeSharesDetails(shareId, shareUserExternalId, textAreaId, divGoogleMapId) {
    /**
     * SharesDetailsController
     */
    app.controller('SharesDetailsController', ['$scope', '$http', function($scope, $http) {
        //Pagination
        $scope.page = null;
        $scope.total_pages = 1;
        $scope.total_results = 0;
        $scope.results_count = 0;
        $scope.results_by_page = 5;

        //Share
        $scope.shareId = shareId;
        $scope.share = null;

        //Google map
        $scope.divGoogleMapId = divGoogleMapId;

        //Comments
        $scope.textAreaId = textAreaId;
        $scope.message = null;
        $scope.comments = [];

        //User
        $scope.shareUserExternalId = shareUserExternalId;
        $scope.user = null;

        /**
         * Method used to get an array from a number (used in pagination)
         * @param number Number to transform
         * @returns An array of <number> elements
         */
        $scope.getNumberArray = function(number) {
            return new Array(number);
        };

        /**
         * Method used to show a specifi comments page
         * @param page Page to show
         */
        $scope.showPage = function(page, onPageLoaded) {
            $scope.page = null;

            //Get call
            $http.get(webroot + 'api/comment/get?shareId=' + $scope.shareId + '&page=' + page)
            .success(function (data, status, headers, config) {
                //Parse JSON response
                $scope.handleCommentsResponse(data);

                //Call delegate
                if (typeof onPageLoaded === 'function') {
                    onPageLoaded(true);
                }
            })
            .error(function (data, status, headers, config) {
                console.log(data);

                //Call delegate
                if (typeof onPageLoaded === 'function') {
                    onPageLoaded(true);
                }
            });
        };

        /**
         * Method used to handle the comments Ajax response
         * @param response The JSON response to parse
         */
        $scope.handleCommentsResponse = function(response) {
            //Handle pagination
            $scope.total_pages = parseInt(response.total_pages);
            $scope.total_results = parseInt(response.total_results);
            $scope.results_count = response.results.length;
            $scope.page = parseInt(response.page);

            //Handle comments
            var comments = response.results;
            $scope.comments = [];

            for (var i = 0; i < comments.length; i++) {
                var comment = comments[i];

                var htmlDate = comment.created;
                var eventDate = new Date(htmlDate);
                var isoEventDate = eventDate.toISOString();

                var momentModifiedTimeAgo = moment(isoEventDate).fromNow();
                comment.moment_created_time_ago = momentModifiedTimeAgo;

                //Add to array
                $scope.comments.push(comment);
            }
        };

        /**
         * Method used to get the user details
         * @param userExternalId Wanted user external identifier
         */
        $scope.getUserDetails = function(userExternalId) {
            //Get call
            $http.get(webroot + 'api/user/details/' + userExternalId)
            .success(function (data, status, headers, config) {
                //Parse JSON response
                $scope.handleUserDetailsResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };

        /**
         * Method used to parse the user details JSON response
         * @param response JSON repsonse to parse
         */
        $scope.handleUserDetailsResponse = function(response) {
            //Save user
            $scope.user = response;

            formatUser($scope.user);
        };

        /**
         * Method used to get the sare details
         * @param shareId Wanted share identifier
         */
        $scope.getShareDetails = function(shareId) {
            //Get call
            $http.get(webroot + 'api/share/details/' + shareId)
            .success(function (data, status, headers, config) {
                //Parse JSON response
                $scope.handleDetailsResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };

        /**
         * Method used to parse the share details JSON response
         * @param response The JSON response to parse
         */
        $scope.handleDetailsResponse = function(response) {
            console.log(response);
            var share = response;

            formatShare(share);

            //Store the share
            $scope.share = share;

            //Create map
            $scope.createGoogleMap();
        };

        /**
         * Method called when the send button was clicked (ui)
         * @param $event Touch event
         */
        $scope.onSendButtonClicked = function($event) {
            var button = angular.element($event.currentTarget);
            button.button('loading');

            //And its message
            var editor = nicEditors.findEditor($scope.textAreaId);
            var message = editor.getContent();

            //Send the message
            $scope.sendComment(message, button);
        };

        /**
         * Method called to send a comment
         * @param message Message to send
         * @param button Button used to send the message (ui)
         */
        $scope.sendComment = function(message, button) {
            //JSON data to send
            var jsonData = {
                share_id: $scope.shareId,
                message: encodeURI(message)
            };

            //Check message length
            if (message.length >= 3) {
                //
                $http.put(webroot + 'api/comment/add', JSON.stringify(jsonData))
                .success(function(data, status, headers, config) {
                    //Add one comment
                    $scope.share.comment_count++;

                    //Reset content
                    var editor = nicEditors.findEditor($scope.textAreaId);
                    editor.setContent('');

                    $scope.showPage(1, function(success) {
                        button.button('reset');
                    });
                })
                .error(function(data, status, headers, config) {
                    //Reset button state
                    button.button('reset');
                });
            } else {
                //Reset button state
                button.button('reset');

                //Empty message
                toastr.warning('Veuillez saisir un message d\'au moins <?php echo SHARE_COMMENT_MESSAGE_MIN_LENGTH; ?> caractères.', 'Attention');
            }
        };

        /**
         * Method called to create the google map
         */
        $scope.createGoogleMap = function() {
            //Create map
            var sharePosition = new google.maps.LatLng($scope.share.latitude, $scope.share.longitude);
            var mapOptions = {
                panControl: false,
                zoomControl: true,
                scaleControl: false,
                streetViewControl: false,
                scrollwheel: false,
                zoom: 17,
                center: sharePosition
            };
            map = new google.maps.Map(document.getElementById($scope.divGoogleMapId), mapOptions);

            //Create the share marker
            var marker = new google.maps.Marker({
                position: sharePosition,
                map: map,
                title: $scope.share.title,
                icon: '../../img/markers/40/marker-' + $scope.share.share_type_category.label + '-' + $scope.share.share_type.label + '.png'
            });
        };

        /**
         * Method used to initialize the DetailsController
         */
        $scope.initialize = function() {
            //Get share details
            $scope.getShareDetails($scope.shareId);

            //Show comments page 1
            $scope.showPage(1);

            //Get user information
            $scope.getUserDetails($scope.shareUserExternalId);
        }

        //Initialize the controller
        $scope.initialize();
    }]);
}