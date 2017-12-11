<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <?php
        if (empty($css)) {
            echo "Erreur: pas de CSS";
            exit;
        }
        if (!is_array($css)) {
            echo "Erreur: \$data[\"css\"] is not an array";
        }
        foreach ($css as $link) {
            echo "<link rel='stylesheet' href='".$link."'/>\n";
        }
    ?>
</head>