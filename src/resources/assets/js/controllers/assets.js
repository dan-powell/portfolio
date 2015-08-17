/*  State Provider
    -------------------------------------------------------------------------------------------------
    Angular routes (states) are defined here. Each State is usually assigned a controller and a view
*/
app.config(function($stateProvider, $urlRouterProvider) {

    // Now set up the states
    $stateProvider

        .state('assets', {
              url: "/assets",
              templateUrl: "/vendor/portfolio/admin/views/assets/assets.html",
              //controller: "AssetController"
            })
});





app.controller('AssetController', function($scope, $http, $stateParams, $state, notificationService, Upload) {


    $scope.image_types = ['jpg', 'gif', 'png'];

    if(typeof $scope.$parent.$parent.module != 'undefined') {
        $scope.type = $scope.$parent.$parent.module.name;
    } else {
        console.log('derp');
        $scope.type = '';
    }

    if(typeof $scope.$parent.model != 'undefined' ) {
        $scope.root_id = $scope.$parent.model.id;
    } else {
        $scope.root_id = '';
    }

    $scope.initial_path = $scope.type + '/' + $scope.root_id

    $scope.active_path = $scope.initial_path;

    // Initialise an empty array to hold data
    $scope.model = [];

    // Initialise (load) data
    $scope.init = function() {

        // Load the data for the first time
        $http.get('/api/assets?path=' + $scope.initial_path)
            .success(function(data) {
                $scope.model = data;
            })
            .error(function(data, status) {

                if (status == 404) {
                    $http.post('/api/assets', {path : $scope.initial_path} )
                        .success(function(data) {
                            $scope.init();
                        })
                        .error(function(data) {
                            $scope.errors = data;
                        });
                }

            });
    };



    $scope.changeDirectory = function(path) {

        $http.get('/api/assets?path=' + path)
            .success(function(data) {
                $scope.model.files = data.files;
                $scope.active_path = path;
            })
            .error(function(data) {
                $scope.errors = data;
            });

    }



    $scope.findDirectory = function(node, path) {

        var i,
            currentChild,
            result;

        if (node.path == path) {

            console.log('found!');
            return node;

        } else {

            for (i = 0; i < node.folders.length; i++) {

                result = $scope.findDirectory(node.folders[i], path);

                if (result !== false) {
                    return result;
                }

            }
            return false;
        }
    }


    $scope.createDirectory = function(path) {

        var name = prompt("Folder name:", "new_folder");

        if(name) {

            $http.post('/api/assets', {path : path.path + '/' + name})
                .success(function(data) {

                    var folder = $scope.findDirectory($scope.model.folder, path.path);
                    folder.folders.push(data.folder);

                    // $scope.init(); / LAAAAAAZY!

                })
                .error(function(data) {
                    $scope.errors = data;
                });

        }

    }


    $scope.deleteDirectory = function(path) {

        if (confirm("Are you sure?")) {

            $http.delete('/api/assets?path=' + path.path)
                .success(function(data) {

                    //var folder = $scope.findDirectory($scope.model.folder, path.path);

                    //folder.name = '';


                    $scope.init(); // LAAAAAAZY!

                })
                .error(function(data) {
                    $scope.errors = data;
                });
        }
    }





    $scope.addFile = function(files) {
        if (files && files.length) {
            for(i=0; i < files.length; i++){

                files[i].progress = 0;
                files[i].progress_type = 'info';
                files[i].filename = files[i].name;

            }
        }
    }

    $scope.removeFile = function(file) {
        $scope.files.splice($scope.files.indexOf(file), 1);
    }

    $scope.uploadFile = function() {

        console.log($scope.files);


        angular.forEach($scope.files, function(file, key) {

        //if ($scope.files && $scope.files.length) {
            //for(i=0; i < $scope.files.length; i++){

                Upload
                    .upload({
                        url: '/api/assets',
                        fields: {'path': unescape($scope.active_path), 'filename': file.filename},
                        file: file
                    })
                    .progress(function (evt) {
                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        file.progress = progressPercentage;
                        if (progressPercentage >= 100) {
                            file.progress_type = 'success';
                        }
                        console.log('progress: ' + progressPercentage + '% ' + evt.config.file.name);
                    })
                    .success(function (data, status, headers, config) {
                        console.log('file ' + config.file.name + ' uploaded. Response: ' + data);

                        $scope.model.files.push(data.file);
                        $scope.files.splice($scope.files.indexOf(file), 1);

                    })
                    .error(function (data, status, headers, config) {
                        console.log('error status: ' + status);
                        file.progress_message = data.errors;
                        file.progress_type = 'danger';
                    });

           // }
        //}

        })







    };




});
