<ul id="Menu">
    <li id="Moncompte"><a href="index.php?c=User&a=AccountPage"><input type="button" value="Mon compte" /></a></li>
    <li id="Mespieces"><a href="index.php?c=Room&a=Rooms"><input type="button" value="Mes pièces"/></a></li>
    <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=PeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
    <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
    <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
</ul>

<h2 id="Tableaudebord">Tableau de bord</h2>

<?php

    $rooms=$data["rooms"];
    $last_measures= $data["last_measures"];

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
            echo "<p class=\"temperature\"><strong>";
            $measure_type = (new \Queries\MeasureTypes)->retrieve($measure->getTypeID());
            echo $measure_type->getName() . "</strong><br><br>"
                . $measure->getValue() . "" . $measure_type->getUnitSymbol() . '<br></p>';
        }
        echo '</div></div>';
     }
     ?>

