class LoginCtrl {
    constructor() {
        this.form = document.getElementById('loginForm');
        this.attachEventListeners();
    }

    attachEventListeners() {
        // Vérifie que le formulaire existe avant d'ajouter un écouteur d'événements.
        if (this.form) {
            this.form.addEventListener('submit', (event) => {
                event.preventDefault(); // Empêche la soumission normale du formulaire.
                this.checkLogin();
            });
        }
    }

    
    checkLogin() {
        const email = this.form.elements["mail"].value;
        const password = this.form.elements["password"].value;
        const http = new ServiceHttp();
        http.checkLogin(email,password, this.successLogin, this.errorLogin);
    }

    //Si l'utilisteur a reussi à se loguer
    successLogin(){
        window.location.href = 'userPage.html';
    }

    //Si l'utilisateur n'a pas réussi
    errorLogin(xhrFields){
        switch(xhrFields.status){
            case 401 : alert('Vérifier votre adresse e-mail ou votre mot de passe !');
            break;
            case 400 : alert("Veuillez remplir tous les champs.");
        }
    }
}

//s'assure que la page a chargée avant d'excuter le script
document.addEventListener('DOMContentLoaded', () => {
    new LoginCtrl();
    
});
