/**
 * Created by bleguelvouit on 10/06/15.
 */

//Create DetailsController
app.controller('DetailsController', ['$scope', function($scope) {
    $scope.comments = [];
    $scope.shareUserExternalId = -1;

    //Method used to handle the Ajax response
    $scope.handleResponse = function(response) {
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

            $scope.comments.push(comment);
        }
    };
}]);

function loadComments(shareId, page) {
    $.ajax({
        url: webroot + 'api/comment/get?shareId=' + shareId + '&page=' + page,
        method: 'GET',
        dataType: 'json'
    })
    .done(function(response) {
        console.log(response);

        //Results
        detailsHandleResponse(response);

        //Pagination
        paginationHandleResponse(response);
    })
    .fail(function(jqXHR, textStatus) {
        console.log(jqXHR);
    });
}

function detailsHandleResponse(response) {
    var detailsResultsDiv = $('#div-share-details-comments');
    var detailsScope = angular.element(detailsResultsDiv).scope();

    detailsScope.$apply(function(){
        detailsScope.handleResponse(response);
    });
}
