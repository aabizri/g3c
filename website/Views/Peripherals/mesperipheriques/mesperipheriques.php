            <ul id="Menu">
                <li id="Moncompte"><a href="index.php?c=User&a=Informations"><input type="button" value="Mon compte" /></a></li>
                <li id="Mesperipheriques"><a href="index.php?c=Peripherals&a=List"><input type="button" value="Mes périphériques"/></a></li>
              <li id="Mespieces"><a href="index.php?c=Room&a=RoomsPage"><input type="button" value="Mes pièces" /></a></li>
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
                                    <th>Statut</th>
                                    <th>Gestion</th>
                                    </tr></thead>';

                            //Ici nous ajoutons une ligne avec les infos
                            foreach ($data["peripherals_list"] as $p){

                                //On met la date sous le bon format
                                $date = date( "d/m/Y", $p->getLastUpdated()) . ' à ' . date( "H:i",$p->getLastUpdated() );

                                //On récupère l'entité salle lié au périphérique pour récupérer le nom de la salle
                                $room = $p->getRoom();

                                //Si le périphérique n'est lié à aucune salle
                                if ($p->getRoomId() !== null){
                                    $room_name= $room->getName();
                                }
                                else{
                                    $room_name = "Ce périphériques n'est pas lié à une salle";
                                }

                                //Le nom du périphérique
                                if ($p->getDisplayName()=== null){
                                    $peripheral_name = "Ce périphérique n'a pas de nom";
                                }
                                else{
                                    $peripheral_name = $p->getDisplayName();
                                }

                                if ($p->getStatus() === "Fonctionnel" ){
                                    $status = "<img id='status' src='http://www.icone-png.com/png/29/29350.png' alt='Fonctionnel' title='Fonctionnel' />";
                                }
                                elseif ($p->getStatus() === "Non-fonctionnel"){
                                    $status = "<img id='status' src='http://www.ic%C3%B4ne.com/images/icones/2/0/attention.png' alt='Non-fonctionnel' title='Non-fonctionnel' />";
                                }
                                else {
                                    $status = "<img id='status' src='http://www.icone-png.com/png/16/16126.png' alt='Pas de capteurs liés' title='Pas de capteurs liés' />";
                                }

                                echo '<tr><form action="index.php?c=Peripherals&a=Remove&pid=1&debug=true" method="post" >
                                        <td>'. $peripheral_name .'</td> 
                                        <td>'. $room_name .'</td>
                                        <td>'. $date .'</td>
                                        <td><input type="hidden" name="peripheral_id" value="'. $p->getUUID() .'"/>'. $p->getUUID() .'</td>
                                        <td>'. $status .'</td>
                                        <td><form action="index.php?c=Peripherals&a=Remove&pid=1&debug=true" method="post" ><input type="submit" value="Supprimer"/></form></td>
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
                    <form name="Ajouter un peripherique" method="post" action="index.php?c=Peripherals&a=Add&pid=1&debug=true">
                        <label>UUID : </label><input type="text" name="uuid" /><br><br>
                        <label>Nom du périphérique : </label><input type="text" name="display_name" />
                        <p> Dans quelle salle ?
                            <select name="room_id">
                                <?php
                                foreach ($data["property_room"] as $pr){
                                    echo "<option value='".$pr->getID()."'>".$pr->getName() ."</option>";
                                }
                                ?>
                            </select>
                        </p>
                        <input type="submit" value="Ajouter un périphérique" id="ajouterperipheriquebouton" >
                        <br>
                    </form>
                </div>
            </div>