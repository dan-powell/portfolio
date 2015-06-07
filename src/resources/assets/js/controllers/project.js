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

        $http.get(RestfulApi.getRoute('project', 'index'))
            .success(function(data) {
                $scope.data = data;
                $scope.tableParams.reload();
            });

    };


    $scope.delete = function(id, title) {

        if (confirm('Are you sure you wish to delete ' + title + '?')) {

            $http.delete('/admin/api/project/' + id)
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



app.controller('ProjectCreateController', function($scope, $http, $stateParams, $state, RestfulApi, notificationService) {

    $scope.data = {};

    $scope.create = true;

    $scope.save = function(apply) {

        $http.post(RestfulApi.getRoute('project', 'store'), $scope.data)
            .success(function(data) {
                notificationService.add("Project '" + data.title + "' added successfully", 'success');
                $scope.errors = [];
                if (!apply) {
                    $state.go( "project.index" );
                } else {
	                $state.go( "project.edit", {id: data.id});
                }
            })
            .error(function(data) {
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


    $http.get(RestfulApi.getRoute('project', 'show', $stateParams.id))
        .success(function(data) {
            $scope.data = data;
            $scope.slug = $scope.data.slug;
            $scope.tableParams.reload();
        })
        .error(function(data, status, headers, config) {
            $scope.errors = data;
        });



    $scope.editSection = function (create, sectionId) {
        sectionId = typeof sectionId !== 'undefined' ? sectionId : false;

        modalData = {
            'create' : create,
            'projectId' : $scope.data.id,
            'sectionId' : sectionId
        };

        console.log(modalData);

        var modalInstance = $modal.open({
            animation: true,
            templateUrl: 'sectionEdit.html',
            controller: 'editSectionController',
            size: 'lg',
            resolve: {
                modalData: function() {
                    return modalData;
                }
            }
        });

        modalInstance.result.then(function (section) {
            console.log('modal closed');
            console.log(section);

            if (create) {
                $scope.data.sections.push(section);
            } else {

                angular.forEach($scope.data.sections, function(value, key) {
                    if (value.id == sectionId) {
                        console.log('updated section: ' + value.id)
                        $scope.data.sections[key] = section
                    }
                });

            }
            console.log($scope.data.sections);
            $scope.tableParams.reload();
        });

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
        $http.put(RestfulApi.getRoute('project', 'update', $stateParams.id), $scope.data)
            .success(function(data) {
                notificationService.add("Project '" + data.title + "' updated successfully", 'success');
                $scope.errors = [];
                if (!apply) {
                    $state.go( "project.index" );
                }
            })
            .error(function(data) {
                $scope.errors = data;
            });
    }


});





app.controller('editSectionController', function ($scope, $http, $modalInstance, RestfulApi, notificationService, modalData) {

    if (!modalData.create) {

        $http.get(RestfulApi.getRoute('section', 'show', modalData.sectionId))
            .success(function(data) {
                $scope.section = data;
            })
            .error(function(data) {
                $scope.errors = data;
            });

    }


    $scope.save = function() {

        console.log(modalData.create);

        if (modalData.create){

            console.log('creating');

            $http.post(RestfulApi.getRoute('projectSection', 'store', modalData.projectId), $scope.section)
                .success(function(data) {
                    notificationService.add("Section '" + data.title + "' created successfully", 'success');
                    $scope.errors = [];
                    $modalInstance.close(data);
                })
                .error(function(data) {
                    $scope.errors = data;
                });

        } else {

            console.log('editing');

            $http.put(RestfulApi.getRoute('section', 'update', $scope.section.id), $scope.section)
                .success(function(data) {
                    notificationService.add("Section '" + data.title + "' updated successfully", 'success');
                    $scope.errors = [];
                    $modalInstance.close(data);
                })
                .error(function(data) {
                    $scope.errors = data;
                });

        }

    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
});
