let data = {};

function submitChanges(){
    let pathway = "../APIs/handleGroupChange.php";

    fetch(pathway, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)  // Convert the array to JSON
    })
        .then(response => response.json())
        .then(data => {
            if(data["error"]) {
                alert(data["errorMessage"]);
            }
            else {
                location.reload(); // Refresh the page
            }
        }) // Handle PHP response
        .catch(error => alter(error.message));
}

function alter(user_id, position_id, leader_id) {
    data[user_id] = [position_id, leader_id]
    console.log(data);
}

function containerChange(id)
{
    const container = document.getElementById(id);
    if (container.style.display === "block"){
        container.style.display = "none";
    }
    else {
        container.style.display = "block";
    }
}

function listener(user_id) {
    let position_id = document.getElementById("position_select_id_"+user_id).value;
    let group_id = document.getElementById("group_select_id_"+user_id).value;
    alter(user_id, position_id, group_id);
}

function changeGroupName(leader_id, name) {
    let pathway = "../APIs/groupNameChangeAPI.php";

    const data = {
        leader_id: leader_id,
        name: name
    };

    fetch(pathway, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)  // Convert the array to JSON
    })
        .then(response => response.json())
        .then(data => {
            if(data["error"]) {
                alert(data["errorMessage"]);
            }
        }) // Handle PHP response
        .catch(error => alter(error.message));
}