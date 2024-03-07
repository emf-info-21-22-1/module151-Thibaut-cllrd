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

    successLogin(){
        alert('succes');
    }

    errorLogin(){
        alert('echec ...');
    }
}

// Assurez-vous que le script s'exécute après que le DOM soit entièrement chargé.
document.addEventListener('DOMContentLoaded', () => {
    new LoginCtrl('loginForm');
    
});
