<?php

namespace Controllers;

use Helpers\DisplayManager;

class FAQ
{
    /**
     * GET faq
     *
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function getFAQ(\Entities\Request $req): void
    {
        // Récupère les questions/réponses
        try {
            $qa_results = (new \Queries\FrequentlyAskedQuestions)->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t);
            return;
        }

        // Les ajoute aux données pour la vue PHP
        $data["qa_results"] = $qa_results;

        // Appelle la vue PHP
        DisplayManager::display("faq", $data);
    }
}