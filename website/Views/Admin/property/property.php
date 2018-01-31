<main>
    <h1>Console d'administration</h1>
    <h2>Vue de la propriété</h2>
    <h3>?</h3>

    <!-- Tableau de données de l'utilisateur -->
    <table>
        <thead>
        <tr>
            <th>Intitulé</th>
            <th>Valeur</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <!-- Actions -->
    <div id="actions_block">
        <ul id="actions_list">
            <li><a id="show_roles_action">Afficher les utilisateurs associées</a></li>
            <li><a id="show_rooms_action">Afficher les pièces associées</a></li>
            <li><a id="show_peripherals_action">Afficher les périphériques associés</a></li>
            <li><a id="show_requests_action">Afficher les requêtes associées</a></li>
        </ul>
    </div>

    <div id="json_data" hidden><?= $data["json"] ?></div>
</main>