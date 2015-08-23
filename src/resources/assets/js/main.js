// A function for outputting to the console
var debug = function(message, type, object) {

    // Setup some default values
    enable_debug = typeof enable_debug !== 'undefined' ? enable_debug : false;
    object = typeof object !== 'undefined' ? object : false;
    message = typeof message !== 'undefined' ? message : 'Debug';
    type = typeof type !== 'undefined' ? type : false;


    // If a cookie called 'debug' is set, then all messages will be displayed
    var getDebugCookie = function() {
        var value = "; " + document.cookie;
        var parts = value.split("; debug=");

        if (parts.length == 2) {
            return true;
        } else {
            return false;
        }
    }
    var cookie_overide = getDebugCookie();


    // Check if debugging is enabled
    if (enable_debug != false || cookie_overide) {

        if (enable_debug.all != false || cookie_overide) {

            // Check if type of message is enabled
            if ( (type != false && enable_debug[type] != false) || (type == false && enable_debug.typeless == true) || cookie_overide) {

                // Do we wrap object in details?

                if (type != false) {

                    if (object) {
                        console.log(type, '"' + message + '"', object);
                    } else {
                        console.log(type, '"' + message + '"');
                    }

                } else {

                    if (object) {
                        console.log(message, object);
                    } else {
                        console.log(message);
                    }
                }

            }
        }
    }
}


// Add a spinner to be displayed beween loading states
var opts = {
    lines: 17, // The number of lines to draw
    length: 0, // The length of each line
    width: 22, // The line thickness
    radius: 84, // The radius of the inner circle
    scale: 1, // Scales overall size of the spinner
    corners: 0, // Corner roundness (0..1)
    color: '#ccc', // #rgb or #rrggbb or array of colors
    opacity: 0.05, // Opacity of the lines
    rotate: 0, // The rotation offset
    direction: 1, // 1: clockwise, -1: counterclockwise
    speed: 1, // Rounds per second
    trail: 30, // Afterglow percentage
    fps: 20, // Frames per second when using setTimeout() as a fallback for CSS
    zIndex: 2e9, // The z-index (defaults to 2000000000)
    className: 'spinner', // The CSS class to assign to the spinner
    top: '50%', // Top position relative to parent
    left: '50%', // Left position relative to parent
    shadow: false, // Whether to render a shadow
    hwaccel: false, // Whether to use hardware acceleration
    position: 'absolute' // Element positioning
}

document.addEventListener("DOMContentLoaded", function(event) {
    new Spinner(opts).spin(document.getElementById('state-transition-spinner'));
});


// Extend the String object to support truncating strings with '...' the end
String.prototype.trunc = String.prototype.trunc || function(n){
    if (this.length>n){
        return this.substr(0,n-1)+'&ellipsis;';
    } else {
        return this.toString();
    }
}