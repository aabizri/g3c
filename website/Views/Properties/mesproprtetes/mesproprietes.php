<!DOCTYPE html>
<html>

<header>
    <div id="Logo"> <a href="paged'accueil.html"><img src=logopetit.png alt="logopetit.png"> </a></div>
    <p id="Nom">LiveWell</p>
    <p id="accroche"><i>Votre sécurité est notre priorité.</i></p>
    <p id="Nomdelapage">Mes pièces</p>
</header>

<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="mesproprietes.css" />
    <title>Mes périphériques</title>
</head>

<body>
    <ul>
    <?php
    //foreach ($data["properties"] as $property) {
        //echo "<li><button type="button" id=$property><img scr="" height="100px" width="100px" ".$property."/></button></li>";
        ?>

        <li id="ajouter"><button type="button" id="ajout" <!--onclick="<?//=\Helpers\DisplayManager::absolutifyURL("index.php?c=Properties&a=");?>"--><img
                        src="plus.png"
                        alt="Ajouter une proptiété"
                        height="100px"
                        width="100px"
                /></button><br>Ajouter une propriété</li>
    </ul>


</body>


</html>

