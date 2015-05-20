var GameApp = angular.module('GameApp',[]);
GameApp.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.useXDomain = true;
    delete $httpProvider.defaults.headers.common['X-Requested-With'];
}]);

GameApp.factory('loadList', [ '$http', '$q', 
    function( $http, $q ) {

        var pub = {};

        var jsonUrl = 'http://i.cdn.turner.com/nba/nba/.element/media/2.0/teamsites/warriors/json/json-as2015.js?callback=JSON_CALLBACK' + (new Date().getTime()),
            cachedResponse;

        pub.getEvent = function() {

            var deferred = $q.defer();

            if ( cachedResponse ) {
                deferred.resolve( cachedResponse );
            }

            else {

                $http.jsonp( jsonUrl );

                window.jsonpCallbackAllStar2015 = function( data ) {
                    cachedResponse = data;
                    deferred.resolve( data );
                }

            }

            return deferred.promise;

        };

        return pub;

    }
]);