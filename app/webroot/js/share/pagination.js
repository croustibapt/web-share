/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Create pagination controller
app.controller('PaginationController', ['$scope', function($scope) {
    $scope.page = 0;
    $scope.total_pages = 0;

    $scope.getNumber = function(num) {
        return new Array(num);
    };

    $scope.handleResponse = function(response) {
        //Create pagination
        $scope.page = parseInt(response.page);
        $scope.total_pages = parseInt(response.total_pages);
    };
}]);

function paginationHandleResponse(response) {
    var searchPaginationDiv = $('#div-search-pagination');
    var paginationScope = angular.element(searchPaginationDiv).scope();

    paginationScope.$apply(function(){
        paginationScope.handleResponse(response);
    });
}
