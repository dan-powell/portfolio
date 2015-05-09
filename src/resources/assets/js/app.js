// Fire up the app
var app = angular.module('ng-portfolio', ['ngRoute', 'ngTable']);

// Configure Angular
app.config(function($interpolateProvider) {

    // Set Angular to use square-brackets instead of curly - a work around to play nice with Laravel Blade templates
    //$interpolateProvider.startSymbol('{{');
    //$interpolateProvider.endSymbol('}}');
});


app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/project', {
                templateUrl: 'vendor/portfolio/admin/views/project.html',
                controller: 'ProjectController'
            }).
            when('/project/create', {
                templateUrl: 'vendor/portfolio/admin/views/project.edit.html',
                controller: 'ProjectCreateController'
            }).
            when('/project/:id/edit', {
                templateUrl: 'vendor/portfolio/admin/views/project.edit.html',
                controller: 'ProjectEditController'
            }).
            otherwise({
                redirectTo: '/admin'
            });
    }
]);

