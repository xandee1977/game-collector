GameApp.controller('GameController', function($scope, $http) {
    // User ID fixo para testes
    $scope.current_action = null;
    $scope.current_view = null; // View inicial
    //$scope.user_id = localStorage.getItem('user_id') || null;
    $scope.user_id = null;
    $scope.base_url = "http://beecoapp.com/ws-game/";



    // Get the action login
    $scope.actionLogin = function() {
        // Logica do modulo
        var action = "login";
        if($scope.current_action != action) {
            // View relativa ao módulo
            $scope.current_view = "views/login.html";
        }
    }

    // Get the action list
    $scope.actionList = function() {
        console.log("list");
        // Logica do modulo
        var action = "list";
        if($scope.current_action != action) {
            $scope.current_action = action
            var callback = "gameListCallback";
            var url = $scope.base_url + "?service=game&action=list&limit=0,10&user_id=" + $scope.user_id + "&callback=" + callback;
            $http.jsonp(url).then(
                    function(s) { $scope.success = JSON.stringify(s); }, 
                    function(e) { $scope.error = JSON.stringify(e); }
            );

            // View relativa ao módulo
            $scope.current_view = "views/game-list.html";
        }
    }

    $scope.adFlag = function(game_id, flag) {
        var url = $scope.base_url + "?service=user&action=flag-" + flag + "&user_id=" + $scope.user_id + "&game_id=" + game_id + "&callback=" + flag + "Callback";
        console.log(url);
        $http.jsonp(url).then(
                function(s) { $scope.success = JSON.stringify(s); }, 
                function(e) { $scope.error = JSON.stringify(e); }
        );        
    }

    // User actions
    $scope.doLogin = function() {
        $scope.email = $("#login_email").val();
        $scope.password = CryptoJS.MD5($("#login_password").val());
        
        var callback = "loginCallback";
        var url = $scope.base_url + "?service=user&action=login&email="+ $scope.email +"&password=" + $scope.password + "&callback=" + callback;
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

    if($scope.user_id == null) {
        $scope.actionLogin();
    } else {
        $scope.actionList();
    }    
});

// When login returns
function loginCallback(data) {
    errorConteiner = $("#error-message");
    errorConteiner.html("");
    errorConteiner.css("display", "none")

    console.log("loginCallback");
    console.log(data.status);
    if(data.status == "NOT_OK") {
        console.log(data.message);        
        errorConteiner.html(data.message);
        errorConteiner.css("display", "block")

    } else {        
        gameController = document.getElementById('game-controller'); 
        
        // Salva u id do usuario
        localStorage.setItem('user_id', data.data.user_id);        
        angular.element(gameController).scope().user_id = data.data.user_id;

        // carrega a lista
        angular.element(gameController).scope().actionList();        
    }
}

// When list requisition returns
function gameListCallback(data) {
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