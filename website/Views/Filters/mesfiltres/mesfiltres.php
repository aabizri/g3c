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
        <form id="sallepropriété">
            <?php
            foreach ($data["salles"] as $salle)
                echo ""
            ?>
        </form>
    </div>

    <div id="filtres">
    <?php
        foreach ($data["measure_type"] as $measure_type){
            echo '<h2>'.$measure_type->getName().' voulue</h2>
                  <form method="post" action="">
                    <input type="number" />
                  </form>';
        }
    ?>
    </div>

</main>