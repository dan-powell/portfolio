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
app.factory('RestfulApi', function ($http, messageBag) {

    // Wot to do incase API fails to respond with 200
    var checkResponseCode = function(data, status) {
        switch(status) {
            // Unauthenticated
            case 422:
                console.log("Model validation error");
                console.log(data);
                break;
            // Unauthenticated
            case 401:
                console.log("You have been logged out. Refresh the page to log back in again");
                break;
            case 500:
                console.log("API Error");
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
            console.log("API Request successful");
        },
        // Do some stuff upon an unsuccessful Ajax request
        error: function(data, status, headers, config) {
            checkResponseCode(data, status);
            return data;
        }
    }
});


app.factory('messageBag', function ($http) {

    // Wot to do incase API fails to respond correctly
    var ApiError = function() {

    }

    return {

        /* -- Lists -- */
        /* ------------------------------ */

        success: function(data, status, headers, config) {

            console.log(data);

            if (status == 401) {
                $scope.errors = [{"Logged out" : "You have been logged out. Refresh the page to log back in again"}];
            } else {
                $scope.errors = data;
            }

        }
    }
});
