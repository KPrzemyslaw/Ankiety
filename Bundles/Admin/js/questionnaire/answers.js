function QuestionnaireAnswers(container, questId) {

    var publicObject = {
        'container': container,
        'questId': questId,

        'addToContainer': function(){
            this.container.innerHTML = '';
            KP.templates.appendTemplateTo('ankiety-answers', this.container, false, true);
        },

        'open': function(data) {
            KP.iterate(
                document.getElementsByClassName('kp-questionnaire-edit_current'),
                function(answName) {
                    answName.innerHTML = data.questionnaire.name;
                }
            );
            KP.iterate(
                document.getElementsByClassName('kp-questionnaire-edit_current-is_not_active'),
                function(answNotActive) {
                    answNotActive.style.display = ((data.questionnaire.current != 1) ? 'inline' : 'none');
                }
            );

            var dataContainer = document.getElementById('kp-questionnaire-answers-data-list');
            dataContainer.innerHTML = '';

            for (var d in data.data) {
                var item = data.data[d];
                if(Object.prototype.toString.call(item) == '[object Object]') {
                    KP.iterate(
                        KP.templates.appendTemplateTo('ankiety-answers-fieldset', dataContainer),
                        function(elem){
                            KP.iterate(
                                elem.getElementsByClassName('kp-questionnaire-answers-fieldset-name'),
                                function(nameObj){
                                    nameObj.innerText = item.line.name;
                                }
                            );

                            KP.iterate(
                                elem.getElementsByClassName('kp-questionnaire-answers-fieldset-lines'),
                                function(fieldsetLinesContainer) {
                                	var prVal, prValSumm = 0, indexVal = 0, maxVal = Object.keys(item.values).length;
                                    for (var v in item.values) {
                                    	indexVal ++;
                                    	
                                    	if(indexVal < maxVal) {
                                    		prVal = Math.round(((item.values[v].counter * 100) / data.sessions_counter) * 100) / 100;
                                    		prValSumm += prVal;
                                    	} else {
                                    		prVal = 100 - prValSumm;
                                    	}
                                    	
                                        KP.iterate(
                                            KP.templates.appendTemplateTo('ankiety-answers-fieldset-line', fieldsetLinesContainer),
                                            function (line) {
                                                KP.iterate(
                                                    line.getElementsByClassName('kp-questionnaire-answers-fieldset-line-name'),
                                                    function (lineName) {
                                                        lineName.innerText = v;
                                                    }
                                                );
                                                KP.iterate(
                                                    line.getElementsByClassName('kp-questionnaire-answers-fieldset-line-value-percent'),
                                                    function (lineValue) {
                                                        lineValue.innerText = prVal.toFixed(2)+'%';
                                                    }
                                                );
                                                KP.iterate(
                                                    line.getElementsByClassName('kp-questionnaire-answers-fieldset-line-value-bar'),
                                                    function (lineBar) {
                                                    	var w = (prVal - 6);
                                                        lineBar.style.width = ((w > 0) ? prVal : 0)+'%';
                                                    }
                                                );
                                            }
                                        );
                                    }
                                }
                            );
                        }
                    );
                }
            }
        }
    }

    return publicObject;
}
