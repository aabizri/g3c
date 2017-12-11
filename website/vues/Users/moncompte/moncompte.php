<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="moncompte.css" />
        <title>Mon compte</title>
    </head>

    <header>
              <div id="Logo"> <a href="paged'accueil.html"><img src=logopetit.png alt="logopetit.png"> </a></div>
              <p id="Nom">LiveWell</p>
              <p id="Phrasedaccroche"><i>Votre sécurité est notre priorité.</i></p>
              <p id="Nomdelapage">Mon compte</p>
            </header>
    <body>
          <!--Faire un lien entre le logo et la page d'accueil-->

          <ul id="Menu">
              <li id="Moncompte"><a href="Moncompte.html"><input type="button" value="Mon compte" /></a></li>
              <li id="Mespieces"><a href="Mespieces.html"><input type="button" value="Mes pièces" /></a></li>
              <li id="Mesperipheriques"> <a href="Mesperipheriques.html"><input type="button" value="Mes périphériques" /></a></li>
              <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
              <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
          </ul>

          <h2 id="titremoncompte" >Informations sur mon compte</h2>

       <div id="infosmoncompte">
        <div id="mesinformations">
            <h4 id="titremesinfos">Mes informations</h4>
            <p>Nom : $_POST['']</p>
            <p>Prénom : $_POST['']</p>
            <p>Email : $_POST['']</p>
            <p>Addresse : $_POST['']</p>
            <p>Téléphone : $_POST['']</p>
            <p>Login : $_POST['']</p>
        </div>

        <div id="modifiermesinformations">
            <h4 id="titremodifierinfos" >Modifier mes informations</h4>
            <form id="formulairemodif" action="pagephp">
                <p><label>Nouvel email : </label><input type="text" id="formdroit" name="" /><br></p>
                <p><label>Confirmer nouvel email : </label><input type="text" id="formdroit" name="" /><br></p>
                <p><label>Nouvelle addresse : </label><input type="text" id="formdroit" name="" /><br></p>
                <p><label>Nouveau téléphone : </label><input type="text" id="formdroit" name="" /><br></p>
                <p><label>Ancien mot de passe : </label><input type="text" id="formdroit" name="" /><br></p>
                <p><label>Nouveau mot de passe : </label><input type="text" id="formdroit" name="" /><br></p>
                <p><label>Confirmer le mot de passe : </label><input type="text" id="formdroit" name="" /><br></p>
                <p><input id="validermodifinfos" type="submit" value="Valider" title="Vous modifiez vos informations" /></p>
            </form>
        </div>

       </div>

          <h4 id="identificationutilisateur">Vous êtes l'utilisateur $_SESSION['']</h4>

          <div id="creervoirutilisateur">

          <div id="voirutilisateurs">
              <h4 id="titrelisteutilisateurs">Liste des utilisateurs</h4>
              <p id="nomsutilisateurs">$_POST['Eytan'] <input id="boutonssupprimer" type="button" value="Supprimer" title="Vous allez supprimer cet utilisateur" /></p>
              <p id="nomsutilisateurs">$_POST['Bryan'] <input id="boutonssupprimer" type="button" value="Supprimer" title="Vous allez supprimer cet utilisateur" /></p>
              <p id="nomsutilisateurs">$_POST['Jérémy']  <input id="boutonssupprimer" type="button" value="Supprimer" title="Vous allez supprimer cet utilisateur" /></p>
              <p id="nomsutilisateurs">$_POST['Dinesh']  <input id="boutonssupprimer" type="button" value="Supprimer" title="Vous allez supprimer cet utilisateur" /></p>
              <p id="nomsutilisateurs">$_POST['Alexandre']  <input id="boutonssupprimer" type="button" value="Supprimer" title="Vous allez supprimer cet utilisateur" /></p>
              <p id="nomsutilisateurs">$_POST['Randy']  <input id="boutonssupprimer" type="button" value="Supprimer" title="Vous allez supprimer cet utilisateur" /></p>

          </div>


        <div id="creerutilisateur">
            <h4 id="titrecreerutilisateur">Créer un utilisateur</h4>
            <form action="pagephp">
                <p><label>Login</label>: <input id="formdroit" type="text" name="login" /></p>
                <p><label>Mot de passe : </label><input id="formdroit" type="password" name="password" /></p>
                <p><label>Confirmer mot de passe: </label><input id="formdroit" type="password" name="password" /></p>
                <input id="validercreationutilisateur" type="submit" value="Valider" title="Vous créez un nouvel utilisateur" />
            </form>
        </div>
       </div>

    </body>
            <footer> 
              <p id="SAV">Contacter le service client <a href="SAV">ici</a><br><a href="FAQ">FAQ</a><br><a href="Mentions légales">Mentions légales</a></p>
              <p class="Renseignements" id="Reseauxsociaux">Réseaux sociaux</p>
              <a href="https://www.facebook.com/"><img id="Fb" src="Fb.png"></a>
              <a href="https://www.twitter.com/"><img id="Twitter" src="Twitter.png"></a>
              <p id="rights">2017 Livewell&copy all rights reserved</p>
            </footer>

</html>