<main>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>

    <table>
        <!-- Table header -->
        <thead>
        <tr>
            <th>ID de l'usager</th>
            <th>Nom complet</th>
            <th>Pseudonyme</th>
            <th>Email</th>
        </tr>
        </thead>

        <!-- Table body, dynamic -->
        <tbody id="users">
        </tbody>
    </table>

    <script>
        /**
         * Applies JSON to update table
         * @param users
         */
        function applyJSON(users) {
            // Select
            let tbody = document.getElementById("users");

            // Purge inside
            tbody.innerHTML = "";

            // Insert
            for (let user of users) {
                // Create the row
                let row = document.createElement("tr");
                row.setAttribute("onclick", "window.location.href = \"index.html?c=Admin&a=User&uid=" + user.id + "\"");

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
                if (!params.hasOwnProperty(property)) {
                    console.log("Hash parameters don't have property index " + property);
                    break;
                }
                url.searchParams.set(property, params[property]);
            }

            // Execute request
            fetch(url.href).then(applyResponse);
        }

        window.addEventListener("hashchange", sync, false);
        sync();
    </script>
</main>