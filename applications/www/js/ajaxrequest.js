function AjaxRquest(arequest) {
    var request = arequest;
    var response;
    var xmlhttpReq = null;

    this.getResponse = function () {
        return this.response;
    };

    this.getMSXMLHTTP = function () {
        var xmlHttp = null;
        try {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            try {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                xmlHttp = null;
            }
        }
        return(xmlHttp);
    };



    this.getXMLHTTPRequest = function () {
        try {
            xmlHttp = new XMLHttpRequest();
        } catch (e) {
            xmlHttp = this.getMSXMLHTTP();
        }
        if (xmlhttp) {
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    this.response = xmlhttp.responseXML;
                }
            };
            return xmlhttp;
        }
       return null;
    };

    this.doRequest = function () {
        this.xmlhttpReq = this.getXMLHTTPRequest();
        if ((this.xmlhttpReq !== null) && (this.request !== null)) {
            this.xmlhttpReq.open(this.request.getMethod(), this.request.getUrl(), true);
            this.xmlhttpReq.send(this.request.getPayload());
        }
    };



}