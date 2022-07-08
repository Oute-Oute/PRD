//function permettant d'ouvrir la modale d'ajout d'un rendez-vous
function addAppointment() {
  $("#add-appointment-modal").modal("show");
}

//function permettant d'ouvrir la modale d'édition d'un rendez-vous
function editAppointment(
  idappointment,
  idpatient,
  idpathway,
  dayappointment,
  earliestappointmenttime,
  latestappointmenttime
) {
  //on initialise les informations affichées avec les données du rendez-vous modifié
  document.getElementById("idappointment").value = idappointment;
  document.getElementById("idpatient").value = idpatient;
  document.getElementById("idpathway").value = idpathway;
  document.getElementById("dayappointment").value = dayappointment;
  document.getElementById("earliestappointmenttime").value =
    earliestappointmenttime;
  document.getElementById("latestappointmenttime").value =
    latestappointmenttime;

  //on affiche la modale
  $("#edit-appointment-modal").modal("show");
}

/**
 * Permet de cacher la fenêtre modale d'édition
 */
function hideEditModalForm() {
  $("#edit-appointment-modal").modal("hide");
}

/**
 * Permet de cacher la fenêtre modale d'édition
 */
function hideNewModalForm() {
  $("#add-appointment-modal").modal("hide");
}
