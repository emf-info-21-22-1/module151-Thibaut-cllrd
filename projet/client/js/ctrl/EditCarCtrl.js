class EditCarCtrl {

    constructor() {
        const self = this;
        this.http = new ServiceHttp();
        this.form = document.getElementById('editCar');
        this.attachEventListeners();
        document.getElementById('deleteCar').addEventListener('click', function () {
            self.http.deleteCar(self.successDeleteCar, self.errorDeleteCar);
        });
        document.getElementById('removeCar').addEventListener('click', function () {
            self.http.removeCar(self.successRemove, self.errorRemove);
        });
    }



    attachEventListeners() {
        this.loadCar();
        this.loadUserInCar();

        // Vérifie que le formulaire existe avant d'ajouter un écouteur d'événements.
        if (this.form) {
            this.form.addEventListener('submit', (event) => {
                event.preventDefault(); // Empêche la soumission normale du formulaire.

                this.editCar();
            });
        }
    }

    loadCar() {
        this.http.getCarInfo(this.successLoadCarInfo, this.errorLoad);
    }

    loadUserInCar() {
        this.http.getUserInCar(this.successLoadUsers, this.errorLoadUsers);
    }



    editCar() {
        const start = this.form.elements["start"].value;
        const selectedDate = new Date(start);
        const formattedDateTime = `${selectedDate.getFullYear()}-${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}-${selectedDate.getDate().toString().padStart(2, '0')} ${selectedDate.getHours().toString().padStart(2, '0')}:${selectedDate.getMinutes().toString().padStart(2, '0')}:${selectedDate.getSeconds().toString().padStart(2, '0')}`;
        const place = this.form.elements["place"].value;
        const direction = this.form.elements["direction"].value;
        const comment = this.form.elements["comment"].value;

        this.http.editCar(formattedDateTime, place, direction, comment, this.successEdit, this.errorEdit);
    }

    successLoadCarInfo(data) {
        document.getElementById("start").value = data['start'];
        document.getElementById("place").value = data['place'];
        document.getElementById("direction").value = data['direction'];
        document.getElementById("comment").value = data['comment'];
        const inParty = data['inParty'];
        if (inParty != null) {
            document.getElementById("removeCar").disabled = false;
        }
        // if(data['picture'] != 'null'){
        //     const newPicture = URL.createObjectURL(atob(data['picture']));
        //     document.getElementById("preview").src=newPicture;
        //     document.getElementById("uploadLink").innerText = 'Modifier la photo'
        // }

    }

    errorLoad(xhrFields) {
        switch (xhrFields.status) {
            case 401: alert("Votre session a pris fin, veuillez vous reconnecter"); window.location.href = 'login.html';
                break;
            case 500: alert('Problème serveur');
                break;
            case 404: alert("Vous n'êtes dans aucune party");
                break;
            case 409: alert("Conflit, il est possible que vous soyez déjà dans une voiture ou que la voiture dont vous essayez de rejoindre est pleine");
                break;
        }
    }

    successEdit() {
        alert('Voiture modifiée !');
    }

    successRemove() {
        alert('La voiture a été retirée avec succès');
        window.location.reload();
    }

    successLoadUsers(data) {
        const p = document.createElement('p');
        if (data.length === 0) {
            console.log('oau'); // Utilisez console.log au lieu de alert
            p.textContent = "Il n'y a personne par ici !"; // Correction de la phrase
            document.getElementById("userInCar").appendChild(p);
        }
    }


    errorLoadUsers() {
        switch (jqXHR.status) {
            case 404:
                alert("La voiture que vous essayez de modifier n'existe plus");
                break;
            case 401:
                alert("Votre session a pris fin"); window.location.href = 'login.html';
                break;
            default: alert('Erreur serveur');
                break;
        }
    }

    errorRemove() {
        switch (jqXHR.status) {
            case 404:
                alert("La voiture que vous essayez de modifier n'existe plus");
                break;
            case 422:
                alert("Vous ne pouvez pas retirer la voiture 30 minutes avant son départ");
                break;
            case 409:
                alert("Vous n'êtes plus dans une soirée !");
                break;
            case 500:
                alert("Un problème serveur est survenu");
                break;
            case 401:
                alert("Votre session a pris fin"); window.location.href = 'login.html';
                break;
        }
    }

    successDeleteCar() {
        alert('Voiture supprimée');
    }

    errorDeleteCar() {
        switch (jqXHR.status) {
            case 404:
                alert("La voiture que vous essayez de modifier n'existe plus");
                break;
            case 422:
                alert("Vous ne pouvez pas supprimer la voiture 30 minutes avant son départ");
                break;
            case 500:
                alert("Un problème serveur est survenu");
                break;
            case 401:
                alert("Votre session a pris fin"); window.location.href = 'login.html';
                break;
        }
    }

    errorEdit(jqXHR) {
        switch (jqXHR.status) {
            case 404:
                alert("La voiture que vous essayez de modifier n'existe plus");
                break;
            case 422:
                alert("Vous ne pouvez pas modifier la voiture 30 minutes avant son départ");
                break;
            case 500:
                alert("Un problème serveur est survenu");
                break;
            case 401:
                alert("Votre session a pris fin"); window.location.href = 'login.html';
                break;
        }

    }
}

// Assurez-vous que le script s'exécute après que le DOM soit entièrement chargé.
document.addEventListener('DOMContentLoaded', () => {
    new EditCarCtrl();

});



