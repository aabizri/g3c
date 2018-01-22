<main>
    <br/>
    <br/>
    <br/>


    <h1>Console d'administration</h1>
    <h2>Utilisateur <span id="user_nick"><?= $data["user_nick"] ?></span></h2>
    <h3>Vue des propriétés associées</h3>


    <div>
        <label for="count-input">Nombre de propriétés à afficher</label>
        <select id="count-input" name="cars" onclick="formSubmit()">
            <option selected value="5">5</option>
            <option value="10">10</option>
            <option value="50">50</option>
        </select>
    </div>
    <br/>

    <table>
        <!-- Table header -->
        <thead>
        <tr>
            <th title="ID Rôle">RoID</th>
            <th title="ID Propriété">PrID</th>
            <th title="Nom de la propriété">Nom</th>
            <th title="Addresse de la propriété">Addresse</th>
            <th title="Date de création du rôle">Création du rôle</th>
            <th title="Date de création de la propriété">Création de la propriété</th>
        </tr>
        </thead>

        <!-- Table body, dynamic -->
        <tbody id="properties">
        </tbody>
    </table>

    <p>Il y a <span id="displayed-count">?</span> résultats affichés sur <span id="total-count">?</span> résultats en
        tout</p>

    <!-- Actions -->
    <p id="create_property_paragraph"><a id="create_property_link" href="index.php?c=Admin&a=CreateProperty">Créer un
            nouveau rôle</a></p>

    <script>

        /**
         * Takes an array of elements, returns the rows to be inserted
         * @param columns the columns to be inserted
         * @param {{}} values the values to be transformed into rows
         * @param callback the callback to be called at each row
         * @return array
         */
        function createRow(columns, values, rowCallback) {
            for (let value of values) {
                // Create the row
                let row = document.createElement("tr");

                // Attributes
                row.setAttribute("onclick", "window.location.href = \"index.php?c=Admin&a=Property&queried_property_id=" + value.property_id + "\"");
                row.setAttribute("title", "Obtenir plus d'informations sur la propriété \"" + value.name + "\"");

                // Deal with them
                for (let column of columns) {
                    // Create the td
                    let td = document.createElement("td");

                    // Set the necessary attributes
                    td.setAttribute("id", "property-" + value.property_id + "-" + column);
                    td.setAttribute("class", column);

                    // Place the content
                    let content = document.createTextNode(value[column]);

                    // Add it all up
                    td.appendChild(content);
                    row.appendChild(td);
                }

                // Call the row callback
                rowCallback(row);
            }
        }

        /**
         * Update all data
         * @param tbodyID a string
         * @param fieldName the name of the field in the JSON
         * @param {{}} data
         */
        function updateData(tbodyID, fieldName, columns, data) {
            // Select
            let tbody = document.getElementById(tbodyID);

            // Purge inside
            tbody.innerHTML = "";

            // Pagination data
            let totalAmount = data.pagination.total;
            document.getElementById("displayed-count").innerText = data[fieldName].length;
            document.getElementById("total-count").innerText = totalAmount;

            // Deal with all users
            let users = data[fieldName];

            // Call createRow
            createRow(columns, users, function (row) {
                tbody.appendChild(row);
            });
        }

        /**
         * Applies the response to update the table
         *
         * @param tbodyID
         * @param fieldName
         * @param columns
         * @param res
         */
        function applyResponse(tbodyID, fieldName, columns, res) {
            // If not ok, log & return
            if (!res.ok) {
                return;
            }

            // Decode to JSON
            res.json().then(function (res) {
                updateData(tbodyID, fieldName, columns, res)
            });
        }

        /**
         * Retrieves hash (#) parameters
         *
         * @returns {{}}
         */
        function getParameters() {
            let hash = window.location.hash.substr(1);

            let result = hash.split('&').reduce(function (result, item) {
                let parts = item.split('=');
                result[parts[0]] = parts[1];
                return result;
            }, {});

            return result;
        }

        /**
         * Sets hash (#) parameters
         *
         * @params {{}}
         */
        function setParameters(parameters) {
            let hash = "";
            for (parameterName in parameters) {
                hash += "&" + parameterName + "=" + parameters[parameterName];
            }
            hash = "#" + hash.substr(1);
            window.location.hash = hash;
        }

        /**
         * Syncs the table
         */
        function sync() {
            // Get the parameters
            let params = getParameters();

            // Build the new URL
            let url = new URL(window.location.href);
            url.searchParams.set("v", "json");
            for (property in params) {
                url.searchParams.set(property, params[property]);
            }

            // Execute request
            fetch(url.href).then(function (res) {
                let columns = ["role_id", "property_id", "name", "address", "role_creation_date", "property_creation_date"];
                applyResponse("properties", "properties", columns, res);
            });
        }

        function formSubmit() {
            let countInput = document.getElementById("count-input");
            let count = countInput.options[countInput.selectedIndex].value;
            setParameters({"count": count});
            sync();
        }

        // Add the event listener
        window.addEventListener("hashchange", sync, false);
        sync();
    </script>
</main>