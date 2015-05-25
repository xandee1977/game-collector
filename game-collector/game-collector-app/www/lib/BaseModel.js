/* BaseModel */
var BaseModel = function(modelName, service, action, queryParams, jsonData, autorun, WS) {
    // params Default Values
    this.jsonData = typeof jsonData !== 'undefined' ? jsonData : {};
    autorun = typeof autorun !== 'undefined' ? autorun : true;
    this.WS = typeof WS !== 'undefined' ? WS : window.WebService;    

    this.modelName = modelName; // Model Name
    this.service = service;
    this.action = action;
    this.queryParams = queryParams; // QueryString params
    
    this.modelData = {}; // data loaded

    this.loadData = function(context) {
        console.log("Running baseModel loadData ...");
        this.WS.doRequest(this.service, this.action, this.queryParams, function(data){ context.successCallBack(data) });
    };

    this.successCallBack = function(response) {
        console.log("BaseModel Success!");
        this.modelData = response.data;
        if(typeof(window[this.modelName + "OnSuccess"]) == "undefined") {
            console.log("Please implement " + this.modelName + "OnSuccess function.")
        } else {
            window[this.modelName + "OnSuccess"](this.modelData);
        }
    };
    
    if(autorun) {
        console.log("Autorun mode ON...")
        this.loadData(this);    
    }
}