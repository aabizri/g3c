<main>
    <br/>
    <br/>
    <br/>


    <h1>Console d'administration</h1>
    <h2>Utilisateur <span id="user_nick"><?= $data["user_nick"] ?></span></h2>
    <h3>Vue des propriétés associées</h3>


    <div>
        <label for="count-input">Nombre de propriétés à afficher</label>
        <select id="count-input" name="cars" onclick="formSubmit()">
            <option selected value="5">5</option>
            <option value="10">10</option>
            <option value="50">50</option>
        </select>
    </div>
    <br/>

    <table>
        <!-- Table header -->
        <thead>
        <tr>
            <th title="ID Rôle">RoID</th>
            <th title="ID Propriété">PrID</th>
            <th title="Nom de la propriété">Nom</th>
            <th title="Addresse de la propriété">Addresse</th>
            <th title="Date de création du rôle">Création du rôle</th>
            <th title="Date de création de la propriété">Création de la propriété</th>
        </tr>
        </thead>

        <!-- Table body, dynamic -->
        <tbody id="properties">
        </tbody>
    </table>

    <p>Il y a <span id="displayed-count">?</span> résultats affichés sur <span id="total-count">?</span> résultats en
        tout</p>

    <!-- Actions -->
    <p id="create_property_paragraph"><a id="create_property_link"
                                         href="admin/users/<?= $data["uid"] ?>/properties/create">Créer un
            nouveau rôle</a></p>
</main>