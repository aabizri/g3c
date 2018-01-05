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

            // Insert
            for (let user of users) {
                let row = document.createElement("tr");

                // Properties
                let properties = ["id", "name", "nick", "email"];

                // Deal with them
                for (let property of properties) {
                    let td = document.createElement("td");
                    td.setAttribute("id", "user-" + user.id);
                    td.setAttribute("class", property);
                    let content = document.createTextNode(user[property]);
                    td.appendChild(content);
                    row.appendChild(td);
                }

                // Deal with the row itself
                tbody.appendChild(row);
            }
        };

        xhr.open("GET", "index.php?c=Admin&a=Users&v=json", true);
        xhr.send();
    </script>
</main>