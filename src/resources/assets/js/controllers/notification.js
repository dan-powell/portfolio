app.controller('NotificationController', function($scope, notificationService, $timeout) {

    $scope.notifier = notificationService;


    $scope.$watch('notifier.get()', function (notifications) {
        if (angular.isDefined(notifications)) {
            $scope.notifications = notifications;
        }
    }, true);


    $scope.remove = function(element) {
        notificationService.removeByElement(element);
    };


});
