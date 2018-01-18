          <ul id="Menu">
              <li id="Moncompte"><a href="index.php?c=User&a=AccountPage"><input type="button" value="Mon compte" /></a></li>
              <li id="Mespieces"><a href="index.php?c=Room&a=RoomsPage"><input type="button" value="Mes pièces" /></a></li>
              <li id="Mesperipheriques"><a href="index.php?c=Peripherals&a=List"><input type="button"
                                                                                        value="Mes périphériques"/></a>
              </li>
              <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
              <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
          </ul>

            <h2 id="nompagepieces">Gestion de mes pièces</h2>

          <form method="post" action="index.php?c=Room&a=AccessRoom" id="choixsalle">
            <select name="salle">
                <?php

                    foreach ( $data["rooms"] as $room) {
                        echo "<option value=". $room -> getID() .">".$room->getName()."</option>";
                    }
                ?>
            </select>

              <input type="submit" value="valider" />

          </form>

            <div id="vueinformation">
            <h3>Informations sur la pièce</h3>
                <div id="informationspiece">
                    <p id="temperature"><strong>Température</strong><br><br>.</p>
                    <p id="humidite"><strong>Humidité</strong><br><br>.</p>
                    <p id="luminosite"><strong>Lumisosité</strong><br><br>.</p>
                    <p id="qualiteair"><strong>Qualité de l'air</strong><br><br>.</p>
                    <p id="presence"><strong>Présence</strong><br><br>.</p>
                </div>
            </div>

            <div id="action">
            <h3>Actions</h3>
                <form action="">
                <div id="actionspieces">
                    <p>Température voulue(°C)<br><br><input type="number" class="action" name="temperaturevoulue" /><br><br><input type="submit" value="Valider" id="valider" /></p>
                    <p>Luminosité voulue(%)<br><br><input type="number"class="action" name="luminositevoulue"/><br><br><input type="submit" value="Valider" id="valider"/></p>
                    <p>Ouvrir/Fermer volets<br><br><input type="button" class="action" value="Ouvrir" onclick="Fermer"/><br>
                        <input type="button" class="action" value="Fermer" onclick="Fermer" >
                    </p>
                </div>
                </form>
            </div>

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