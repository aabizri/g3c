<body>
<header>
    <a id="logo" href="login"><img src="Views/Layout/header/logopetit.png" alt="logopetit.png"> </a>
        <p id="nom"><?=(!empty($header) && is_array($header) && array_key_exists("website_name", $header) ? $header["website_name"] : "ERROR");?> </p>
        <p id="accroche"><?=(!empty($header) && is_array($header) && array_key_exists("tagline", $header) ? $header["tagline"] : "ERROR");?></i></p>
        <p id="nomdelapage"><?=(!empty($header) && is_array($header) && array_key_exists("page_name", $header) ? $header["page_name"] : "ERROR");?></p>
    <?php
    //Si connecté n'affiche rien
    if (!empty($_SESSION["user_id"])) :
        $user_entity = (new \Queries\Users)
            ->retrieve($_SESSION['user_id']);
        $nick = $user_entity -> getNick();
        ?>

        <div id="menu">
            <ul class="niveau1">
                <li>
                    <?= $user_entity->getNick() ?>
                    <img width="10px" height="10px"
                         src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Black_triangle.svg/220px-Black_triangle.svg.png">
                    <ul class="niveau2">
                        <li></li>
                        <li><a href="account">
                                <button>Mon compte</button>
                            </a></li>
                        <li><a href="properties">
                                <button>Mes propriétés</button>
                            </a></li>
                        <?php if ($user_entity->getAdmin()) : ?>
                            <li><a href="admin">
                                    <button>Console d'administration</button>
                                </a></li>
                        <?php endif; ?>
                        <li>
                            <form action="logout" method="post">
                                <button type="submit">Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    

    <?php endif; ?>


</header>

