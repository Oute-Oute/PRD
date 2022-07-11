/**
 * @brief This function is called when we want to change the date of the page
 */
 function changeDate() {
  console.log("coucou")
  var date = new Date(document.getElementById("Date").value); //get the new date in the DatePicker
  var day = date.getDate(); //get the day
  var month = date.getMonth() + 1; //get the month (add 1 because it starts at 0)
  var year = date.getFullYear(); //get the year
  if (day < 10) {
    day = "0" + day;
  } //if the day is less than 10, add a 0 before to fit with DateTime format
  if (month < 10) {
    month = "0" + month;
  } //if the month is less than 10, add a 0 before to fit with DateTime format
  dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
  window.location.assign(
    "/appointments?date=" +
      dateStr
  ); //rerender the page with a new date
}

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
