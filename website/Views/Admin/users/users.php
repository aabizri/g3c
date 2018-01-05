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
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== xhr.DONE) {
                return;
            }

            // Parse
            let users = JSON.parse(xhr.response);

            // Select
            let tbody = document.getElementById("users");

            // Purge inside
            tbody.innerHTML = "";

            // Insert
            for (let user of users) {
                // Create the row
                let row = document.createElement("tr");

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
        };

        function getCurrentParameters() {
            let hash = window.location.hash.substr(1);

            let result = hash.split('&').reduce(function (result, item) {
                let parts = item.split('=');
                result[parts[0]] = parts[1];
                return result;
            }, {});

            return result;
        }

        function setCurrentParameters(params) {
            let hash = "";
            for (property in params) {
                hash += "&" + property + "=" + params[property];
            }
            hash[0] = "#";
            window.location.hash = hash;
        }

        function hydrate() {
            // Get the parameters
            let params = getCurrentParameters();

            // Build the new URL
            let url = new URL(window.location.href);
            url.searchParams.set("v", "json");
            for (property in params) {
                url.searchParams.set(property, params[property]);
            }

            // Execute request
            xhr.open("GET", url.href, true);

            // Send
            xhr.send();
        }

        window.addEventListener("hashchange", hydrate, false);
        hydrate();
    </script>
</main>