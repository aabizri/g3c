<main>
    <div id="faq">

        <h2 id="titre">Foire Aux Questions</h2>

                <?php

            foreach ($data["qa_results"] as $p) {

                //on récupère la question, puis sa réponse
                $question = $p->getQuestion();

                $answer = $p->getAnswer();

                ?>


                <?php
                echo '
                        <div id="questions"><p>' . $question . '</p> </div>
                        
                     <div id="reponses"<p>' . $answer . '</p> </div>
                     <div id="trait"></div>
                      ';
            }
                 ?>


    </div>
</main>
