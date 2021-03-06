<main xmlns="http://www.w3.org/1999/html">
<ul id="Menu">
    <li id="Mapropriete"><a class="button" href="properties/<?= $data["pid"]?>"><input type="button" value="Ma propriété" /></a> </li>
    <li id="Mespieces"><a class="button" href="properties/<?= $data["pid"] ?>/rooms"><input type="button" value="Mes pièces"/></a>
    </li>
    <li id="Mesperipheriques"><a class="button" href="properties/<?= $data["pid"] ?>/peripherals"><input type="button"
                                                                                          value="Mes périphériques"/></a>
    </li>
</ul>

            <h2 id="nompagepieces">Gestion de mes pièces</h2>





<p id="nombrepc">Vous avez actuellement <?php echo count($data["rooms"])?> pièces.</p>

          <div id="listepiece">
              <h3 id="pieces"><strong>Pièces</strong><br></h3>
              <div id="tablepieces">

                      <form action="properties/<?= $data["pid"] ?>/rooms/delete" method="post">
                          <table align="center">
                      <?php
                      //Créer un tableau qui s'indente en fonction du nombre de périphériques
                      $Nbrdonnees = count($data["rooms"]);
                      if ($Nbrdonnees != 0){
                          //Ici nous faisons le tableau avec ses titres
                          echo '<thead><tr>
                                    <th>Nom de la pièce</th>
                                    <th>Date de création</th>
                                    <th>Dernière mise à jour</th>
                                    <th>Gestion</th>
                                    </tr></thead>';

                //Ici nous ajoutons une ligne avec les infos
                foreach ($data["rooms"] as $r){

                    //On met les date sous le bon format
                    $du = date( "d/m/Y", $r->getLastUpdated()) . ' à ' . date( "H:i",$r->getLastUpdated() );
                    $dc = date( "d/m/Y", $r->getCreationDate()) . ' à ' . date( "H:i",$r->getCreationDate() );
                    //On récupère le nom pour des salles
                    $name = $r->getName();
                    $rid = $r->getID();



                              echo '<tr>
                                        <td><a class="link"  href="properties/' . $data["pid"] . "/rooms/" . $r->getID() . '">   
                                        '.htmlspecialchars($name).'</a></td> 
                                        <td>'. $du .'</td>
                                        <td>'. $dc .'</td>
                                        <td><input type="checkbox" name="rid[]" value="' . $rid . '"/></td>
                                      </tr>';}

            }

            else {
                echo 'Pas de pièces dans la propriété';
            }
            ?>
        </table>
                  <input type="submit" value="Supprimer"
                         onclick="return confirm('êtes vous sûr de vouloir supprimer toutes ces pièces ?')"/>
                      </form>
    </div>
</div>


<div id="ajouterpieces">
    <h3>Ajouter une pièce</h3>
    <div id="champsajouterpiece">
        <form method="post" name="ajouterpieces" action="properties/<?= $data["pid"] ?>/rooms/create">
            <br>
            <label>Nom de la pièce : </label><input type="text" name="name" /><br><br>
            <br>
            <input type="submit" value="Valider" id="validerajoutpiece" >
        </form>
    </div>
</div>
</main>