
class ServiceHttp {
    static instance = null;


    constructor() {
        this.URL = "http://localhost:8081/server.php";
    }

    // singleton getInstance pour ne pas le créer plusieurs fois
    static getInstance() {
        if (ServiceHttp.instance === null) {
            ServiceHttp.instance = new ServiceHttp();
        }
        return ServiceHttp.instance;
    }

    //----------------------------------------------------------------------------
    //GET REQUEST
    //----------------------------------------------------------------------------

    /**
     * Retourne un json de toutes les voitures dispo dans la soirée.
     * @param {*} successCallback La fonction appelé si succès
     * @param {*} errorCallback La fonction appelé si erreur 
     */
    getParticipationsOfTheParty(successCallback, errorCallback) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: 'localhost:8081/server.php',
            data: 'action=getParticipations',
            success: successCallback,
            error: errorCallback
        });
    }

    /**
     * Retourne un json contenant toutes les infos de notre voiture.
     * @param {*} successCallback La fonction appelé si succès
     * @param {*} errorCallback La fonction appelé si erreur
     */
    getCarInfo(successCallback, errorCallback) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: this.URL,
            data: 'action=getCarInfo',
            success: successCallback,
            error: errorCallback
        });
    }

    /**
     * Retourne un json contenant toutes les infos sur notre profile.
     * @param {*} successCallback La fonction appelé si succès
     * @param {*} errorCallback La fonction appelé si erreur
     */
    getProfile(successCallback, errorCallback) {
        
        $.ajax({
            type: "GET",
            dataType: "json",
            url: this.URL,
            data: 'action=getProfile',
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }

    //----------------------------------------------------------------------------
    //POST REQUEST
    //----------------------------------------------------------------------------


    /**
     * Verifie le login d'un utilisateur et retourne code 200 si ok et code erreur si pas ok.
     * @param {*} mail Le mail à vérifier.
     * @param {*} password Le mdp à vérifier.
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur.
     */
    checkLogin(mail, password, successCallback, errorCallback) {
        this.disconnect();
        $.ajax({
            type: "POST",
            contentType: "application/x-www-form-urlencoded",
            url: this.URL,
            data: "action=checkLogin&mail=" + mail + "&password=" + password,
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }

    /**
     * Créer un profile et retourne code 200 si ok et code d'erreurs si pas ok
     * En paramètres les informations pour créer le profile.
     * @param {*} username 
     * @param {*} mail 
     * @param {*} name 
     * @param {*} firstname 
     * @param {*} password 
     * @param {*} picture 
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur.
     */
    createProfile(username, mail, name, firstname, password, picture, successCallback, errorCallback) {
        $.ajax({
            type: "POST",
            contentType: "application/x-www-form-urlencoded",
            url: this.URL,
            data: "action=createProfile&username=" + username + "&mail=" + mail + "&name=" + name + "&firstname=" + firstname + "&password=" + password + "&picture=" + picture,
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }

    /**
     * Rejoind une voiture et retourne code 200 si ok et code d'erreurs si pas ok.
     * @param {*} usernameToJoin 
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur.
     */
    joinCar(usernameToJoin, successCallback, errorCallback) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: this.URL,
            data: 'action=joinCar&username=' + usernameToJoin,
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }

    /**
     * Créer une voiture et retourne code 200 si ok et code erreur si pas ok.
     * @param {*} start 
     * @param {*} place 
     * @param {*} direction 
     * @param {*} comment 
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur.
     */
    createCar(start, place, direction, comment, successCallback, errorCallback) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: this.URL,
            data: 'action=createCar&start=' + start + '&place=' + place + '&direction=' + direction + '&comment=' + comment,
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }

    /**
     * Deconnecte un utilisateur et retourne un code 200 si ok et code 204 si l'utilisateur était déjà déconnecté.
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur.
     */
    disconnect() {
        $.ajax({
            type: "POST",
            url: this.URL,
            data: 'action=disconnect',
            xhrFields: {
                withCredentials: true
            },
        });
    }

    //----------------------------------------------------------------------------
    //PUT REQUEST
    //----------------------------------------------------------------------------


    /**
     * Modifie la voiture et retourne un code 200 si ok et un code d'erreur si pas ok.
     * @param {*} start 
     * @param {*} place 
     * @param {*} direction 
     * @param {*} comment 
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur.
     */
    editCar(start, place, direction, comment, successCallback, errorCallback) {
        $.ajax({
            type: "PUT",
            dataType: "json",
            url: this.URL,
            data: 'action=editCar&start=' + start + '&place=' + place + '&direction=' + direction + '&comment=' + comment,
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }

    /**
     * Modifie le profile de l'utilisateur et retourne un code 200 si ok et code d'erreur si pas ok.
     * @param {*} name 
     * @param {*} firstname 
     * @param {*} password 
     * @param {*} picture 
     * @param {*} username 
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur.
     */
    editProfile(name, firstname, password, picture, username, successCallback, errorCallback) {
        $.ajax({
            type: "PUT",
            dataType: "json",
            url: this.URL,
            data: 'action=editProfile&name=' + name + '&firstname=' + firstname + '&password=' + password + '&picutre=' + picture + '&username=' + username,
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }

    //----------------------------------------------------------------------------
    //DELETE REQUEST
    //----------------------------------------------------------------------------


    /**
     * Supprime la voiture de l'utilisateur et retourne un code 200 si ok et code d'erreur si pas ok.
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur.
     */
    deleteCar(successCallback, errorCallback) {
        $.ajax({
            type: "DELETE",
            dataType: "json",
            url: this.URL,
            data: 'action=deleteCar',
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }

    /**
     * Retire la voiture de la soirée où elle est et retourne un code 200 si ok et un code d'erreur si pas ok.
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur.
     */
    removeCar(successCallback, errorCallback) {
        $.ajax({
            type: "DELETE",
            dataType: "json",
            url: this.URL,
            data: 'action=removeCar',
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }

    /**
     * Supprime le profile de l'utilisateur et retourne un code 200 si ok et un code d'erreur si pas ok.
     * @param {*} successCallback La fonction appelé si succès.
     * @param {*} errorCallback La fonction appelé si erreur. 
     */
    deleteProfile(successCallback, errorCallback) {
        $.ajax({
            type: "DELETE",
            dataType: "json",
            url: this.URL,
            data: 'action=deleteProfile',
            xhrFields: {
                withCredentials: true
            },
            success: successCallback,
            error: errorCallback
        });
    }


}

