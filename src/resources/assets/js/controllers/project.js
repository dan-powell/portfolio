app.controller('ProjectController', function($scope, $filter, ngTableParams, $http, RestfulApi) {

    // Initialise an empty array to hold data
    $scope.data = [];

    // Initialise the data table
    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,          // count per page
        sorting: {
            updated_at: 'desc'     // initial sorting
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

    // Initialise (load) data
    $scope.init = function() {

        //console.log(RestfulApi.getRoute('project', 'update', 4));

        $http.get(RestfulApi.getRoute('project', 'index')).
        success(function(data, status, headers, config) {

            RestfulApi.success(data, status, headers, config);

            $scope.data = data;

            $scope.tableParams.reload();

        }).
        error(function(data, status, headers, config) {
            RestfulApi.error(data, status, headers, config);
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





app.controller('ProjectCreateController', function($scope, $http, $stateParams, $location, RestfulApi) {

    $scope.data = {};

    $scope.save = function() {

        $http.post(RestfulApi.getRoute('project', 'create'), $scope.data).
            success(function(data, status, headers, config) {
                $location.path( "/project" );
            }).
            error(function(data, status, headers, config) {
                RestfulApi.error(data, status, headers, config);
            });
    }

});


app.controller('ProjectEditController', function($scope, $http, $stateParams, $location, RestfulApi) {

    $scope.data = {};

    $http.get(RestfulApi.getRoute('project', 'show', $stateParams.id)).
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

        $http.put(RestfulApi.getRoute('project', 'update', $stateParams.id), $scope.data).
            success(function(data, status, headers, config) {
                $location.path( "/project/index" );
            }).
            error(function(data, status, headers, config) {
                $scope.errors = RestfulApi.error(data, status, headers, config);
            });
    }

});