<ul id="Menu">
    <li id="Moncompte"><a href="index.php?c=User&a=getAccountPage"><input type="button" value="Mon compte" /></a></li>
    <li id="Mespieces"><a href="index.php?c=Room&a=getRoomsPage"><input type="button" value="Mes pièces" /></a></li>
    <li id="Mesperipheriques"> <a href="index.php?c=Peripheral&a=getPeripheralsPage"><input type="button" value="Mes périphériques" /></a></li>
    <li id="Mesfiltres"><a href="Mesfiltres.html"><input type="button" value="Mes filtres" /></a></li>
    <li id="Mesparametres"><a href="Mesparametres.html"><input type="button" value="Mes paramètres" /></a></li>
</ul>


<div id="contacterserviceclient">
    <h3>Contacter le service client</h3>
    <div id="champscontacterserviceclient">
        <p>Le contact avec le service client se déroule par Mail.<br>
            Merci de bien vouloir remplir l'adresse mail à laquelle vous souhaitez que l'on vous contacte.<br>
            Un mail vous sera envoyé immédiatement après validation afin de vous assister du mieux possible.</p>
        <form method="post" action="index.php?c=Maintenance&a=postSendMail">
            <p>
                <label>Adresse mail</label>: <input id="line" type="text" name="email" />
            </p>

            <input id="valider" type="submit" value="Valider">
        </form>
    </div>
</div>










