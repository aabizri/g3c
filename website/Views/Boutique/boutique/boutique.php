<main>

                      <div id="navigation"> <!--Boutons pour accéder aux différentes catégories-->
                  <a href="index.php?c=Product&a=ProductList#"><button title="Voir les Périphériques" id="Periphériques">Periphériques</button></a>
                  <a href="index.php?c=Product&a=ProductList#accessoire"><button title="Voir les accessoires" id="accessoire">Accessoires</button></a>
                      </div>


                      <div id="peripheriques">
                          <p id="titre1">Périphériques</p><br>
                          <p id="texteCeMac"><u>
                                  <?php
                                    foreach ($data["products"] as $product)
                                  {
                                      echo $product->getName()." :";
                                      echo $product->getDescription()." :";
                                      echo $product->getPrix()." :";
                                  }


                                  ?>


                          <img src="../../../Ressources/Produits/CeMac.png" title="Photo du CeMac" id="imageCeMac">
                      </div>

                      <div id="accessoire">
                         <p id="titre2">Accessoires</p><br>
                      </div>


</main>