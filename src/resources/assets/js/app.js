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



app.run(function($rootScope){

    $rootScope
        .$on('$stateChangeStart',
            function(event, toState, toParams, fromState, fromParams){
                angular.element( document.querySelectorAll(".state-transition") ).addClass("-loading");
        });

    $rootScope
        .$on('$stateChangeSuccess',
            function(event, toState, toParams, fromState, fromParams){
                angular.element( document.querySelectorAll(".state-transition") ).removeClass("-loading");
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









