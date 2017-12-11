<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="inscription.css" />
        <title>Page d'inscription</title>
    </head>

    <header>
              <div id="logo"> <a href="D:\Bryan\ISEP\APP G3C\Info\Site\Premierepage\Premierepage.html"><img src=logopetit.png alt="logopetit.png"> </a></div>
              <p id="livewell">LiveWell</p>
              <p id="catchphrase"><i>Votre sécurité est notre priorité.</i></p>
              <p id="namepage">Inscription</p>
    </header>

    <body>
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

            <form method="post" action="url.php" id="surname">
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
                    <label>Login</label>: <input id="line" type="text" name="login" />
                </p>

                <p>
                    <label>Adresse mail</label>: <input id="line" type="text" name="mail" />
                </p>

                <p>
                    <label>Confirmer l'adresse mail</label>: <input id="line" type="text" name="mailconfirmation" />
                </p>

                <p>
                    <label>Mot de passe</label>: <input id="line" type="password" name="mdp" />
                </p>

                <p>
                    <label>Confirmer le mot de passe</label>: <input id="line" type="password" name="mdpconfirmation" />
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
            
    </body>

    <footer> 
              <p id="SAV">Contacter le service client <a href="SAV">ici</a><br><a href="FAQ">FAQ</a><br><a href="Mentions légales">Mentions légales</a></p>
              <p class="Renseignements" id="social">Réseaux sociaux</p>
              <a href="https://www.facebook.com/"><img id="Fb" src="Fb.png"></a>
              <a href="https://www.twitter.com/"><img id="Twitter" src="Twitter.png"></a>
              <p id="rights">2017 Livewell&copy all rights reserved</p>
    </footer>
</html>