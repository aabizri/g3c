<main>
    <h1>Erreur 500</h1>
    <h2>Erreur Interne, veuillez nous excuser pour la gène occasionée</h2>
    <p>Retournez sur la page d'acceuil <a href="">en cliquant ici</a></p>
    <?php
    $req = $data["req"];
    if ($req->getInDebug()) :
        $t = $data["throwable"] ?? null;
        $message = $data["message"]; ?>
        <h3>Informations de débuggage</h3>

        <?php if (!empty($message)) : ?>
        <h4>Message informatif</h4>
        <pre><?= $message ?></pre>
    <?php endif; ?>

        <?php if ($t !== null) : ?>
        <h4>Exception/Erreur</h4>
        <pre><?= $t ?></pre>;
    <?php endif; ?>

        <h4> Informations de débuggage sur la requête : </h4>
        <pre><?= $req->prettyPrint() ?></pre>
    <? endif; ?>
</main>