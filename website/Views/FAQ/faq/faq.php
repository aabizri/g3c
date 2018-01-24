<main>
    <div id="faq">
        <h2 id="titre">Foire Aux Questions</h2>

        <?php
        //créer un tableau qui s'indente en fonction du nombre de questions
        $Nbrdonnees = count($data["qa_results"]);
        if ($Nbrdonnees != 0) {
            //on fait le tableau d'un coté question et de l'autre sa réponse

            echo '<thread><tr>
                    <th> Questions</th>
                    <th> Réponses</th>
                    </tr></thread>';
                    
            foreach ($data["qa_results"] as $p) {

                //on récupère la question, puis sa réponse en vérifiant qu'il y en a bien une
                $question = $p->getQuestion();

                $answer = $p->getAnswer();


                echo '<tr>
                        <td>'. $question . '</td>
                        <td>' . $answer . '</td>
                      </tr>';

            }
        }
