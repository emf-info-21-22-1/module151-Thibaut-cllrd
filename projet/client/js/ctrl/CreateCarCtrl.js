class CreateCarCtrl {

    constructor() {
        this.http = new ServiceHttp();
        this.form = document.getElementById('addCar');
        this.attachEventListeners();
    }



    attachEventListeners() {
        // Vérifie que le formulaire existe avant d'ajouter un écouteur d'événements.
        if (this.form) {
            this.form.addEventListener('submit', (event) => {
                event.preventDefault(); // Empêche la soumission normale du formulaire.
                this.createCar();
            });
        }
    }

    createCar() {

        const start = this.form.elements["start"].value;
        const place = this.form.elements["place"].value;
        const direction = this.form.elements["direction"].value;
        const comment = this.form.elements["comment"].value;
        console.log(start);



        
        const selectedDate = new Date(start);

       
        const formattedDateTime = `${selectedDate.getFullYear()}-${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}-${selectedDate.getDate().toString().padStart(2, '0')} ${selectedDate.getHours().toString().padStart(2, '0')}:${selectedDate.getMinutes().toString().padStart(2, '0')}:${selectedDate.getSeconds().toString().padStart(2, '0')}`;


        this.http.createCar(formattedDateTime, place, direction, comment, this.successCreation, this.errorCreation);
    }

    //Si la voiture a bien été créé
    successCreation() {
        alert('La voiture a été ajoutée à votre profile !');
        window.location.href = "editProfile.html";
    }

    //Si la création de la voiture n'a pas fonctionnée
    errorCreation(jqXHR) {
        alert('erreur');
        switch (jqXHR.status) {
            case 409:
                alert("Vous avez déjà une voiture !");
                break;
            case 500:
                alert("Un problème est survenu");
                break;
            case 401:
                alert("Votre session a prit fin .."); window.location.href = "login.html";
                break;
            case 400: alert("Veuillez remplir tous les champs !");
                break;
        }

    }
}

// S'assure que le script s'exécute après que le DOM soit entièrement chargé.
document.addEventListener('DOMContentLoaded', () => {
    new CreateCarCtrl();

});



