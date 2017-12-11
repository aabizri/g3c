<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <?php // Dynamic Meta tags
        if (!empty($meta) && is_array($meta)) {
            // Title tag
            if (array_key_exists("page_title", $meta)){
                echo "<title>" . $meta["page_title"] . "</title>\n";
            }

            // Base tag
            if (array_key_exists("base", $meta)){
                echo '<base> href="'. $meta["base"] . '</base>\n';
            }

            // Other meta tags
            $names = ["description", "keywords", "author"];
            foreach ($names as $name) {
                if (!array_key_exists($name, $meta)) {
                    continue;
                }
                echo '<meta name="' . $name . '" content="' . $meta["name"] . '">\n';
            }
        }
    ?>

    <?php // Function for including CSS or JS
        function include_linked(array $arr, string $statement) {
            foreach ($arr as $url) {
                if (!is_string($url)) {
                    continue;
                }
                echo $statement . $url . "'/>\n";
            }
        }

        // CSS
        if (empty($css)) {
            echo "Error: no \$css !";
        } else {
            include_linked($css,  "<link rel='stylesheet' href='");
        }

        // JS
        if (!empty($js)) {
            include_linked($js,  "<script src='");
        }
    ?>
</head>