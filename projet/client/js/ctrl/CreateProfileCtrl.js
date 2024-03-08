class CreateProfileCtrl {

    constructor() {
        this.form = document.getElementById('createProfileForm');
        this.attachEventListeners();
        document.getElementById('uploadLink').addEventListener('click', function () {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function () {
            var file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                alert('Veuillez sélectionner une image.');
            }
        });

    }



    attachEventListeners() {
        // Vérifie que le formulaire existe avant d'ajouter un écouteur d'événements.
        if (this.form) {
            this.form.addEventListener('submit', (event) => {
                event.preventDefault(); // Empêche la soumission normale du formulaire.
                this.createProfile();
            });
        }
    }

    createProfile() {
        const mail = this.form.elements["mail"].value;
        const username = this.form.elements["username"].value;
        const name = this.form.elements["name"].value;
        const firstname = this.form.elements["firstname"].value;
        const password = this.form.elements["password"].value;
        const confirmPassword = this.form.elements["confirmPassword"].value;
        const picture = null;

        if (password == confirmPassword) {
            const http = new ServiceHttp();
            //le mot de passe a bien été saisit

            //Aucune picture choisit donc picture par défaut
            http.createProfile(username, mail, name, firstname, password, picture, this.successCreation, this.errorCreation);

        }
        else {
            alert('Les deux mots de passe sont différents');
        }

    }

    successCreation() {
        window.location.href = 'login.html';
        alert('Compte créé avec succès !');
    }

    errorCreation(jqXHR) {
        switch(jqXHR.status){
            case 409 :
                alert("Le nom d'utilisateur ou l'adresse e-mail que vous avez saisit appartient déjà à un compte !");
                break;
            case 500 :
                alert("Un problème est survenu");
                break;
        }

    }
}

// Assurez-vous que le script s'exécute après que le DOM soit entièrement chargé.
document.addEventListener('DOMContentLoaded', () => {
    new CreateProfileCtrl();

});



