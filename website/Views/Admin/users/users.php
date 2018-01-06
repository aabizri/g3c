<main>
    <br/>
    <br/>
    <br/>


    <h1>Console d'administration</h1>
    <h2>Liste des utilisateurs</h2>

    <div>
        <label for="count-input">Nombre d'utilisateurs à afficher</label>
        <select id="count-input" name="cars" onclick="formSubmit()">
            <option selected value="5">5</option>
            <option value="10">10</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="500">500</option>
        </select>
    </div>
    <br/>

    <table>
        <!-- Table header -->
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom complet</th>
            <th>Pseudonyme</th>
            <th>Email</th>
        </tr>
        </thead>

        <!-- Table body, dynamic -->
        <tbody id="users">
        </tbody>
    </table>

    <p>Il y a <span id="displayed-count">?</span> résultats affichés sur <span id="total-count">?</span> résultats en
        tout</p>


    <script>
        /**
         * Applies JSON to update table
         * @param {{}} data
         */
        function applyJSON(data) {
            // Select
            let tbody = document.getElementById("users");

            // Purge inside
            tbody.innerHTML = "";

            // Pagination data
            let totalAmount = data.pagination.total;
            document.getElementById("displayed-count").innerText = data.users.length;
            document.getElementById("total-count").innerText = totalAmount;

            // Deal with all users
            let users = data.users;
            for (let user of users) {
                // Create the row
                let row = document.createElement("tr");
                row.setAttribute("onclick", "window.location.href = \"index.html?c=Admin&a=User&uid=" + user.id + "\"");
                row.setAttribute("title", "Obtenir plus d'informations sur l'utilisateur \"" + user.nick + "\"");

                // Properties
                let properties = ["id", "name", "nick", "email"];

                // Deal with them
                for (let property of properties) {
                    // Create the td
                    let td = document.createElement("td");

                    // Set the necessary attributes
                    td.setAttribute("id", "user-" + user.id);
                    td.setAttribute("class", property);

                    // Place the content
                    let content = document.createTextNode(user[property]);

                    // Add it all up
                    td.appendChild(content);
                    row.appendChild(td);
                }

                // Deal with the row itself
                tbody.appendChild(row);
            }
        }

        /**
         * Applies the response to update the table
         *
         * @param res
         */
        function applyResponse(res) {
            // If not ok, log & return
            if (!res.ok) {
                return;
            }

            // Decode to JSON
            res.json().then(applyJSON);
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
        function setParameters(params) {
            let hash = "";
            for (property in params) {
                hash += "&" + property + "=" + params[property];
            }
            hash[0] = "#";
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
            fetch(url.href).then(applyResponse);
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