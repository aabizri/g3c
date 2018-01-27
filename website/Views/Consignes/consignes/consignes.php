<main>

    <ul id="Menu">
        <li id="Moncompte"><a href="index.php?c=User&a=Informations"><input type="button" value="Mon compte" /></a></li>
        <li id="Mespieces"><a href="index.php?c=Room&a=Rooms"><input type="button" value="Mes pièces"/></a></li>
        <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=PeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
        <li id="Mesconsignes"><a href="index.php?c=Consigne&a=ConsignesPage"><input type="button" value="Mes Consignes" /></a></li>
        <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
    </ul>


    <div id="selectionsalle">
        <h2>Choix de la salle</h2>
        <form id="sallepropriété" action="index.php" method="get">
            <input type="hidden" name="c" value="Consigne"/>
            <input type="hidden" name="a" value="RoomConsignesPage"/>
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