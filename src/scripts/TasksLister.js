function showMatchTasks() {
    document.getElementById("outer_match_container").style.display = "block";
    document.getElementById("inner_match_container").style.display = "block";

    document.body.style.overflow = "hidden"; // Disable scrolling
}

function hideMatchTasks() {
    document.getElementById("outer_match_container").style.display = "none";
    document.getElementById("inner_match_container").style.display = "none";
    document.body.style.overflow = ""; // Restore scrolling
}

function handleServerResponse(task_id, response) {
    let divElement = document.getElementById("task_container_"+task_id);
    let checkbox = document.getElementById("task_id_"+task_id);
    let wait_element = document.getElementById("wait_id_" + task_id);

    if (response["error"]) {
        checkbox.checked = !checkbox.checked;
        alert(response["errorMessage"]);
    }

    if (response["operation"] === "add") {
        if (response["has_to_be_approved"]) {
        divElement.style.textDecoration = "none";
        wait_element.style.display = "inline";
        }
        else if (response["is_approved"]) {
            divElement.style.textDecoration = "line-through";
            wait_element.style.display = "none";

            try {
                document.getElementById("button_id_"+task_id).style.display = "none";
            }catch (error) {}
        }
    }
    else if (response["operation"] === "remove") {
        divElement.style.textDecoration = "none";
        wait_element.style.display = "none";

        try {
        document.getElementById("input_id_"+task_id).value = "";
        }
        catch (error) {}
        try {
            document.getElementById("button_id_"+task_id).style.display = "none";
        }catch (error) {}
    }
}

function changePoints(task_id) {
    let points = document.getElementById("input_id_"+task_id).value;

    if (points === "") {
        document.getElementById("task_id_"+task_id).checked = false;
        submitTask(task_id, points);
    }
    else if (points <= 0) {
        alert("Zadajte body alebo opravte body na kladne cislo");
    }
    else {
        document.getElementById("task_id_"+task_id).checked = true;
        submitTask(task_id, points);
    }
}

function submitTask(task_id, points = null) {
    console.log(task_id, points);

    let checkbox = document.getElementById("task_id_"+task_id);

    try{
        let temp_points = document.getElementById("input_id_"+task_id).value;
        if (temp_points === "" && checkbox.checked) {
            alert("Zadajte body alebo opravte body na kladne cislo");
            checkbox.checked = false;
            return;
        }
    } catch (error) {}

    let pathway;
    if (checkbox.checked) {
        pathway = "../APIs/addTaskToUser.php";
    }
    else {
        pathway = "../APIs/removeTaskFromUser.php";
    }

    const data = {
        task_id: task_id,
        points: points
    };

    fetch(pathway, {
            method: "POST",
            headers: {
            "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(response => handleServerResponse(task_id, response))
    .catch(error => alert(error.message));
}

function commentListener(user_id, task_id) {
    let comment = document.getElementById("comment_input_"+user_id+"_"+task_id).value;
    alterComment(user_id, task_id, comment);
}

function alterComment(user_id, task_id, comment) {
    let operation = "write";
    if (comment === "") {
        operation = "remove";
    }

    let pathway = "../APIs/commentAPI.php";

    const data = {
        user_id: user_id,
        task_id: task_id,
        comment: comment,
        operation: operation
    };

    fetch(pathway, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(response => {
        if (response["error"]) {
            alert(response["errorMessage"]);
        }
    })
    .catch(error => alert("my error: " + error.message));
}