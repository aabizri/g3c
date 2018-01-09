<body>
<header>
        <a id="logo" href="index.php?c=User&a=ConnectionPage"><img src="Views/Layout/header/logopetit.png" alt="logopetit.png"> </a>
        <p id="nom"><?=(!empty($header) && is_array($header) && array_key_exists("website_name", $header) ? $header["website_name"] : "ERROR");?> </p>
        <p id="accroche"><?=(!empty($header) && is_array($header) && array_key_exists("tagline", $header) ? $header["tagline"] : "ERROR");?></i></p>
        <p id="nomdelapage"><?=(!empty($header) && is_array($header) && array_key_exists("page_name", $header) ? $header["page_name"] : "ERROR");?></p>
    <?php
    //Si connecté n'affiche rien
    if (!empty($_SESSION["user_id"])){
        echo '<form action="index.php?a=User&c=Deconnexion" id="deconnexion"><input type="submit" value="Déconnexion"/></form>';
    }
    ?>
</header>

