app.controller('ProjectSectionController', function($scope, $filter, ngTableParams, $http, $route, $routeParams, $location) {

    $scope.data = [];

    // Initialise the data table
    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,          // count per page
        sorting: {
            created_at: 'desc'     // initial sorting
        },
    }, {
        filterDelay: 10,
        total: $scope.data.length, // length of data
        getData: function($defer, params) {
            // use build-in angular filter
            var filteredData = params.filter() ?
                    $filter('filter')($scope.data, params.filter()) :
                    $scope.data;
            var orderedData = params.sorting() ?
                    $filter('orderBy')(filteredData, params.orderBy()) :
                    $scope.data;

            params.total(orderedData.length); // set total for recalc pagination
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
        }
    });

    $scope.init = function() {

        console.log($routeParams.project_id);

        $http.get('/admin/api/project/' + $routeParams.project_id + '/section').
        success(function(data, status, headers, config) {
            $scope.data = data;

            // Parse ID from text to integer
            angular.forEach(data, function (data) {
                data.id  = parseFloat(data.id);
            });

            $scope.tableParams.reload();

        }).
        error(function(data, status, headers, config) {
            console.log(status);
            if (status == 401) {
                $scope.errors = [{"Logged out" : "You have been logged out. Refresh the page to log back in again"}];
            } else {
                $scope.errors = data;
            }

        });

    };


    $scope.delete = function(id) {

        $http.delete('/admin/api/project/' + id).
          success(function(data, status, headers, config) {
                $scope.init();
          }).
          error(function(data, status, headers, config) {
                $scope.errors = data;
          });
    }

    $scope.init();

});



app.controller('SectionCreateController', function($scope, $http, $route, $routeParams, $location) {

    $scope.data = {};

    $scope.save = function() {

        $http.post('/admin/api/project', $scope.data).
            success(function(data, status, headers, config) {
                $location.path( "/project" );
            }).
            error(function(data, status, headers, config) {
                if (status == 401) {
                    $scope.errors = [{"Logged out" : "You have been logged out. Refresh the page to log back in again"}];
                } else {
                    $scope.errors = data;
                }
            });
    }

});


app.controller('SectionEditController', function($scope, $http, $route, $routeParams, $location) {

    $scope.data = {};

    $http.get('/admin/api/project/' + $routeParams.id + '/edit').
        success(function(data, status, headers, config) {
            $scope.data = data;
        }).
        error(function(data, status, headers, config) {
            if (status == 401) {
                $scope.errors = [{"Logged out" : "You have been logged out. Refresh the page to log back in again"}];
            } else {
                $scope.errors = data;
            }
        });


    $scope.addSection = function() {

        $scope.data.sections.push({});

    };

    $scope.deleteSection = function(index) {

        $scope.data.sections.splice(index, 1);

    };


    $scope.save = function() {

        $http.put('/admin/api/project/' + $routeParams.id, $scope.data).
            success(function(data, status, headers, config) {
                $location.path( "/project" );
            }).
            error(function(data, status, headers, config) {
                if (status == 401) {
                    $scope.errors = [{"Logged out" : "You have been logged out. Refresh the page to log back in again"}];
                } else {
                    $scope.errors = data;
                }
            });
    }

});