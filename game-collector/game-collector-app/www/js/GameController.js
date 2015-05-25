GameApp.controller('GameController', function($scope, $http) {
    //$http({method: 'json',responseType: "json"});
    $http.get("http://beecoapp.com/ws-game/?service=game&action=list&limit=0,10")
    .success(function(response) {$scope.gameList = response.data;});
});