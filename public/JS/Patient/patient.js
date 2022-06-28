function addPatient(){
    $('#add-patient-modal').modal("show");
}

function editPatient(id, lastname, firstname) {
    document.getElementById('idpatient').value = id;
    document.getElementById('lastname').value = lastname;
    document.getElementById('firstname').value = firstname;
    $('#edit-patient-modal').modal("show");
}

function showPatient(id) {
    document.getElementById('idpatient').value = id;
    document.getElementById('lastname').value = lastname;
    document.getElementById('firstname').value = firstname;
    $('#show-patient-modal').modal("show");
}
