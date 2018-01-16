
    <ul>
    <?php
    $properties = $data["properties"];
    foreach ($properties as $property) {
        echo "<li><button type='button'><img scr='' height='100px' width='100px'.$property./></button></li>";
    }
        ?>

        <li id="ajouter"><button type="button" id="ajout" onclick='window.location.href = "index.php?c=Property&a=NewProperty"' >
                <img
                        src="Views/Properties/mesproprietes/plus.png"
                        alt="Ajouter une proptiété"
                        height="100px"
                        width="100px"
                >
                </button><br>Ajouter une propriété</li>

    </ul>
