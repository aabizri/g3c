function validateEmail()
{
    var email = document.forms["modifierinfos"]["email"].value;
    var cemail = document.forms["modifierinfos"]["newemail"].value;

    atpos = email.indexOf("@");
    dotpos = email.lastIndexOf(".");

    if (atpos < 1 || ( dotpos - atpos < 2 )) {
        alert("L'email n'est pas valide");
        document.myForm.EMail.focus() ;
        return false;
    }

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

