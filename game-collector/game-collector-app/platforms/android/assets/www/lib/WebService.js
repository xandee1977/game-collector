var WebService = {
    WS_URL : "http://beecoapp.com/ws-game", // URL
    JQ: window.$, // Jquery
    doRequest : function(service, action, params, successCallback, jsonData, errorCallback, completeCallback) {
        // params Default Values
        jsonData = typeof jsonData !== 'undefined' ? jsonData : {};
        errorCallback = typeof errorCallback !== 'undefined' ? errorCallback : null;
        completeCallback = typeof completeCallback !== 'undefined' ? completeCallback : null;

        jsonData={}, errorCallback=null, completeCallback=null

        console.log("doRequest");
        var postObject = this.getPostObject(service, action, params);
        this.JQ.ajax(postObject);
        this.ws_success = successCallback;
        if(errorCallback) {
            this.ws_error = errorCallback;            
        }
        if(completeCallback){
            this.ws_complete = completeCallback;            
        }
    },

    getPostObject : function(service, action, params) {
        console.log("getPostObject");
        var queryStringParam = this.paramToQS(params);
        console.log(queryStringParam);
        var result = {
            url: this.WS_URL + "/?service=" + service + "&action=" + action,
            data: queryStringParam,
            dataType: "json",
            success: function(data){
                window.WebService.ws_success(data);
            },
            error: function(e){
                window.WebService.ws_error(e);
            },
            complete: function(data){
                window.WebService.ws_complete(data);
            }
        }
        return result;
    },

    // param to queryString
    paramToQS : function(paramBoject) {
        var result = decodeURIComponent(this.JQ.param(paramBoject));
        return result;
    },

    /*
    Implement these functions on Model
    */
    ws_success : function(data) {
        console.log("ws_success");
        //console.log(data);
    },

    ws_error : function(e, status, error) {
        console.log("ws_error");
        console.log(e.status);
    },

    ws_complete : function(data) {
        console.log("ws_complete");
        //console.log(data);
        //console.log(data);
    }
}