<?php

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

class FAQ
{


    public static function getFAQ (\Entities\Request $req):void
    {
        $question = (new \Queries\faq) -> orderBy("question", true) -> find();
        $answer = (new \Queries)

        $data["faq"] = $faq;

        DisplayManager::display("faq", $data);
    }













}