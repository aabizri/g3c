<ul id="Menu">
    <li id="Moncompte"><a href="index.php?c=User&a=AccountPage"><input type="button" value="Mon compte" /></a></li>
    <li id="Mespieces"><a href="index.php?c=Room&a=RoomsPage"><input type="button" value="Mes pièces" /></a></li>
    <li id="Mesperipheriques"><a href="index.php?c=Peripherals&a=List"><input type="button" value="Mes périphériques"/></a>
    </li>
    <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
    <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
</ul>

<div id="infosmoncompte">
    <div id="mesinformations">
        <h4 id="titremesinfos">Mes informations</h4>
        <p>Nom :</p>
        <p>Prénom :</p>
        <p>Email :</p>
        <p>Addresse :</p>
        <p>Téléphone :</p>
        <p>Login :</p>
    </div>

    <div id="modifiermesinformations">
        <h4 id="titremodifierinfos">Modifier mes informations</h4>
        <form id="formulairemodif" action="pagephp">
            <p><label>Nouvel email : </label><input type="text" id="formdroit" name=""/><br></p>
            <p><label>Confirmer nouvel email : </label><input type="text" id="formdroit" name=""/><br></p>
            <p><label>Nouvelle addresse : </label><input type="text" id="formdroit" name=""/><br></p>
            <p><label>Nouveau téléphone : </label><input type="text" id="formdroit" name=""/><br></p>
            <p><label>Ancien mot de passe : </label><input type="text" id="formdroit" name=""/><br></p>
            <p><label>Nouveau mot de passe : </label><input type="text" id="formdroit" name=""/><br></p>
            <p><label>Confirmer le mot de passe : </label><input type="text" id="formdroit" name=""/><br></p>
            <p><input id="validermodifinfos" type="submit" value="Valider" title="Vous modifiez vos informations"/></p>
        </form>
    </div>

</div>

<h4 id="identificationutilisateur">Vous êtes l'utilisateur ...... </h4>

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