(function(){
    var app = angular.module('store', []);
    app.controller('StoreController', function(){
        this.product = gem;
    });

    var gem = {
        name: 'Dodecaedro',
        price: 2.95,
        description: 'Este é um dodecaedro.'
    }

})();