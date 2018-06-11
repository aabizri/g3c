<main>
    <br/>
    <br/>
    <br/>


    <h1>Console d'administration</h1>
    <h2>Liste des periphériques</h2>

    <div>
        <label for="count-input">Nombre de périphériques à afficher</label>
        <select id="count-input" name="cars">
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
            <th>UUID</th>
            <th>Date de fabrication</th>
            <th>ID de la propriété liée</th>
            <th>Nom de la propriété liée</th>
            <th>ID de la pièce liée</th>
            <th>Nom de la pièce liée</th>
            <th>Date d'ajout</th>
        </tr>
        </thead>

        <!-- Table body, dynamic -->
        <tbody id="peripherals">
        </tbody>
    </table>

    <p>Il y a <span id="displayed-count">?</span> résultats affichés sur <span id="total-count">?</span> résultats en
        tout</p>

    <!-- Actions -->
    <p hidden id="create_peripheral_paragraph"><a id="create_peripheral_link" href="admin/peripherals/create">Créer un
            nouveau peripherique</a></p>
</main>