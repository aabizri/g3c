<main>
   <div id="cgupage">
       <h2 id="titre">Conditions générales d'utilisation</h2>
       <p>
           <?php
               $cgu = $data["cgu"];
               echo $cgu->getText()
           ?>
       </p>
   </div>

</main>