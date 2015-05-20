(function() {
     GameApp.controller('GameController', ['$scope','$http', function($scope,$http) {
          //$http is working in this
        this.gameList = window.listGames;      
     }]);
})();