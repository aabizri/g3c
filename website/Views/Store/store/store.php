<main>

    <div id="navigation"> <!--Boutons pour accéder aux différentes catégories-->
        <a href="boutique">
            <button title="Voir les Périphériques" id="bouton1">Periphériques</button>
        </a>
        <a href="boutique">
            <button title="Voir les accessoires" id="bouton2">Accessoires</button>
        </a>
    </div>


    <div id="peripheriques">
        <p id="titre1">Périphériques</p><br>
        <div id="texteCeMac">
            <?php
            foreach ($data["products_peripherals"] as $product) {
                echo '<div class="product-peripheral">';
                echo '<span class="productname" id="productname">' . $product->getName() . '</span>' . ": ";
                echo $product->getDescription() . '<br><br>';
                echo '<img src="Ressources/Produits/' . $product->getID() . '.png" ><br>';
                echo 'Son prix est de : ';
                echo $product->getPrix() . "€. <br><br>";
                echo '</div>';
            }
            ?>
        </div>

    </div>

    <div id="accessoires">
        <p id="titre2">Accessoires</p><br>
        <?php
        foreach ($data["products_accessory"] as $product) {
            echo '<div class="product-accessory">';
            echo '<span class="productname" id="productname">' . $product->getName() . '</span>' . ": ";
            echo $product->getDescription() . '<br><br>';
            echo '<img src="Ressources/Produits/' . $product->getID() . '.png" ><br>';
            echo 'Son prix est de : ';
            echo $product->getPrix() . "€. <br><br>";
            echo '</div>';
        }
        ?>
    </div>


</main>