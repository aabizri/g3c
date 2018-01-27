<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <?php // Dynamic Meta tags
        if (!empty($meta) && is_array($meta)) {
            // Title tag
            if (array_key_exists("page_title", $meta)){
                echo "<title>" . $meta["page_title"] . "</title>";
            }

            // Base tag
            if (array_key_exists("base", $meta)){
                echo '<base href="'. $meta["base"] . '"/>';
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

        // CSS
        if (empty($css)) {
            echo "Error: no \$css !";
        } else {
            foreach ($css as $to_be_linked) {
                echo '<link rel="stylesheet" href="' . $to_be_linked . '"/>';
            }
        }

        // JS
        if (!empty($js)) {
            foreach ($js as $to_be_linked) {
                echo '<script src="' . $to_be_linked . '"></script>';
            }
        }
    ?>
</head>