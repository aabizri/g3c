<ul id="Menu">
    <li id="properties"><a href="properties"><input type="button" value="Mes propriétés"/></a></li>
</ul>

<h4 id="identificationutilisateur">Vous êtes l'utilisateur
    <?php
        $u = $data["user"];
        echo $u->getNick()
    ?>
</h4>

<div id="infosmoncompte">
    <div id="mesinformations">
        <h4 id="titremesinfos">Mes informations</h4>
        <?php
                echo
                "
                <p>Prénom/Nom : ".$u -> getDisplay()."</p>
                <p>Email : ".$u -> getEmail()."</p>
                <p>Téléphone : ".$u -> getPhone()."</p>
                <p>Login : ".$u -> getNick()."</p>
                 ";
        ?>

    </div>

    <div id="modifiermesinformations">
        <h4 id="titremodifierinfos">Modifier mes informations</h4>
        <form method="post" id="formulairemodif" action="account" name="modifierinfos"
              onsubmit="return validateEmail()">
            <p>
                <label>Nouvel email :</label><input type="email" id="formdroit" name="email"/><br>
            </p>

            <p>
                <label>Confirmer nouvel email : </label><input type="email" id="formdroit" name="newemail"/><br>
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

    <div id="modifmdp">
        <h4 id="titremodifmdp">Modifier mon mot de passe</h4>
        <form method="post" action="account/changepassword" name="modifiermdp" onsubmit=" return validateMdp()">
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