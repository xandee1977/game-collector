/**
* GameModel - loads games data from Webservice
*/
var GameModel = function(modelName, email, autorun, WS) {
    // params Default Values
    autorun = typeof autorun !== 'undefined' ? autorun : true;
    this.WS = typeof WS !== 'undefined' ? WS : window.WebService;      

    this.service = "game";
    this.action = "list";
    this.params = {};
    this.jsonData = {};

    // Load DaseModel
    BaseModel.call(this, modelName, this.service, this.action, this.params, this.jsonData);
}