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




        <?php
                $last_measures = $data["last_measures"];

                foreach ($last_measures as $measure)
                {
                    echo "<p class=\"temperature\"><strong>";
                    $measure_type=(new \Queries\MeasureTypes)-> retrieve($measure -> getTypeID());
                    echo $measure_type->getName()."</strong><br><br>"
                        .$measure->getValue()."". $measure_type->getUnitSymbol()."<br></p>";
                }

        ?>



    </div>


</div>
