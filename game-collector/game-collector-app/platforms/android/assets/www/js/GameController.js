GameApp.controller('GameController', function($scope, $http) {
    $scope.base_url = "http://beecoapp.com/ws-game/";
    $scope.user_id = localStorage.getItem('user_id') || null;
        
    if(!localStorage["profile_data"]) {
        $scope.profile_data = null;
    } else {
        $scope.profile_data = JSON.parse(localStorage["profile_data"]) || null;
    }
    
    $scope.params = {
        "user_id" : $scope.user_id,
        "service" : "game",
        "action" : "list",
        "limit" : "0,10",
        "search" : null,
        "flag" : null,
        "callback" : null
    };
    $scope.controlId = [];
    $scope.gameList = [];
    $scope.current_action = null;
    $scope.current_view = null; // View inicial
    $scope.states = [];
    $scope.cities = [];

    // Get the action login
    $scope.actionLogin = function() {
        // Logica do modulo
        var action = "login";
        if($scope.current_action != action) {
            // View relativa ao módulo
            $scope.current_view = "views/login.html";
        }
    }

    // Get the action profile
    $scope.actionProfile = function() {
        // Logica do modulo
        var action = "profile";
        if($scope.current_action != action) {
            $scope.current_action = action;
            
            $scope.doGetStates();// Losds ths states list
            $scope.current_profile_image = "img/camera.gif";

            if(typeof $scope.profile_data != "undefined" && $scope.profile_data != null) {
                // Carrega a lista de cidades
                if($scope.profile_data.state_id != null) {
                    $scope.doGetCities($scope.profile_data.state_id);
                }

                if($scope.profile_data.picture_url) {
                    $scope.current_profile_image = $scope.base_url + "pictures/profile/" +  $scope.profile_data.picture_url; 
                }   
            }
            // View relativa ao módulo
            $scope.current_view = "views/profile.html";
        }
    }

    $scope.get_url = function() {
        var url = $scope.base_url;
        // Remove null falues
        for (var key in $scope.params) {
           if ($scope.params[key] == null) {
              delete $scope.params[key];
           }
        }
        var qs = $.param($scope.params);
        
        console.log(url + "?" + qs);
        return url + "?" + qs;
    }

    // Get the action list
    $scope.actionList = function() {
        // Logica do modulo
        var action = "list";
        if($scope.current_action != action) {
            // Limpa os filtros
            $scope.params.search = null;
            $scope.params.flag = null;

            $scope.current_action = action
            $scope.params.callback = "gameListCallback";
            var url = $scope.get_url();
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

    $scope.doSearchGames = function(word) {
        $scope.current_action = 'list';

        $scope.params.callback = "gameSearchCallback";

        // Remove os espacos na busca
        word = String(word).trim(); 
        
        if(word.length >= 4) {
            $scope.params.search = word;
        } else {
            $scope.params.search = null;
        }

        var url = $scope.get_url();

       // window.setTimeout(function(){
            $http.jsonp(url).then(
                    function(s) { $scope.success = JSON.stringify(s); },
                    function(e) { $scope.error = JSON.stringify(e); }
            );
            // View relativa ao módulo
            $scope.current_view = "views/game-list.html";
        //}, 100);
    }

    $scope.doSearchByFlag = function(flag) {
        $scope.current_action = 'list';

        $('body,html').animate({scrollTop:0},600);
        $scope.params.limit = "0,10"; // Volta para a primeira pagina

        disabledFlagButtons();

        if($scope.params.flag == flag) {
            $scope.params.flag = null;
            $("#bt-" + flag).attr("class", "bt-header bt-" + flag);
        } else {
            $scope.params.flag = flag;
            $("#bt-" + flag).attr("class", "bt-header bt-" + flag + "-enabled");
        }
        $scope.params.callback = "gameSearchCallback";

        var url = $scope.get_url();
        $http.jsonp(url).then(
                function(s) { $scope.success = JSON.stringify(s); },
                function(e) { $scope.error = JSON.stringify(e); }
        );
        // View relativa ao módulo
        $scope.current_view = "views/game-list.html";                
    }

    $scope.moreGames = function() {
        var limit1 = 0;
        var limit2 = 10;
        if($scope.gameList.length > 0) {
            limit1 = $scope.gameList.length-1;
        }
        $scope.params.limit = limit1 + "," + limit2;

        $scope.params.callback = "moreGamesCallback";
        var url = $scope.get_url();
        $http.jsonp(url).then(
                function(s) { $scope.success = JSON.stringify(s); },
                function(e) { $scope.error = JSON.stringify(e); }
        );
        // View relativa ao módulo
        $scope.current_view = "views/game-list.html";        
    }

    // Liga/Desliga uma flag
    $scope.toggleFlag = function (element_id) {
        var element = $("#" + element_id);
        var parts = String(element_id).split("-");
        var flag = parts[0];
        var game_id = parts[1];
        var status = element.attr("data-flag-status");

        if(status == "Y") {
            $scope.removeFlag(game_id, flag);
            element.attr("data-flag-status", "N");
        }
        if(status == "N") {
            $scope.adFlag(game_id, flag);
            element.attr("data-flag-status", "Y");
        }

        element.toggleClass("bt-" + flag + " bt-" + flag + "-enabled");
    }

    $scope.adFlag = function(game_id, flag) {
        //console.log("adFlag");
        var url = $scope.base_url + "?service=user&action=flag-" + flag + "&user_id=" + $scope.user_id + "&game_id=" + game_id + "&callback=" + flag + "Callback";
        $http.jsonp(url).then(
                function(s) { $scope.success = JSON.stringify(s); }, 
                function(e) { $scope.error = JSON.stringify(e); }
        );        
    }

    $scope.removeFlag = function(game_id, flag) {
        //console.log("removeFlag");
        var url = $scope.base_url + "?service=user&action=remove-flag&user_id=" + $scope.user_id + "&game_id=" + game_id + "&flag=" + flag + "&callback=removeFlagCallback";

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
    // Saving an user
    $scope.doSaveUser = function() {
        try {
            clearErrorMessage();
            clearSuccessMessage();

            var email = $("#cad_email").val();
            var password = $("#cad_password").val();
            var passconf = $("#cad_password_conf").val();
            var url = $scope.base_url + "?service=user&action=save";

            if(password != passconf) {
                throw "Senha e confirmação não conferem.";
            }            
            if($.trim(email) == "") {
                throw "Por favor preencha o email.";
            }
            
            if(!validateEmail($.trim(email))) {
                throw "Opa! Email invalido.";                
            }

            if($.trim(password) == "") {
                throw "Por favor preencha a senha.";
            }

            var json_data = {"user_email": email, "user_password": password};
            $http({
                method: 'POST',
                url: url,
                data: json_data,
                headers: {'Content-Type': 'application/json; charset=utf-8'}
            })
            .then(function(response) {                
                if(response.data.status == "NOT_OK") {
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
                //console.log("falha :( !");
                //debugObject(response.stack);
            });
        } catch(err) {
            showErrorMessage(err);
        }
    }    

    // Saving an profile
    $scope.doSaveProfile = function() {
        clearErrorMessage();
        clearSuccessMessage();

        $scope.current_action = 'profile';

        var profile_id = $("#profile_id").val();
        var nickname = $("#nickname").val();
        var state_id = $("#state_id ").val();
        var city_id = $("#city_id").val();
        var about_me = $("#about_me").val();

        var json_data = {
            "user_id": $scope.user_id,
            "nickname": nickname,
            "state_id": state_id,
            "city_id": city_id,
            "resume": about_me
        };
        // Adiciona o id do profile se houver
        if(profile_id != "") {
            json_data["profile_id"] = profile_id;
        };

        //console.log(json_data);

        var url = $scope.base_url + "?service=user&action=save-profile&user_id=" + String($scope.user_id);
        $http({
            method: 'POST',
            url: url,
            data: json_data,
            headers: {'Content-Type': 'application/json; charset=utf-8'}
        })
        .then(function(response) {                
            //console.log(response);
            if(response.data.status == "NOT_OK") {
                showErrorMessage(response.data.message);
            } else {
                // Exibe a messagem de sucesso
                showSuccessMessage("Perfil editado com sucesso.");

                var profile_data = response.data.data;

                // Salve o profile
                localStorage.setItem('profile_data', JSON.stringify(profile_data));
                angular.element(document.getElementById('game-controller')).scope().profile_data = profile_data;


                //console.log(response.data.data);
                $("#profile_id").val(response.data.data.profile_id);
                // Manda o usuario para a list
                $scope.actionProfile();
            }
            //debugObject(response);
        }, 
        function(response) { // optional
            //console.log("falha :( !");
            //debugObject(response.stack);
        });        
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
        clearErrorMessage();
        clearSuccessMessage();

        $scope.current_action = 'logout';
        // Salva u id do usuario
        localStorage.setItem('user_id', null);
        $scope.user_id = null;
        $scope.actionLogin();
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
                //console.log( String(itens[i].game_id) + " ja esta na lista." );
            }

            $scope.controlId.push(itens[i].game_id);
        }        
    }

    // Pega a lista de estados
    $scope.doGetStates = function() {
        var url = $scope.base_url + "?service=address&action=list-states&callback=getStatesCallback";
        $http.jsonp(url).then(
                function(s) { $scope.success = JSON.stringify(s); },
                function(e) { $scope.error = JSON.stringify(e); }
        );
    }

    // Pega a lista de cidades
    $scope.doGetCities = function(state_id) {
        if(state_id != 0) {
            var url = $scope.base_url + "?service=address&action=list-cities&state_id=" + state_id + "&callback=getCitiesCallback";
            $http.jsonp(url).then(
                    function(s) { $scope.success = JSON.stringify(s); },
                    function(e) { $scope.error = JSON.stringify(e); }
            );
        }
    }

    $scope.clearGameList = function() {
        $scope.gameList = [];
    }
});

// When getStates returns
function getStatesCallback(data) {
    if(data.status == "NOT_OK") {
        //console.log(data.message);
        showErrorMessage(data.message);
    } else {
        angular.element(document.getElementById('game-controller')).scope().states = data.data;
    }
}

// When getCities returns
function getCitiesCallback(data) {
    if(data.status == "NOT_OK") {
        //console.log(data.message);
        showErrorMessage(data.message);
    } else {
        angular.element(document.getElementById('game-controller')).scope().cities = data.data;
    }
}

// When login returns
function loginCallback(data) {
    clearErrorMessage();
    clearSuccessMessage();

    //console.log("loginCallback");
    //console.log(data.status);
    //console.log(data);
    if(data.status == "NOT_OK") {
        //console.log(data.message);
        showErrorMessage(data.message);
    } else {
        var profile_data = {
            "profile_id": data.data.profile_id,
            "nickname": data.data.nickname,
            "state_id": data.data.state_id,
            "city_id": data.data.city_id,
            "resume": data.data.resume,
            "picture_url": data.data.picture_url,
            "state_name": data.data.state_name,
            "city_name": data.data.city_name
        };
        // Salva o id do usuario
        localStorage.setItem('profile_data', JSON.stringify(profile_data));
        angular.element(document.getElementById('game-controller')).scope().profile_data = profile_data;

        // Salva o id do usuario
        localStorage.setItem('user_id', data.data.user_id);
        angular.element(document.getElementById('game-controller')).scope().user_id = data.data.user_id;

        // carrega a lista
        angular.element(document.getElementById('game-controller')).scope().actionList();        
    }
}

// When list requisition returns
function gameSearchCallback(data) {
    clearErrorMessage();
    clearSuccessMessage();    

    if(data.status == "NOT_OK") {
        var word = angular.element(document.getElementById('game-controller')).scope().params.search;
        var errorMessage = "Busca por '" + word + "' não retornou resultado."
        showErrorMessage(errorMessage);
    } else {
        if(data.data instanceof Array) {
            angular.element(document.getElementById('game-controller')).scope().addToGameList(data.data, true);
        }        
    }


}

function gameListCallback(data) {
    clearErrorMessage();
    clearSuccessMessage();

    if(data.data instanceof Array) {
        window.angular.element(document.getElementById('game-controller')).scope().addToGameList(data.data, false);
    }
}

function moreGamesCallback(data) {
    clearErrorMessage();
    clearSuccessMessage();
            
    if(data.data instanceof Array) {
        angular.element(document.getElementById('game-controller')).scope().addToGameList(data.data, false);
    }
}

// When watch returns
function watchCallback(data) {
    //console.log("watchCallback");
    //console.log(data.status);  
}

// When have returns
function haveCallback(data) {
    //console.log("haveCallback");
    //console.log(data.status);
}

// When favorite returns
function favoriteCallback(data) {
    //console.log("favoriteCallback");
    //console.log(data.status);   
}

function removeFlagCallback(data) {
    //console.log("removeFlagCallback");
    //console.log(data.status);   
}

// Util
function disabledFlagButtons() {
    var flags = ["have", "favorite", "watch"];
    for(var i=0; i<flags.length; i++) {
        $("#bt-" + flags[i]).attr("class", "bt-header bt-" + flags[i]);
    }
}

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
        //console.log(key, obj[key]);
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
    angular.element(document.getElementById('game-controller')).scope().moreGames();
}

window.onscroll = function(ev) {
    if ( !$( "#form-container" ).length ) {
        //console.log(angular.element(document.getElementById('game-controller')).scope().current_action);    
        if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight)) {
            gameListScroll();
        }
    } else {
        //console.log("Estou num form.")
    }
};

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

// faz a consulta apenas quando termina a busca
$(document).ready(function(){
    
    var typingTimer; // IDdo timer
    var doneTypingInterval = 150; // Tempo de delay em milisegundos

    //on keyup, start the countdown
    $("#search-field").keyup(function(){
        clearTimeout(typingTimer); // Limpa o identificador
        typingTimer = setTimeout(doneTyping, doneTypingInterval); // Cria um novo identificador
    });

    //on keydown, clear the countdown 
    $("#search-field").keydown(function(){
        clearTimeout(typingTimer); // Limpa o identificador
    });

    //user is "finished typing," do something
    function doneTyping () {
        //do something
        var word = $("#search-field").val();
        angular.element(document.getElementById('game-controller')).scope().doSearchGames(word);
    }
});


