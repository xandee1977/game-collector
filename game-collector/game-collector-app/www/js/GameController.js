GameApp.controller('GameController', function($scope, $http) {
    $scope.controlId = [];
    $scope.searchWord = null;
    $scope.gameList = [];
    $scope.current_action = null;
    $scope.current_view = null; // View inicial
    $scope.user_id = localStorage.getItem('user_id') || null;
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
            console.log(url);
            $http.jsonp(url).then(
                    function(s) { $scope.success = JSON.stringify(s); }, 
                    function(e) { $scope.error = JSON.stringify(e); }
            );

            // View relativa ao módulo
            $scope.current_view = "views/game-list.html";
        }
    }

    if($scope.user_id == null) {
        $scope.actionLogin();
    } else {
        $scope.actionList();
    }

    $scope.moreGames = function() {
        var limit1 = 0;
        var limit2 = 10;
        if($scope.gameList.length > 0) {
            limit1 = $scope.gameList.length-1;
        }
        
        var callback = "moreGamesCallback";
        var url = $scope.base_url + "?service=game&action=list&limit=" + limit1 + "," + limit2 + "&user_id=" + $scope.user_id + "&callback=" + callback;
        console.log(url);
        if($scope.searchWord) {
            url = url + "&search=" + String($scope.searchWord);
        }
        $http.jsonp(url).then(
                function(s) { $scope.success = JSON.stringify(s); },
                function(e) { $scope.error = JSON.stringify(e); }
        );        
    }

    $scope.adFlag = function(game_id, flag) {
        var url = $scope.base_url + "?service=user&action=flag-" + flag + "&user_id=" + $scope.user_id + "&game_id=" + game_id + "&callback=" + flag + "Callback";
        console.log(url);
        $http.jsonp(url).then(
                function(s) { $scope.success = JSON.stringify(s); }, 
                function(e) { $scope.error = JSON.stringify(e); }
        );        
    }

    $scope.showSaveUser = function() {
        // Logica do modulo
        var action = "cadastro";
        if($scope.current_action != action) {
            // View relativa ao módulo
            $scope.current_view = "views/cadastro.html";
        }        
    }

    // User actions
    $scope.doSaveUser = function() {
        clearErrorMessage();
        clearSuccessMessage();

        var email = $("#cad_email").val();
        var password = $("#cad_password").val();
        var passconf = $("#cad_password_conf").val();
        var url = $scope.base_url + "?service=user&action=save";

        if(password != passconf) {
            showErrorMessage("Senha e confirmação não conferem.");
        } else {
            var json_data = {"user_email": email, "user_password": password};
            $http({
                method: 'POST',
                url: url,
                data: json_data,
                headers: {'Content-Type': 'application/json; charset=utf-8'}
            })
            .then(function(response) {                
                if(response.data.status == "NOT_OK") {
                    console.log(response.data.message);
                    showErrorMessage(response.data.message);
                } else {
                    // Exibe a messagem de sucesso
                    showSuccessMessage("Feito! Bora organizar minha coleção.");
                    $scope.user_id = response.data.data.user_id;
                    // Manda o usuario para a list
                    $scope.actionList();
                }
                //debugObject(response);
            }, 
            function(response) { // optional
                console.log("falha :( !");
                //debugObject(response.stack);
            });
        }
    }    

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

    $scope.doLogout = function() {
        // Salva u id do usuario
        localStorage.setItem('user_id', data.data.user_id);
        $scope.user_id = null;
        $scope.actionLogin();
    }

    $scope.doSearchGames = function(word) {
        $scope.searchWord = null;
        var callback = "gameSearchCallback";

        if(String(word).length >= 4) {
            $scope.searchWord = String(word);
            var url = $scope.base_url + "?service=game&action=list&limit=0,10&user_id=" + $scope.user_id + "&callback=" + callback + "&search=" + String(word);
            console.log(url);
            $http.jsonp(url).then(
                    function(s) { $scope.success = JSON.stringify(s); }, 
                    function(e) { $scope.error = JSON.stringify(e); }
            );
        }

        if(String(word).length == 0) {            
            var url = $scope.base_url + "?service=game&action=list&limit=0,10&user_id=" + $scope.user_id + "&callback=" + callback;
            console.log(url);
            $http.jsonp(url).then(
                    function(s) { $scope.success = JSON.stringify(s); }, 
                    function(e) { $scope.error = JSON.stringify(e); }
            );
        }        
        
    }

    $scope.doWatch = function(game_id) {
        $scope.adFlag(game_id, 'watch');
        var element = document.getElementById("watch-" + game_id + "-icon");
        element.src = String(element.src).replace("watch-icon.", "watch-icon-color.");
    }

    $scope.doHave = function(game_id) {
        $scope.adFlag(game_id, 'have');
        var element = document.getElementById("have-" + game_id + "-icon");
        element.src = String(element.src).replace("have-icon.", "have-icon-color.");
    }

    $scope.doFavorite = function(game_id) {
        $scope.adFlag(game_id, 'favorite');
        var element = document.getElementById("favorite-" + game_id + "-icon");
        element.src = String(element.src).replace("favorite-icon.", "favorite-icon-color.");
    }

    $scope.addToGameList = function(itens, clearlist) {
        if(clearlist) {
            $scope.gameList = [];
            $scope.controlId = [];
        }

        for(var i=0;  i<itens.length; i++) {
            if($scope.controlId.indexOf(itens[i].game_id) == -1) {
                $scope.gameList.push(itens[i]);
            } else {
                console.log( String(itens[i].game_id) + " ja esta na lista." );
            }

            $scope.controlId.push(itens[i].game_id);
        }        
    }

    $scope.clearGameList = function() {
        $scope.gameList = [];
    }    
});

