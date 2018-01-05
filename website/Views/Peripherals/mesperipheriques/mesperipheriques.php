            <ul id="Menu">
              <li id="Moncompte"><a href="index.php?c=User&a=AccountPage"><input type="button" value="Mon compte" /></a></li>
              <li id="Mespieces"><a href="index.php?c=Room&a=RoomsPage"><input type="button" value="Mes pièces" /></a></li>
              <li id="Mesperipheriques"> <a href="index.php?c=Peripherals&a=PeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
              <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
              <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
            </ul>

            <h2 id="titreperipherique">Liste des peripériques connectés</h2>
            <p id="nombrepc">Vous avez actuellement <?php echo count($data["peripherals_list"])?> périphériques connectés.</p>

            <div id="listeperipheriques">
                <h3 id="peripheriques"><strong>Périphériques</strong><br></h3>
                <div id="tableperipheriques">
                    <table align="center">

                        <?php
                        //Créer un tableau qui s'indente en fonction du nombre de périphériques
                        $Nbrdonnees = count($data["peripherals_list"]);
                        if ($Nbrdonnees != 0){
                            //Ici nous faisons le tableau avec ses titres
                            echo '<thead><tr>
                                    <th>Nom du périphérique</th>
                                    <th>Localisation</th>
                                    <th>Dernière mise à jour</th>
                                    <th>UUID</th>
                                    <th>Gestion</th>
                                    </tr></thead>';

                            //Ici nous ajoutons une ligne avec les infos
                            foreach ($data["peripherals_list"] as $p){

                                $date = date( "d/m/Y", $p->getLastUpdated()) . ' à ' . date( "H:i",$p->getLastUpdated() );
                                $room = $p->getRoom();

                                echo '<tr><form action="index.php?c=Peripherals&a=DissociatePeripheralFromProperty" method="post" >
                                        <td>'. $p->getDisplayName() .'</td> 
                                        <td>'. $room->getName() .'</td>
                                        <td>'. $date .'</td>
                                        <td><input type="hidden" name="peripheral_id" value="'. $p->getUUID() .'"/>'. $p->getUUID() .'</td>
                                        <td><form action="index.php?c=Peripherals&a=DissociatePeripheralFromProperty" method="post" ><input type="submit" value="Supprimer"/></form></td>
                                      </tr>';}

                            }
                        
                        else {
                            echo 'Pas de périphériques dans la propriété';
                        }

                        ?>
                    </table>
                </div>
            </div>

            <div id="ajouterperipherique">
                <h3>Ajouter un périphérique</h3>
                <div id="champsajouterperipherique">
                    <form name="Ajouter un peripherique" method="post" action="index.php?c=Peripherals&a=AddPeripheral">
                        <label>UUID : </label><input type="text" name="uuid" /><br><br>
                        <label>Nom du périphérique : </label><input type="text" name="display_name" />
                            <p> Dans quelle salle ? <!-- Faire un lien avec la branche dev-room. Utiliser une liste
                             déroulante et récupérer les id qui correspondent ici à la value-->
                                <input type="checkbox" value="1" name="room_id"/> <!--value = 1 pour tester--><label>Chambre</label>
                            </p>
                        <br>
                        <input type="submit" value="Ajouter un périphérique" id="ajouterperipheriquebouton" >
                    </form>
                </div>
            </div>