<?php

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

class FAQ
{


    public static function getFAQ (\Entities\Request $req):void
    {
        //Récupère les questions sous forme d'array
        $questions_list = (new \Queries\faq) ->filterByQuestion("=",$id) -> find();

        $data["questions_list"] = $questions_list;

        DisplayManager::display("faq", $data);
    }




}