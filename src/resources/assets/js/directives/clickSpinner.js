/*  Click Spinner
    -------------------------------------------------------------------------------------------------
    Uses spin.js to show loading spinner on buttons
*/
app.directive('clickSpinner', ['$q', function ($q) {
    var spinnerId = 1;

    var directive = {
      restrict: 'A',
      link: link,
      transclude: true,
      scope: {
        clickHandler: '&clickSpinner'
      },
      template: '<span style="position: relative"><span ng-transclude></span></span>'
    };

    var opts = {
      width: 3,
      length: 3,
      lines: 9,
      radius: 4,
      color: '#fff'
    };

    return directive;

    function link(scope, element, attr) {
      var spinner = new Spinner(opts);
      var spinnerTarget = element.children();
      var textElement = spinnerTarget.children();

      function handler() {
        var p = $q.when(scope.clickHandler());

        attr.$set('disabled', true);
        textElement.css('visibility', 'hidden');
        spinner.spin(spinnerTarget[0]);

        p['finally'](function() {
          attr.$set('disabled', false);
          textElement.css('visibility', 'visible');
          spinner.stop();
        });

      }

      element.on('click', handler);

      scope.$on('$destroy', function() {
        element.off('click', handler);
      });
    }

  }]);