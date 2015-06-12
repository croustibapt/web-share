/**
 * Created by bleguelvouit on 10/06/15.
 */

function initializeDetails(shareId, textAreaId) {
    //Create DetailsController
    app.controller('DetailsController', ['$scope', '$http', function($scope, $http) {
        $scope.page = 1;
        $scope.total_pages = 1;

        $scope.shareId = shareId;
        $scope.shareUserExternalId = -1;

        $scope.textAreaId = textAreaId;
        $scope.message = null;

        $scope.comments = [];

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
                $scope.handleResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };

        /**
         * Method used to handle the Ajax response
         * @param response
         */
        $scope.handleResponse = function(response) {
            //Handle pagination
            $scope.page = parseInt(response.page);
            $scope.total_pages = parseInt(response.total_pages);

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

        $scope.onSendButtonClicked = function($event) {
            var button = angular.element($event.target);
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
            $scope.showPage(1);
        }

        $scope.initialize();
    }]);
}