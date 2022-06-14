console.log("hola mundo");

$(document).ready(function () {
    const myStorage = window.localStorage;

    $('#logoutLink').click(function(){ 
        myStorage.removeItem('bibliotecaLoggedUser');        
    });
});