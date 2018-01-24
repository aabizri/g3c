<?php

namespace Controllers;

use Helpers\DisplayManager;
use Queries;
use Entities;

class FAQ
{


    public static function getFAQ (\Entities\Request $req):void
    {
        $qa_query = new \Queries\FAQ;
        $qa_results = $qa_query->find();

        $data["qa_results"] = $qa_results;

        DisplayManager::display("faq", $data);
    }




}