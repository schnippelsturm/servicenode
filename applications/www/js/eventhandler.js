
function onIDClick() {
    
    
}



function addEvent(element, event, funct) {
    var result = false;
    if (element.attachEvent) {
        result = element.attachEvent('on' + event, funct);
    } else {
        result = element.addEventListener(event, funct, false);
    }
    return result;
}



