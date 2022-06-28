function addPatient(){
    $('#add-patient-modal').modal("show");
}

function editPatient(lastname, firstname) {
    document.getElementById('edit-patient-modal').getElementById('lastname').value = lastname;
    document.getElementById('edit-patient-modal').getElementById('firstname').value = firstname;
    $('#edit-patient-modal').modal("show");
}

function showPatient(id) {
    $('#show-patient-modal').modal("show");
}
