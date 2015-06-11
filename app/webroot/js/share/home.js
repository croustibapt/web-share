/**
 * Created by bleguelvouit on 11/06/15.
 */

//Create HomeController
app.controller('HomeController', ['$scope', '$http', function($scope, $http) {
    $scope.shareTypeCategories = {};
    $scope.shareTypeCategory = "-1";
    $scope.shareType = "-1";

    //
    $http.get(webroot + 'api/share_type_categories/get')
    .success(function (data, status, headers, config) {
        //
        getShareTypeCategories($scope, data);
    })
    .error(function (data, status, headers, config) {
        console.log(data);
    });
}]);
