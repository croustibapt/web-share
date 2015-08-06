function initializeUsersAccount(userExternalId) {
    /**
     * UsersAccountController
     */
    app.controller('UsersAccountController', ['$scope', '$http', function($scope, $http) {
        //User
        $scope.user = null;

        $scope.shares = null;
        $scope.user_shares_page = null;
        $scope.user_shares_total_pages = 0;
        $scope.user_shares_total_results = 0;
        $scope.user_shares_results_count = 0;
        $scope.user_shares_results_by_page = 10;

        $scope.requests = null;

        $scope.getNumberArray = function(number) {
            return new Array(number);
        };

        $scope.getUserAccount = function(userExternalId) {
            //Get user/details call
            $http.get(webroot + 'user/details/' + userExternalId)
            .success(function (data, status, headers, config) {
                //Parse JSON response
                $scope.handleUserDetailsResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });

            //Get user/shares call
            $http.get(webroot + 'user/shares?page=1&limit=1')
            .success(function (data, status, headers, config) {
                //Parse JSON response
                $scope.handleUserSharesResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });

            //Get user/requests call
            $http.get(webroot + 'user/requests')
            .success(function (data, status, headers, config) {
                //Parse JSON response
                $scope.handleUserRequestsResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };

        $scope.handleUserDetailsResponse = function(response) {
            console.log(response);
            $scope.user = response;

            formatUser($scope.user);
        }

        $scope.handleUserSharesResponse = function(response) {
            console.log(response);

            //Handle pagination
            $scope.user_shares_total_pages = parseInt(response.total_pages);
            $scope.user_shares_total_results = parseInt(response.total_results);
            $scope.user_shares_results_count = response.results.length;
            $scope.user_shares_page = parseInt(response.page);

            $scope.shares = response.results;

            for (var shareIndex in $scope.shares) {
                var share = $scope.shares[shareIndex];
                formatShare(share);
            }
        };

        $scope.handleUserRequestsResponse = function(response) {
            console.log(response);
            $scope.requests = response.results;

            for (var requestIndex in $scope.requests) {
                var request = $scope.requests[requestIndex];
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

            $http.get(webroot + 'request/accept/' + requestId)
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
                $http.get(webroot + 'request/decline/' + requestId)
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
                $http.get(webroot + 'request/cancel/' + requestId)
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
                $http.get(webroot + 'request/cancel/' + requestId)
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
        $scope.initialize = function(userExternalId) {
            //Get user account
            $scope.getUserAccount(userExternalId);
        };

        //Initialize the controller
        $scope.initialize(userExternalId);
    }]);
}