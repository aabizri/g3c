<main>
<h1 id="phrase">Sélectionnez votre propriété</h1>
<ul>
    <?php
    foreach ($data[$properties] as $property)
        "<li><a href='lien'>".$property->getName()."</a></li>";
    ?>
    <li><a href="creer">Nouvelle Propriété</a></li>
</ul>
</main>