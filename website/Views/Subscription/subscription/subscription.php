<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>



<main>
    <table>
        <thead>
        <tr>
            <th>Titre</th>
            <th>Valeur</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Date de début d'abonnement</td>
            <td>
                <?php
                $subscription = $data["subscription"];
                echo $subscription -> getStartDate();
                ?>
            </td>
        </tr>
        <tr>
            <td>Date de fin d'abonnement</td>
            <td><?php
                $subscription = $data["subscription"];
                echo $subscription -> getEndDate();
                ?>
            </td>
        </tr>
        <tr>
            <td>Etat de l'abonnement</td>
            <td>TOMATR</td>
        </tr>
        <tr>
            <td>Date d'achat de l'abonnement</td>
            <td>TOMATR</td>
        </tr>
        </tbody>
    </table>
</main>