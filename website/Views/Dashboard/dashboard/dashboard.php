<main>
    <ul id="Menu">
        <li id="Mapropriete"><a class="button" href="properties/<?= $data["pid"]?>"><input type="button" value="Ma propriété" /></a> </li>
        <li id="Mespieces"><a class="button" href="properties/<?= $data["pid"] ?>/rooms"><input type="button" value="Mes pièces"/></a>
        </li>
        <li id="Mesperipheriques"><a class="button" href="properties/<?= $data["pid"] ?>/peripherals"><input type="button" value="Mes périphériques"/></a>
        </li>
    </ul>

    <h2 id="Tableaudebord">Tableau de bord de ma propriété</h2>
    <br><div id="setting">
        <a id="settings" href="properties/<?= $data["pid"] ?>/settings"><button title="Ajouter/Supprimer des utilisateurs de la propriété">Gestion de ma propriété</button></a></div>

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
