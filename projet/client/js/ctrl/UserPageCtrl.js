class UserPageCtrl {
    constructor() {
        this.http = new ServiceHttp();
        this.loadPage();
    }

    loadPage() {
        this.http.getProfile(this.showUserData, this.error);
        this.http.getParticipationsOfTheParty(this.showCarsOfParty, this.error);
    }

    showUserData(data) {
        document.getElementById('username').innerText = data['username'];
        if (data['picture'] != null) {
            document.getElementById('picture').src = data['picture'];
        }
    }

    showCarsOfParty(data) {
        blocContainer = document.getElementById('blocContainer');
        bloc = document.createElement('div');
        bloc.className = 'bloc';

        // Création et ajout de la photo de profil
        const img = document.createElement('img');
        img.src = "../img/default.png";
        if (data['picture']) {
            img.src = data[picture];
        }
        img.className = 'profilePicture';
        bloc.appendChild(img);
        // Ajout des informations de la personne et voiture
        const infos = ["nom", "prenom", "age"]; // Liste des clés que vous voulez afficher
        infos.forEach(info => {
            if (data[info]) { // Vérifie si l'info existe
                const p = document.createElement('p');
                p.textContent = `${info}: ${personne[info]}`;
                bloc.appendChild(p);
            }
        });

        bloc.addEventListener('click', function () {
            bloc.classList.toggle('selected');
        });

        blocContainer.appendChild(bloc);
    }


error(){
    alert("Votre session a pris fin, veuillez vous reconnecter");
    window.location.href = 'login.html';
}

}

// Assurez-vous que le script s'exécute après que le DOM soit entièrement chargé.
document.addEventListener('DOMContentLoaded', () => {
    new UserPageCtrl();

});
