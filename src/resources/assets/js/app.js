// Fire up the app
var app = angular.module('ng-portfolio', ['ui.router', 'ui.bootstrap', 'ngTable']);

// Configure Angular

/* app.config(function($interpolateProvider) {
    // Set Angular to use square-brackets instead of curly - a work around to play nice with Laravel Blade templates
    $interpolateProvider.startSymbol('{{');
    $interpolateProvider.endSymbol('}}');
}); */

app.config(function($httpProvider) {
    // Add the XMLHttpRequest header so that Laravel can tell apart AJAX requests
    $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
});


// Setup the state manager (Router)
app.config(function($stateProvider, $urlRouterProvider) {

  // For any unmatched url, redirect to /state1
  $urlRouterProvider.otherwise("/dashboard");

  //$urlRouterProvider.when('/project', '/project/index');

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
    .state('project-section', {
      url: "/project/:id/section",
      templateUrl: "/vendor/portfolio/admin/views/project/project.section.index.html",
      controller: "ProjectSectionController"
    })
    .state('section-edit', {
      url: "/section/:id/edit",
      templateUrl: "/vendor/portfolio/admin/views/section/section.edit.html",
      controller: "SectionEditController"
    });
});


// Some helpful functions for AJAX requests
app.factory('RestfulApi', function ($http, notificationService) {

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

        // Returns the string of a particular route
        getRoute: function(resource, method, id) {
            if (typeof id === 'undefined') { id = '0'; }

            var prefix = '/admin/api';

            var routes = {
                project     :  {
                    index   : prefix + '/project',
                    show    : prefix + '/project/' + id,
                    store   : prefix + '/project/',
                    update  : prefix + '/project/' + id,
                    destroy : prefix + '/project/' + id
                },
                section     :  {
                    index   : prefix + '/section',
                    show    : prefix + '/section/' + id,
                    store   : prefix + '/section/',
                    update  : prefix + '/section/' + id,
                    destroy : prefix + '/section/' + id
                }
            };

            return routes[resource][method];

        },

        // Do some stuff upon a successful Ajax request
        success: function(data, status, headers, config) {
            notificationService.removeByType('danger');
            return data;
        },
        // Do some stuff upon an unsuccessful Ajax request
        error: function(data, status, headers, config) {
            checkResponseCode(data, status);
            return data;
        }
    }
});


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
                //$scope.alerts.splice($scope.alerts.indexOf(alert), 1);
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
