GameApp.controller('GameController', function($scope, $http) {
    //$http({method: 'json',responseType: "json"});
    //$http.get("http://beecoapp.com/ws-game/?service=game&action=list&limit=0,10")
    //.success(function(response) {$scope.gameList = response.data;});
    //$http.get('http://beecoapp.com/ws-game/?service=game&action=list&limit=0,10').success();
    var url = "http://beecoapp.com/ws-game/?service=game&action=list&limit=0,10";
    $http.jsonp(url).then(
            function(s) { $scope.success = JSON.stringify(s); }, 
            function(e) { $scope.error = JSON.stringify(e); }
    );

    $scope.gameList = window.gameList;
});

function collectorWSCallBack(data) {
    console.log(data.data);
    gameController = document.getElementById('game-controller'); 
    angular.element(gameController).scope().gameList = data.data;
}