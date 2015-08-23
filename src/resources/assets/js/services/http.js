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

                // Add the notification
                notificationService.add('Validation failed, please correct the issues', 'danger');

                break;

            // Unauthenticated
            case 401:
                notificationService.add('You have been logged out', 'warning');
                break;
            case 500:
                notificationService.add('API error', 'danger');
                break;
            default:
                debug('Some other problem!', 'http', data);
        }
    }


  return {
    // optional method
    'request': function(config) {
      // do something on success
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
      return response;
    },

    // optional method
   'responseError': function(rejection) {
      // do something on error
      checkResponseCode(rejection.data, rejection.status);
      debug('responseError', 'http', rejection);
      return $q.reject(rejection);
    }
  };
});