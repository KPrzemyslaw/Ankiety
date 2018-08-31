
function QuestionnaireLine(container) {

    function setLineWithData(line, data) {
        line.setAttribute('line_no', data.id);
        var titles = line.getElementsByClassName('title');
        KP.iterate(
            titles,
            function(line){
                line.innerHTML = data.name;
            });

        var inputs = line.getElementsByTagName('input');
        KP.iterate(
            inputs,
            function(input){
                if(input.name == 'kp-questionnaire-list-line-current') {
                    input.checked = (data.current == 1);
                }
            });

        var buttons = line.getElementsByTagName('button');
        KP.iterate(
            buttons,
            function(button){
                button.onclick = function(){
                    var elem = KP.findParent(this, function(obj, i){
                        return (obj.className.indexOf('kp-questionnaire-list-line') >= 0);
                    });
                    if(this.className.indexOf('kp-questionnaire-list-line-edit') >= 0) {
                        questObject.openEdit(elem.getAttribute('line_no'));
                    }
                    else if(this.className.indexOf('kp-questionnaire-list-line-answers') >= 0) {
                        questObject.openAnswers(elem.getAttribute('line_no'));
                    }
                };
            });
    }

    var publicObject = {
        'container': container,

        'addToContainer': function(data, atBegin){
            var elems = KP.templates.appendTemplateTo('ankiety-linia', this.container, atBegin);

            KP.iterate(
                elems,
                function(line){
                    setLineWithData(line, data);
                });
        },

        edit: function(lineNo, data) {
            var lines = this.container.getElementsByClassName('kp-questionnaire-list-line');
            KP.iterate(
                lines,
                function(line){
                    if(line.getAttribute('line_no') == lineNo) {
                        setLineWithData(line, data);
                    }
                });
        },

        remove: function(lineNo) {
            var lines = this.container.getElementsByClassName('kp-questionnaire-list-line');
            KP.iterate(
                lines,
                function(line){
                    if(line.getAttribute('line_no') == lineNo) {
                        line.parentNode.removeChild(line);
                    }
                });
        }
    };

    return publicObject;
}
