angular.module('angular-flot', []).directive('flot', function() {
  return {
    restrict: 'EA',
    template: '<div></div>',
    scope: {
      dataset: '=',
      options: '=',
      callback: '='
    },
    link: function(scope, element, attributes) {
      var height, init, onDatasetChanged, onOptionsChanged, plot, plotArea, width;
      plot = null;
      width = attributes.width || '100%';
      height = attributes.height || '100%';
      if (!scope.dataset) {
        scope.dataset = [];
      }
      if (!scope.options) {
        scope.options = {
          legend: {
            show: false
          }
        };
      }
      plotArea = $(element.children()[0]);
      plotArea.css({
        width: width,
        height: height
      });
      init = function() {
        var plotObj;
        plotObj = $.plot(plotArea, scope.dataset, scope.options);
        if (scope.callback) {
          scope.callback(plotObj);
        }
        return plotObj;
      };
      onDatasetChanged = function(dataset) {
        if (plot) {
          plot.setData(dataset);
          plot.setupGrid();
          return plot.draw();
        } else {
          return plot = init();
        }
      };
      scope.$watchCollection('dataset', onDatasetChanged, true);
      onOptionsChanged = function() {
        return plot = init();
      };
      return scope.$watch('options', onOptionsChanged, true);
    }
  };
});
