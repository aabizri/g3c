            <ul id="Menu">
              <li id="Moncompte"><a href="index.php?c=User&a=getAccountPage"><input type="button" value="Mon compte" /></a></li>
              <li id="Mespieces"><a href="index.php?c=Room&a=getRoomsPage"><input type="button" value="Mes pièces" /></a></li>
              <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=getPeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
              <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
              <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
            </ul>

            <h2 id="titreperipherique">Liste des peripériques connectés</h2>
            <p id="nombrepc">Vous avez actuellement n périphériques connectés.</p>

            <div id="listeperipheriques">
                <h3 id="peripheriques"><strong>Périphériques</strong><br></h3>
                <div id="tableperipheriques">
                    <table align="center">
                        <thead><tr>
                            <th>Type périphérique</th>
                            <th>Localisation</th>
                            <th>Etat</th>
                        </tr></thead>
                        <tbody><tr>
                            <td>Thermomètre</td>
                            <td>Chambre 1</td>
                            <td>En marche</td>
                        </tr></tbody>
                        
                    </table>
                </div>
            </div>

            <div id="ajouterperipherique">
                <h3>Ajouter un périphérique</h3>
                <div id="champsajouterperipherique">
                    <form name="Ajouter un peripherique" method="post" action="index.php?c=Peripherals&a=postAddPeripheral">
                        <label>UUID : </label><input type="text" name="uuid" /><br><br>
                        <label>Nom du périphérique : </label><input type="text" name="display_name" />
                            <p> Dans quelle salle ?
                                <input type="checkbox" value="1" name="room_id"/><label>Chambre</label>
                            </p>
                        <br>
                        <br>
                        <input type="submit" value="Ajouter un périphérique" id="ajouterperipheriquebouton" >
                    </form>
                </div>
            </div>