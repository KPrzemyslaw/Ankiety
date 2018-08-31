
function QuestionnaireEditLine(container, questEditId) {

    function setPageWithData(page, data) {
        page.setAttribute('page_no', data.id);
        KP.iterate(
            page.getElementsByClassName('ankiety-edycja-strona_name'),
            function(elem){
                elem.innerHTML = data.name;
            });
        KP.iterate(
            page.getElementsByClassName('ankiety-edycja-strona_descbegin'),
            function(elem){
                elem.innerHTML = data.desc_begin;
            });
        KP.iterate(
            page.getElementsByClassName('ankiety-edycja-strona_descend'),
            function(elem){
                elem.innerHTML = data.desc_end;
            });


        var pageAct = new QuestionnaireEditPage(page);
        pageAct.showLines();
        pageAct.setButtons();
    };

    function setPageInContainer(elems, data) {
        KP.iterate(
            elems,
            function(page){
                setPageWithData(page, data);
            });
    };

    var publicObject = {
        'container': container,
        'questEditId': questEditId,

        'addToContainer': function(data){
            var elems = KP.templates.appendTemplateTo('ankiety-linia', this.container, atBegin);

            KP.iterate(
                elems,
                function(line){
                    line.setAttribute('line_no', data.id);
                    var titles = line.getElementsByClassName('title');
                    KP.iterate(
                        titles,
                        function(line){
                            line.innerHTML = data.name;
                        });

                    var buttons = line.getElementsByTagName('button');
                    KP.iterate(
                        buttons,
                        function(button){
                            button.onclick = function(){
                                var clName = 'kp-questionnaire-list-line', elem = this;
                                while(elem.className.indexOf(clName) < 0) {
                                    elem = elem.parentNode;
                                }
                                questObject.openEdit(elem.getAttribute('line_no'));
                            };
                        });
                });
        },

        'addPage': function(data){
            var tthis = this;
            var ajax = new KPAjax({
                url: './php/admin/questionnairePage/save',
                method: 'post',
                contentEncoding: 'gzip',
                contentType: 'form',
                data: {
                    'questionnaire_id': data.questEditId,
                    'name': data.name
                },
                oncompleted: function(data, status){
                    if(status == 200) {
                        var jsonData = null;
                        try {
                            jsonData = JSON.parse(data);
                        } catch(err) {}

                        if(jsonData && (jsonData.id > 0)) {
                            var
                                editedQuestEditedLineNo = tthis.questEditId,
                                pageList = document.getElementById('kp-questionnaire-edit-page-list'),
                                elems = KP.templates.appendTemplateTo('ankiety-edycja-strona', pageList)
                            ;

                            KP.iterate(
                                elems,
                                function(page){
                                    setPageWithData(page, jsonData);
                                });
                        } else {
                            window.alert('Wystąpił problem z tworzeniem nowej strony');
                        }
                    }
                }
            });
            ajax.start();
        },

        'setPagesInContainer': function(pagesList){
            if( Object.prototype.toString.call( pagesList ) === '[object Array]' ) {
                var pageList = document.getElementById('kp-questionnaire-edit-page-list');

                for (var i = 0; i < pagesList.length; i++) {
                    var elems = KP.templates.appendTemplateTo('ankiety-edycja-strona', pageList);
                    setPageInContainer(elems, pagesList[i]);
                }
            }
        },

        /**
         * Ustawianie buttonów dodawania nowych pozycji w edytowanej ankiecie
         */
        'setButtonsPageAdd': function() {
            var
                tthis = this,
                buttonsAddfield = document
                .getElementById('kp-questionnaire-edit-fieldset')
                .getElementsByClassName('kp-questionnaire-edit-addpage')
            ;

            KP.iterate(
                buttonsAddfield,
                function(button){
                    button.onclick = function(){
                        tthis.addPage({
                            'questEditId': tthis.questEditId
                        });
                    };
                });
        }
    };

    return publicObject;
}