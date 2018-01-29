<main>
    <ul id="Menu">
        <li id="Moncompte"><a href="account"><input type="button" value="Mon compte"/></a></li>
        <li id="Mapropriete"><a href="properties/<?= $data["pid"]?>"><input type="button" value="Ma propriété" /></a> </li>
        <li id="Mespieces"><a href="properties/<?= $data["pid"] ?>/rooms"><input type="button" value="Mes pièces"/></a>
        </li>
        <li id="Mesperipheriques"><a href="properties/<?= $data["pid"] ?>/peripherals"><input type="button"
                                                                                              value="Mes périphériques"/></a>
        </li>
        <li id="Mesconsignes"><a href="properties/<?= $data["pid"] ?>/consignes"><input type="button"
                                                                                        value="Mes Consignes"/></a></li>
    </ul>

    <h2 id="Tableaudebord">Tableau de bord</h2>
    <div id="setting">
    <a id="settings" href="properties/<?= $data["pid"] ?>/settings">Réglages</a></div>

    <?php

    $rooms=$data["rooms"];
    $last_measures= $data["last_measures"];

    // Si il n'y a pas de pièce
    if (empty($rooms)) {
        echo "<p id='norooms' >Pas de pièces dans la propriété. Vous pouvez en rajouter dans 'Mes pièces'.</p> ";
        return;
    }

    foreach ($rooms as $r)
    {

        $room_measures=$last_measures[$r->getID()];
        echo    '<div class="vueinformation">
                <h3 class="nomdelapièce">';

        $room_name = $r->getName();
        echo $room_name. '</h3>';
        if($room_measures===[]){
        echo '<p>Pas de capteur dans la pièce</p>';}
        else
            echo '<h4>Information pièce</h4>
        
            <div class="informationspiece">';

        foreach ($room_measures as $measure)
        {
            echo "<p class=\"temperature\"><strong class='temperatureName'>";
            $measure_type = (new \Queries\MeasureTypes)->retrieve($measure->getTypeID());
            echo $measure_type->getName() . "</strong><br><br>"
                . $measure->getValue() . "" . $measure_type->getUnitSymbol() . '<br></p>';
        }
        echo '</div></div>';
     }
     ?>
