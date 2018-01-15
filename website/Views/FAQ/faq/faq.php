<main>
    <div id="faq">
        <h2 id="titre">Foire Aux Questions</h2>
        <p>
            <?php
            $faq = $data["faq"];
            echo $faq->getQuestion();
            ?>
        </p>
    </div>

</main>