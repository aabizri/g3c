<p>
    <div id="infos">
    <h3>INFORMATIONS GENERALES :</h3>

<p id="civilite">Civilité :
    <div id="hommefemme">
    <input type="radio" name="civilite" value="Homme" checked="checked" /><label for="civilite">Homme</label>
    <input type="radio" name="civilite" value="Femme" /><label for="civilite">Femme</label>
    </div>
</p>

<div id="leftline">

<form method="post" action="http://localhost/g3c/index.php?c=User&a=postJoin" id="surname">
    <p>
         <label>Nom</label>: <input id="line" type="text" name="surname"  />
    </p>

    <p>
        <label>Prénom</label>: <input id="line" type="text" name="name" />
    </p>

    <p>
        <label>Téléphone</label>: <input id="line" type="text" name="phone"/>
    </p>

    <p>
        <label>Login</label>: <input id="line" type="text" name="nick" />
    </p>

    <p>
        <label>Adresse mail</label>: <input id="line" type="text" name="email" />
    </p>

    <p>
        <label>Confirmer l'adresse mail</label>: <input id="line" type="text" name="email_conf" />
    </p>

    <p>
        <label>Mot de passe</label>: <input id="line" type="password" name="password" />
    </p>

    <p>
        <label>Confirmer le mot de passe</label>: <input id="line" type="password" name="password_conf" />
    </p>


</div>

      <p id="confirmation">Une fois inscrit, vous recevrez un lien de confirmation.<br>Cliquez sur le lien pour valider votre compte.</p>
    <p id="CGU">
        <input type="checkbox" value="" />
        <label>J'ai lu et j'accepte les <a href="CGU">CGU</a></label>
    </p>
    <input type="submit" value="Valider">

</div>
</form>
