function QuestionnaireFieldsEditField(lineObject, withoutEditButtons) {

    var publicObject = {
        'container': lineObject.getElementsByClassName('ankiety-edycja-fieldsList')[0],
        'line': lineObject,
        'field': null,

        'elemsList': null,

        'open': function(data, inEditTemplate, afterObject) {
            var tthis = this;

            if(inEditTemplate === true) {
                this.elemsList = KP.templates.appendTemplateTo('ankiety-edycja-fields-field-edit', this.container);
                KP.iterate(
                    this.elemsList,
                    function(elem){

                        elem.setAttribute('field_no', data.id);
                        var
                            // Save field
                            buttonsSaveField = elem.getElementsByClassName('kp-questionnaire-edit-page-line-field-saveFieldButton'),
                            buttonsCancelField = elem.getElementsByClassName('kp-questionnaire-edit-page-line-field-cancelFieldButton')
                        ;
                        KP.iterate(
                            buttonsSaveField,
                            function(button){
                                if(withoutEditButtons === true) {
                                    button.parentNode.removeChild(button);
                                } else {
                                    button.onclick = function() {
                                        tthis.save({
                                            'id': elem.getAttribute('field_no'),
                                            'name': document.getElementById('ankiety-edycja-fields-field_main_description').value,
                                            'type': document.getElementById('ankiety-edycja-fields-field_main_type').value,
                                            'params': tthis.getParams()
                                        }, this);
                                        tthis.cancel(this);
                                    }
                                }
                            });
                        KP.iterate(
                            buttonsCancelField,
                            function(button){
                                if(withoutEditButtons === true) {
                                    button.parentNode.removeChild(button);
                                } else {
                                    button.onclick = function () {
                                        tthis.edit(elem.getAttribute('field_no'), button, false);
                                    }
                                }
                            });

                        var fmdList = elem.getElementsByClassName('ankiety-edycja-fields-field_main_description');
                        KP.iterate(
                            fmdList,
                            function(fmd){
                                if (fmd.tagName.toLowerCase() == 'input') {
                                    fmd.value = data.name;
                                } else {
                                    fmd.innerHTML = data.name;
                                }
                            });

                        var fmtList = elem.getElementsByClassName('ankiety-edycja-fields-field_main_type');
                        KP.iterate(
                            fmtList,
                            function(fmt){
                                if (fmt.tagName.toLowerCase() == 'select') {
                                    fmt.value = data.type;
                                } else {
                                    fmt.innerHTML = data.type;
                                }
                            });
                        var fmtpList = elem.getElementsByClassName('ankiety-edycja-fields-field_main_type_params_new');
                        KP.iterate(
                            fmtpList,
                            function(fmtp){
                                fmtp.onclick = function() {
                                    tthis.addParameter(elem.getAttribute('field_no'));
                                };
                            });

                        try {
                            var params = JSON.parse(data.params);
                            for(var key in params) {
                                tthis.addParameter(elem.getAttribute('field_no'), key, params[key]);
                            }
                        } catch (e) {}

                        if(afterObject instanceof HTMLElement) {
                            afterObject.parentNode.insertBefore(elem, afterObject.nextSibling);
                            afterObject.parentNode.removeChild(afterObject);

                            var buttonsEditFieldList = tthis.container.getElementsByClassName('kp-questionnaire-edit-page-line-field-editFieldButton');
                            KP.iterate(
                                buttonsEditFieldList,
                                function(buttonsEditField){
                                    buttonsEditField.style.display = 'none';
                                });
                        }

                        tthis.field = elem;
                    });
            } else {
                this.elemsList = KP.templates.appendTemplateTo('ankiety-edycja-fields-field', this.container);

                var tthis = this;

                KP.iterate(
                    this.elemsList,
                    function(elem){
                        var
                            buttonsRemoveField = elem.getElementsByClassName('kp-questionnaire-edit-page-line-field-removeFieldButton'),
                            buttonsEditField = elem.getElementsByClassName('kp-questionnaire-edit-page-line-field-editFieldButton'),
                            buttonsMoveLeftField = elem.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveLeftFieldButton'),
                            buttonsMoveRightEditField = elem.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveRightFieldButton')
                        ;
                        elem.setAttribute('field_no', data.id);

                        KP.iterate(
                            buttonsRemoveField,
                            function(button){
                                if(withoutEditButtons === true) {
                                    button.parentNode.removeChild(button);
                                } else {
                                    button.onclick = function () {
                                        var mess = 'Czy na pewno usunąć to pole?';
                                        if(confirm(mess)) {
                                            var currentField = KP.findParent(this, function(obj, i){
                                                return (obj.className.indexOf('ankiety-edycja-fields-field') >= 0);
                                            });
                                            tthis.remove(currentField.getAttribute('field_no'), this, function(data, status){
                                                if((status == 200) && data.id) {
                                                    currentField.parentNode.removeChild(currentField);
                                                } else {
                                                    window.alert('Problem z usunięciem rekordu');
                                                }
                                            });
                                        }
                                    }
                                }
                            });
                        KP.iterate(
                            buttonsEditField,
                            function(button){
                                if(withoutEditButtons === true) {
                                    button.parentNode.removeChild(button);
                                } else {
                                    button.onclick = function () {
                                        tthis.edit(elem.getAttribute('field_no'), button, true);
                                    }
                                }
                            });
                        KP.iterate(
                            buttonsMoveLeftField,
                            function(button){
                                if(withoutEditButtons === true) {
                                    button.parentNode.removeChild(button);
                                } else {
                                    button.setAttribute('field_no_oponent', data.prev);
                                    button.onclick = function () {
                                        var thisButton = this;
                                        tthis.swapOrdering(
                                            elem.getAttribute('field_no'),
                                            this,
                                            this.getAttribute('field_no_oponent'),
                                            function(data, status){
                                                var field = KP.findParent(thisButton, function(obj, i){
                                                    return (obj.className.indexOf('ankiety-edycja-fields-field') >= 0);
                                                });

                                                var lp = field.previousSibling.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveLeftFieldButton')[0];
                                                var rp = field.previousSibling.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveRightFieldButton')[0];

                                                var l = field.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveLeftFieldButton')[0];
                                                var r = field.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveRightFieldButton')[0];

                                                var l_fno = l.getAttribute('field_no_oponent');
                                                var r_fno = r.getAttribute('field_no_oponent');
                                                var lp_fno = lp.getAttribute('field_no_oponent');
                                                var rp_fno = rp.getAttribute('field_no_oponent');

                                                lp.setAttribute('field_no_oponent', rp_fno);
                                                rp.setAttribute('field_no_oponent', r_fno);
                                                l.setAttribute('field_no_oponent', lp_fno);
                                                r.setAttribute('field_no_oponent', l_fno);

                                                lp.disabled = (rp_fno < 1);
                                                rp.disabled = (r_fno < 1);
                                                l.disabled = (lp_fno < 1);
                                                r.disabled = (l_fno < 1);

                                                field.parentNode.insertBefore(field, field.previousSibling);
                                            }
                                        );
                                    }
                                    button.disabled = (data.prev < 1);
                                }
                            });
                        KP.iterate(
                            buttonsMoveRightEditField,
                            function(button){
                                if(withoutEditButtons === true) {
                                    button.parentNode.removeChild(button);
                                } else {
                                    button.setAttribute('field_no_oponent', data.next);
                                    button.onclick = function () {
                                        var thisButton = this;
                                        tthis.swapOrdering(
                                            elem.getAttribute('field_no'),
                                            this,
                                            this.getAttribute('field_no_oponent'),
                                            function(data, status){
                                                var field = KP.findParent(thisButton, function(obj, i){
                                                    return (obj.className.indexOf('ankiety-edycja-fields-field') >= 0);
                                                })

                                                var ln = field.nextSibling.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveLeftFieldButton')[0];
                                                var rn = field.nextSibling.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveRightFieldButton')[0];

                                                var l = field.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveLeftFieldButton')[0];
                                                var r = field.getElementsByClassName('kp-questionnaire-edit-page-line-field-moveRightFieldButton')[0];

                                                var l_fno = l.getAttribute('field_no_oponent');
                                                var r_fno = r.getAttribute('field_no_oponent');
                                                var ln_fno = ln.getAttribute('field_no_oponent');
                                                var rn_fno = rn.getAttribute('field_no_oponent');

                                                ln.setAttribute('field_no_oponent', l_fno);
                                                rn.setAttribute('field_no_oponent', ln_fno);
                                                l.setAttribute('field_no_oponent', r_fno);
                                                r.setAttribute('field_no_oponent', rn_fno);

                                                ln.disabled = (l_fno < 1);
                                                rn.disabled = (ln_fno < 1);
                                                l.disabled = (r_fno < 1);
                                                r.disabled = (rn_fno < 1);

                                                field.parentNode.insertBefore(field, field.nextSibling.nextSibling);
                                            }
                                        );
                                    }
                                    button.disabled = (data.next < 1);
                                }
                            });

                        var fmdList = elem.getElementsByClassName('ankiety-edycja-fields-field_main_description');
                        KP.iterate(
                            fmdList,
                            function(fmd){
                                if (fmd.tagName.toLowerCase() == 'input') {
                                    fmd.value = data.name;
                                } else {
                                    fmd.innerHTML = data.name;
                                }
                            });

                        var fmtList = elem.getElementsByClassName('ankiety-edycja-fields-field_main_type');
                        KP.iterate(
                            fmtList,
                            function(fmt){
                                if (fmt.tagName.toLowerCase() == 'select') {
                                    fmt.value = data.type;
                                } else {
                                    fmt.innerHTML = data.type;
                                }
                            });

                        if(afterObject instanceof HTMLElement) {
                            afterObject.parentNode.insertBefore(elem, afterObject.nextSibling);
                            afterObject.parentNode.removeChild(afterObject);

                            var buttonsEditFieldList = tthis.container.getElementsByClassName('kp-questionnaire-edit-page-line-field-editFieldButton');
                            KP.iterate(
                                buttonsEditFieldList,
                                function(buttonsEditField){
                                    buttonsEditField.style.display = 'block';
                                });

                            var buttonsEditFieldList = tthis.container.getElementsByClassName('kp-questionnaire-edit-page-line-field-removeFieldButton');
                            KP.iterate(
                                buttonsEditFieldList,
                                function(buttonsEditField){
                                    buttonsEditField.style.display = 'block';
                                });
                        }

                        tthis.field = null;
                    });
            }
        },

        'getData': function(fieldId, callback) {

            var
                tthis = this,
                localData = {
                    'id': fieldId
                };

            var ajax = new KPAjax({
                url: './php/admin/questionnairePageLineField/getData',
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

        'edit': function(fieldId, buttonObject, inEditTemplate) {
            var tthis = this;
            this.getData(fieldId, function(jsonData, status) {
                if(status == 200) {
                    var currentField = KP.findParent(buttonObject, function(obj, i){
                        var clName = 'ankiety-edycja-fields-field'+(inEditTemplate ? '' : '-edit');
                        return (obj.className.indexOf(clName) >= 0);
                    });
                    tthis.open(JSON.parse(jsonData), inEditTemplate, currentField);
                }
            })
        },

        'swapOrdering': function(fieldId, buttonObject, fieldIdOponent, callback) {
            var ajax = new KPAjax({
                url: './php/admin/questionnairePageLineField/swapOrdering',
                method: 'post',
                contentEncoding: 'gzip',
                contentType: 'form',
                //accept: 'json',
                data: {
                    'field_no': fieldId,
                    'field_no_oponent': fieldIdOponent
                },
                //onsuccess: function(data){
                //    if(typeof callback == 'function') {
                //        callback(data, 200);
                //    }
                //},
                oncompleted: function(jsonData, status){
                    var data = null;
                    if(status == 200) {
                        var data = JSON.parse(jsonData);
                    }
                    if(typeof callback == 'function') {
                        callback(data, status);
                    }
                }
            });
            ajax.start();
        },

        'save': function(fieldsData, buttonObject, callback) {

            var
                tthis = this,
                localData = {
                    'line_id': this.line.getAttribute('line_no'),
                    'params': fieldsData.params
                };

            if(typeof fieldsData.id != 'undefined') {
                localData.id = fieldsData.id;
            }

            if(typeof fieldsData.name != 'undefined') {
                localData.name = fieldsData.name;
            }

            if(typeof fieldsData.type != 'undefined') {
                localData.type = fieldsData.type;
            }

            var ajax = new KPAjax({
                url: './php/admin/questionnairePageLineField/save',
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
                    if(status == 200) {
                        var data = JSON.parse(jsonData);
                        tthis.edit(data.id, buttonObject, !buttonObject);
                    }
                }
            });
            ajax.start();
        },

        'cancel': function(buttonObject) {},

        'remove': function(fieldId, buttonObject, callback) {
            var tthis = this;

            var currentLine = KP.findParent(buttonObject, function(obj, i){
                return (obj.tagName.toLowerCase() == 'fieldset' && obj.className.indexOf('ankiety-edycja-fields') >= 0);
            });

            var ajax = new KPAjax({
                url: './php/admin/questionnairePageLineField/remove',
                method: 'post',
                contentEncoding: 'gzip',
                contentType: 'form',
                //accept: 'json',
                data: {
                    'id': fieldId,
                    'line_id': currentLine.getAttribute('line_no')
                },
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

        'openAll': function(inEditTemplate, callback) {
            var tthis = this;
            var ajax = new KPAjax({
                url: './php/admin/questionnairePageLineField/getList',
                method: 'post',
                contentEncoding: 'gzip',
                contentType: 'form',
                //accept: 'json',
                data: {
                    'line_id': this.line.getAttribute('line_no')
                },
                //onsuccess: function(data){
                //    if(typeof callback == 'function') {
                //        callback(data, 200);
                //    }
                //},
                oncompleted: function(jsonData, status){
                    var data = {id:0};
                    if(status == 200) {
                        try {
                            data = JSON.parse(jsonData);
                        } catch(e) {}
                        for(var i=0; i<data.length; i++) {
                            tthis.open(data[i], inEditTemplate);
                        }
                    }
                    if(typeof callback == 'function') {
                        callback(data, status);
                    }
                }
            });
            ajax.start();
        },

        'addParameter': function(fieldNo, key, value) {
            var
                bodyParams = document.getElementById('ankiety-edycja-fields-field_main_type_params_list'),
                row = bodyParams.insertRow(0),
                cell1 = row.insertCell(0),
                cell2 = row.insertCell(1),
                cell3 = row.insertCell(2),
                deleteButton = document.createElement('button')
            ;

            row.className = 'param-row';

            cell1.innerHTML = '<input type="text" class="param-row-name '+'" value="'+( (typeof key != 'undefined') ? key : '' )+'" />';
            cell2.innerHTML = '<input type="text" class="param-row-value '+'" value="'+( (typeof value != 'undefined') ? value : '' )+'" />';

            deleteButton.setAttribute('type', 'button');
            deleteButton.innerHTML = '-';
            deleteButton.onclick = function(){
                var tr = this.parentNode.parentNode;
                tr.parentNode.removeChild(tr);
            };
            cell3.appendChild(deleteButton);
        },

        'getParams': function() {
            var
                params = {},
                bodyParams = document.getElementById('ankiety-edycja-fields-field_main_type_params_list')
            ;

            KP.iterate(
                bodyParams.getElementsByClassName('param-row'),
                function(tr){
                    var key = tr.getElementsByClassName('param-row-name')[0].value;
                    params[key] = tr.getElementsByClassName('param-row-value')[0].value;
                });

            return params;
        }
    };

    return publicObject;
}
