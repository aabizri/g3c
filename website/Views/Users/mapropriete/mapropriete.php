<html>
<ul id="Menu">
    <li id="Moncompte"><a href="index.php?c=User&a=AccountPage"><input type="button" value="Mon compte" /></a></li>
    <li id="Mapropriete"><a href="http://localhost/g3c/website/index.php?c=Property&a=PropertyPage"><input type="button" value="Ma propriété" /></a> </li>
    <li id="Mespieces"><a href="index.php?c=Room&a=RoomsPage"><input type="button" value="Mes pièces" /></a></li>
    <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=PeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
    <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
    <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
</ul>

<div id="creervoirutilisateur">

    <div id="voirutilisateurs">
        <h4 id="titrelisteutilisateurs">Liste des utilisateurs</h4>
        <p id="nomsutilisateurs">Eytan <input id="boutonssupprimer" type="button" value="Supprimer"
                                              title="Vous allez supprimer cet utilisateur"/></p>
        <p id="nomsutilisateurs">Bryan <input id="boutonssupprimer" type="button" value="Supprimer"
                                              title="Vous allez supprimer cet utilisateur"/></p>
        <p id="nomsutilisateurs">Jérémy <input id="boutonssupprimer" type="button" value="Supprimer"
                                               title="Vous allez supprimer cet utilisateur"/></p>
        <p id="nomsutilisateurs">Dinesh <input id="boutonssupprimer" type="button" value="Supprimer"
                                               title="Vous allez supprimer cet utilisateur"/></p>
        <p id="nomsutilisateurs">Alexandre <input id="boutonssupprimer" type="button" value="Supprimer"
                                                  title="Vous allez supprimer cet utilisateur"/></p>
        <p id="nomsutilisateurs">Randy <input id="boutonssupprimer" type="button" value="Supprimer"
                                              title="Vous allez supprimer cet utilisateur"/></p>

    </div>


    <div id="creerutilisateur">
        <h4 id="titrecreerutilisateur">Ajouter un utilisateur à la propriété</h4>
        <form action="pagephp">
            <p><label>Login</label>: <input id="formdroit" type="text" name="login"/></p><br>
            <input id="validercreationutilisateur" type="submit" value="Valider"
                   title="Vous créez un nouvel utilisateur"/>
        </form>
    </div>
</div>
</html>