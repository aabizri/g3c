function showPropertyUsers() {

    //Création de l'objet XMLHttpRequest selon le navigateur
    if (window.XMLHttpRequest) {
        xmlhttp=new XMLHttpRequest();
    } else {
        if (window.ActiveXObject)
            try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    return NULL;
                }
            }
    }

    //Envoyer la requête au serveur
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            console.log(xmlhttp.response);
        }
    };

    xmlhttp.open("GET", "index.php?c=Property&a=PropertyUsers&pid=1", true);
    xmlhttp.send()
}