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

            $http.delete('/api/project/' + id)
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
        sections : [],
        pages : []
    };

    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,          // count per page
        sorting: {
            updated_at: 'desc'     // initial sorting
        },
    }, {
        filterDelay: 10,
        total: $scope.data.pages.length, // length of data
        getData: function($defer, params) {
            // use build-in angular filter
            var filteredData = params.filter() ?
                    $filter('filter')($scope.data.pages, params.filter()) :
                    $scope.data.pages;
            var orderedData = params.sorting() ?
                    $filter('orderBy')(filteredData, params.orderBy()) :
                    $scope.data.pages;

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


    $scope.alertMe = function () {
        //alert('Boob');
    }


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
        });

    };


    $scope.deleteSection = function (sectionId) {

        if (confirm('Are you sure you wish to delete this section?.')) {
            $http.delete('/api/section/' + sectionId)
                .success(function(data) {

                    // Remove the section
                    angular.forEach($scope.data.sections, function(value, key) {
                        if (value.id == sectionId) {
                            console.log(key);
                            $scope.data.sections.splice(key, 1)
                        }
                    })

                    notificationService.add("Section '" + sectionId + "' deleted successfully", 'info');
                    //
                })
                .error(function(data) {
                    $scope.errors = data;
                });
        }

    }


    $scope.editPage = function (create, pageId) {
        pageId = typeof pageId !== 'undefined' ? pageId : false;

        modalData = {
            'create' : create,
            'projectId' : $scope.data.id,
            'pageId' : pageId
        };

        console.log(modalData);

        var modalInstance = $modal.open({
            animation: true,
            templateUrl: 'pageEdit.html',
            controller: 'editPageController',
            size: 'lg',
            resolve: {
                modalData: function() {
                    return modalData;
                }
            }
        });

        modalInstance.result.then(function (page) {
            console.log('modal closed');
            console.log(page);

            if (create) {
                $scope.data.pages.push(page);
            } else {

                angular.forEach($scope.data.pages, function(value, key) {
                    if (value.id == pageId) {
                        console.log('updated section: ' + value.id);
                        $scope.data.pages[key] = page;
                    }
                });

            }
            console.log($scope.data.pages);
            $scope.tableParams.reload();
        });

    };

    $scope.deletePage = function (pageId) {

        if (confirm('Are you sure you wish to delete this page?.')) {
            $http.delete('/api/page/' + pageId)
                .success(function(data) {

                    // Remove the section
                    angular.forEach($scope.data.pages, function(value, key) {
                        if (value.id == pageId) {
                            console.log(key);
                            $scope.data.pages.splice(key, 1)
                        }
                    })

                    notificationService.add("Page '" + pageId + "' deleted successfully", 'info');
                    $scope.tableParams.reload();
                    //
                })
                .error(function(data) {
                    $scope.errors = data;
                });
        }
    }




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
                $scope.data.updated_at_human = 'Just now';
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
                    notificationService.add("Section (ID:" + data.id + ") created successfully", 'success');
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
                    notificationService.add("Section (ID:" + data.id + ") updated successfully", 'success');
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


app.controller('editPageController', function ($scope, $http, $modalInstance, RestfulApi, notificationService, modalData) {

    if (!modalData.create) {

        $http.get(RestfulApi.getRoute('page', 'show', modalData.pageId))
            .success(function(data) {
                $scope.page = data;
            })
            .error(function(data) {
                $scope.errors = data;
            });

    }


    $scope.save = function() {

        console.log(modalData.create);

        if (modalData.create){

            console.log('creating');

            $http.post(RestfulApi.getRoute('projectPage', 'store', modalData.projectId), $scope.page)
                .success(function(data) {
                    notificationService.add("Page '" + data.title + "' created successfully", 'success');
                    $scope.errors = [];
                    $modalInstance.close(data);
                })
                .error(function(data) {
                    $scope.errors = data;
                });

        } else {

            console.log('editing');

            $http.put(RestfulApi.getRoute('page', 'update', $scope.page.id), $scope.page)
                .success(function(data) {
                    notificationService.add("Page '" + data.title + "' updated successfully", 'success');
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