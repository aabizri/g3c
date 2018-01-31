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
        <h4>Vues</h4>
        <ul id="views_list">
            <li><a href="admin/properties/<?= urlencode($data["pid"]) ?>/roles" id="show_roles_action">Afficher les
                    propriétés
                    associées</a></li>
            <li><a href="admin/properties/<?= urlencode($data["pid"]) ?>/sessions" id="show_sessions_action">Afficher
                    les
                    sessions associées</a></li>
            <li><a href="admin/properties/<?= urlencode($data["pid"]) ?>/peripherals" id="show_peripherals_action">Afficher
                    les
                    périphériques associées</a></li>
            <li><a href="admin/properties/<?= urlencode($data["pid"]) ?>/requests" id="show_requests_action">Afficher
                    les
                    requêtes associées</a></li>
        </ul>
        <h4>Actions</h4>
        <ul id="actions_list">
            <li>
                <button id="delete_action" onclick="return deleteProperty()">Supprimer la propriété du système</button>
            </li>
        </ul>
    </div>

    <div id="json_data" hidden><?= $data["json"] ?></div>
</main>