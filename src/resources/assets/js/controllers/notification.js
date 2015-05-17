app.controller('NotificationController', function($scope, notificationService, $timeout) {

    $scope.notifier = notificationService;


    $scope.$watch('notifier.get()', function (notifications) {
        if (angular.isDefined(notifications)) {
            $scope.notifications = notifications;
        }
    }, true);


    $scope.closeNotification = function(index) {
        notificationService.removeByIndex(index);
    };


});
