
var questObject = null;

function isLogged() {
    return ((
        KP.sessionData
        && KP.sessionData.user
        && KP.sessionData.user.id
        && KP.sessionData.user.id.length
    ) ? KP.sessionData.user : null);
}

function KPAppMain() {
    var htmltemplates = document.getElementById('htmltemplates');
    if(isLogged()) {
        htmltemplates.innerHTML = KP.templates.getTemplate('main', false);
        questObject = new Questionnaire();
        questObject.getList(function(data, status){
            for(var i=0; i<data.length; i++) {
                var lineList = document.getElementById('kp-questionnaire-list');
                (new QuestionnaireLine(lineList)).addToContainer(data[i]);
            }
        });
    }
    else {
        htmltemplates.innerHTML = KP.templates.getTemplate('login', false);
    }
}
