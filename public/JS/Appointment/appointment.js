function addAppointment(){
    $('#add-appointment-modal').modal("show");
}

function editAppointment(idappointment, idpatient, idpathway, dayappointment, earliestappointmenttime, latestappointmenttime) {
    document.getElementById('idappointment').value = idappointment;
    document.getElementById('idpatient').value = idpatient;
    document.getElementById('idpathway').value = idpathway;
    document.getElementById('dayappointment').value = dayappointment;
    document.getElementById('earliestappointmenttime').value = earliestappointmenttime;
    document.getElementById('latestappointmenttime').value = latestappointmenttime;
    console.log(dayappointment);
    $('#edit-appointment-modal').modal("show");
}