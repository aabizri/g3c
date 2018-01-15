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
                echo $subscription -> getExpiryDate();
                ?>
            </td>
        </tr>
        <tr>
            <td>Etat de l'abonnement</td>
            <td><?php
                if ($subscription -> getExpiryDate() < (new \DateTime)->format("Y-m-d")) {
                    echo "Abonnement invalide";
                }
                else
                    echo "Abonnement valide";
                ?></td>
        </tr>
        <tr>
            <td>Date d'achat de l'abonnement</td>
            <td><?php
                echo $subscription->getLastUpdated();
                ?></td>
        </tr>
        </tbody>
    </table>
</main>