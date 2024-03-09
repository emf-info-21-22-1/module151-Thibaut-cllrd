class EditProfileCtrl {

    constructor() {
        this.http = new ServiceHttp();
        this.picture = null;
        const self = this;
        this.form = document.getElementById('editProfile');
        this.attachEventListeners();
        document.getElementById('uploadLink').addEventListener('click', function () {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function () {
            var file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    //self.picture = e.target.result;
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
        this.loadProfile();
        
        // Vérifie que le formulaire existe avant d'ajouter un écouteur d'événements.
        if (this.form) {
            this.form.addEventListener('submit', (event) => {
                event.preventDefault(); // Empêche la soumission normale du formulaire.

                this.editProfile();
            });
        }
    }

    loadProfile(){
        this.http.getProfile(this.successLoad, this.errorLoad);
    }

    editProfile() {
        const username = this.form.elements["username"].value;
        const name = this.form.elements["name"].value;
        const firstname = this.form.elements["firstname"].value;
        const password = this.form.elements["password"].value;
        const confirmPassword = this.form.elements["confirmPassword"].value;

        if (password == confirmPassword) {
            //le mot de passe a bien été saisit
            
            this.http.editProfile(name, firstname, password, this.picture,username, this.successEdit, this.errorEdit);

        }
        else {
            alert('Les deux mots de passe sont différents');
        }

    }

    successLoad(data){
        document.getElementById("username").value = data['username'];
        document.getElementById("name").value = data['name'];
        document.getElementById("firstname").value = data['firstname'];
        // if(data['picture'] != 'null'){
        //     const newPicture = URL.createObjectURL(atob(data['picture']));
        //     document.getElementById("preview").src=newPicture;
        //     document.getElementById("uploadLink").innerText = 'Modifier la photo'
        // }
        
    }

    errorLoad(){
        alert('pok');
    }

    successEdit() {
        alert('Compte créé avec succès !');
    }

    errorEdit(jqXHR) {
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
    new EditProfileCtrl();

});



