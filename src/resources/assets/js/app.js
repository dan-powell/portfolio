// Fire up the app
var app = angular.module('ng-portfolio', ['ui.router', 'ui.bootstrap', 'ngFileUpload', 'ngTable', 'hc.marked', 'ngTagsInput', 'ui.tree']);

/*  Angular Configuration
    -------------------------------------------------------------------------------------------------
    Angular routes (states) are defined here. Each State is usually assigned a controller and a view
*/

/* app.config(function($interpolateProvider) {
    // Set Angular to use square-brackets instead of curly - a work around to play nice with Laravel Blade templates
    $interpolateProvider.startSymbol('{{');
    $interpolateProvider.endSymbol('}}');
}); */

app.config(function($httpProvider) {
    // Add the XMLHttpRequest header so that Laravel can tell apart AJAX requests
    $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

    $httpProvider.interceptors.push('HttpInterceptor');
});

app.config(function(tagsInputConfigProvider) {
  tagsInputConfigProvider.setDefaults('tagsInput', {
    replaceSpacesWithDashes : false
    //placeholder: 'New tag',
    //removeTagSymbol: '✖'
  })
});

app.config(['markedProvider', function(markedProvider) {
    markedProvider.setOptions({
        gfm: true,
        tables: true
    });
}]);


/*  State Provider
    -------------------------------------------------------------------------------------------------
    Angular routes (states) are defined here. Each State is usually assigned a controller and a view
*/
app.config(function($stateProvider, $urlRouterProvider) {

  // For any unmatched url, redirect to dasboard
  $urlRouterProvider.otherwise("/dashboard");

  // Now set up the states
  $stateProvider
    .state('dashboard', {
      url: "/dashboard",
      templateUrl: "/vendor/portfolio/admin/views/dashboard.html",
      controller: "DashboardController"
    })
    .state('project', {
      url: "/project",
      templateUrl: "/vendor/portfolio/admin/views/project/project.html",
    })
    .state('project.index', {
      url: "/index",
      templateUrl: "/vendor/portfolio/admin/views/project/project.index.html",
      controller: "ProjectController"
    })
    .state('project.create', {
      url: "/create",
      templateUrl: "/vendor/portfolio/admin/views/project/project.edit.html",
      controller: "ProjectCreateController"
    })
    .state('project.edit', {
      url: "/:id/edit",
      templateUrl: "/vendor/portfolio/admin/views/project/project.edit.html",
      controller: "ProjectEditController"
    })
    .state('project.edit.assets', {
      url: "/assets/:type",
      templateUrl: "/vendor/portfolio/admin/views/assets/assets.html",
      controller: "AssetsController"
    })
    .state('project.edit.assets.files', {
      url: "/files/:folder",
      templateUrl: "/vendor/portfolio/admin/views/assets/files.html",
      controller: "AssetsFileController"
    })
    .state('assets', {
      url: "/assets/:type/:id",
      templateUrl: "/vendor/portfolio/admin/views/assets/assets.html",
      controller: "AssetsController"
    })
    .state('tag', {
      url: "/tag",
      templateUrl: "/vendor/portfolio/admin/views/tag/tag.html",
    })
    .state('tag.index', {
      url: "/index",
      templateUrl: "/vendor/portfolio/admin/views/tag/tag.index.html",
      controller: "TagController"
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
    .state('page', {
      url: "/page",
      templateUrl: "/vendor/portfolio/admin/views/page/page.html",
    })
    .state('page.index', {
      url: "/index",
      templateUrl: "/vendor/portfolio/admin/views/page/page.index.html",
      controller: "PageController"
    })
    /*
    .state('page.create', {
      url: "/create",
      templateUrl: "/vendor/portfolio/admin/views/page/page.edit.html",
      controller: "PageCreateController"
    })
    */
    .state('page.edit', {
      url: "/:id/edit",
      templateUrl: "/vendor/portfolio/admin/views/page/page.edit.html",
      controller: "PageEditController"
    });

});



/*  Restful Api (Deprecated)
    -------------------------------------------------------------------------------------------------
    Some helpful functions for AJAX requests
*/
app.factory('RestfulApi', function () {

    return {

        // Returns the string of a particular route
        getRoute: function(resource, method, id) {
            if (typeof id === 'undefined') { id = '0'; }

            var prefix = '/api';

            var routes = {
                project     :  {
                    index   : prefix + '/project',
                    show    : prefix + '/project/' + id,
                    store   : prefix + '/project',
                    update  : prefix + '/project/' + id,
                    destroy : prefix + '/project/' + id
                },
                section     :  {
                    index   : prefix + '/section',
                    show    : prefix + '/section/' + id,
                    store   : prefix + '/section',
                    update  : prefix + '/section/' + id,
                    destroy : prefix + '/section/' + id
                },
                projectSection     :  {
                    store   : prefix + '/project/' + id + '/section'
                },
                projectPage     :  {
                    store   : prefix + '/project/' + id + '/page'
                },
                tag         :  {
                    index   : prefix + '/tag',
                    show    : prefix + '/tag/' + id,
                    store   : prefix + '/tag',
                    update  : prefix + '/tag/' + id,
                    destroy : prefix + '/tag/' + id
                },
                page     :  {
                    index   : prefix + '/page',
                    show    : prefix + '/page/' + id,
                    store   : prefix + '/page',
                    update  : prefix + '/page/' + id,
                    destroy : prefix + '/page/' + id
                },
                pageSection     :  {
                    store   : prefix + '/page/' + id + '/section'
                }
            };

            return routes[resource][method];

        }
    }
});


/*  HTTP Intercepter
    -------------------------------------------------------------------------------------------------
    Intercepts all AJAX request and responses. Used to perform a few checks on each request.
*/
app.factory('HttpInterceptor', function($q, notificationService) {

    // Wot to do incase API fails to respond with 200
    var checkResponseCode = function(data, status) {
        switch(status) {
            // Failed validation
            case 422:

                // Clear all existing notifications
                notificationService.clear();

                // Concatenate all the validation error messages
                var msg = [];
                angular.forEach(data, function(value, key) {
                    msg = msg.concat(value);
                });

                // Add the notification
                notificationService.add("Validation failed, please correct the following issues:", 'danger', msg);
                break;

            // Unauthenticated
            case 401:
                notificationService.add('You have been logged out', 'warning');
                break;
            case 500:
                notificationService.add('API error', 'danger');
                break;
            default:
                console.log("Some other problem!");
                console.log(data);
        }
    }


  return {
    // optional method
    'request': function(config) {
      // do something on success
      //console.log('request');
      //console.log(config);
      return config;
    },

    // optional method
   'requestError': function(rejection) {
      // do something on error
      return $q.reject(rejection);
    },

    // optional method
    'response': function(response) {
      // do something on success
      //console.log('Response');
      //console.log(response);
      notificationService.removeByType('danger');
      return response;
    },

    // optional method
   'responseError': function(rejection) {
      // do something on error
      checkResponseCode(rejection.data, rejection.status);
      console.log(rejection);
      return $q.reject(rejection);
    }
  };
});


/*  Notification Service
    -------------------------------------------------------------------------------------------------
    Manages pop-up alert info & danger messages that are displayed on page
*/
app.service('notificationService', function ($timeout) {

    var notifications = [];

    // Retrieve all messages
    this.get = function() {
        return notifications;
    };

    // Remove new notification
    this.add = function(message, type, messages) {

        // Only allow one success message at a time
        if (type == 'success') {
            this.removeByType('success');
        }

        var notification = {
            type : type,
            message : message,
            messages : messages,
            //action : action
        };

        notifications.push(notification);

        console.log('added ' + type + ' message');

        if (type != 'danger') {
            // Set the alert to be removed after a delay
            $timeout(function(){
                notifications.splice(notifications.indexOf(notification), 1);
            }, 6000); // maybe '}, 3000, false);' to avoid calling apply
        }

    };

    // Remove notification by array index
    this.removeByIndex = function(index) {
        console.log('removing one message: ' + index);
        notifications.splice(index, 1);
    }

    // Remove all notifications of type
    this.removeByType = function(type) {
        console.log('clearing ' + type + ' messages');
        for (i=0; i < notifications.length; i++) {
            if (notifications[i].type == type) {
                notifications.splice(i, 1);
            }
        }
    }

    // Clear all notifications
    this.clear = function() {
        console.log('clearing all messages');
        notifications = [];
    }

});
