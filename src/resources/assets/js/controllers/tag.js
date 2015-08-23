/*  States
    -------------------------------------------------------------------------------------------------
    Angular routes (states) are defined here. Each State is usually assigned a controller and a view
*/

app.config(function($stateProvider, $urlRouterProvider) {

    // Now set up the states
    $stateProvider
        .state('tag', {
          url: "/tag",
          templateUrl: "/vendor/portfolio/admin/views/tag/tag.html",
        })
        .state('tag.index', {
          url: "/index",
          templateUrl: "/vendor/portfolio/admin/views/tag/tag.index.html",
          controller: "TagIndexController"
        })
        .state('tag.create', {
          url: "/create",
          templateUrl: "/vendor/portfolio/admin/views/tag/tag.edit.html",
          controller: "TagCreateController"
        })
        .state('tag.edit', {
          url: "/:id/edit",
          templateUrl: "/vendor/portfolio/admin/views/tag/tag.edit.html",
          controller: "TagEditController"
        })

})


/*  State Controllers
    -------------------------------------------------------------------------------------------------
    Angular routes (states) are defined here. Each State is usually assigned a controller and a view
*/


app.controller('TagIndexController', function($scope, $filter, ngTableParams, $http, notificationService) {

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

        $http.get('/api/tag')
            .success(function(data) {
                $scope.data = data;
                $scope.tableParams.reload();
            })

    };


    $scope.delete = function(id, title) {

        if (confirm('Are you sure you wish to delete ' + title + '?')) {

            $http.delete('/api/tag/' + id)
                .success(function(data) {
                    notificationService.add("Project '" + data.title + "' deleted successfully", 'info');
                    $scope.init();
                })
                .error(function(data) {
                    $scope.errors = data;
                });
        }
    }

    $scope.init();


});


app.controller('TagCreateController', function($scope, $http, $stateParams, $state, notificationService) {

    $scope.data = {};

    $scope.save = function(apply) {

        $http.post('/api/tag', $scope.data)
            .success(function(data) {
                notificationService.add("Tag '" + data.title + "' added successfully", 'success');
                $scope.errors = [];
                if (!apply) {
                    $state.go( "tag.index" );
                } else {
	                $state.go( "tag.edit", {id: data.id});
                }
            })
            .error(function(data) {
                $scope.errors = data;
            });
    }

});


app.controller('TagEditController', function($scope, $http, $stateParams, $state, notificationService, ngTableParams, $filter, $modal) {

    $scope.data = {
        projects: []
    };


    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,          // count per page
        sorting: {
            updated_at: 'desc'     // initial sorting
        },
    }, {
        filterDelay: 10,
        total: $scope.data.projects.length, // length of data
        getData: function($defer, params) {
            // use build-in angular filter
            var filteredData = params.filter() ?
                $filter('filter')($scope.data.projects, params.filter()) :
                $scope.data.projects;
            var orderedData = params.sorting() ?
                $filter('orderBy')(filteredData, params.orderBy()) :
                $scope.data.projects;

            params.total(orderedData.length); // set total for recalc pagination
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
        }
    });


    $http.get('/api/tag/' + $stateParams.id)
        .success(function(data) {
            $scope.data = data;
            $scope.tableParams.reload();
        })
        .error(function(data) {
            $scope.errors = data;
        });


    $scope.save = function(apply) {
        apply = typeof apply !== 'undefined' ? apply : false;

        $http.put('/api/tag/' + $stateParams.id, $scope.data)
            .success(function(data) {
                notificationService.add("Tag '" + data.title + "' updated successfully", 'success');
                $scope.errors = [];
                if (!apply) {
                    $state.go( "tag.index" );
                }
            })
            .error(function(data){
                $scope.errors = data;
            });
    };

});
