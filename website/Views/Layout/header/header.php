<body>
<header>
        <div id="Logo"> <a href="index.php?c=User&a=getConnectionPage"><img src=logopetit.png alt="logopetit.png"> </a></div>
        <p id="Nom"><?=(!empty($header) && is_array($header) && array_key_exists("website_name", $header) ? $header["website_name"] : "ERROR");?> </p>
        <p id="Phrasedaccroche"><?=(!empty($header) && is_array($header) && array_key_exists("tagline", $header) ? $header["tagline"] : "ERROR");?></i></p>
        <p id="Nomdelapage"><?=(!empty($header) && is_array($header) && array_key_exists("page_name", $header) ? $header["page_name"] : "ERROR");?></p>
</header>

