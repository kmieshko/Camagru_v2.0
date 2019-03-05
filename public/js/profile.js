var editLogin = document.getElementById('edit-login');
var editEmail = document.getElementById('edit-email');
var editPassword = document.getElementById('edit-password');
var inputLogin = document.createElement('input');
var inputEmail = document.createElement('input');
var inputPassword = document.createElement('input');
var login = document.getElementById('label-login');
var email = document.getElementById('label-email');
var password = document.getElementById('label-password');
editLogin.onclick = function () {
    if (!inputLogin.value) {
        inputLogin.type = "text";
        inputLogin.name = "login";
        inputLogin.value = login.innerText;
        login.innerHTML = '';
        login.appendChild(inputLogin);
    }
};
editEmail.onclick = function () {
    if (!inputEmail.value) {
        inputEmail.type = "text";
        inputEmail.name = "email";
        inputEmail.value = email.innerText;
        email.innerHTML = '';
        email.appendChild(inputEmail);
    }
};
editPassword.onclick = function () {
    inputPassword.type = "password";
    inputPassword.name = 'new-password';
    inputPassword.autoComplete = 'on';
    inputPassword.value = password.innerText;
    password.appendChild(inputPassword);
};