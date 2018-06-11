var data;

function deleteProperty() {
    // Confirmer
    let ok = confirm("CELA SUPPRIMERA DEFINITIVEMENT LA PROPRIETE " + data["name"].value + "\nÊTES VOUS SÛR DE CONTINUER ?");
    if (!ok) {
        return false;
    }

    // Fetch options
    let fetchOptions = {
        method: "POST",
    };

    // Push
    return fetch(window.location.href + "/delete", fetchOptions).then(function (response) {
        return response;
    }).then(function (response) {
        if (response.status !== 200) {
            return false;
        } else {
            window.location.href = "admin/properties"
        }
    });
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
        credentials: "same-origin",
    };

    // Push
    return fetch(window.location.href, fetchOptions).then(function (response) {
        return response;
    }).then(function (response) {
        return (response.status === 200);
    });
}

// Data populated in PHP, but set via JS
window.addEventListener("load", function () {
    data = JSON.parse(document.getElementById("json_data").innerHTML);
    updateTable(data);
    document.getElementsByTagName("h3")[0].innerHTML = data["name"].value;
});