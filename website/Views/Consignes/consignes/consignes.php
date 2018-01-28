<main>
    <ul id="Menu">
        <li id="Moncompte"><a href="account"><input type="button" value="Mon compte"/></a></li>
        <li id="Mespieces"><a href="properties/<?= $data["pid"] ?>/rooms"><input type="button" value="Mes pièces"/></a>
        </li>
        <li id="Mesperipheriques"><a href="properties/<?= $data["pid"] ?>/peripherals"><input type="button"
                                                                                              value="Mes périphériques"/></a>
        </li>
        <li id="Mesconsignes"><a href="properties/<?= $data["pid"] ?>/consignes"><input type="button"
                                                                                        value="Mes Consignes"/></a></li>
    </ul>


    <div id="selectionsalle">
        <h2>Choix de la salle</h2>
        <form id="sallepropriété" action="index.php" method="get">
            <input type="hidden" name="c" value="Consignes"/>
            <input type="hidden" name="a" value="RoomConsignes"/>
            <select name="room_id">
                <?php
                foreach ($data["property_rooms"] as $pr){
                    echo "<option value='".$pr->getID()."'>".$pr->getName() ."</option>";
                    }
                    ?>
            </select>
            <input type="submit" value="Valider">
        </form>
    </div>


</main>