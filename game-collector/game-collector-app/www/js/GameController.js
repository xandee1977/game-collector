(function() {
     GameApp.controller('GameController', ['$scope','$http', function($scope,$http) {
        $scope.teste = 'LERO LERO';
        $scope.gameList = [
            {
                "game_id":"1",
                "game_title":"2020 Super Baseball",
                "game_desc":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ultrices, nulla vel venenatis euismod, quam dui auctor enim, ut egestas risus enim et felis.",
                "game_developer":null,
                "game_type_id":null,
                "system_id":"1"
            }
                
            ,    {
                "game_id":"2",
                "game_title":"3 Ninjas Kick Back",
                "game_desc":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ultrices, nulla vel venenatis euismod, quam dui auctor enim, ut egestas risus enim et felis.",
                "game_developer":null,
                "game_type_id":null,
                "system_id":"1"
            }
                
            ,    {
                "game_id":"3",
                "game_title":"3x3 Eyes - Seima Kourinden",
                "game_desc":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ultrices, nulla vel venenatis euismod, quam dui auctor enim, ut egestas risus enim et felis.",
                "game_developer":null,
                "game_type_id":null,
                "system_id":"1"
            }
        ];

        $scope.getData = function() {
            /*
            var initInjector = angular.injector(["ng"]);
            var $http = initInjector.get("$http");
            
            return $http.get("http://beecoapp.com/ws-game/?service=game&action=list").then(function(response) {
                //myApplication.constant("config", response.data);
                console.log(response.data);
            }, function(errorResponse) {
                // Handle error case
            });
            */
            console.log("teste");
        };
     }]);
})();