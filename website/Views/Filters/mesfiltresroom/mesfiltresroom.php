<main>
    <ul id="Menu">
        <li id="Moncompte"><a href="index.php?c=User&a=Informations"><input type="button" value="Mon compte" /></a></li>
        <li id="Mespieces"><a href="index.php?c=Room&a=RoomsPage"><input type="button" value="Mes pièces" /></a></li>
        <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=PeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
        <li id="Mesfiltres"><a href="index.php?c=Filtre&a=FiltersPage"><input type="button" value="Mes filtres" /></a></li>
        <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
    </ul>

    <div id="selectionsalle">
        <h2>Choix de la salle</h2>
        <form id="sallepropriété" action="index.php?c=filter&a=roomfilterspage&pid=1&debug=true" method="post">
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

    <div id="champsfiltres">
        <div id="filtres">
            <div id="unfiltre">
            <?php
            foreach ($data["measure_type"] as $measure_type){
                echo '<div id="filter"><h4>'.$measure_type->getName().' voulue</h4>
                      <form method="post" action="">
                        <input name="threshold" id="threshold" type="number" /><br><br>
                        <input type="submit"/>
                      </form></div>';
            }
            ?>
            </div>
        </div>
    </div>

</main>