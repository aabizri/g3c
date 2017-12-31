<ul id="Menu">
    <li id="Moncompte"><a href="index.php?c=User&a=getAccountPage"><input type="button" value="Mon compte" /></a></li>
    <li id="Mespieces"><a href="index.php?c=Room&a=getRoomsPage"><input type="button" value="Mes pièces" /></a></li>
    <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=getPeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
    <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
    <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
</ul>

<div id="infosmoncompte">
    <div id="mesinformations">
        <h4 id="titremesinfos">Mes informations</h4>
        <?php
        $u = $data["user"];
                echo
                "
                <p>Prénom/Nom : ".$u -> getDisplay()."</p>
                <p>Email : ".$u -> getEmail()."</p>
                <p>Addresse : </p>
                <p>Téléphone : ".$u -> getPhone()."</p>
                <p>Login : ".$u -> getNick()."</p>
                 ";
        ?>

    </div>

    <div id="modifiermesinformations">
        <h4 id="titremodifierinfos">Modifier mes informations</h4>
        <form method="post" id="formulairemodif" action="http://localhost/g3c/website/index.php?c=User&a=postMAJInformations">
            <p>
                <label>Nouvel email :</label><input type="text" id="formdroit" name="email"/><br>
            </p>

            <p>
                <label>Confirmer nouvel email : </label><input type="text" id="formdroit" name="cnewemail"/><br>
            </p>

            <p>
                <label>Nouvelle addresse : </label><input type="text" id="formdroit" name="nouvelleaddresse"/><br>
            </p>

            <p>
                <label>Nouveau téléphone : </label><input type="text" id="formdroit" name="nouveautel"/><br>
            </p><br>

            <p>
                <label>Mot de passe pour pouvoir modifier : </label><input type="password" id="formdroit" name="mdp"/><br>
            </p>

            <p>
                <input id="validermodifinfos" type="submit" value="Valider" title="Vous modifiez vos informations"/>
            </p><br>

        </form>
    </div>

</div>

<h4 id="identificationutilisateur">Vous êtes l'utilisateur <?php echo $u->getNick()?> </h4>

    <div id="modifmdp">
        <h4 id="titremodifmdp">Modifier mon mot de passe</h4>
        <form method="post" action="http://localhost/g3c/website/index.php?c=User&a=setMDP">
        <p>
            <label>Ancien mot de passe : </label><input type="password" id="droit" name="ancienmdp"/><br>
        </p>

        <p>
            <label>Nouveau mot de passe : </label><input type="password" id="droit" name="nouveaumdp"/><br>
        </p>

        <p>
            <label>Confirmer le mot de passe : </label><input type="password" id="droit" name="cnouveaumdp"/><br>
        </p>

        <p>
            <input id="validermodifmdp" type="submit" value="Valider" title="Vous modifiez votre mot de passe"/>
        </p>
        </form>
    </div>