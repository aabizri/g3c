
<ul>
<?php
$properties = $data["properties"];
$lien = 'window.location.href = "index.php?c=Property&a=Dashboard&debug=true"';
foreach ($properties as $property) {
    echo "<li>"."<button type='button' id='ajout' onclick='$lien'>"."<img src='https://lh4.ggpht.com/xBh0lfVel9SHv7z8xGKONTXEubFP71NoBZTeC0sWRYz5ZF5L-tABYuzZ9FP2j_lTdw=w300' height='100px' width='100px'>"."</button>"."<br>".$property->getName()."</li>";
}
    ?>

    <li id="ajouter"><button type="button" id="ajout" onclick='window.location.href = "index.php?c=Property&a=NewProperty&debug=true"' >
            <img
                    src="Views/Properties/mesproprietes/plus.png"
                    alt="Ajouter une proptiété"
                    height="100px"
                    width="100px"
            >
            </button><br>Ajouter une propriété</li>

</ul>
