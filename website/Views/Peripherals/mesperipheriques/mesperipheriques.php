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
                    <form name="Ajouter un peripherique" action="php ajouter pièce">
                        <label>UUID : </label><input type="text" /><br><br>
                        <label>Nom du périphérique : </label><input type="text" />
                        <form method="post" action="php pour ajouter à la salle" id="choixsalle">
                            <p> Dans quelle salle ?
                            <select name="Choix de la salle">
                                <option value="" >Salon</option>
                                <option value="">Salle de bain</option>
                                <option value="">Cuisine</option>
                                <option value="">Chambre 1</option>
                                <option value="">Chambre 2</option>
                            </select>
                            </p>
                        </form>
                        <label>Type de périphérique : </label><br><br>
                        <input type="checkbox" value="capteurtempérature" name=""> Température
                        <input type="checkbox" value="capteurpresence" name=""> Présence
                        <input type="checkbox" value="capteurpression" name=""> Pression
                        <input type="checkbox" value="capteurluminosite" name=""> Luminosité
                        <input type="checkbox" value="capteurqualiteair" name=""> Qualité de l'air
                        <input type="checkbox" value="capteurhumidite" name=""> Humidité
                        <br>
                        <br>
                        <input type="submit" value="Ajouter un périphérique" id="ajouterperipheriquebouton" >
                    </form>
                </div>
            </div>