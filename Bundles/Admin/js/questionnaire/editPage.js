
function QuestionnaireEditPage(pageObject) {
    var publicObject = {
        'page': pageObject,
        'buttonContainers': pageObject.getElementsByClassName('kp-questionnaire-edit-page-containerButtons'),

        'getData': function(callback) {
            var
                tthis = this,
                localData = {
                    //'questionnaire_id': this.page.questionnaireId,
                    'id': this.page.getAttribute('page_no')
                };

            var ajax = new KPAjax({
                url: './php/admin/questionnairePage/getData',
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

        //'new': function(buttonObject) {},

        'edit': function(buttonObject) {
            var tthis = this;
            this.getData(function(jsonData, status){
                var
                    data = JSON.parse(jsonData),
                    containerEditPages = tthis.page.getElementsByClassName('kp-questionnaire-edit-field-list'),
                    containerEditPage = containerEditPages[0],
                    editPageList = containerEditPage.getElementsByClassName('ankiety-edycja-fields')
                ;

                KP.removeCollection(editPageList);

                var elems = KP.templates.appendTemplateTo('ankiety-edycja-strona-edycja', containerEditPage);
                KP.iterate(
                    elems,
                    function(editPage){
                        KP.iterate(
                            editPage.getElementsByClassName('ankiety-edycja-strona_name'),
                            function(elem){
                                if(elem.tagName.toLowerCase() == 'input') {
                                    elem.value = data.name;
                                } else {
                                    elem.innerHTML = data.name;
                                }
                            });
                        KP.iterate(
                            editPage.getElementsByClassName('ankiety-edycja-strona_descbegin'),
                            function(elem){
                                if(elem.tagName.toLowerCase() == 'input') {
                                    elem.value = data.desc_begin;
                                } else {
                                    elem.innerHTML = data.desc_begin;
                                }
                            });
                        KP.iterate(
                            editPage.getElementsByClassName('ankiety-edycja-strona_descend'),
                            function(elem){
                                if(elem.tagName.toLowerCase() == 'input') {
                                    elem.value = data.desc_end;
                                } else {
                                    elem.innerHTML = data.desc_end;
                                }
                            });
                    });

                var questFieldsEditLine = new QuestionnaireFieldsEditLine(
                    document.getElementById('kp-questionnaire-edit-page-line-field_list')
                );
                questFieldsEditLine.page = tthis.page;

                KP.iterate(
                    data.lines,
                    function(line){
                        questFieldsEditLine.open(line);
                    });

                questFieldsEditLine.setButtons();

                var buttonObjectContainer = KP.findParent(buttonObject, function(obj, i){
                    return (obj.tagName.toLowerCase() == 'fieldset' && obj.className.indexOf('ankiety-edycja-strona') >= 0);
                });
                tthis.setVisibilityButtonsOnOtherPages(false, buttonObjectContainer);
            });
        },

        'save': function(buttonObject) {
            var
                tthis = this,
                localData = {
                    'questionnaire_id': this.page.questionnaireId,
                    'id': this.page.getAttribute('page_no'),
                    'name': document.getElementById('ankiety-edycja-strona_name').value,
                    'desc_begin': document.getElementById('ankiety-edycja-strona_descbegin').value,
                    'desc_end': document.getElementById('ankiety-edycja-strona_descend').value,
                    'line_desc_list': {},
                    'required_list': {}
                }
            ;

            KP.iterate(
                document.getElementsByClassName('ankiety-edycja-strona-edycja'),
                function(elemAE){
                    KP.iterate(
                        elemAE.getElementsByClassName('ankiety-edycja-fields_main_description'),
                        function(elem){
                            if(elem.tagName.toLowerCase() == 'input') {
                                var line = KP.findParent(elem, function(obj, i){
                                    return (obj.tagName.toLowerCase() == 'fieldset' && obj.className.indexOf('ankiety-edycja-fields') >= 0);
                                });
                                localData.line_desc_list[line.getAttribute('line_no')] = elem.value;
                            }
                        });
                    KP.iterate(
                        elemAE.getElementsByClassName('ankiety-edycja-fields_main_required'),
                        function(elem){
                            var line = KP.findParent(elem, function(obj, i){
                                return (obj.tagName.toLowerCase() == 'fieldset' && obj.className.indexOf('ankiety-edycja-fields') >= 0);
                            });
                            localData.required_list[line.getAttribute('line_no')] = (elem.checked ? elem.value : 0);
                        });
                });


            var ajax = new KPAjax({
                url: './php/admin/questionnairePage/save',
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
                    if(status == 200) {
                        KP.iterate(
                            tthis.page.getElementsByClassName('ankiety-edycja-strona_name'),
                            function(elem){
                                if(elem.tagName.toLowerCase() == 'input') {
                                    elem.value = localData.name;
                                } else {
                                    elem.innerHTML = localData.name;
                                }
                            });
                        KP.iterate(
                            tthis.page.getElementsByClassName('ankiety-edycja-strona_descbegin'),
                            function(elem){
                                if(elem.tagName.toLowerCase() == 'input') {
                                    elem.value = localData.desc_begin;
                                } else {
                                    elem.innerHTML = localData.desc_begin;
                                }
                            });
                        KP.iterate(
                            tthis.page.getElementsByClassName('ankiety-edycja-strona_descend'),
                            function(elem){
                                if(elem.tagName.toLowerCase() == 'input') {
                                    elem.value = localData.desc_end;
                                } else {
                                    elem.innerHTML = localData.desc_end;
                                }
                            });
                    } else {
                        window.alert('Wystapił problem z zapisem');
                    }
                    tthis.cancel(buttonObject, data);
                }
            });
            ajax.start();
        },

        'cancel': function(buttonObject) {
            KP.iterate(
                this.page.getElementsByClassName('ankiety-edycja-strona-edycja'),
                function(editFieldset){
                    editFieldset.parentNode.removeChild(editFieldset);
                });

            this.showLines();
        },

        'remove': function(buttonObject) {
            var
                tthis = this,
                localData = {
                    'questionnaire_id': this.page.questionnaireId,
                    'id': this.page.getAttribute('page_no')
                }
            ;

            var ajax = new KPAjax({
                url: './php/admin/questionnairePage/remove',
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
                    if(status == 200) {
                        tthis.page.parentNode.removeChild(tthis.page);
                    } else {
                        window.alert('Wystapił problem z operacją usunięcia');
                    }
                    tthis.cancel(buttonObject, data);
                }
            });
            ajax.start();
        },

        'setVisibilityButtonsOnOtherPages': function(visibility, parentObj) {
            var
                tthis = this,
                questEditFieldset = ((typeof parentObj != 'undefined') ? parentObj : document.getElementById('kp-questionnaire-edit-fieldset')),
                containerEditButtons = questEditFieldset.getElementsByClassName('kp-questionnaire-edit-page-containerButtons'),
                editButtons = questEditFieldset.getElementsByClassName('kp-questionnaire-edit-page-editButton'),
                saveButtons = questEditFieldset.getElementsByClassName('kp-questionnaire-edit-page-saveButton'),
                cancelButtons = questEditFieldset.getElementsByClassName('kp-questionnaire-edit-page-cancelButton'),
                deleteButtons = questEditFieldset.getElementsByClassName('kp-questionnaire-edit-page-deleteButton'),
                addPageButtons = questEditFieldset.getElementsByClassName('kp-questionnaire-edit-addpage'),
                desriptions = questEditFieldset.getElementsByClassName('ankiety-edycja-fields_main_description')
            ;

            KP.iterate(
                addPageButtons,
                function(button){
                    button.parentNode.style.display = visibility ? 'block' : 'none';
                });

            KP.iterate(
                containerEditButtons,
                function(container){
                    container.style.display = visibility ? 'block' : 'none';
                    for(var i=0; i<tthis.buttonContainers.length; i++) {
                        tthis.buttonContainers[i].style.display = 'block';
                    }
                });

            KP.iterate(
                editButtons,
                function(button){
                    button.style.display = visibility ? 'block' : 'none';
                });

            KP.iterate(
                saveButtons,
                function(button){
                    button.style.display = visibility ? 'none' : 'block';
                });

            KP.iterate(
                cancelButtons,
                function(button){
                    button.style.display = visibility ? 'none' : 'block';
                });

            KP.iterate(
                deleteButtons,
                function(button){
                    button.style.display = visibility ? 'none' : 'block';
                });

            KP.iterate(
                desriptions,
                function(elem){
                    if(elem.tagName.toLowerCase() == 'input') {
                        elem.parentNode.style.display = (visibility ? 'none' : 'block');
                    }
                    else if(elem.tagName.toLowerCase() == 'span') {
                        elem.parentNode.style.display = (visibility ? 'block' : 'none');
                    }
                });
        },

        'setButtons': function() {
            var tthis = this;

            var editButtons = this.page.getElementsByClassName('kp-questionnaire-edit-page-editButton');
            KP.iterate(
                editButtons,
                function(button){
                    button.style.display = 'block';
                    button.onclick = function(){
                        tthis.edit(this);
                    };
                });

            var saveButtons = this.page.getElementsByClassName('kp-questionnaire-edit-page-saveButton');
            KP.iterate(
                saveButtons,
                function(button){
                    button.style.display = 'none';
                    button.onclick = function(){
                        tthis.save(this);
                    };
                });

            var cancelButtons = this.page.getElementsByClassName('kp-questionnaire-edit-page-cancelButton');
            KP.iterate(
                cancelButtons,
                function(button){
                    button.style.display = 'none';
                    button.onclick = function(){
                        tthis.cancel(this);
                    };
                });

            var deleteButtons = this.page.getElementsByClassName('kp-questionnaire-edit-page-deleteButton');
            KP.iterate(
                deleteButtons,
                function(button){
                    button.style.display = 'none';
                    button.onclick = function(){
                        var mess = 'Czy na pewno usunąć te strone i wszystkie pola do niej przypisane?';
                        if(confirm(mess)) {
                            tthis.remove(this);
                        }
                    };
                });
        },

        showLines: function() {
            var tthis = this;

            this.getData(function(jsonData, status){
                var
                    data = JSON.parse(jsonData),
                    container = tthis.page.getElementsByClassName('kp-questionnaire-edit-field-list')[0]
                ;

                var questFieldsEditLine = new QuestionnaireFieldsEditLine(container);
                questFieldsEditLine.page = tthis.page;

                KP.iterate(
                    data.lines,
                    function(line){
                        questFieldsEditLine.open(line, false, false, true);
                    });
                tthis.setVisibilityButtonsOnOtherPages(true);
            });
        }
    };

    publicObject.page.questionnaireId = questObject.questEditId;

    return publicObject;
}
