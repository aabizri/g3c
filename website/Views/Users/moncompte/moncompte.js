function validateEmail()
{
    var email = document.forms["modifierinfos"]["email"].value;
    var cemail = document.forms["modifierinfos"]["newemail"].value;

    if (email !== cemail) {
        alert("La confirmation de l'email n'est pas valide");
        return false;
    }
}

function validateMdp()
{
    var ancienmdp = document.forms['modifiermdp']['ancienmdp'].value;
    var mdp = document.forms['modifiermdp']['nouveaumdp'].value;
    var cmdp = document.forms['modifiermdp']['cnouveaumdp'].value;

    if (ancienmdp === mdp ){
        alert("Le nouveau mot de passe ne peut être le même que l'ancien");
        return false;
    }

    if (mdp !== cmdp){
        alert("Le nouveau mot de passe n'est pas confirmé");
        return false;
    }
}

function validateDelete() {
    return window.confirm("Etes-vous sur de vouloir supprimer votre compte? La suppresion d'un compte est irréversible.")
}

