<main>
    <h1>Erreur 404</h1>
    <h2>Page inexistante, veuillez re-essayer avec une page valide</h2>
    <p>Retournez sur la page d'acceuil <a href="">en cliquant ici</a></p>
    <?php $req = $data["req"];
    if ($req->getInDebug()) : ?>
        <br/>
        <h3> Informations de débuggage sur le routage : </h3>
        <table>
            <tr>
                <th>Titre</th>
                <th>Valeur</th>
            </tr>
            <tr>
                <td>Méthode utilisée</td>
                <td><?= htmlspecialchars($req->getMethod()) ?></td>
            </tr>
            <tr>
                <td>Controlleur demandé</td>
                <td><?= htmlspecialchars($req->getController()) ?></td>
            </tr>
            <tr>
                <td>Action demandée</td>
                <td><?= htmlspecialchars($req->getAction()) ?></td>
            </tr>
        </table>
        <h3>Informations de débuggage sur la requête :</h3>
        <pre><?= htmlspecialchars($req->prettyPrint()) ?></pre>
    <?php endif; ?>
</main>