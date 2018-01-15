<main>
    <h1>Console d'administration</h1>
    <h2>Vue de l'utilisateur</h2>
    <h3></h3>

    <!-- Tableau de données de l'utilisateur -->
    <table>
        <thead>
        <tr>
            <th>Intitulé</th>
            <th>Valeur</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <!-- Actions -->
    <div id="actions_block">
        <ul id="actions_list">
            <li><a id="reset_password_action" onclick="resetPassword()">Réinitialiser le mot de passe de
                    l'utilisateur</a></li>
            <li><a id="delete_action" onclick="deleteUser()">Supprimer l'utilisateur du système</a></li>
        </ul>
    </div>

    <script>

        function resetPassword() {
            return confirm("Cela enverra un mail de réinitialisation à " + data["nick"].value + " <" + data["email"].value + ">\nÊtes vous sûr ?");
        }

        function deleteUser() {
            return confirm("CELA SUPPRIMERA DEFINITIVEMENT L'UTILISATEUR " + data["nick"].value + "\nÊTES VOUS SÛR DE CONTINUER ?");
        }

        function startModify() {
            // If immutable, do not modify
            if (data[this.parentNode.id].type === "immutable") {
                return false;
            }

            // Store previous value
            let previousValue = this.innerText;

            // Reset inside of cell
            this.innerHTML = "";

            // Remove previous event handler
            this.onclick = null;

            // Add an input
            let input = document.createElement("input");
            input.value = previousValue;
            input.type = data[this.parentNode.id].type;

            // Append it
            this.appendChild(input);

            // Select it
            input.select();

            // Add event handlers
            attachEventsToInput(input, previousValue);
        }

        function attachEventsToInput(input, previousValue) {
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
            // Check that the value is valid
            if (element.checkValidity() === false) {
                return false;
            }

            // Retrieve the value
            let inputValue = element.value;

            // Get the parent
            let cell = element.parentNode;

            // Push the new data value
            let ok = pushModify(cell.parentNode.id, inputValue);
            if (ok === false) {
                return false;
            }

            // Remove the event listeners to prevent any interference
            detachEvents(element);

            // Remove input from cell
            cell.removeChild(element);

            // Set the text to the input value
            cell.innerText = inputValue;

            // Set the H3 to the input value if this is the nick
            if (cell.parentNode.id === "display") {
                document.getElementsByTagName("h3")[0].innerHTML = inputValue;
            }

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

        // Updates the table given json data
        function updateTable(data) {
            let tbody = document.getElementsByTagName("tbody")[0];

            // For each element in the table, create a row and add it to the tbody
            for (index in data) {
                // Create row
                row = document.createElement("tr");
                row.setAttribute("id", index);

                // Title
                titleCell = document.createElement("td");
                titleCell.setAttribute("class", "title");
                titleCell.innerText = data[index].title;
                row.appendChild(titleCell);

                // Value
                valueCell = document.createElement("td");
                valueCell.setAttribute("class", "value");
                valueCell.addEventListener("click", startModify);
                valueCell.innerText = data[index].value;
                row.appendChild(valueCell);

                // Append the row to the table
                tbody.appendChild(row);
            }
        }

        // Push
        function pushModify(key, value) {
            console.log(key, value);

            // Create form
            let form = new FormData;
            form.set("new_" + key, value);

            // Fetch options
            let fetchOptions = {
                method: "POST",
                body: form,
            };

            // Push
            return fetch(window.location.href, fetchOptions).then(function (response) {
                return response;
            }).then(function (response) {
                return (response.status === 200);
            });
        }

        // Data populated in PHP, but set via JS
        let data = <?=$data["json"]?>;
        updateTable(data);
        document.getElementsByTagName("h3")[0].innerHTML = data["display"].value;
    </script>
</main>