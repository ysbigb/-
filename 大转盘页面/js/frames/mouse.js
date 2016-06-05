/**
 * 地鼠动画帧
 */
var getMouseFrames = (function() {

    var frames = {
        mouse1 : [{
            x : 0,
            y : 0
        }, {
            x : 114,
            y : 0
        }, {
            x : 228,
            y : 0
        }, {
            x : 342,
            y : 0
        }],
        mouse2 : [{
            x : 0,
            y : 142
        }, {
            x : 114,
            y : 142
        }, {
            x : 228,
            y : 142
        }, {
            x : 342,
            y : 142
        }],  
	    mouse3 : [{
            x : 0,
            y : 284
        }, {
            x : 114,
            y : 284
        }, {
            x : 228,
            y : 284
        }, {
            x : 342,
            y : 284
        }],
	    mouse4 : [{
            x : 0,
            y : 426
        }, {
            x : 114,
            y : 426
        }, {
            x : 228,
            y : 426
        }, {
            x : 342,
            y : 426
        }],
	    mouse5 : [{
            x : 0,
            y : 568
        }, {
            x : 114,
            y : 568
        }, {
            x : 228,
            y : 568
        }, {
            x : 342,
            y : 568
        }]      
    }

    /**
     * @param {String} animName
     */
    return function(animName) {
        return frames[animName];
    }
})();
