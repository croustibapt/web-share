/**
 * Created by bleguelvouit on 10/06/15.
 */

function initializeDetails(shareId, shareUserExternalId, textAreaId, divGoogleMapId) {
    //Create DetailsController
    app.controller('DetailsController', ['$scope', '$http', function($scope, $http) {
        //Pagination
        $scope.page = 1;
        $scope.total_pages = 1;
        $scope.total_results = 0;
        $scope.results_count = 0;

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
         */
        $scope.showPage = function(page) {
            $scope.page = page;

            //Get call
            $http.get(webroot + 'api/comment/get?shareId=' + $scope.shareId + '&page=' + $scope.page)
            .success(function (data, status, headers, config) {
                //Results
                $scope.handleCommentsResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };

        /**
         * Method used to handle the comments Ajax response
         * @param response
         */
        $scope.handleCommentsResponse = function(response) {
            console.log(response);

            //Handle pagination
            $scope.page = parseInt(response.page);
            $scope.total_pages = parseInt(response.total_pages);
            $scope.total_results = parseInt(response.total_results);
            $scope.results_count = response.results.length;

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

        //
        $scope.getUser = function(userExternalId) {
            //Get call
            $http.get(webroot + 'api/user/details/' + userExternalId)
            .success(function (data, status, headers, config) {
                //Results
                $scope.handleUserResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };

        //
        $scope.handleUserResponse = function(response) {
            console.log(response);

            $scope.user = response;

            //Created
            var createdDate = new Date($scope.user.created);
            var isoCreatedDate = createdDate.toISOString();
            var momentCreated = moment(isoCreatedDate).format('D MMMM YYYY', 'fr');
            $scope.user.moment_created = momentCreated;
        };

        //
        $scope.getShare = function(shareId) {
            //Get call
            $http.get(webroot + 'api/share/details/' + shareId)
            .success(function (data, status, headers, config) {
                //Results
                $scope.handleShareResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };

        $scope.createMap = function() {
            //Create map
            var myLatlng = new google.maps.LatLng($scope.share.latitude, $scope.share.longitude);
            var mapOptions = {
                panControl: false,
                zoomControl: true,
                scaleControl: false,
                streetViewControl: false,
                scrollwheel: false,
                zoom: 17,
                center: myLatlng
            };
            map = new google.maps.Map(document.getElementById($scope.divGoogleMapId), mapOptions);

            var marker = new MarkerWithLabel({
                position: myLatlng,
                map: map,
                title: $scope.share.title,
                labelContent: '<div class="img-circle text-center" style="border: 4px solid white; background-color: ' + $scope.share.share_color + '; display: table; min-width: 40px; width: 40px; min-height: 40px; height: 40px;"><i class="' + $scope.share.share_icon + '" style="display: table-cell; vertical-align: middle; color: #ffffff; font-size: 18px;"></i></div>',
                labelAnchor: new google.maps.Point(16, 16),
                icon: ' '
            });
        };

        //
        $scope.handleShareResponse = function(response) {
            console.log(response);

            var share = response;

            //Share type category label
            var shareTypeCategoryLabel = getShareTypeCategoryLabel(share.share_type_category.label);
            share.share_type_category_label = shareTypeCategoryLabel;

            //Share type label
            var shareTypeLabel = getShareTypeLabel(share.share_type_category.label, share.share_type.label);
            share.share_type_label = shareTypeLabel;

            //Event date
            var eventDate = new Date(share.event_date);
            var isoEventDate = eventDate.toISOString();
            var momentDay = moment(isoEventDate).format('D MMMM', 'fr');
            share.moment_day = momentDay;

            //Event time
            if (share.event_time != null) {
                var eventTime = new Date(share.event_date + ' ' + share.event_time);
                var isoEventTime = eventTime.toISOString();
                var momentHour = moment(isoEventTime).format('LT', 'fr');
                share.moment_hour = momentHour;
            }

            //Share color
            var shareColor = getIconColor(share.share_type_category.label);
            share.share_color = shareColor;

            //Share icon
            var shareIcon = getMarkerIcon(share.share_type_category.label, share.share_type.label);
            share.share_icon = shareIcon;

            //Created
            var createdDate = new Date(share.created);
            var isoCreatedDate = createdDate.toISOString();
            var momentCreatedTimeAgo = moment(isoCreatedDate).fromNow();
            share.moment_created_time_ago = momentCreatedTimeAgo;

            //Places left
            var totalPlaces = parseInt(share.places) + 1;
            var participationCount = parseInt(share.participation_count) + 1;
            var placesLeft = totalPlaces - participationCount;
            share.places_left = placesLeft;

            //Formatted price
            var price = parseFloat(share.price);
            share.formatted_price = numeral(price).format('0.0a');

            $scope.share = share;

            //Create map
            $scope.createMap();
        };

        $scope.onSendButtonClicked = function($event) {
            var button = angular.element($event.currentTarget);
            button.button('loading');

            //And its message
            var editor = nicEditors.findEditor($scope.textAreaId);
            var message = editor.getContent();

            $scope.sendComment(message, button);
        };

        $scope.sendComment = function(message, button) {
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

                    $scope.showPage(1);

                    button.button('reset');
                })
                .error(function(data, status, headers, config) {
                    console.log(data);
                    button.button('reset');
                });
            } else {
                //Empty message
                toastr.warning('Veuillez saisir un message d\'au moins <?php echo SHARE_COMMENT_MESSAGE_MIN_LENGTH; ?> caractères.', 'Attention');
            }
        };

        /**
         *
         */
        $scope.initialize = function() {
            //Get share
            $scope.getShare($scope.shareId);

            //Show comments page 1
            $scope.showPage(1);

            //Get user information
            $scope.getUser($scope.shareUserExternalId);
        }

        $scope.initialize();
    }]);
}