
function Questionnaire(containerHtml) {

    var publicObject = {
        container: containerHtml,
        currentPage: 0,
        data: null
    };

    publicObject.setName = function(name) {


        KP.iterate(
            this.container.getElementsByClassName('strona-ankieta-title'),
            function(obj){
                obj.innerHTML = name;
            }
        );
    };

    publicObject.createInput = function(numPage, lineId, inputName, lineNum, typeNum, label, attributes) {
        var elemId = null, attrNum = 0,
            inp = document.createElement('input'),
            div = document.createElement('div'),
            inputValue = ((typeof attributes['value'] != 'undefined') ? attributes['value'] : null)
        ;
        div.className = 'kpfloatleft';
        inp.value = (attributes['type'] == 'checkbox') ? (label.length ? label : 'No name field') : inputValue;

        for(var attr in attributes) {
            inp[attr] = attributes[attr];
            if (attr == 'id') {
                elemId = attr;
            }
            if (attr == 'name') {
                inputName = attributes[attr];
            }
            attrNum++;
        }

        if(elemId) {
            inp.id = elemId;
        } else {
            inp.id = 'page_'+lineNum+'_'+numPage+'_'+typeNum+'_'+attrNum+'_';
        }

        if(inputName) {
            inp.name = inputName;
        } else {
            inp.name = inp.id;
        }
        inp.setAttribute('real-name', lineId)

        div.appendChild(inp);
        if(label.length) {
            var labelObj = document.createElement('label')
            labelObj.setAttribute('for', inp.id);
            labelObj.innerHTML = label;
            div.appendChild(labelObj);
        }

        return div;
    };

    publicObject.createTextarea = function(numPage, lineId, inputName, lineNum, typeNum, label, attributes) {
        var elemId = null, attrNum = 0,
            textarea = document.createElement('textarea'),
            div = document.createElement('div'),
            inputValue = ((typeof attributes['value'] != 'undefined') ? attributes['value'] : null)
        ;
        textarea.value = inputValue;
        textarea.innerText = inputValue;


        div.className = 'kpfloatleft';
        for(var attr in attributes) {
            textarea[attr] = attributes[attr];
            if (attr == 'id') {
                elemId = attr;
            }
            if (attr == 'name') {
                inputName = attributes[attr];
            }
            attrNum++;
        }

        if(elemId) {
            textarea.id = elemId;
        } else {
            textarea.id = 'page_'+lineNum+'_'+numPage+'_'+typeNum+'_'+attrNum+'_';
        }

        var namePrefix = 'page_'+numPage+'_';
        if(inputName) {
            textarea.name = namePrefix+inputName;
        } else {
            textarea.name = namePrefix+textarea.id;
        }
        textarea.setAttribute('real-name', lineId);

        div.appendChild(textarea);
        if(label.length) {
            var labelObj = document.createElement('label');
            labelObj.setAttribute('for', textarea.id);
            labelObj.innerHTML = label;
            div.appendChild(labelObj);
        }

        return div;
    };

    publicObject.createLine = function(numPage, lineNum, data) {
        var
            div = document.createElement('div'),
            fields = document.createElement('div'),
            required = (data.required != 0)
        ;

        div.className = 'kpcontainer kp100pr strona-ankieta-lines-line';

        if(data.name.length) {
            var title = document.createElement('div');
            title.className = 'kpcontainer kp100pr strona-ankieta-lines-line-title';
            title.innerHTML = data.name;
            div.appendChild(title);
        }

        fields.className = 'kpcontainer kpauto strona-ankieta-lines-line-fields';

        if(Object.prototype.toString.call(data.fields) === '[object Array]') {
            for(var f=0; f<data.fields.length; f++) {
                var
                    field = null,
                    type = data.fields[f].type,
                    attrs = {}
                ;

                try {
                    var attrsJson = JSON.parse(data.fields[f].params);
                    if(Object.prototype.toString.call(attrsJson) === '[object Object]') {
                        attrs = attrsJson
                    }
                } catch(e) {}


                if(type == 'other') {
                    type = 'label';
                }

                if(type == 'textarea') {
                    field = this.createTextarea(numPage, data.id, null, lineNum, f, data.fields[f].name, attrs);
                } else /*if(type == 'radio' || type == 'label' || type == 'checkbox')*/ {
                    attrs['type'] = type;
                    field = this.createInput(numPage, data.id, null, lineNum, f, data.fields[f].name, attrs);

                }

                /**
                 * Prawdopodobnie radio musi być zawsze wymagalne
                 *
                if(type == 'radio') {
                    required = true;
                }*/

                if(field) {
                    field.className = field.className + ' strona-ankieta-lines-line-fields-field';
                    fields.appendChild(field);
                }
            }
        }

        if(required) {
            var requiredElem = document.createElement('div');
            requiredElem.className = 'kpfloatleft kprequired';
            requiredElem.innerHTML = '<sup>*</sup>';
            fields.appendChild(requiredElem);
        }

        div.appendChild(fields);

        return div;
    };

    publicObject.createPage = function(numPage, data) {
        var tthis = this;
        var elems = KP.templates.appendTemplateTo('strona', this.container);

        KP.iterate(
            elems,
            function(strona){
                KP.iterate(
                    strona.getElementsByClassName('strona-ankieta-title'),
                    function(obj){
                        obj.innerHTML = data.title;
                    }
                );
                KP.iterate(
                    strona.getElementsByClassName('strona-ankieta-name'),
                    function(obj){
                        obj.innerHTML = data.name;
                    }
                );
                KP.iterate(
                    strona.getElementsByClassName('strona-ankieta-begin'),
                    function(obj){
                        obj.innerHTML = data.desc_begin;
                    }
                );
                KP.iterate(
                    strona.getElementsByClassName('strona-ankieta-ending'),
                    function(obj){
                        obj.innerHTML = data.desc_end;
                    }
                );
                KP.iterate(
                    strona.getElementsByClassName('strona-ankieta-lines'),
                    function(obj){
                        if(Object.prototype.toString.call(data.lines) === '[object Array]') {
                            for(var i=0; i<data.lines.length; i++) {
                                var line = tthis.createLine(numPage, i, data.lines[i]);
                                obj.appendChild(line);
                            }
                        }
                    }
                );
            }
        );
    };

    publicObject.createButtons = function(onclick) {
        var
            button = document.createElement('button'),
            hr = document.createElement('hr'),
            div = document.createElement('div');
        ;

        div.className = 'kpcontainer kp100pr';
        div.appendChild(hr);

        button.setAttribute('type', 'button');
        button.innerHTML = 'Submit and go to next page';
        button.onclick = onclick;
        button.className = 'kp-submit-and-go-to-next-page';

        div.appendChild(button);
        this.container.appendChild(div);

        var buttonPosition = function() {
            var pixelLeft = (div.offsetWidth/2) - (button.offsetWidth/2);
            button.style.marginLeft = pixelLeft+'px';
        };
        window.addEventListener('resize', buttonPosition);
        buttonPosition();
    };

    publicObject.startWithPage = function(pageNo) {
        var
            pages = this.container.getElementsByClassName('strona')
        ;

        if(pageNo < 0) {pageNo = 0;}

        if((pageNo >= 0) && (pageNo < pages.length)) {
            this.currentPage = pageNo;
            pages[pageNo].style.display = 'block';
            return pages[pageNo];
        }

        return null;
    };

    publicObject.incrementPage = function(inc) {
        var
            pages = this.container.getElementsByClassName('strona'),
            nextCurrent = this.currentPage + inc;
        ;

        var t = ((nextCurrent >= 0) && (nextCurrent < pages.length));
        console.log(t);
        if((nextCurrent >= 0) && (nextCurrent < pages.length)) {
            this.currentPage = nextCurrent;
            for(var i=0; i<pages.length; i++) {
                pages[i].style.display = ((i == nextCurrent) ? 'block' : 'none');
            }

            return nextCurrent;
        }

        return -1;
    };

    publicObject.createPageExtra = function(data) {
        var elems = KP.templates.appendTemplateTo('strona', this.container);

        KP.iterate(
            elems,
            function(strona){
                for(var clName in data) {
                    KP.iterate(
                        strona.getElementsByClassName(clName),
                        function(obj){
                            obj.innerHTML = data[clName];
                        }
                    );
                }
            }
        );
    };

    publicObject.getPageHtmlElement = function(){
        var
            pages = this.container.getElementsByClassName('strona')
        ;
        return pages[this.currentPage];
    };

    publicObject.collectData = function(){
        var pageHtml = this.getPageHtmlElement();
        var formTool = new KPForm();
        this.data = formTool.getFormResults(pageHtml);
    };

    publicObject.sendData = function(callback){
        var localData = this.data;
        localData['__page_number'] = this.currentPage;

        var ajax = new KPAjax({
            url: './php/questionnaire/dataSave',
            method: 'post',
            contentEncoding: 'gzip',
            contentType: 'form',
            //accept: 'json',
            data: localData,
            //onsuccess: function(data){
            //    if(typeof callback == 'function') {
            //        callback(data, 200);
            //    }
            //},
            oncompleted: function(data, status){
                if(typeof callback == 'function') {
                    callback(data, status);
                }
            }
        });
        ajax.start();
    };

    return publicObject;
}
