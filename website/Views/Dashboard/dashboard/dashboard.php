<ul id="Menu">
    <li id="Moncompte"><a href="index.php?c=User&a=AccountPage"><input type="button" value="Mon compte" /></a></li>
    <li id="Mespieces"><a href="index.php?c=Room&a=Rooms"><input type="button" value="Mes pièces"/></a></li>
    <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=PeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
    <li id="Mesconsignes"><a href="index.php?c=Consigne&a=ConsignesPage"><input type="button" value="Mes Consignes" /></a></li>
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
