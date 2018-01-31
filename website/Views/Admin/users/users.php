<main>
    <br/>
    <br/>
    <br/>

    <h1>Console d'administration</h1>
    <h2>Liste des utilisateurs</h2>

    <div>
        <label for="count-input">Nombre d'utilisateurs à afficher</label>
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
            <th>Nom complet</th>
            <th>Pseudonyme</th>
            <th>Email</th>
        </tr>
        </thead>

        <!-- Table body, dynamic -->
        <tbody id="users">
        </tbody>
    </table>

    <p>Il y a <span id="displayed-count">?</span> résultats affichés sur <span id="total-count">?</span> résultats en
        tout</p>

    <!-- Actions -->
    <p id="create_user_paragraph"><a id="create_user_link" href="admin/users/create">Créer un nouvel utilisateur</a></p>
</main>