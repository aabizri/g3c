<?php
$queried_user_metadata = $data["queried_user_metadata"];
$queried_user_data = $data["queried_user_data"];
?>
<main>
    <h1>Console d'administration</h1>
    <h2>Vue de l'utilisateur</h2>
    <h3><?= $queried_user_data["display"] ?></h3>

    <!-- TAbleau de données -->
    <table>
        <thead>
        <tr>
            <th>Intitulé</th>
            <th>Valeur</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($queried_user_data as $datum_index => $datum_value) {
            // La ligne
            $row_id = 'row_' . $datum_index;
            echo '<tr id="' . $row_id . '">' . "\n";

            // L'index (intitulé)
            $index_class = 'index';
            echo '<td class="' . $index_class . '">' . "\n";
            echo $queried_user_metadata[$datum_index];
            echo '</td>';

            // La valeur
            $value_class = 'value';
            echo '<td class="' . $value_class . '">' . "\n";
            echo $datum_value;
            echo '</td>' . "\n";

            echo '</tr>' . "\n";
        }
        ?>
        </tbody>
    </table>

    <script>
        function startModify() {
            // Store previous value
            let previousValue = this.innerText;

            // Reset inside of cell
            this.innerHTML = "";

            // Remove previous event handler
            this.onclick = null;

            // Add an input
            let input = document.createElement("input");
            input.value = previousValue;

            // Append it
            this.appendChild(input);

            // Select it
            input.select();

            // Add event handlers
            attachEvents(input, previousValue);
        }

        function attachEvents(input, previousValue) {
            input.onkeypress = function () {
                if (event.key == "Enter") {
                    return commitModify(this);
                } else {
                    return true;
                }
            };
            input.onblur = function () {
                cancelModify(this, previousValue);
            };
        }

        function detachEvents(input) {
            input.onkeypress = null;
            input.onblur = null;
        }

        // Commit modification
        function commitModify(element) {
            // Remove the event listeners to prevent any interference
            detachEvents(element);

            // Retrieve the value
            let inputValue = element.value;

            // Get the parent
            let cell = element.parentNode;

            // Remove input from cell
            cell.removeChild(element);

            // Set the text to the input value
            cell.innerText = inputValue;

            // Attach startModify onclick
            cell.onclick = startModify;
        }

        // Cancel modification
        function cancelModify(element, previousValue) {
            // Remove the event listeners to prevent any interference
            detachEvents(element);

            // Get the parent
            let cell = element.parentNode;

            // Remove input from cell
            cell.removeChild(element);

            // Set the text to the input value
            cell.innerText = previousValue;

            // Attach startModify onclick
            cell.onclick = startModify;
        }

        let rows = document.getElementsByTagName("tbody")[0].children;
        for (let i = 0; i < rows.length; i++) {
            let row = rows[i];
            let valueCell = row.children[1];
            valueCell.onclick = startModify;
        }
    </script>
</main>