/*  State Provider
    -------------------------------------------------------------------------------------------------
    Angular routes (states) are defined here. Each State is usually assigned a controller and a view
*/
app.config(function($stateProvider, $urlRouterProvider) {

    // Now set up the states
    $stateProvider

        // Project base view
        .state('project', {
            url: "/project",
            templateUrl: "/vendor/portfolio/admin/views/project/project.html",
            controller: "ProjectController",
        })

        // Project index
        .state('project.index', {
            url: "/index",
            templateUrl: "/vendor/portfolio/admin/views/project/project.index.html",
            controller: "ProjectIndexController",
            resolve:{
                model: function($http){
                    return $http.get('/api/project');
                }
            }
        })

        // Project create
        .state('project.create', {
            url: "/create",
            templateUrl: "/vendor/portfolio/admin/views/project/project.edit.html",
            controller: "ProjectCreateController"
        })

        // Project edit
        .state('project.edit', {
            url: "/:id/edit",
            templateUrl: "/vendor/portfolio/admin/views/project/project.edit.html",
            controller: "ProjectEditController",
            resolve:{
                model: function($http, RestfulApi, $stateParams){
                    return $http.get(RestfulApi.getRoute('project', 'show', $stateParams.id));
                }
            }
        })
})




app.controller('ProjectController', function($scope) {

    $scope.module = {
        name : 'projects'
    }

});



app.controller('ProjectIndexController', function($scope, model, $filter, ngTableParams, $http, RestfulApi, notificationService) {

    // Initialise an empty array to hold data
    $scope.model = model.data;

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


});



app.controller('ProjectCreateController', function($scope, $http, $stateParams, $state, RestfulApi, notificationService) {

    $scope.model = {};

    $scope.create = true;

    $scope.save = function(apply) {

        $http.post(RestfulApi.getRoute('project', 'store'), $scope.model)
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

    $scope.loadTags = function(query) {
        return $http.get('/api/tag/search?query=' + query);
    };

});


app.controller('ProjectEditController', function($scope, model, $http, $stateParams, $state, RestfulApi, notificationService, ngTableParams, $filter, $modal) {

    $scope.model = model.data;
    $scope.slug = model.data.slug;

    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,          // count per page
        sorting: {
            updated_at: 'desc'     // initial sorting
        },
    }, {
        filterDelay: 10,
        total: $scope.model.pages.length, // length of data
        getData: function($defer, params) {
            // use build-in angular filter
            var filteredData = params.filter() ?
                    $filter('filter')($scope.model.pages, params.filter()) :
                    $scope.model.pages;
            var orderedData = params.sorting() ?
                    $filter('orderBy')(filteredData, params.orderBy()) :
                    $scope.model.pages;

            params.total(orderedData.length); // set total for recalc pagination
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
        }
    });

    // UI-Tree Items
    $scope.options = {

        // Update the items properties after drag and drop
        dragStop: function(scope) {

            debug('stopped dragging', 'ui', scope);

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


    $scope.loadTags = function(query) {
        return $http.get('/api/tag/search?query=' + query);
    };

    $scope.editSection = function (create, sectionId) {
        sectionId = typeof sectionId !== 'undefined' ? sectionId : false;

        modalData = {
            'create' : create,
            'projectId' : $scope.model.id,
            'sectionId' : sectionId
        };

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

            debug('modal closed', 'ui', section);

            if (create) {
                $scope.model.sections.push(section);
            } else {

                angular.forEach($scope.model.sections, function(value, key) {
                    if (value.id == sectionId) {
                        debug('updated section: ' + value.id)
                        $scope.model.sections[key] = section
                    }
                });

            }

        });

    };


    $scope.deleteSection = function (sectionId) {

        if (confirm('Are you sure you wish to delete this section?.')) {
            $http.delete('/api/section/' + sectionId)
                .success(function(data) {

                    // Remove the section
                    angular.forEach($scope.model.sections, function(value, key) {
                        if (value.id == sectionId) {
                            $scope.model.sections.splice(key, 1)
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
            'projectId' : $scope.model.id,
            'pageId' : pageId
        };

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

            if (create) {
                $scope.model.pages.push(page);
            } else {

                angular.forEach($scope.model.pages, function(value, key) {
                    if (value.id == pageId) {
                        debug('updated section: ' + value.id);
                        $scope.model.pages[key] = page;
                    }
                });

            }

            $scope.tableParams.reload();
        });

    };

    $scope.deletePage = function (pageId) {

        if (confirm('Are you sure you wish to delete this page?.')) {
            $http.delete('/api/page/' + pageId)
                .success(function(data) {

                    // Remove the section
                    angular.forEach($scope.model.pages, function(value, key) {
                        if (value.id == pageId) {
                            $scope.model.pages.splice(key, 1)
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
        if ($scope.slug != $scope.model.slug) {
            notificationService.add("You have modified the project slug. Please be aware that this may break hyperlinks to this project.", 'warning');
        }
    }


    $scope.save = function(apply) {

        apply = typeof apply !== 'undefined' ? apply : false;

        if ($scope.slug != $scope.model.slug) {
            if (confirm('Are you sure you wish to change the slug?')) {
                return $scope.put(apply);
            } else {
                $scope.model.slug = $scope.slug;
                notificationService.add('Slug reset', 'info');
            }
        } else {
            return $scope.put(apply);
        }

    }

    $scope.put = function(apply) {
        return $http.put(RestfulApi.getRoute('project', 'update', $stateParams.id), $scope.model)
            .success(function(data) {
                notificationService.add("Project '" + data.title + "' updated successfully", 'success');
                $scope.errors = [];
                $scope.model.updated_at_human = 'Just now';
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

        debug('Saving section', 'controller', modalData.create);

        if (modalData.create){

            debug('Creating new section', 'controller');

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

            debug('Updating existing section', 'controller');

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

        debug('Saving page', 'controller', modalData);

        if (modalData.create){

            debug('Creating new page', 'controller');

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

            debug('Updating existing page', 'controller');

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