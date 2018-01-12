<ul id="Menu">
    <li id="Moncompte"><a href="index.php?c=User&a=AccountPage"><input type="button" value="Mon compte" /></a></li>
    <li id="Mespieces"><a href="index.php?c=Room&a=Rooms"><input type="button" value="Mes pièces"/></a></li>
    <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=PeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
    <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
    <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
</ul>

<h2 id="nompiece">ma pièce</h2>

<?php
if (!empty($data["rooms"])):
?>
<form method="get" action="index.php" id="choixsalle">
    <input type="hidden" name="c" value="Room"/>
    <input type="hidden" name="a" value="LastMeasure"/>
    <select name="room">
        <?php
        $rooms = $data["rooms"];
        foreach ($rooms as $room) {
            echo '<option value="'.$room->getID().'">'.$room->getName().'</option>';
        }
        ?>
    </select>

    <input type="submit" value="Valider" title="Valider pour accéder à la salle" />

</form>
 <?php
 endif
?>
<div id="vueinformation">
    <h3>Informations sur la pièce</h3>
    <div id="informationspiece">
        <p id="temperature"><strong>Température</strong><br><br>
            <?php
                        $last_measures = $data["last_measures"];
                        foreach ($last_measures as $measure)
                        { echo $measure->getValue()."<br>";}

            ?>
        </p>
        <p id="humidite"><strong>Humidité</strong><br><br>20%</p>
        <p id="luminosite"><strong>Lumisosité</strong><br><br>70%</p>
        <p id="qualiteair"><strong>Qualité de l'air</strong><br><br>Bonne</p>
        <p id="presence"><strong>Présence</strong><br><br>1</p>
    </div>
</div>

<div id="action">
    <h3>Actions</h3>
    <form action="">
        <div id="actionspieces">
            <p>Température voulue(°C)<br><br><input type="number" class="action" name="temperaturevoulue" /><br><br><input type="submit" value="Valider" id="valider" /></p>
            <p>Luminosité voulue(%)<br><br><input type="number"class="action" name="luminositevoulue"/><br><br><input type="submit" value="Valider" id="valider"/></p>
            <p>Ouvrir/Fermer volets<br><br><input type="button" class="action" value="Ouvrir" onclick="Fermer"/><br>
                <input type="button" class="action" value="Fermer" onclick="Fermer" >
            </p>
        </div>
    </form>
</div>