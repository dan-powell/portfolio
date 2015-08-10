app.controller('AssetsController', function($scope, $http, $stateParams, $state, notificationService, $modal) {

    // Initialise an empty array to hold data
    $scope.model = [];

    // Initialise (load) data
    $scope.init = function() {

        $http.get('/api/assets?folder=' + $stateParams.type + '/' + $stateParams.id)
            .success(function(data) {
                $scope.model = data;
            });

    };

    $scope.init();
});




app.controller('AssetsFileController', function($scope, $http, $stateParams, $state, notificationService, $modal, Upload) {

    // Initialise an empty array to hold data
    $scope.model = [];

    // Initialise (load) data
    $scope.init = function() {


        $http.get('/api/assets?folder=' + unescape($stateParams.folder))
            .success(function(data) {
                $scope.model = data;
            });


        $scope.dynamic = 0;

    };


    $scope.uploadFile = function() {

        $scope.dynamic = 0;

        console.log($stateParams.folder);

        Upload.upload({
            url: '/api/assets',
            fields: {'path': unescape($stateParams.folder)},
            file: $scope.file
        }).progress(function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            $scope.dynamic = progressPercentage;
            console.log('progress: ' + progressPercentage + '% ' + evt.config.file.name);
        }).success(function (data, status, headers, config) {
            console.log('file ' + config.file.name + 'uploaded. Response: ' + data);
        }).error(function (data, status, headers, config) {
            console.log('error status: ' + status);
        });

    };


    $scope.init();
});