<main>
    <br/>
    <br/>
    <br/>


    <h1>Console d'administration</h1>
    <h2>Liste des propriétés</h2>

    <div>
        <label for="count-input">Nombre de propriétés à afficher</label>
        <select id="count-input" name="cars" onclick="formSubmit()">
            <option selected value="5">5</option>
            <option value="10">10</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="500">500</option>
        </select>
    </div>
    <br/>

    <table>
        <!-- Table header -->
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Addresse</th>
            <th>Date de création</th>
        </tr>
        </thead>

        <!-- Table body, dynamic -->
        <tbody id="properties">
        </tbody>
    </table>

    <p>Il y a <span id="displayed-count">?</span> résultats affichés sur <span id="total-count">?</span> résultats en
        tout</p>

    <!-- Actions -->
    <p hidden id="create_property_paragraph"><a id="create_property_link" href="admin/properties/create">Créer une
            nouvelle
            propriété</a></p>
</main>