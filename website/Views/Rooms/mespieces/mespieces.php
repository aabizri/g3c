          <ul id="Menu">
              <li id="Moncompte"><a href="index.php?c=User&a=AccountPage"><input type="button" value="Mon compte" /></a></li>
              <li id="Mespieces"><a href="index.php?c=Room&a=Rooms"><input type="button" value="Mes pièces"/></a></li>
              <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=PeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
              <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
              <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
          </ul>

            <h2 id="nompagepieces">Gestion de mes pièces</h2>



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
                        echo "<option value=".$room->getID()."\>".$room->getName();
                    }
                ?>
            </select>

              <input type="submit" value="Valider" title="Valider pour accéder à la salle" />

          </form>

              <?php
          endif
          ?>


            <div id="ajouterpieces">
                <h3>Ajouter une pièce</h3>
                <div id="champsajouterpiece">
                    <form name="Ajouter une pièce" action="php ajouter pièce">
                        <label>Nom de la pièce : </label><input type="text" /><br><br>
                        <label>Capteurs à ajouter : </label><br><br>
                            <input type="checkbox" value="capteurtempérature"> Température
                            <input type="checkbox" value="capteurpresence" > Présence
                            <input type="checkbox" value="capteurpression"> Pression
                            <input type="checkbox" value="capteurluminosite"> Luminosité
                            <input type="checkbox" value="capteurqualiteair"> Qualité de l'air
                            <input type="checkbox" value="capteurhumidite"> Humidité
                        <br>
                        <br>
                        <input type="submit" value="Valider" >
                    </form>
                </div>
            </div>