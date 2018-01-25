<main>
    <div id="join-block">
        <h3>Formulaire d'inscription</h3>
        <form id="join-form" method="post" action="/join">
            <div id="join-inputs">
                <fieldset id="civil-fieldset">
                    <legend>état civil</legend>

                    <label for="name-input">Prénom</label>:
                    <input id="name-input" type="text" name="name" spellcheck="false" autocomplete="given-name"
                           required/> <br/>

                    <label for="surname-input">Nom</label>:
                    <input id="surname-input" type="text" name="surname" spellcheck="false" autocomplete="family-name"
                           required/> <br/>
                </fieldset>
                <fieldset id="account-fieldset">
                    <legend>informations du compte</legend>

                    <label for="phone-input">Téléphone</label>:
                    <input id="phone-input" type="tel" name="phone" spellcheck="false" autocomplete="tel"/> <br/>

                    <label for="login-input">Login</label>:
                    <input id="login-input" type="text" name="nick" spellcheck="false" required/> <br/>
                </fieldset>
                <fieldset id="email-fieldset">
                    <legend>e-mail</legend>

                    <label for="email-input">Adresse mail</label>:
                    <input id="email-input" type="email" name="email" autocomplete="email" required/> <br/>

                    <label for="email-conf-input">Confirmer l'adresse mail</label>:
                    <input id="email-conf-input" type="email" name="email_conf" autocomplete="email" required/> <br/>
                </fieldset>
                <fieldset id="password-fieldset">
                    <legend>mot de passe</legend>

                    <label for="password-input">Mot de passe</label>:
                    <input id="password-input" type="password" name="password" autocomplete="new-password" minlength="6"
                           required/> <br/>

                    <label for="password-conf-input">Confirmer le mot de passe</label>:
                    <input id="password-conf-input" type="password" name="password_conf" autocomplete="new-password"
                           minlength="6" required/> <br/>
                </fieldset>
            </div>

            <p id="confirmation">Une fois inscrit, vous recevrez un lien de confirmation.<br>Cliquez sur le lien pour
                valider votre compte.</p>
            <p id="CGU">
                <input id="cgu-input" type="checkbox" name="cgu" value="accepted" required/>
                <label for="cgu-input">J'ai lu et j'accepte les <a href="index.php?c=General&a=CGU">CGU</a></label>
            </p>
            <?php
            // On n'affiche le captcha que si le site est servi en HTTPS
            if (isset($_SERVER['HTTPS'])) {
                if ($_SERVER['HTTPS'] == "on") {
                    $captcha = new \Helpers\ReCAPTCHA("6Le5Pz4UAAAAAN4feu00Xw0d9A33hUIGAenxYnkp");
                    echo $captcha->getHTML();
                }
            }
            ?>
            <input type="submit" value="Valider">


        </form>
    </div>
</main>