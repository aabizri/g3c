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
        row.setAttribute("onclick", "window.location.href = \"admin/users/" + value.id + "\"");
        row.setAttribute("title", "Obtenir plus d'informations sur l'utilisateur \"" + value.nick + "\"");

        // Deal with them
        for (let column of columns) {
            // Create the td
            let td = document.createElement("td");

            // Set the necessary attributes
            td.setAttribute("id", "user-" + value.id + "-" + column);
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

    // Cross-origin request
    let options = {credentials: 'same-origin'};

    // Execute request
    fetch(url.href, options).then(function (res) {
        let columns = ["id", "name", "nick", "email"];
        applyResponse("users", "users", columns, res);
    });
}

function formSubmit() {
    let countInput = document.getElementById("count-input");
    let count = countInput.options[countInput.selectedIndex].value;
    setParameters({"count": count});
    sync();
}

window.addEventListener("load", function () {
    // Add the event listener
    window.addEventListener("hashchange", sync, false);
    sync();
});