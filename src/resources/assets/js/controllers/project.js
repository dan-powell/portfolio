app.controller('ProjectController', function($scope, $filter, ngTableParams, $http, RestfulApi, notificationService) {

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


    $scope.delete = function(id, title) {

        if (confirm('Are you sure you wish to delete ' + title + '?')) {

            $http.delete('/admin/api/project/' + id).
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



app.controller('ProjectCreateController', function($scope, $http, $stateParams, $state, RestfulApi, notificationService) {

    $scope.data = {};

    $scope.save = function(apply) {

        $http.post(RestfulApi.getRoute('project', 'store'), $scope.data).
            success(function(data, status, headers, config) {
                RestfulApi.success(data, status, headers, config);
                notificationService.add("Project '" + data.title + "' added successfully", 'success');
                $scope.errors = [];
                if (!apply) {
                    $state.go( "project.index" );
                } else {
	                $state.go( "project.edit", {id: data.id});
                }
            }).
            error(function(data, status, headers, config) {
                RestfulApi.error(data, status, headers, config);
                $scope.errors = data;
            });
    }

});


app.controller('ProjectEditController', function($scope, $http, $stateParams, $state, RestfulApi, notificationService, ngTableParams, $filter, $modal) {

    $scope.data = {
        sections : []
    };


    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,          // count per page
        sorting: {
            updated_at: 'desc'     // initial sorting
        },
    }, {
        filterDelay: 10,
        total: $scope.data.sections.length, // length of data
        getData: function($defer, params) {
            // use build-in angular filter
            var filteredData = params.filter() ?
                    $filter('filter')($scope.data.sections, params.filter()) :
                    $scope.data.sections;
            var orderedData = params.sorting() ?
                    $filter('orderBy')(filteredData, params.orderBy()) :
                    $scope.data.sections;

            params.total(orderedData.length); // set total for recalc pagination
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
        }
    });




    $http.get(RestfulApi.getRoute('project', 'show', $stateParams.id)).
        success(function(data, status, headers, config) {
            RestfulApi.success(data, status, headers, config);
            $scope.data = data;
            $scope.slug = $scope.data.slug;
            $scope.tableParams.reload();
        }).
        error(function(data, status, headers, config) {
            RestfulApi.error(data, status, headers, config);
            $scope.errors = data;
        });


/*
    $scope.addSection = function() {

        $scope.data.sections.push({});

    };

    $scope.deleteSection = function(index) {

        $scope.data.sections.splice(index, 1);

    };
*/


$scope.editSection = function (sectionIndex) {

    var modalInstance = $modal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'sectionEdit.html',
      controller: 'editSectionController',
      size: 'lg',
      resolve: {
        section: function () {
          return $scope.data.sections[sectionIndex];
        }
      }
    });

/*
    modalInstance.result.then(function () {
      $scope.selected = selectedItem;
    }, function () {
      //$log.info('Modal dismissed at: ' + new Date());
    });
*/
  };

  $scope.toggleAnimation = function () {
    $scope.animationsEnabled = !$scope.animationsEnabled;
  };




    $scope.slugWarning = function() {
        notificationService.removeByType('warning');
        if ($scope.slug != $scope.data.slug) {
            notificationService.add("You have modified the project slug. Please be aware that this may break hyperlinks to this project.", 'warning');
        }
    }


    $scope.save = function(apply) {
        apply = typeof apply !== 'undefined' ? apply : false;

        if ($scope.slug != $scope.data.slug) {
            if (confirm('Are you sure you wish to change the slug?')) {
                $scope.put(apply);
            } else {
                $scope.data.slug = $scope.slug;
                notificationService.removeByType('warning');
                notificationService.add("Slug reset", 'info');
            }
        } else {
            $scope.put(apply);
        }

    }

    $scope.put = function(apply) {
            $http.put(RestfulApi.getRoute('project', 'update', $stateParams.id), $scope.data).
            success(function(data, status, headers, config) {
                RestfulApi.success(data, status, headers, config);
                notificationService.add("Project '" + data.title + "' updated successfully", 'success');
                $scope.errors = [];
                if (!apply) {
                    $state.go( "project.index" );
                }
            }).
            error(function(data, status, headers, config) {
                RestfulApi.error(data, status, headers, config);
                $scope.errors = data;
            });
    }


});





app.controller('editSectionController', function ($scope, $http, $modalInstance, RestfulApi, notificationService, section) {

    $scope.section = section;

    $scope.save = function(apply) {

        $http.put(RestfulApi.getRoute('section', 'update', $scope.section.id), $scope.section)
            .success(function(data, status, headers, config) {
                RestfulApi.success(data, status, headers, config);
                notificationService.add("Section '" + data.title + "' updated successfully", 'success');
                $scope.errors = [];
                if (!apply) {
                    $modalInstance.close();
                }
            })
            .error(function(data, status, headers, config) {
                RestfulApi.error(data, status, headers, config);
                $scope.errors = data;
            });

    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
});
