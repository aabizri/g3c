<body>
<header>
        <a id="logo" href="index.php?c=User&a=getConnectionPage"><img src="<?=\Helpers\DisplayManager::absolutifyURL("logopetit.png", "Views/Layout/header/")?>" alt="logopetit.png"> </a>
        <p id="nom"><?=(!empty($header) && is_array($header) && array_key_exists("website_name", $header) ? $header["website_name"] : "ERROR");?> </p>
        <p id="accroche"><?=(!empty($header) && is_array($header) && array_key_exists("tagline", $header) ? $header["tagline"] : "ERROR");?></i></p>
        <p id="nomdelapage"><?=(!empty($header) && is_array($header) && array_key_exists("page_name", $header) ? $header["page_name"] : "ERROR");?></p>
</header>
