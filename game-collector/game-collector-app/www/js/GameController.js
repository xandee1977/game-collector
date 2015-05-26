GameApp.controller('GameController', function($scope, $http) {
    // User ID fixo para testes
    $scope.user_id = 1;
    $scope.base_url = "http://beecoapp.com/ws-game/";

    var url = $scope.base_url + "?service=game&action=list&limit=0,10&user_id=" + $scope.user_id;
    $http.jsonp(url).then(
            function(s) { $scope.success = JSON.stringify(s); }, 
            function(e) { $scope.error = JSON.stringify(e); }
    );

    $scope.adFlag = function(game_id, flag) {
        var url = $scope.base_url + "?service=user&action=flag-" + flag + "&user_id=" + $scope.user_id + "&game_id=" + game_id + "&callback=" + flag + "Callback";
        console.log(url);
        $http.jsonp(url).then(
                function(s) { $scope.success = JSON.stringify(s); }, 
                function(e) { $scope.error = JSON.stringify(e); }
        );        
    }

    $scope.doWatch = function(game_id) {
        $scope.adFlag(game_id, 'watch');
    }

    $scope.doHave = function(game_id) {
        $scope.adFlag(game_id, 'have');
    }

    $scope.doFavorite = function(game_id) {
        $scope.adFlag(game_id, 'favorite');
    }
});

// When list requisition returns
function collectorWSCallBack(data) {
    gameController = document.getElementById('game-controller'); 
    angular.element(gameController).scope().gameList = data.data;
}

// When watch returns
function watchCallback(data) {
    console.log("watchCallback");
    console.log(data.status);
    if(data.status == "NOT_OK") {
        console.log(data.message);
    } else {
        var element = document.getElementById("watch-" + data.data.game_id + "-icon");
        element.src = String(element.src).replace("icon", "icon-color");
    }
}

// When have returns
function haveCallback(data) {
    console.log("haveCallback");
    console.log(data.status);
    if(data.status == "NOT_OK") {
        console.log(data.message);
    } else {
        var element = document.getElementById("have-" + data.data.game_id + "-icon");
        element.src = String(element.src).replace("icon", "icon-color");
    }
}

// When favorite returns
function favoriteCallback(data) {
    console.log("favoriteCallback");
    console.log(data.status);
    if(data.status == "NOT_OK") {
        console.log(data.message);
    } else {
        var element = document.getElementById("favorite-" + data.data.game_id + "-icon");
        element.src = String(element.src).replace("icon", "icon-color");
    }    
}