<main>
<ul>
    <div id="property">
    <?php
    $properties = $data["properties"];
    foreach ($properties as $property) : ?>
        <li>
            <a href="properties/<?= $property->getID() ?>">
                <button type='button' id='ajout'>
                    <img src='https://lh4.ggpht.com/xBh0lfVel9SHv7z8xGKONTXEubFP71NoBZTeC0sWRYz5ZF5L-tABYuzZ9FP2j_lTdw=w300'
                         height='100px' width='100px'>
                </button>
            </a>
            <p><?= $property->getName() ?></p>
        </li>
    <?php endforeach ?>

    <li id="ajouter"><a href="properties/new">
            <button type="button" id="ajout">
                <img
                        src="Views/Properties/mesproprietes/plus.png"
                        alt="Ajouter une proptiété"
                        height="100px"
                        width="100px"
                >
            </button>
        </a><p>Ajouter une propriété</p>
    </li>
    </div>
</ul>
</main>