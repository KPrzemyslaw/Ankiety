
function KPAppMain() {
    function getCurrentQuestionnaire(callback) {
        var ajax = new KPAjax({
            url: './php/questionnaire',
            method: 'post',
            contentEncoding: 'gzip',
            //contentType: 'form',
            //accept: 'json',
            //data: {},
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
    }

    getCurrentQuestionnaire(function(data, status){
        if(status != 200) {
            var htmltemplates = document.getElementById('htmltemplates');
            htmltemplates.innerHTML = KP.templates.getTemplate('error', false);
        }
        else {
            var jsonData = {};
            try {jsonData = JSON.parse(data);}
            catch(e) {}

            var
                quest = new Questionnaire(
                    document.getElementById('ankieta-main-container')
                ),
                cookie = new KPCookie(),
                currentPage = parseInt(cookie.getCookie('ankieta-page'))
            ;

            quest.setName(jsonData.data.name);

            if(isNaN(currentPage)){currentPage = 0}
            if(jsonData.data.pages.length) {
                for(var i=0; i<jsonData.data.pages.length; i++) {
                    quest.createPage(i, jsonData.data.pages[i]);
                }
                quest.createPageExtra({
                    'strona-ankieta-title':'To już jest koniec',
                    'strona-ankieta-begin':'Dziękujemy za udział w ankiecie',
                    'strona-ankieta-lines':'',
                    'strona-ankieta-ending':'<a href="https://onet.pl">Onet.pl</a>',
                });
            } else {
                quest.createPageExtra({
                    'strona-ankieta-title':'Miło nam Cię gościć ale obecnie nie ma aktywnych ankiet.',
                    'strona-ankieta-begin':'Zapraszamy ponownie już nie długo.<br />Zespół ankietera.',
                    'strona-ankieta-lines':'',
                    'strona-ankieta-ending':'<a href="https://onet.pl">Onet.pl</a>',
                });
            }

            quest.createButtons(function(){
                quest.collectData();
                quest.sendData();
                currentPage = quest.incrementPage(1);
                cookie.setCookie('ankieta-page', currentPage);
            });
            quest.startWithPage(currentPage);
        }
    });
}
