function QuestionnaireFieldsEditLine(container) {
    var publicObject = {
        'page': null,
        'line': null,
        'container': container,

        'elemsList': null,

        'callbackAfterOpenAll': null,

        'open': function(data, inEditTemplate, afterObject, withoutEditButtonsOfFields) {
            var
                tthis = this,
                filedsInLine,
                required = parseInt(data.required)
            ;
            required = (isNaN(required) ? 0 : required);

            if(inEditTemplate === true) {
                this.elemsList = KP.templates.appendTemplateTo('ankiety-edycja-fields-edycja', this.container);
                KP.iterate(
                    this.elemsList,
                    function(elem){

                        elem.setAttribute('line_no', data.id);
                        var
                            // Save line
                            buttonsSaveField = tthis.page.getElementsByClassName('kp-questionnaire-edit-page-saveNewFieldButton'),
                            buttonsCancelField = tthis.page.getElementsByClassName('kp-questionnaire-edit-page-cancelNewFieldButton'),
                            fieldsEditField = new QuestionnaireFieldsEditField(elem, withoutEditButtonsOfFields)
                        ;
                        KP.iterate(
                            buttonsSaveField,
                            function(button){
                                button.onclick = function() {
                                    tthis.save();

                                    var fieldsData = [];
                                    fieldsEditField.save(fieldsData);

                                    tthis.cancel(this);
                                }
                            });
                        KP.iterate(
                            buttonsCancelField,
                            function(button){
                                button.onclick = function() {
                                    tthis.edit(elem.getAttribute('line_no'), button, false);
                                }
                            });

                        KP.iterate(
                            elem.getElementsByClassName('ankiety-edycja-fields_main_description'),
                            function(fmd){
                                if (fmd.tagName.toLowerCase() == 'input') {
                                    fmd.value = data.name;
                                } else {
                                    fmd.innerHTML = data.name;
                                }
                            });

                        KP.iterate(
                            elem.getElementsByClassName('ankiety-edycja-fields_main_required'),
                            function(fmd){
                                var req = (required != 0);
                                if (fmd.tagName.toLowerCase() == 'input') {
                                    fmd.checked = req;
                                } else {
                                    fmd.innerHTML = 'Pola '+(req ? '' : 'nie ')+'są obowiązkowe';
                                }
                            });

                        if(afterObject instanceof HTMLElement) {
                            afterObject.parentNode.insertBefore(elem, afterObject.nextSibling);
                            afterObject.parentNode.removeChild(afterObject);

                            var buttonsEditFieldList = tthis.container.getElementsByClassName('kp-questionnaire-edit-page-editNewFieldButton');
                            KP.iterate(
                                buttonsEditFieldList,
                                function(buttonsEditField){
                                    buttonsEditField.style.display = 'none';
                                });
                        }

                        filedsInLine = new QuestionnaireFieldsEditField(elem, withoutEditButtonsOfFields);
                        tthis.line = elem;
                    });
            } else {
                this.elemsList = KP.templates.appendTemplateTo('ankiety-edycja-fields', this.container);

                var tthis = this;

                KP.iterate(
                    this.elemsList,
                    function(elem){
                        var
                            buttonsEditField = elem.getElementsByClassName('kp-questionnaire-edit-page-editNewFieldButton'),
                            buttonsAddField = elem.getElementsByClassName('kp-questionnaire-edit-page-addNewFieldButton'),
                            buttonsRemoveField = tthis.page.getElementsByClassName('kp-questionnaire-edit-page-removeNewFieldButton'),
                            fieldsEditField = new QuestionnaireFieldsEditField(elem, withoutEditButtonsOfFields)
                        ;

                        elem.setAttribute('line_no', data.id);

                        KP.iterate(
                            buttonsAddField,
                            function(button){
                                button.onclick = function() {
                                    var currentLine = KP.findParent(this, function(obj, i){
                                        return (obj.tagName.toLowerCase() == 'fieldset' && obj.className.indexOf('ankiety-edycja-fields') >= 0);
                                    });
                                    fieldsEditField.save({
                                        'line_no': currentLine.getAttribute('line_no')
                                    });
                                }
                            });
                        KP.iterate(
                            buttonsEditField,
                            function(button){
                                button.onclick = function() {
                                    var currentLine = KP.findParent(this, function(obj, i){
                                        return (obj.tagName.toLowerCase() == 'fieldset' && obj.className.indexOf('ankiety-edycja-fields') >= 0);
                                    });
                                    tthis.edit(currentLine.getAttribute('line_no'), this, true);
                                }
                            });
                        KP.iterate(
                            buttonsRemoveField,
                            function(button){
                                button.onclick = function() {
                                    var
                                        buttonObject = this,
                                        mess = 'Czy na pewno usunąć tę linię i wszystkie jej pola?'
                                    ;
                                    if(confirm(mess)) {
                                        var currentLine = KP.findParent(this, function(obj, i){
                                            return (obj.tagName.toLowerCase() == 'fieldset' && obj.className.indexOf('ankiety-edycja-fields') >= 0);
                                        });
                                        tthis.remove(currentLine.getAttribute('line_no'), this, function(data, status){
                                            if((status == 200) && data.id) {
                                                currentLine.parentNode.removeChild(currentLine);
                                            } else {
                                                window.alert('Problem z usunięciem rekordu');
                                            }
                                        });
                                    }
                                }
                            });

                        KP.iterate(
                            elem.getElementsByClassName('ankiety-edycja-fields_main_description'),
                            function(fmd){
                                if (fmd.tagName.toLowerCase() == 'input') {
                                    fmd.value = data.name;
                                } else {
                                    fmd.innerHTML = data.name;
                                }
                            });

                        KP.iterate(
                            elem.getElementsByClassName('ankiety-edycja-fields_main_required'),
                            function(fmd){
                                var req = (required != 0);
                                if (fmd.tagName.toLowerCase() == 'input') {
                                    fmd.checked = req;
                                } else {
                                    fmd.innerHTML = 'Pola '+(req ? '' : 'nie ')+'są obowiązkowe';
                                }
                            });

                        if(afterObject instanceof HTMLElement) {
                            afterObject.parentNode.insertBefore(elem, afterObject.nextSibling);
                            afterObject.parentNode.removeChild(afterObject);

                            var buttonsEditFieldList = tthis.container.getElementsByClassName('kp-questionnaire-edit-page-editNewFieldButton');
                            KP.iterate(
                                buttonsEditFieldList,
                                function(buttonsEditField){
                                    buttonsEditField.style.display = 'block';
                                });
                        }

                        filedsInLine = new QuestionnaireFieldsEditField(elem, withoutEditButtonsOfFields);
                        tthis.line = null;
                    });
            }

            filedsInLine.openAll(inEditTemplate, tthis.callbackAfterOpenAll);


            var buttonsContainer = this.page.getElementsByClassName('ankiety-edycja-fieldsList-buttons');
            KP.iterate(
                buttonsContainer,
                function(bContainer) {
                    bContainer.style.display = ((inEditTemplate === false) ? 'none' : 'block');
                }
            );

        },

        'getData': function(lineId, callback) {

            var
                tthis = this,
                localData = {
                    'id': lineId
                };

            var ajax = new KPAjax({
                url: './php/admin/questionnairePageLine/getData',
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
        },

        'edit': function(lineId, buttonObject, inEditTemplate) {
            var tthis = this;

            this.getData(lineId, function(jsonData, status) {
                if(status == 200) {
                    var currentLine = KP.findParent(buttonObject, function(obj, i){
                        return (obj.tagName.toLowerCase() == 'fieldset' && obj.className.indexOf('ankiety-edycja-fields') >= 0);
                    });
                    tthis.open(JSON.parse(jsonData), inEditTemplate, currentLine);
                }
            })
        },

        'save': function(buttonObject, callback) {
            var
                tthis = this,
                localData = {
                    'id': 0,
                    'page_id': this.page.getAttribute('page_no')
                };
            if(this.line) {
                localData.id = this.line.getAttribute('line_no');
                KP.iterate(
                    this.line.getElementsByClassName('ankiety-edycja-fields_main_description'),
                    function(fmd){
                        if(fmd.tagName.toLowerCase() == 'input') {
                            localData.name = fmd.value
                        }
                    });
            }

            var ajax = new KPAjax({
                url: './php/admin/questionnairePageLine/save',
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
        },

        'cancel': function(buttonObject) {
            this.edit(this.line.getAttribute('line_no'), buttonObject, false);
        },

        'remove': function(lineId, buttonObject, callback) {
            var
                tthis = this,
                localData = {
                    'id': lineId,
                    'page_id': this.page.getAttribute('page_no')
                };

            var ajax = new KPAjax({
                url: './php/admin/questionnairePageLine/remove',
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
                oncompleted: function(jsonData, status){
                    if(typeof callback == 'function') {
                        var data = {id:0};
                        try {
                            data = JSON.parse(jsonData);
                        } catch(e) {}
                        callback(data, status);
                    }
                }
            });
            ajax.start();
        },

        'setButtons': function() {
            var
                tthis = this,
                buttonsAddLine = this.page.getElementsByClassName('kp-questionnaire-edit-page-editNewFieldLineButton')
            ;

            KP.iterate(
                buttonsAddLine,
                function(button){
                    button.onclick = function() {

                        tthis.save(button, function(jsonData, status) {
                            tthis.open(JSON.parse(jsonData));
                        });
                    }
                });
        }
    };

    return publicObject;
}
