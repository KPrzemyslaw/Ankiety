"use strict";
/**
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */

/**
 * To do this in any ES5-compatible environment, such as Node, Chrome, IE 9+, FF 4+, or Safari 5+:
 */
if(!Object.keys) {
    Object.keys = function (obj) {
        var keys = [], k;
        for (k in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, k)) {
                keys.push(k);
            }
        }
        return keys;
    };
}

var KP = {
    getTimestamp: function() {
        if (!Date.now) {
            return new Date().getTime();
        }
        return Date.now();
    },

    sessionData: {},

    loadScriptCounter: 0,

    templates: null,

    /**
     * includeJSWithObject: function(jsPath);
     * includeJS: function(jsPath, async, callback);
     * includeCSS: function(cssPath, callback);
     */
    loadScript: function(type, attributes, callback) {
        this.loadScriptCounter++;

        var s = document.createElement(type);
        for(var a in attributes) {
            s[a] = attributes[a];
        }

        var tthis = this;
        if(s.onreadystatechange === null) {
            s.onreadystatechange = function(){
                if(this.readyState == 'complete' || this.readyState == 'loaded') {
                    tthis.loadScriptCounter--;
                    if(!tthis.loadScriptCounter) {
                        tthis.onLoadScript();
                    }
                }
                if(typeof callback == 'function') {
                    callback(this.readyState, this.status);
                }
            };
        }
        else if(s.onload === null) {
            s.onload = function(){
                tthis.loadScriptCounter--;
                if(!tthis.loadScriptCounter) {
                    tthis.onLoadScript();
                }
                if(typeof callback == 'function') {
                    callback(4, 200);
                }
            };
            s.onerror = function() {
                if(typeof callback == 'function') {
                    callback(0, 500 /*, this.src*/);
                }
            };
        }

        document.getElementsByTagName('head')[0].appendChild(s);
    },

    onLoadScript: function(){},

    loadApplication: function(bundle, callback) {
        var tthis = this;
        this.loadScript('link', {href: 'css/default.css?t='+tthis.appVersion, 'type': 'text/css', 'rel': 'stylesheet'});
        this.loadScript('script', {src: 'js/kpcookie.js?t='+tthis.appVersion, 'type': 'text/javascript'});
        this.loadScript('script', {src: 'js/kpform.js?t='+tthis.appVersion, 'type': 'text/javascript'});
        this.loadScript('script', {src: 'js/kptemplates.js?t='+tthis.appVersion, 'type': 'text/javascript'}, function(){
            tthis.loadScript('script', {src: 'js/kpajax.js?t='+tthis.appVersion, 'type': 'text/javascript'}, function(readyState, status) {
                tthis.loadScript('link', {href: 'css/'+bundle+'.css?t='+tthis.appVersion, 'type': 'text/css', 'rel': 'stylesheet'});
                tthis.loadScript('script', {src: 'js/'+bundle+'.js?t='+tthis.appVersion, 'type': 'text/javascript'}, function(readyState, status) {
                    var ajax = new KPAjax({
                        url: './html/'+bundle+'.html?t='+tthis.appVersion,
                        method: 'post',
                        contentEncoding: 'gzip',
                        oncompleted: function(data, status){
                            if(status == 200) {
                                tthis.templates = new KPTemplates(data);
                            }
                            if(typeof callback == 'function') {
                                callback(data, status);
                            }
                        }
                    });
                    ajax.start();
                });
            });
        });
    },

    iterate: function(collection, callback) {
        if(Object.prototype.toString.call(collection) == '[object Null]') {
            console.log('collection is not object!');
            return null;
        }

        for(var i=0; i<collection.length; i++) {
            callback(collection[i]);
        }
    },

    findParent: function(startObj, ifCallback) {
        if(startObj) {
            var ifResult = false, iterator = 0;
            do {
                startObj = startObj.parentNode;
                ifResult = ifCallback(startObj, iterator++);
            } while(!ifResult);
        } else {
            startObj = null;
        }

        return startObj;
    },

    removeCollection: function(collection) {
        while(collection.length) {
            collection[collection.length-1].parentNode.removeChild(collection[collection.length-1]);
        }
    },

    getAppVersion: function() {
        var script = document.getElementById('text_javascript_kp');
        if(script) {
            var url = new URL(script.src);
            return url.searchParams.get('t');
        }
        return '';
    }
};

KP.appVersion = KP.getAppVersion();
