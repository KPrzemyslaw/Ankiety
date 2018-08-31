"use strict";
/**
 * @author KPrzemyslaw Przemysław Kotlarz
 * @package KPAjax
 *
 * With help ...
 * https://xhr.spec.whatwg.org/
 *
 * @param confObj = {
 * 	method	- POST, GET, etc.
 * 	url		-
 *  async	-
 * }
 *
 * Events:
 * 	onnotinitialized();
 * 	onconnectionestablished();
 * 	onrequestreceived();
 * 	onprocess();
 * 	oncompleted(responseBody, status);
 * 	onsuccess(responseBody);
 * 	onerror(responseBody, status);
 * 	onabort();
 */

function KPTemplates(xmlText, confObj) {

    var privateObj = {
    };

    var publicObj = {
        getTemplate: function(id, asObjects) {
            var xml = document.createElement('div');
            xml.innerHTML = xmlText;

            var items = xml.getElementsByTagName('htmltemplate');
            for(var i=0; i < items.length; i++) {
                if(items[i].getAttribute('id') == id) {
                    if((typeof asObjects == 'undefined') || (asObjects === true)) {
                        return this.contentAsObjects(items[i].innerHTML);
                    }
                    return items[i].innerHTML;
                }
            }

            return null;
        },

        contentAsObjects: function(contontentHTML) {
            var div = document.createElement('div');
            div.innerHTML = contontentHTML;
            return div.children;
        },

        appendTemplateTo: function(id, destObject, atBegin, onlyOne) {
            if(Object.prototype.toString.call(destObject) == '[object Null]') {
                console.log('On append \''+id+'\' template, destObject is not object!');
                return null;
            }

            var list = this.getTemplate(id), result = [], onlyOneIsFounded;
            for(var i=0; i<list.length; i++) {
                onlyOneIsFounded = false;
                if(onlyOne === true) {
                    var destObjChildList = destObject.getElementsByTagName('*');
                    for(var j=0; j<destObjChildList.length; j++) {
                        if(destObjChildList[j].innerHTML === list[i].innerHTML) {
                            onlyOneIsFounded = true;
                        }
                    }
                }
                if(!onlyOneIsFounded) {
                    result.push(list[i]);
                    if((typeof atBegin != 'undefined') && (atBegin === true)) {
                        destObject.insertBefore(list[i], destObject.firstChild);
                    } else {
                        destObject.appendChild(list[i]);
                    }
                }
            }
            return result;
        }
    };

    return publicObj;
}


