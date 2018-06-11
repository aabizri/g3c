<main>
    <h1>Console d'administration</h1>
    <h2>Vue de l'utilisateur</h2>
    <h3></h3>

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
            <li><a href="admin/users/<?= urlencode($data["uid"]) ?>/properties" id="show_roles_action">Afficher les
                    propriétés
                    associées</a></li>
            <li hidden><a href="admin/users/<?= urlencode($data["uid"]) ?>/sessions" id="show_sessions_action">Afficher
                    les
                    sessions associées</a></li>
            <li hidden><a href="admin/users/<?= urlencode($data["uid"]) ?>/requests" id="show_requests_action">Afficher
                    les
                    requêtes associées</a></li>
        </ul>
        <h4>Actions</h4>
        <ul id="actions_list">
            <li hidden>
                <button id="reset_password_action" onclick="return resetPassword()">Réinitialiser le mot de passe de
                    l'utilisateur
                </button>
            </li>
            <li>
                <button id="delete_action" onclick="return deleteUser()">Supprimer l'utilisateur du système</button>
            </li>
        </ul>
    </div>

    <div id="json_data" hidden><?= $data["json"] ?></div>
</main>