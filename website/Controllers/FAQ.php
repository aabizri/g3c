<?php

namespace Controllers;

use Helpers\DisplayManager;

class FAQ
{
    /**
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function getFAQ(\Entities\Request $req): void
    {
        $qa_results = (new \Queries\FrequentlyAskedQuestions)->find();

        $data["qa_results"] = $qa_results;

        DisplayManager::display("faq", $data);
    }
}