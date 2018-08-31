
function Questionnaire() {
    var publicObject = {
        questEditId: 0,
        questEdit: document.getElementById('kp-questionnaire-edit'),
        questList: document.getElementById('kp-questionnaire-list'),
        questAnswers: document.getElementById('kp-questionnaire-answers'),
        questFieldset: document.getElementById('kp-questionnaire-edit-fieldset'),

        showMainButtons: function(){
            var buttons = this.questEdit.parentNode.getElementsByClassName('kp-questionnaire-button-main');
            for(var i=0; i<buttons.length; i++) {
                buttons[i].style.display = 'block';
            }
        },

        hideMainButtons: function(){
            var buttons = this.questEdit.parentNode.getElementsByClassName('kp-questionnaire-button-main');
            for(var i=0; i<buttons.length; i++) {
                buttons[i].style.display = 'none';
            }
        },

        openEdit: function(lineNo) {
            var tthis = this;
            this.questEdit.style.display = 'block';
            this.questList.style.display = 'none';
            this.questAnswers.style.display = 'none';
            this.hideMainButtons();
            this.questFieldset.style.display = 'block';
            this.readDataForLine(lineNo, function(data, status){
                tthis.questEditId = data.id;
                document.getElementById('kp-questionnaire-edit_name').value = data.name;
                document.getElementById('kp-questionnaire-edit_current').checked = (data.current == 1);

                var questionnaireEL = new QuestionnaireEditLine(null, tthis.questEditId);
                questionnaireEL.setButtonsPageAdd();
                questionnaireEL.setPagesInContainer(data['pages']);
            });
        },

        openAnswers: function(lineNo) {
            var tthis = this;
            this.questEdit.style.display = 'none';
            this.questList.style.display = 'none';
            this.questAnswers.style.display = 'block';
            this.hideMainButtons();
            this.questFieldset.style.display = 'block';
            this.readDataForAnswers(lineNo, function(data, status){
                tthis.questEditId = data.id;
                var questionnaireEL = new QuestionnaireAnswers(tthis.questAnswers, tthis.questEditId);
                questionnaireEL.addToContainer();
                questionnaireEL.open(data);
            });
        },

        closeEdit: function() {
            this.questEditId = 0;
            this.questEdit.style.display = 'none';
            this.questList.style.display = 'block';
            this.showMainButtons();
            this.questFieldset.style.display = 'none';
            document.getElementById('kp-questionnaire-edit-page-list').innerHTML = '';
        },

        closeAnswers: function() {
            this.questAnswers.style.display = 'none';
        },

        createNew: function() {
            this.openEdit();
            this.questFieldset.style.display = 'none';
        },

        cancel: function() {
            this.questEditId = 0;
            this.closeEdit();
            this.closeAnswers();
            document.getElementById('kp-questionnaire-edit_name').value = '';
        },

        save: function() {
            var
                tthis = this,
                name = document.getElementById('kp-questionnaire-edit_name').value.trim(),
                current = (document.getElementById('kp-questionnaire-edit_current').checked ? 1 : 0)
            ;

            if(name.length) {
                var ajax = new KPAjax({
                    url: './php/admin/questionnaire/save',
                    method: 'post',
                    contentEncoding: 'gzip',
                    contentType: 'form',
                    data: {
                        'id': tthis.questEditId,
                        'name': name,
                        'current': current
                    },
                    oncompleted: function(data, status){
                        if(status == 200) {
                            var jsonData = JSON.parse(data);

                            KP.iterate(
                                tthis.questList.getElementsByClassName('kp-questionnaire-list-line'),
                                function(elem){
                                    if(elem.getAttribute('line_no') == jsonData.id) {
                                        elem.parentNode.removeChild(elem);
                                    }
                                }
                            );

                            (new QuestionnaireLine(tthis.questList)).addToContainer({
                                id: jsonData.id,
                                name: name,
                                current: current
                            }, true);
                        } else {
                            window.alert('Wystapiły błędy przy zapisie');
                        }
                        tthis.cancel();
                    }
                });
                ajax.start();
            } else {
                window.alert('Nazwa nie możę być pusta.');
            }
        },

        remove: function() {
            if(window.confirm('Czy na pewno chcesz usunąć tę ankietę ?')) {
                var
                    tthis = this,
                    name = document.getElementById('kp-questionnaire-edit_name').value.trim(),
                    current = (document.getElementById('kp-questionnaire-edit_current').checked ? 1 : 0)
                ;

                var ajax = new KPAjax({
                    url: './php/admin/questionnaire/remove',
                    method: 'post',
                    contentEncoding: 'gzip',
                    contentType: 'form',
                    data: {
                        'id': tthis.questEditId,
                        'name': name,
                        'current': current
                    },
                    oncompleted: function(data, status){
                        var jsonData = null;
                        if(status == 200) {
                            try {
                                jsonData = JSON.parse(data);
                            } catch(err) {jsonData = null;}
                        }

                        if (jsonData) {
                            (new QuestionnaireLine(tthis.questList)).remove(tthis.questEditId);
                        } else {
                            window.alert('Wystapiły błędy przy usuwaniu');
                        }

                        tthis.cancel();
                    }
                });
                ajax.start();
            }
        },

        readDataForLine: function(lineNo, callback) {
            var ajax = new KPAjax({
                url: './php/admin/questionnaire/getData',
                method: 'post',
                contentEncoding: 'gzip',
                contentType: 'form',
                data: {
                    'id': lineNo
                },
                oncompleted: function(data, status){
                    if(status == 200) {
                        var jsonData = JSON.parse(data);
                        if(typeof callback == 'function') {
                            callback(jsonData, status);
                        }
                    }
                }
            });
            ajax.start();
        },

        getList: function(callback) {
            var ajax = new KPAjax({
                url: './php/admin/questionnaire/getList',
                method: 'post',
                contentEncoding: 'gzip',
                contentType: 'form',
                data: {},
                oncompleted: function(data, status){
                    if(status == 200) {
                        var jsonData = {};
                        try {jsonData = JSON.parse(data);} catch (e) {}
                        if(typeof callback == 'function') {
                            callback(jsonData, status);
                        }
                    }
                }
            });
            ajax.start();
        },

        readDataForAnswers: function(lineNo, callback) {
            var ajax = new KPAjax({
                url: './php/admin/questionnaire/getAnswers',
                method: 'post',
                contentEncoding: 'gzip',
                contentType: 'form',
                data: {
                    'id': lineNo
                },
                oncompleted: function(data, status){
                    if(status == 200) {
                        var jsonData = {};
                        try {jsonData = JSON.parse(data);}
                        catch (e) {};
                        if(typeof callback == 'function') {
                            callback(jsonData, status);
                        }
                    }
                }
            });
            ajax.start();
        },
    };

    return publicObject;
}



