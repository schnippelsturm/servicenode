function Request(aurl,apayload,amethod) {
    var url=aurl;
    var method=amethod;
    var payload=apayload;
   
    this.getUrl=function () {
        return this.url;
    }
   
    this.getMethod=function () {
        return this.method;
    }
   
    this.getPayload=function () {
        return this.payload;
    }
}


