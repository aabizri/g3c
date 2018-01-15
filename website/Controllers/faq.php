<?php

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

class QuestionAnswer
{


    public static function getFAQ (\Entities\Request $req):void
    {
        $faq = (new \Queries\faq) -> orderBy("id", true) -> find();

        $data["faq"] = $faq;

        DisplayManager::display("faq", $data);
    }













}