// When login returns
function loginCallback(data) {
    clearErrorMessage();

    console.log("loginCallback");
    console.log(data.status);
    if(data.status == "NOT_OK") {
        console.log(data.message);
        showErrorMessage(data.message);
    } else {
        // Salva u id do usuario
        localStorage.setItem('user_id', data.data.user_id);
        angular.element(document.getElementById('game-controller')).scope().user_id = data.data.user_id;

        // carrega a lista
        angular.element(document.getElementById('game-controller')).scope().actionList();        
    }
}

// When list requisition returns
function gameSearchCallback(data) {
    if(data.data instanceof Array) {
        angular.element(document.getElementById('game-controller')).scope().addToGameList(data.data, true);
    }
}

function gameListCallback(data) {
    if(data.data instanceof Array) {
        window.angular.element(document.getElementById('game-controller')).scope().addToGameList(data.data, false);
    }
}

function moreGamesCallback(data) {
    console.log("moreGamesCallback");
    if(data.data instanceof Array) {
        angular.element(document.getElementById('game-controller')).scope().addToGameList(data.data, false);
    }
}

// When watch returns
function watchCallback(data) {
    console.log("watchCallback");
    console.log(data.status);
    if(data.status == "NOT_OK") {
        console.log(data.message);
        // Se da erro desmarca
        var element = document.getElementById("watch-" + data.data.game_id + "-icon");
        element.src = String(element.src).replace("icon-color", "icon");
    }
}

// When have returns
function haveCallback(data) {
    console.log("haveCallback");
    console.log(data.status);
    if(data.status == "NOT_OK") {
        console.log(data.message);
        // Se da erro desmarca
        var element = document.getElementById("have-" + data.data.game_id + "-icon");
        element.src = String(element.src).replace("icon-color", "icon");
    }
}

// When favorite returns
function favoriteCallback(data) {
    console.log("favoriteCallback");
    console.log(data.status);
    if(data.status == "NOT_OK") {
        console.log(data.message);
        // Se da erro desmarca
        var element = document.getElementById("favorite-" + data.data.game_id + "-icon");
        element.src = String(element.src).replace("icon-color", "icon");
    }    
}

// Util
function clearErrorMessage() {
    errorConteiner = $("#error-message");
    errorConteiner.html("");
    errorConteiner.css("display", "none");
}

function showErrorMessage(message) {
    errorConteiner = $("#error-message");
    errorConteiner.html(message);
    errorConteiner.css("display", "block");
}


function clearSuccessMessage() {
    successConteiner = $("#success-message");
    successConteiner.html("");
    successConteiner.css("display", "none");
}

function showSuccessMessage(message) {
    successConteiner = $("#success-message");
    successConteiner.html(message);
    successConteiner.css("display", "block");
}

// If you wanna debug an object
function debugObject(obj) {
    Object.keys(obj).forEach(function(key) {
        console.log(key, obj[key]);
    });
}

function removeDoubles(array){
    var result = [];
    for(var i = 0; i < array.length; i++){
        if(array.indexOf(array[i]) == -1){
            result.push(array[i]);
        }
    }
    return result;
}

// Verify if an object are in list
function containsObject(obj, list) {
    /*
    var res = _.find(list, function(val){ return _.isEqual(obj, val)});
    return (_.isObject(res))? true:false;
    */
    if(jQuery.inArray(obj, list) == -1) {
        return false;
    } else {
        return true;
    }
}


// Trigger on scroll
function gameListScroll(){
    console.log("gameListScroll");
    angular.element(document.getElementById('game-controller')).scope().moreGames();
}


window.onscroll = function(ev) {
    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight)) {
        gameListScroll();
    }
};