
function KPForm() {
    var publicObject = {
        setOrPush: function (target, val) {
            var result = val;
            if (target) {
                result = [target];
                result.push(val);
            }
            return result;
        },

        getFormResults: function (formElement) {
            var
                formElements = formElement.elements,
                formParams = {},
                elem = null,
                realName = null
            ;

            for (var i = 0; i < formElements.length; i += 1) {
                elem = formElements[i];

                realName = elem.getAttribute('real-name');
                if(
                    (Object.prototype.toString.call(realName) == '[object Null]')
                    || ((Object.prototype.toString.call(realName) != '[object Null]') && !realName.length)
                ) {
                    realName = elem.name;
                }

                switch (elem.type) {
                    case 'submit':
                        break;
                    case 'radio':
                        if (elem.checked) {
                            formParams[realName] = elem.value;
                        }
                        break;
                    case 'checkbox':
                        if (elem.checked) {
                            formParams[realName] = this.setOrPush(formParams[realName], elem.value);
                        }
                        break;
                    default:
                        formParams[realName] = this.setOrPush(formParams[realName], elem.value);
                }
            }
            return formParams;
        }
    }

    return publicObject;
}
