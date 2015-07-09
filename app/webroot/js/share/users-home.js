function initializeUsersHome() {
    /**
     * DetailsController
     */
    app.controller('UsersHomeController', ['$scope', '$http', function($scope, $http) {
        //User
        $scope.user = null;

        $scope.getUserHome = function() {
            //Get call
            $http.get(webroot + 'api/user/home')
            .success(function (data, status, headers, config) {
                //Parse JSON response
                $scope.handleUserHomeResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };

        $scope.handleUserHomeResponse = function(response) {
            console.log(response);
            $scope.user = response;

            formatUser($scope.user);

            for (var shareIndex in $scope.user.shares) {
                var share = $scope.user.shares[shareIndex];
                formatShare(share);
            }

            for (var requestIndex in $scope.user.requests) {
                var request = $scope.user.requests[requestIndex];
                formatShare(request.share);
            }
        };

        $scope.updateRequestStatus = function(shareId, requestId, status) {
            for (var shareIndex in $scope.user.shares) {
                var share = $scope.user.shares[shareIndex];

                if (share.share_id == shareId) {
                    for (var requestIndex in share.requests) {
                        var request = share.requests[requestIndex];

                        if (request.request_id == requestId) {
                            console.log(request);
                            request.status = status;

                            break;
                        }
                    }

                    break;
                }
            }
        };

        $scope.updateOwnRequestStatus = function(requestId, status) {
            for (var requestIndex in $scope.user.requests) {
                var request = $scope.user.requests[requestIndex];

                if (request.request_id == requestId) {
                    console.log(request);
                    request.status = status;

                    break;
                }
            }
        };

        $scope.acceptRequest = function(shareId, requestId, $event) {
            var button = angular.element($event.currentTarget);
            button.button('loading');

            $http.get(webroot + 'api/request/accept/' + requestId)
            .success(function (data, status, headers, config) {
                //Update request status
                $scope.updateRequestStatus(shareId, requestId, 1);

                //Reset button state
                button.button('reset');
            })
            .error(function (data, status, headers, config) {
                //Reset button state
                button.button('reset');

                //Empty message
                toastr.error(data, 'Erreur');
            });
        };

        $scope.declineRequest = function(shareId, requestId, $event) {
            var button = angular.element($event.currentTarget);
            console.log(button);
            button.button('loading');

            if (confirm('Are you sure you want to decline this request?')) {
                $http.get(webroot + 'api/request/decline/' + requestId)
                .success(function (data, status, headers, config) {
                    //Update request status
                    $scope.updateRequestStatus(shareId, requestId, 2);

                    //Reset button state
                    button.button('reset');
                })
                .error(function (data, status, headers, config) {
                    //Reset button state
                    button.button('reset');

                    //Empty message
                    toastr.error(data, 'Erreur');
                });
            } else {
                //Reset button state
                button.button('reset');
            }
        };

        $scope.cancelRequest = function(shareId, requestId, $event) {
            var button = angular.element($event.currentTarget);
            button.button('loading');

            if (confirm('Are you sure you want to cancel this request?')) {
                $http.get(webroot + 'api/request/cancel/' + requestId)
                .success(function (data, status, headers, config) {
                    //Update request status
                    $scope.updateRequestStatus(shareId, requestId, 3);

                    //Reset button state
                    button.button('reset');
                })
                .error(function (data, status, headers, config) {
                    //Reset button state
                    button.button('reset');

                    //Empty message
                    toastr.error(data, 'Erreur');
                });
            } else {
                //Reset button state
                button.button('reset');
            }
        };

        $scope.cancelOwnRequest = function(requestId, $event) {
            var button = angular.element($event.currentTarget);
            button.button('loading');

            if (confirm('Are you sure you want to cancel your request?')) {
                $http.get(webroot + 'api/request/cancel/' + requestId)
                .success(function (data, status, headers, config) {
                    //Update request status
                    $scope.updateOwnRequestStatus(requestId, 3);

                    //Reset button state
                    button.button('reset');
                })
                .error(function (data, status, headers, config) {
                    //Reset button state
                    button.button('reset');

                    //Empty message
                    toastr.error(data, 'Erreur');
                });
            } else {
                //Reset button state
                button.button('reset');
            }
        };

        /**
         * Method used to initialize the HomeController
         */
        $scope.initialize = function() {
            //Get user home
            $scope.getUserHome();
        };

        //Initialize the controller
        $scope.initialize();
    }]);
}