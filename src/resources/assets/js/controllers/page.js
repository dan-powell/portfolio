app.controller('PageController', function($scope, $filter, ngTableParams, $http, RestfulApi, notificationService) {

    // Initialise an empty array to hold data
    $scope.model = [];

    // Initialise the data table
    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,          // count per page
        sorting: {
            updated_at: 'desc'     // initial sorting
        },
    }, {
        filterDelay: 10,
        total: $scope.model.length, // length of data
        getData: function($defer, params) {
            // use build-in angular filter
            var filteredData = params.filter() ?
                    $filter('filter')($scope.model, params.filter()) :
                    $scope.model;
            var orderedData = params.sorting() ?
                    $filter('orderBy')(filteredData, params.orderBy()) :
                    $scope.model;

            params.total(orderedData.length); // set total for recalc pagination
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
        }
    });

    // Initialise (load) data
    $scope.init = function() {

        $http.get(RestfulApi.getRoute('page', 'index'))
            .success(function(data) {
                $scope.model = data;
                $scope.tableParams.reload();
            });

    };


    $scope.delete = function(id, title) {

        if (confirm('Are you sure you wish to delete ' + title + '?')) {

            $http.delete('/api/page/' + id)
                .success(function(data) {
                    notificationService.add("Page '" + data.title + "' deleted successfully", 'info');
                    $scope.init();
                })
                .error(function(data) {
                    $scope.errors = data;
                });
        }
    }

    $scope.init();

});


app.controller('PageEditController', function($scope, $http, $stateParams, $state, RestfulApi, notificationService, ngTableParams, $filter, $modal) {

    $scope.model = {
        sections : []
    };


    $http.get(RestfulApi.getRoute('page', 'show', $stateParams.id))
        .success(function(data) {
            $scope.model = data;
            $scope.slug = $scope.model.slug;
            //$scope.tableParams.reload();
        })
        .error(function(data, status, headers, config) {
            $scope.errors = data;
        });




    // UI-Tree Items
    $scope.options = {

        // Update the items properties after drag and drop
        dragStop: function(scope) {
            console.log('stopped dragging');
            console.log(scope);

            // Update rank of all sibling elements
            for(i=0; i < scope.dest.nodesScope.$modelValue.length; i++ ) {
                scope.dest.nodesScope.$modelValue[i].rank = i;
            }
        },

        accept: function(sourceNodeScope, destNodesScope, destIndex) {
            return true;
        }
    };


    var getRootNodesScope = function() {
        return angular.element(document.getElementById("tree-root")).scope();
    };




    $scope.editSection = function (create, sectionId) {
        sectionId = typeof sectionId !== 'undefined' ? sectionId : false;

        modalData = {
            'create' : create,
            'pageId' : $scope.model.id,
            'sectionId' : sectionId
        };

        console.log(modalData);

        var modalInstance = $modal.open({
            animation: true,
            templateUrl: 'pageSectionEdit.html',
            controller: 'editPageSectionController',
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
                $scope.model.sections.push(section);
            } else {

                angular.forEach($scope.model.sections, function(value, key) {
                    if (value.id == sectionId) {
                        console.log('updated section: ' + value.id)
                        $scope.model.sections[key] = section
                    }
                });

            }
            console.log($scope.model.sections);
        });

    };


    $scope.deleteSection = function (sectionId) {

        if (confirm('Are you sure you wish to delete this section?.')) {
            $http.delete('/api/section/' + sectionId)
                .success(function(data) {

                    // Remove the section
                    angular.forEach($scope.model.sections, function(value, key) {
                        if (value.id == sectionId) {
                            console.log(key);
                            $scope.model.sections.splice(key, 1)
                        }
                    })
                    notificationService.add("Section '" + sectionId + "' deleted successfully", 'info');
                })
                .error(function(data) {
                    $scope.errors = data;
                });
        }
    }


    $scope.slugWarning = function() {
        notificationService.removeByType('warning');
        if ($scope.slug != $scope.model.slug) {
            notificationService.add("You have modified the project slug. Please be aware that this may break hyperlinks to this project.", 'warning');
        }
    }


    $scope.save = function(apply) {
        apply = typeof apply !== 'undefined' ? apply : false;

        if ($scope.slug != $scope.model.slug) {
            if (confirm('Are you sure you wish to change the slug?')) {
                $scope.put(apply);
            } else {
                $scope.model.slug = $scope.slug;
                notificationService.removeByType('warning');
                notificationService.add("Slug reset", 'info');
            }
        } else {
            $scope.put(apply);
        }

    }

    $scope.put = function(apply) {
        $http.put(RestfulApi.getRoute('page', 'update', $stateParams.id), $scope.model)
            .success(function(data) {
                notificationService.add("Page '" + data.title + "' updated successfully", 'success');
                $scope.errors = [];
                $scope.model.updated_at_human = 'Just now';
                if (!apply) {
                    $state.go( "page.index" );
                }
            })
            .error(function(data) {
                $scope.errors = data;
            });
    }


});


app.controller('editPageSectionController', function ($scope, $http, $modalInstance, RestfulApi, notificationService, modalData) {

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

            $http.post(RestfulApi.getRoute('pageSection', 'store', modalData.pageId), $scope.section)
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
