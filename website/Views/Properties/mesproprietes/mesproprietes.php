<main>
<ul>
    <?php
    $properties = $data["properties"];
    foreach ($properties as $property) : ?>
        <li>
            <a href="index.php?c=Property&a=Property&pid=<?= $property->getID() ?>">
                <button type='button' id='ajout'>
                    <img src='https://lh4.ggpht.com/xBh0lfVel9SHv7z8xGKONTXEubFP71NoBZTeC0sWRYz5ZF5L-tABYuzZ9FP2j_lTdw=w300'
                         height='100px' width='100px'>
                </button>
                <br><?= $property->getName() ?>
            </a>
        </li>
    <?php endforeach ?>

    <li id="ajouter"><a href="index.php?c=Property&a=New">
            <button type="button" id="ajout">
                <img
                        src="Views/Properties/mesproprietes/plus.png"
                        alt="Ajouter une proptiété"
                        height="100px"
                        width="100px"
                >
            </button>
        </a><br>Ajouter une propriété
    </li>

</ul>
</main>