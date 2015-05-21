var GameApp = angular.module('GameApp',[]);
/*
GameApp.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.useXDomain = true;
    delete $httpProvider.defaults.headers.common['X-Requested-With'];
}]);
*/

GameApp.factory('gameWebService', function($rootScope, $http) {
    var gameWebService = {};

    nukeService.game = {};

    //Gets the list of nuclear weapons
    gameWebService.getGames = function() {
        $http.get('nukes/nukes.json')
            .success(function(data) {
                gameWebService.nukes = data;
            });

        return gameWebService.nukes;
    };

    return gameWebService;
});