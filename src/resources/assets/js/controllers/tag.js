app.controller('TagController', function($scope, $filter, ngTableParams, $http, RestfulApi, notificationService) {

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

        $http.get(RestfulApi.getRoute('tag', 'index')).
        success(function(data, status, headers, config) {
            RestfulApi.success(data, status, headers, config);
            $scope.data = data;
            $scope.tableParams.reload();
        }).
        error(function(data, status, headers, config) {
            RestfulApi.error(data, status, headers, config);
        });

    };


    $scope.delete = function(id, title) {

        if (confirm('Are you sure you wish to delete ' + title + '?')) {

            $http.delete(RestfulApi.getRoute('tag', 'destroy', id)).
                success(function(data, status, headers, config) {
                    notificationService.add("Project '" + data.title + "' deleted successfully", 'info');
                    $scope.init();
                }).
                error(function(data, status, headers, config) {
                    RestfulApi.error(data, status, headers, config);
                    $scope.errors = data;
                });
        }
    }

    $scope.init();


});


app.controller('TagCreateController', function($scope, $http, $stateParams, $state, RestfulApi, notificationService) {

    $scope.data = {};

    $scope.create = true;

    $scope.save = function(apply) {

        $http.post(RestfulApi.getRoute('tag', 'store'), $scope.data).
            success(function(data, status, headers, config) {
                RestfulApi.success(data, status, headers, config);
                notificationService.add("Tag '" + data.title + "' added successfully", 'success');
                $scope.errors = [];
                if (!apply) {
                    $state.go( "tag.index" );
                } else {
	                $state.go( "tag.edit", {id: data.id});
                }
            }).
            error(function(data, status, headers, config) {
                RestfulApi.error(data, status, headers, config);
                $scope.errors = data;
            });
    }

});


app.controller('TagEditController', function($scope, $http, $stateParams, $state, RestfulApi, notificationService, ngTableParams, $filter, $modal) {

    $scope.data = {};

    $http.get(RestfulApi.getRoute('tag', 'show', $stateParams.id)).
        success(function(data, status, headers, config) {
            RestfulApi.success(data, status, headers, config);
            $scope.data = data;
        }).
        error(function(data, status, headers, config) {
            RestfulApi.error(data, status, headers, config);
            $scope.errors = data;
        });

    $scope.save = function(apply) {
        apply = typeof apply !== 'undefined' ? apply : false;
        $scope.put(apply);
    }

    $scope.put = function(apply) {
        $http.put(RestfulApi.getRoute('tag', 'update', $stateParams.id), $scope.data).
        success(function(data, status, headers, config) {
            RestfulApi.success(data, status, headers, config);
            notificationService.add("Tag '" + data.title + "' updated successfully", 'success');
            $scope.errors = [];
            if (!apply) {
                $state.go( "tag.index" );
            }
        }).
        error(function(data, status, headers, config) {
            RestfulApi.error(data, status, headers, config);
            $scope.errors = data;
        });
    }


});
