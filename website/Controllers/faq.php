<?php

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

class QuestionAnswer
{

    //ajouter une question et une réponse

    public static function postAddQuestionAnswer(\Entities\Request $req): void
    {

        $post = $req->getAllPOST();

        // Check if the data exists
        $required = ["id", "question", "answer", "creation_date"];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                echo "Missing key: " . $key;
                return;
            }
        }

        /*Assigne les valeurs*/
        $question = $post["question"];
        $answer= $post["answer"];

        /*Créer l'entité*/
        $q = new Entities\QuestionAnswer();
        $q->setQuestion($question);
        $q->setAnswer($answer);

        /*Insérer l'entité dans la bdd*/
        try {
            Repositories\QuestionAnswer::insert($q);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }

        \Helpers\DisplayManager::redirectToController("faq", "QuestionsPage");
    }










}