<main>
    <h1>Console d'administration</h1>
    <h2>Vue du périphérique</h2>
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
            <li id="show_property_action" <?= $data["pid"] === null ? "hidden" : "" ?>><a
                        href="admin/properties/<?= $data["pid"] ?>">Afficher la propriété associée</a></li>
            <li id="show_room_action" <?= $data["rid"] === null ? "hidden" : "" ?>><a
                        href="admin/rooms/<?= $data["rid"] ?>">Afficher la pièce associée</a></li>
        </ul>
    </div>

    <div id="json_data" hidden><?= $data["json"] ?></div>
</main>