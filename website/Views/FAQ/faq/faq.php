<main>
    <div id="faqpage">

        <h2 id="titre">Foire Aux Questions</h2>

        <?php

        foreach ($data["qa_results"] as $p) {

            //on récupère la question, puis sa réponse
            $question = $p->getQuestion();

            $answer = $p->getAnswer();

            ?>


            <?php
            echo '
                        <div id="questions"><p>' . htmlspecialchars($question) . '</p> </div>
                        
                     <div id="reponses"><p>' . htmlspecialchars($answer) . '</p> </div>
                     <div id="trait"></div>
                      ';
        }
        ?>


    </div>
</main>
