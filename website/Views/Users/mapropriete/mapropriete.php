<main>
<ul id="Menu">
    <li id="Mapropriete"><a class="button" href="properties/<?= $data["property"]->getID() ?>"><input type="button"
                                                                                       value="Ma propriété"/></a></li>
    <li id="Mespieces"><a class="button" href="properties/<?= $data["property"]->getID() ?>/rooms"><input type="button"
                                                                                           value="Mes pièces"/></a>
    </li>
    <li id="Mesperipheriques"><a class="button" href="properties/<?= $data["property"]->getID() ?>/peripherals"><input type="button"
                                                                                                        value="Mes périphériques"/></a>
    </li>
</ul>

<h2 id="titrepage">Ma propriété</h2>

<div id="property">
    <h4>Informations sur ma propriété</h4>
    <?php
    $property = $data["property"];
    echo "<p><strong>Nom de la propriété : </strong></p>" . $property->getName();
    echo "<p><strong>Addresse de la propriété : </strong></p>" . $property->getAddress();
    ?>
</div>

<div id="creervoirutilisateur">

    <div id="voirutilisateurs">
        <h4 id="titrelisteutilisateurs">Liste des utilisateurs</h4>
        <table id="propertyusers" align="center">
            <?php
            //Créer un tableau qui s'indente en fonction du nombre d'utilisateurs
            $Nbrdonnees = count($data["users_list"]);
            if ($Nbrdonnees != 0) {
                //Ici nous faisons le tableau avec ses titres
                echo '<thead><tr>
                          <th>Utilisateur</th>
                          <th>Gestion</th>
                          </tr></thead>';

                //Ici nous ajoutons une ligne avec les infos et la possibilité de supprimer la personne
                foreach ($data["users_list"] as $u) {
                    echo '<tr>
                              <td>' . $u->getNick() . '</td>
                              <td>
                                  <form action="properties/' . $data["property"]->getID() . '/removeuser" method="post" >
                                      <input type="hidden"  name="user_id" value="' . $u->getID() . '" />    
                                      <input type="submit" value="Supprimer"/>
                                  </form>
                              </td>
                              </tr>';
                }
            }
            ?>
        </table>
    </div>


    <div id="creerutilisateur">
        <h4 id="titrecreerutilisateur">Ajouter un utilisateur à la propriété</h4>
        <form method="post" action="properties/<?= $data["property"]->getID() ?>/adduser">
            <p>
                <label>Login</label>: <input id="formdroit" type="text" name="nickname"/>
            </p>
            <input id="validercreationutilisateur" type="submit" value="Valider"
                   title="Vous créez un nouvel utilisateur"/>
        </form>
    </div>
</div>
</main>