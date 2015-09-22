function initializeUsersAccount(userExternalId) {    
    /**
     * UsersAccountController
     */
    app.controller('UsersAccountController', ['$scope', '$http', function($scope, $http) {
        //User
        $scope.user = null;
        $scope.user_external_id = userExternalId;
        
        $scope.user_data = 'shares';
        $scope.user_data_status = 'pending';

        $scope.shares = null;
        $scope.user_shares_page = null;
        $scope.user_shares_total_pages = 0;
        $scope.user_shares_total_results = 0;
        $scope.user_shares_results_count = 0;
        $scope.user_shares_results_by_page = 10;

        $scope.requests = null;
        $scope.user_requests_page = null;
        $scope.user_requests_total_pages = 0;
        $scope.user_requests_total_results = 0;
        $scope.user_requests_results_count = 0;
        $scope.user_requests_results_by_page = 10;
        
        $scope.now = moment().format('YYYY-MM-DD');
        
        $scope.user_shares_showPage = function(page) {
            //Update page
            $scope.user_shares_page = null;

            //Simply call the user/shares method
            $scope.getUserShares(page, $scope.user_shares_status);
        };
        
        $scope.user_requests_showPage = function(page) {
            //Update page
            $scope.user_requests_page = null;

            //Simply call the user/requests method
            $scope.getUserRequests(page, $scope.user_requests_status);
        };

        $scope.getNumberArray = function(number) {
            return new Array(number);
        };
        
        $scope.getUserShares = function(page, status) {
            var url = webroot + 'user/shares?page=' + page;
            if (status == 'finished') {                
                url += '&start=' + moment().subtract(1, 'month').unix(); //Last month
                url += '&end=' + moment().unix();
            } else {
                url += '&start=' + moment().unix();
            }
            console.log(url);

            //Get user/shares call
            $http.get(url)
            .success(function (data, status, headers, config) {
                console.log(data);
        
                //Parse JSON response
                $scope.handleUserSharesResponse(data);

                console.log($scope.shares.length);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };
        
        $scope.getUserRequests = function(page, status) {
            var url = webroot + 'user/requests?page=' + page;
            if (status == 'finished') {                
                url += '&start=' + moment().subtract(1, 'month').unix(); //Last month
                url += '&end=' + moment().unix();
            } else {
                url += '&start=' + moment().unix();
            }
            console.log(url);

            //Get user/requests call
            $http.get(url)
            .success(function (data, status, headers, config) {
                console.log(data);
                
                //Parse JSON response
                $scope.handleUserRequestsResponse(data);

                console.log($scope.requests.length);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });
        };

        $scope.getUserAccount = function() {            
            var url = webroot + 'user/details/' + $scope.user_external_id;
            console.log(url);
            
            //Get user/details call
            $http.get(url)
            .success(function (data, status, headers, config) {
                //Parse JSON response
                $scope.handleUserDetailsResponse(data);
            })
            .error(function (data, status, headers, config) {
                console.log(data);
            });

            //
            $scope.getUserShares(1);

            //
            $scope.getUserRequests(1);
        };

        $scope.handleUserDetailsResponse = function(response) {
            console.log(response);
            $scope.user = response;

            formatUser($scope.user);
        };

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

            //Handle pagination
            $scope.user_requests_total_pages = parseInt(response.total_pages);
            $scope.user_requests_total_results = parseInt(response.total_results);
            $scope.user_requests_results_count = response.results.length;
            $scope.user_requests_page = parseInt(response.page);

            $scope.requests = response.results;

            for (var requestIndex in $scope.requests) {
                var request = $scope.requests[requestIndex];
                formatShare(request.share);
            }
        };
        
        $scope.updateData = function() {
            //Update results
            if ($scope.user_data == 'shares') {
                $scope.getUserShares($scope.user_shares_page, $scope.user_data_status);
            } else {
                $scope.getUserRequests($scope.user_shares_page, $scope.user_data_status);
            }
        }

        $scope.onUserDataStatusChanged = function(status, $event) {
            if ($scope.user_data_status != status) {
                $scope.user_data_status = status;
                $scope.updateData();
            }            
        };
        
        $scope.onUserDataChanged = function(data, $event) {
            if ($scope.user_data != data) {
                $scope.user_data = data;
                $scope.updateData();
            }
        };

        $scope.updateRequestStatus = function(shareId, requestId, status) {
            for (var shareIndex in $scope.shares) {
                var share = $scope.shares[shareIndex];

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
            for (var requestIndex in $scope.requests) {
                var request = $scope.requests[requestIndex];

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
                handleAjaxError(data);
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
                    handleAjaxError(data);
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
                    handleAjaxError(data);
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
                    console.log(data);

                    //Update request status
                    $scope.updateOwnRequestStatus(requestId, 3);

                    //Reset button state
                    button.button('reset');
                })
                .error(function (data, status, headers, config) {
                    console.log(data);

                    //Reset button state
                    button.button('reset');

                    //Empty message
                    handleAjaxError(data);
                });
            } else {
                //Reset button state
                button.button('reset');
            }
        };

        $scope.evaluate = function(requestId, userExternalId, rating, $event) {
            var button = angular.element($event.currentTarget);
            button.button('loading');

            var message = prompt("Saisissez votre message :", "Super partage !");
            if (message != null) {
                console.log(message);
                console.log(userExternalId);
                
                $http.put(webroot + 'evaluation/add', {
                    request_id: requestId,
                    user_external_id: userExternalId,
                    rating: rating,
                    message: encodeURI(message)
                })
                .success(function(data, status, headers, config) {
                    console.log(data);
            
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
                    console.log(data);
            
                    //Reset button state
                    button.button('reset');
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