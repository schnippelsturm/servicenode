function XMLReader() {
  
    this.getDoc = function (xmlstring) {
        xmlDoc=null;
        if (window.DOMParser)
        {
            parser = new DOMParser();
            xmlDoc = parser.parseFromString(xmlstring, "text/xml");
        }
        else // Internet Explorer
        {
            xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
            xmlDoc.async = false;
            xmlDoc.loadXML(xmlstring);
        }
       return(xmlDoc);
    };
    
    
    this.getElement=function(xmldoc,elementname) {
      //  xmldoc.
          xmldoc.async=false;
    };
    

}