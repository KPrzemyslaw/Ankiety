
function submitLoginData(callback) {
    var loginForm = document.getElementById('ankiety_login_form');
    var formTool = new KPForm();
    var formData = formTool.getFormResults(loginForm);
    var ajax = new KPAjax({
        url: './php/admin/user/login',
        method: 'post',
        contentEncoding: 'gzip',
        contentType: 'form',
        data: formData,
        oncompleted: function(data, status){
            var jsonData = JSON.parse(data);
            KP.sessionData.user = (jsonData.user && jsonData.user.id && jsonData.user.id.length) ? jsonData.user : null;
            KPAppMain();
            if(!KP.sessionData.user){
                window.alert('Login lub hasło jest nieprawidłowe.');
            }
            if(typeof callback == 'function') {
                callback(data, status);
            }
        }
    });
    ajax.start();
}

function submitLogoutData(callback) {
    var ajax = new KPAjax({
        url: './php/admin/user/logout',
        method: 'post',
        contentEncoding: 'gzip',
        contentType: 'form',
        oncompleted: function(data, status){
            var jsonData = JSON.parse(data);

            KP.sessionData.user = (jsonData.user && jsonData.user.id && jsonData.user.id.length) ? jsonData.user : null;
            KPAppMain();
            if(typeof callback == 'function') {
                callback(data, status);
            }
        }
    });
    ajax.start();
}
