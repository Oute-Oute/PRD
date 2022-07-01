function addUser(){
    $('#add-user-modal').modal("show");
}

function editUser(id, username ) {
    document.getElementById('iduser').value = id;
    document.getElementById('username').value = username;
    //document.getElementById('role').value = role;
    $('#edit-user-modal').modal("show");
}

function showPatient(id) {
    document.getElementById('iduser').value = id;
    document.getElementById('username').value = username;
    document.getElementById('role').value = role;
    $('#show-user-modal').modal("show");
}
