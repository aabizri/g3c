<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="mespieces.css" />
    <title>Mes pièces</title>
</head>
<body>
<header>
    <div id="Logo"> <a href="paged'accueil.html"><img src=logopetit.png alt="logopetit.png"> </a></div>
    <p id="Nom">LiveWell</p>
    <p id="accroche"><i>Votre sécurité est notre priorité.</i></p>
    <p id="Nomdelapage">Tableau de bord</p>
</header>

<!--Faire un lien entre le logo et la page d'accueil-->

<ul id="Menu">
    <li id="Moncompte"><a href="Moncompte.html"><input type="button" value="Mon compte" /></a></li>
    <li id="Mespieces"><a href="Mespieces.html"><input type="button" value="Mes pièces" /></a></li>
    <li id="Mesperipheriques"> <a href="Mesperipheriques.html"><input type="button" value="Mes périphériques" /></a></li>
    <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
    <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
</ul>

<h2 id="Tableaudebord">Tableau de bord</h2>

<div id="vueinformation">
    <h3>Vues favorites</h3>
    <div id="informationspiece">
        <p id="temperature"><strong>Température globale</strong><br><br>22°C</p>
        <p id="humidite"><strong>Humidité du salon</strong><br><br>17%</p>
        <p id="luminosite"><strong>Lumisosité de la chambre 1</strong><br><br>68%</p>
        <p id="qualiteair"><strong>Qualité de l'air du salon</strong><br><br>Bonne</p>
        <p id="presence"><strong>Présence dans la maison</strong><br><br>3</p>
    </div>
</div>

<div id="action">
    <h3>Actions favorites</h3>
    <form action="">
        <div id="actionspieces">
            <p>Température voulue dans la chambre des enfants(°C)<br><br><input type="number" class="action" name="temperaturevoulue" /><br><br><input type="submit" value="Valider" id="valider" /></p>
            <p>Luminosité voulue dans le salon(%)<br><br><input type="number"class="action" name="luminositevoulue"/><br><br><input type="submit" value="Valider" id="valider"/></p>
            <p>Ouvrir/Fermer volets<br><br><input type="button" class="action" value="Ouvrir" onclick="Fermer"/><br>
                <input type="button" class="action" value="Fermer" onclick="Fermer" >
            </p>
        </div>
    </form>
</div>



<footer>
    <p id="SAV">Contacter le service client <a href="SAV">ici</a><br><a href="FAQ">FAQ</a><br><a href="Mentions légales">Mentions légales</a></p>
    <p class="Renseignements" id="Reseauxsociaux">Réseaux sociaux</p>
    <a href="https://www.facebook.com/"><img id="Fb" src="Fb.png"></a>
    <a href="https://www.twitter.com/"><img id="Twitter" src="Twitter.png"></a>
    <p id="rights">2017 Livewell&copy all rights reserved</p>
</footer>
</body>
</html>