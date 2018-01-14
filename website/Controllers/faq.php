<?php

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

class QuestionAnswer
{


    public static function getFAQ (\Entities\Request $req):void
    {
        // Si la requête n'est pas associée à une question, retourner une erreur
        $id = $req->getID();
        if (empty($id)) {
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        //mettre le retrieve pour affichage question/reponse

        \Helpers\DisplayManager::display("mespieces");










}