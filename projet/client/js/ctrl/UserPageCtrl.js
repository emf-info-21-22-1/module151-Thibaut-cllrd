class UserPageCtrl {
    constructor() {
        
        this.http = new ServiceHttp();
        this.loadPage();
        //Ecouteur du bouton d'ajout de la voiture à la soirée
        document.getElementById('addCarToParty').addEventListener('click', () => {
            this.addCarToParty();
        });
        //Ecouteur de se déconnecter
        document.getElementById('disconnect').addEventListener('click', () => {
            this.disconnect();
        });
        
    }

    loadPage() {      
        this.http.getProfile(this.showUserData, this.error);
        this.http.getParticipationsOfTheParty(this.showCarsOfParty.bind(this), this.error);
    }

    addCarToParty(){
        const confirmation = window.confirm("Voulez vous réelement ajouter votre véhicule à cette soirée ?");
        if(confirmation){
            this.http.addCarToParty(this.addCarSuccess, this.addCarFail);
        }
    }

    disconnect(){
        const confirmation = window.confirm("Voulez vous réelement vous déconnecter ?");
        if(confirmation){
            this.http.disconnect();
            window.location.reload();

        }
    }

    showUserData(data) {
        document.getElementById('username').innerText = data['username'];
        if (data['picture'] != 'null') {
            document.getElementById('picture').src = data['picture'];
        }
    }

    showCarsOfParty(data) {
        const carsOfParty = data['participations'];
        const blocContainer = document.getElementById('blocContainer');
        const self = this;

        for (const i in carsOfParty) {
            const car = carsOfParty[i];
            const availableSeats = car['availableSeats'];
            const username = car['user']['username'];
            const picture = car['user']['picture'];
            const start = new Date(car['car']['start']).toLocaleString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            const direction = car['car']['direction'];
            const comment = car['car']['comment'];
            if(availableSeats > 0){
            const bloc = document.createElement('div');
            bloc.className = 'bloc';
            const imgContainer = document.createElement('div');
            imgContainer.className = 'img-container';
            const img = document.createElement('img');
            img.style.maxWidth = '100%';
            img.style.maxHeight = '100px'; // Ajustez selon les besoins
            img.src = picture ? URL.createObjectURL(picture) : '../img/default.png';
            imgContainer.appendChild(img);
            bloc.appendChild(imgContainer);

            const userInfoContainer = document.createElement('div');
            userInfoContainer.className = 'user-info-container';

            const userInfo = document.createElement('h2');
            userInfo.textContent = username;
            userInfoContainer.appendChild(userInfo);

            const directionText = document.createElement('p');
            directionText.textContent = `Direction: ${direction}`;
            userInfoContainer.appendChild(directionText);

            bloc.appendChild(userInfoContainer);

            const seatsAndDepartureContainer = document.createElement('div');
            seatsAndDepartureContainer.className = 'seats-departure-container';

            const seatsInfo = document.createElement('p');
            seatsInfo.textContent = `${availableSeats} places restantes`;
            seatsAndDepartureContainer.appendChild(seatsInfo);

            const departureInfo = document.createElement('p');
            departureInfo.textContent = `Départ: ${start}`;
            seatsAndDepartureContainer.appendChild(departureInfo);

            bloc.appendChild(seatsAndDepartureContainer);

            blocContainer.appendChild(bloc);
            

            bloc.addEventListener('click', function () {
                document.getElementById('joinPopup').style.display = 'block'; // Montre la popup
                document.getElementById("comment").innerText = comment;
                
                document.getElementById('confirmJoin').dataset.username=username;
                
            });
        }
        }

        document.getElementById('confirmJoin').addEventListener('click', function () {
            // L'utilisateur confirme vouloir rejoindre le véhicule
            const usernameToJoin = this.dataset.username; 
            self.http.joinCar(usernameToJoin, self.joinCarSuccess ,self.error);
            document.getElementById('joinPopup').style.display = 'none'; // Cache la popup
        });

        document.getElementById('cancelJoin').addEventListener('click', function () {
            document.getElementById('joinPopup').style.display = 'none'; // Cache la popup sans autres actions
        });

        

    }

    //Methode appelée si l'utilisateur a reussit a rejoindre une voiture
    joinCarSuccess(){
        alert('Voiture rejoind avec succès');
        window.location.reload();
    }

    //Methode appelée si l'utilisateur a reussit a ajouter sa voiture a la soirée
    addCarSuccess(){
        alert('nice');
    }

    //Methode appelée si l'utilisateur n'a pas reussit a ajouter sa voiture a la soirée
    addCarFail(xhrFields){
        switch(xhrFields.status){
            case 401 : alert("Votre session a pris fin, veuillez vous reconnecter"); window.location.href = 'login.html';
            break;
            case 404 : alert("Oups, vérifier que vous avez une voiture et que vous êtes bien dans une fête !");
            break;
            case 409 : alert("Il semblerait que vous êtes déjà dans une voiture, quittez la pour ajouter la votre !");
            break;
            case 500 : alert("Problème serveur");
            break;
        }
    }

    error(xhrFields) {
        switch (xhrFields.status) {
            case 401: alert("Votre session a pris fin, veuillez vous reconnecter"); window.location.href = 'login.html';
                break;
            case 500: alert('Problème serveur');
                break;
            case 404: alert("Vous n'êtes dans aucune party");
                break;
            case 409 : alert("Conflit, il est possible que vous soyez déjà dans une voiture ou que la voiture dont vous essayez de rejoindre est pleine");
            break;
        }


    }

}

// Créé la classe dès que la page a finit de charger
document.addEventListener('DOMContentLoaded', () => {
    new UserPageCtrl();

});
