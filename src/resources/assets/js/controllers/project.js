app.controller('ProjectController', function($scope, $filter, ngTableParams, $http, $route, $routeParams, $location) {

    $scope.data = {};
    var initialised = false;

    $scope.initTable = function(data) {

        // Setup the table
        $scope.tableParams = new ngTableParams({
            page: 1,            // show first page
            count: 10,          // count per page
            sorting: {
                created_at: 'desc'     // initial sorting
            },
        }, {
            filterDelay: 10,
            total: data.length, // length of data
            getData: function($defer, params) {
                // use build-in angular filter
                var filteredData = params.filter() ?
                        $filter('filter')(data, params.filter()) :
                        data;
                var orderedData = params.sorting() ?
                        $filter('orderBy')(filteredData, params.orderBy()) :
                        data;

                params.total(orderedData.length); // set total for recalc pagination
                $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });
    };


    $scope.getData = function() {

        $http.get('/admin/api/project').
              success(function(data, status, headers, config) {
                    $scope.data = data;
                    console.log($scope.data);

                    // Parse ID from text to integer
                    angular.forEach(data, function (data) {
                        data.id  = parseFloat(data.id);
                    });

                    if (!initialised) {
                        $scope.initTable(data);
                    } else {
                        $scope.tableParams.reload();
                    }

              }).
              error(function(data, status, headers, config) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
              });

    };


    $scope.delete = function(id) {

        $http.delete('/admin/api/project/' + id).
          success(function(data, status, headers, config) {
                console.log(data);
                $scope.getData();
          }).
          error(function(data, status, headers, config) {
                $scope.errors = data;
          });

    }


    $scope.getData();


});





app.controller('ProjectCreateController', function($scope, $http, $route, $routeParams, $location) {

    $scope.errors = [];
    $scope.data = {};

    $scope.save = function() {
        console.log('Saving');

        console.log($scope.data);

        $http.post('/admin/api/project', $scope.data).
          success(function(data, status, headers, config) {
                console.log(data);
                $location.path( "/project" );
          }).
          error(function(data, status, headers, config) {
                $scope.errors = data;
          });

    }


});


app.controller('ProjectEditController', function($scope, $http, $route, $routeParams, $location) {

    $scope.errors = [];
    $scope.data = {};

    $http.get('/admin/api/project/' + $routeParams.id + '/edit').
          success(function(data, status, headers, config) {
                $scope.data = data;
                console.log($scope.data);
            // this callback will be called asynchronously
            // when the response is available
          }).
          error(function(data, status, headers, config) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
          });


    $scope.save = function() {
        console.log('Saving');

        console.log($scope.data);

        $http.put('/admin/api/project/' + $routeParams.id, $scope.data).
          success(function(data, status, headers, config) {
                console.log(data);
                $location.path( "/project" );
          }).
          error(function(data, status, headers, config) {
                $scope.errors = data;
          });


    }


});