<html>
<ul id="Menu">
    <li id="Moncompte"><a href="index.php?c=User&a=AccountPage"><input type="button" value="Mon compte" /></a></li>
    <li id="Mapropriete"><a href="http://localhost/g3c/website/index.php?c=Property&a=PropertyPage"><input type="button" value="Ma propriété" /></a> </li>
    <li id="Mespieces"><a href="index.php?c=Room&a=RoomsPage"><input type="button" value="Mes pièces" /></a></li>
    <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=PeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
    <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
    <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
</ul>

<div id="creervoirutilisateur">

    <div id="voirutilisateurs">
        <h4 id="titrelisteutilisateurs">Liste des utilisateurs</h4>
        <?php
                        //Créer un tableau qui s'indente en fonction du nombre d'utilisateurs
                        $Nbrdonnees = count($data["users_list"]);
                        if ($Nbrdonnees != 0){
                            //Ici nous faisons le tableau avec ses titres
                            echo '<thead><tr>
                                    <th>Utilisateur</th>
                                    <th>Gestion</th>
                                    </tr></thead>';

                            //Ici nous ajoutons une ligne avec les infos
                            foreach ($data["users_list"] as $u){

                                echo '<tr><form action="index.php?c=Property&a=getPropertyUsers" method="post" >
                                        <td>'. $u->getNick() .'</td>
                                        <td><form action="" method="post" ><input type="submit" value="Supprimer"/></form></td>
                                      </tr>';}
                            }

                            ?>
    </div>


    <div id="creerutilisateur">
        <h4 id="titrecreerutilisateur">Ajouter un utilisateur à la propriété</h4>
        <form action="pagephp">
            <p><label>Login</label>: <input id="formdroit" type="text" name="login"/></p><br>
            <input id="validercreationutilisateur" type="submit" value="Valider"
                   title="Vous créez un nouvel utilisateur"/>
        </form>
    </div>
</div>
</html>