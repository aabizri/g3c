<main>
    <div id="modifieCGU">
        <h2>Modification des Conditions générales d'utilisation du site</h2>
        <form method="post" action="index.php?c=CGU&a=ModificateCGU&debug=true">
            <textarea rows="27" cols="150" wrap="physical" id="textcgu" name="cgu"  >
                <?php
                $cgu = $data["cgu"];
                echo $cgu->getText()
                ?>
            </textarea>
            <input id="boutonvalider" type="submit" value="Mettre à jour"/>
        </form>
    </div>
</main>