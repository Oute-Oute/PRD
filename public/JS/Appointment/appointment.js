/**
 * Allows to change the date
 */
var tagsPatients = [];
var tagsPathways = [];
function changeDate() {
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
    "/appointments?date=" + dateStr); //rerender the page with a new date
}

/**
 * Allows to open a modal to create a new appointment
 */
function addAppointment(mode) {
  while (tagsPathways.length > 0) {
    tagsPathways.pop();
  }
  while (tagsPatients.length > 0) {
    tagsPatients.pop();
  }
  if (mode == "add") {
    $("#add-appointment-modal").modal("show");
  }
  if (mode == "auto") {
    $("#auto-modal").modal("show");
  }
  var dataPatients = JSON.parse(document.getElementById("patientValues").value.replaceAll("3aZt3r", " "));
  for (var i = 0; i < dataPatients.length; i++) {
    firstname = dataPatients[i]["firstname"];
    lastname = dataPatients[i]["lastname"];
    patient = lastname + " " + firstname;
    tagsPatients.push(patient);
  }
  var dataPathways = JSON.parse(document.getElementById("pathwayValues").value.replaceAll("3aZt3r", " "));
  for (var i = 0; i < dataPathways.length; i++) {
    pathName = dataPathways[i]["title"];
    tagsPathways.push(pathName);
  }
}

/**
 * Allows to get the targets filtered by month
 */
function getTargetsbyMonth(date, pathwayName) {
  dateStr = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
  $.ajax({
    type: 'POST',
    url: '/ajaxAppointment',
    data: { pathway: pathwayName, date: dateStr },
    dataType: "json",
    success: function (data) {
      if (data[0]['pathway'].length == 0) {
        document.getElementById("empty-parcours").style.visibility = "visible";
      }
      else {
        document.getElementById("empty-parcours").style.visibility = "hidden";
      }
      addTargetsToCalendar(data[0]["targets"]);
    },
    error: function (data) {
    }
  });
}

/**
 * Allows to display a modal that allows to add a new appointment
 */
function openDayModale(type) {
  var dateToGet = new Date();
  if (type == "new") {
    var pathwayName = $('#autocompletePathwayAdd').val();
  }
  if (type == "edit") {
    var pathwayName = $('#autocompletePathwayEdit').val();
  }

  getTargetsbyMonth(dateToGet, pathwayName);
  document.getElementById("buttonSelect").onclick = function () { validate(type); }
  document.getElementById("buttonCancel").onclick = function () { hideDayModale(type); }
  document.getElementById('load').style.visibility = "";
  $("#add-appointment-modal").modal("hide");
  $("#select-day-modal").modal("show");
  createCalendar(type);
  document.getElementById("pathwayHidden").value = pathwayName;
}

/**
 * Allows to display a modal that allows to edit an appointment
 */
function editAppointment(
  idappointment,
  lastnamepatient,
  firstnamepatient,
  idpathway,
  dayappointment,
  earliestappointmenttime,
  latestappointmenttime
) {
  while (tagsPathways.length > 0) {
    tagsPathways.pop();
  }
  while (tagsPatients.length > 0) {
    tagsPatients.pop();
  }
  //Filling fields with the data of the appointment
  document.getElementById("wrong-edit-input").style.visibility = "hidden";
  document.getElementById("idappointment").value = idappointment;
  document.getElementById("autocompletePatientEdit").value = lastnamepatient + " " + firstnamepatient;
  document.getElementById("patient-name").value = lastnamepatient + " " + firstnamepatient;
  document.getElementById("autocompletePathwayEdit").value = idpathway;
  document.getElementById("pathway-hidden").value = idpathway;
  document.getElementById("dayAppointment").value = dayappointment
  document.getElementById("dayAppointment-hidden").value = dayappointment
  document.getElementById("earliestappointmenttime").value = earliestappointmenttime;
  document.getElementById("latestappointmenttime").value = latestappointmenttime;

  // Displaying the modal
  $("#edit-appointment-modal").modal("show");
}

/**
 * Allows to hide the edit modal form appointment. Called when a click is done somewhere else than the modal
 */
function hideEditModalForm() {
  $("#edit-appointment-modal").modal("hide");
}

/**
 * Allows to hide the add modal form appointment. Called when a click is done somewhere else than the modal
 */
function hideNewModalForm() {
  $("#add-appointment-modal").modal("hide");
}

/**
 * Allows to hide the day modal appointments. Called when a click is done somewhere else than the modal
 */
function hideDayModale(type) {
  $("#select-day-modal").modal("hide");
  if (type == "new") {
    $("#add-appointment-modal").modal("show");
  }
  if (type == "edit") {
    $("#edit-appointment-modal").modal("show");
  }
}

/**
 * Allows to validate the form
 */
function validate(type) {

  split1 = document.getElementById("dateTemp").value.split('-')
  newDateFormat = split1[2] + '/' + split1[1] + '/' + split1[0]

  if (type == "new") {
    document.getElementById("dateSelected").value = newDateFormat
    //document.getElementById("dateSelected").value = document.getElementById("dateTemp").value.replaceAll('-', '/');;//set the date from the hidden input in the real input
  }
  else if (type == "edit") {
    document.getElementById("dayAppointment").value = newDateFormat
    //document.getElementById("dayAppointment").value = document.getElementById("dateTemp").value.replaceAll('-', '/');;//set the date from the hidden input in the real input
  }
  hideDayModale(type);
}


/**
 * @brief This function is called when we want to create or recreate the calendar
 * @param {*} resources the type of resources to display (Patients, Resources...)
 */
function createCalendar(type) {
  date = new Date(); //create a new date with the date in the hidden input
  var calendarEl = document.getElementById("calendar-appointment"); //create the calendar variable

  //create the calendar
  calendar = new FullCalendar.Calendar(calendarEl, {
    schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives", //we use a non commercial license
    initialView: "dayGridMonth", //set the format of the calendar
    locale: "frLocale", //set the language in french
    firstDay: 1, //set the first day of the week to monday
    timeZone: "Europe/Paris", //set the timezone for France
    selectable: true, //set the calendar to be selectable
    editable: true, //set the calendar not to be editable
    nowIndicator: true,
    headerToolbar: {
      start: null,
      center: "title",
      end: null,
    },
    eventDidMount: function (info) {
      $(info.el).tooltip({
        title: info.event.extendedProps.description,
        placement: "top",
        trigger: "hover",
        container: "body",
      });
    },
    dateClick: function (info) {
      document.getElementById("buttonSelect").style = "background-color : #37BC9B; border : 1px solid #37BC9B;"
      document.getElementById("buttonSelect").disabled = false;
      document.getElementById("dateTemp").value = info.dateStr; //set the date in the hidden input
    },
  });
  if (type == "new") {
    calendar.select(document.getElementById("dateSelected").value);
  }
  if (type == "edit") {
    calendar.select(document.getElementById("dayAppointment").value);
  }
  calendar.render(); //render the calendar
}

function addTargetsToCalendar(targets) {
  targets.forEach(element => {
    calendar.addEvent({
      allDay: true,
      start: element.start,
      description: element.description,
      display: 'background',
      color: element.color,

    });

  });
  calendar.render();
  document.getElementById('load').style.visibility = "hidden";
}

/**
 * @brief This function is called when we want to go to the previous day
 */
function PreviousMonth() {
  var oldDate = calendar.getDate() //get the old day in the calendar
  var newDate = new Date(
    oldDate.getFullYear(),
    oldDate.getMonth() - 1,
    oldDate.getDate()
  ); //create a new day after the old one
  var day = newDate.getDate(); //get the day
  var month = newDate.getMonth() + 1; //get the month (add 1 because it starts at 0)
  var year = newDate.getFullYear(); //get the year
  if (day < 10) {
    day = "0" + day;
  } //if the day is less than 10, add a 0 before to fit with DateTime format
  if (month < 10) {
    month = "0" + month;
  } //if the month is less than 10, add a 0 before to fit with DateTime format
  calendar.removeAllEvents();
  getTargetsbyMonth(newDate, document.getElementById("pathwayHidden").value)
  calendar.gotoDate(newDate)
  document.getElementById('load').style.visibility = "visible";
}


/**
 * @brief This function is called when we want to go to the next day
 */
function NextMonth() {
  var oldDate = calendar.getDate() //get the old day in the calendar
  var newDate = new Date(
    oldDate.getFullYear(),
    oldDate.getMonth() + 1,
    oldDate.getDate()
  ); //create a new day after the old one
  var day = newDate.getDate(); //get the day
  var month = newDate.getMonth() + 1; //get the month (add 1 because it starts at 0)
  var year = newDate.getFullYear(); //get the year
  if (day < 10) {
    day = "0" + day;
  } //if the day is less than 10, add a 0 before to fit with DateTime format
  if (month < 10) {
    month = "0" + month;
  } //if the month is less than 10, add a 0 before to fit with DateTime format
  calendar.removeAllEvents();
  getTargetsbyMonth(newDate, document.getElementById("pathwayHidden").value)
  calendar.gotoDate(newDate)
  document.getElementById('load').style.visibility = "visible";
}


/**
 * @brief This function is called when we want to go to the date of today
 */
function Today() {
  var today = new Date(); //get the date of today
  var day = today.getDate(); //get the day
  var month = today.getMonth() + 1; //get the month (add 1 because it starts at 0)
  var year = today.getFullYear(); //get the year
  if (day < 10) {
    day = "0" + day;
  } //if the day is less than 10, add a 0 before to fit with DateTime format
  if (month < 10) {
    month = "0" + month;
  } //if the month is less than 10, add a 0 before to fit with DateTime format
  calendar.removeAllEvents();
  getTargetsbyMonth(today, document.getElementById("pathwayHidden").value)
  calendar.gotoDate(today)
  document.getElementById('load').style.visibility = "visible";
}

/**
 * Allows to display a modal infos that show data of a specific appointment
 */
function showAppointment(id) {
  document.getElementById('load-info-appt').style.visibility = "visible";
  $.ajax({
    type: 'POST',
    url: '/ajaxInfosAppointment',
    data: { id: id },
    dataType: "json",
    success: function (data) {
      document.getElementById('infos-appointment-title').textContent = data[0].patientLastname + " " + data[0].patientFirstname + " : " + data[0].pathwayName;
      if (data[0].activities.length == 0) {
        document.getElementById('infos-appointment-unscheduled').hidden = false;
        document.getElementById("calendar-infos-appointment").hidden = true;
      }
      else {
        document.getElementById('infos-appointment-unscheduled').hidden = true;
        document.getElementById("calendar-infos-appointment").hidden = false;
        var calendarEl = document.getElementById("calendar-infos-appointment"); //create the calendar variable

        //create the calendar
        calendar = new FullCalendar.Calendar(calendarEl, {
          schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives", //we use a non commercial license
          initialView: "timeGridDay", //set the format of the calendar
          locale: "frLocale", //set the language in french
          timeZone: "Europe/Paris", //set the timezone for France
          selectable: false, //set the calendar to be selectable
          editable: false, //set the calendar not to be editable
          allDaySlot: false,
          nowIndicator: true,
          contentHeight: "500px",
          headerToolbar: {
            start: null,
            center: "title",
            end: null,
          },
          eventDisplay: "block",
          eventBackgroundColor: '#3788d8',
          eventDidMount: function (info) {
            $(info.el).tooltip({
              title: info.event.extendedProps.description,
              placement: "top",
              trigger: "hover",
              container: "body",
            });
          },
        });
        day = data[0]["dayAppointment"]
        calendar.gotoDate(day + "T12:00:00");
        activities = data[0]["activities"];
        for (var i = 0; i < activities.length; i++) {
          calendar.addEvent({
            start: day + "T" + activities[i]['startTime'],
            end: day + "T" + activities[i]['endTime'],
            title: activities[i]['activity'],
            description: activities[i]['activity']
          })
        }
        calendar.addEvent({
          start: day + "T00:00:00",
          end: day + "T" + data[0]["earliestAppointmentTime"],
          description: "Patient Absent",
          display: 'background',
          color: '#000000'
        })

        calendar.addEvent({
          end: day + "T23:59:59",
          start: day + "T" + data[0]["latestAppointmentTime"],
          description: "Patient Absent",
          display: 'background',
          color: '#000000'
        })
        calendar.render(); //render the calendar
      }
      document.getElementById('load-info-appt').style.visibility = "hidden";
    },
    error: function (data) {
    },

  });
  $('#infos-appointment-modal').modal("show");
}

/**
 * Allows to filter patients to not display all at the same time
 */
function filterAppointment(selected) {
  if (selected.id != "notfound") {
    var trs = document.querySelectorAll('#tableAppointment tr:not(.headerAppointment)');
    for (let i = 0; i < trs.length; i++) {
      trs[i].style.display = 'none';
    }
    table = document.getElementById('appointmentTable');
    var tr = document.createElement('tr');
    table.appendChild(tr);
    var name = document.createElement('td');
    name.append(selected.lastname + " " + selected.firstname);
    tr.appendChild(name);
    var pathway = document.createElement('td');
    pathway.append(selected.pathway);
    tr.appendChild(pathway);
    var dayAppointment = document.createElement('td');
    dayAppointment.append(selected.dayappointment);
    tr.appendChild(dayAppointment);
    var buttons = document.createElement('td');
    var infos = document.createElement('button');
    infos.setAttribute('class', 'btn-infos btn-secondary');
    infos.setAttribute('onclick', 'showAppointment(' + selected.id + ')');
    infos.append('Informations');
    var edit = document.createElement('button');
    edit.setAttribute('class', 'btn-edit btn-secondary');
    edit.setAttribute('onclick', 'editAppointment(' + selected.id + ',"' + selected.lastname + '","' + selected.firstname + '","' + selected.pathway + '","' + selected.dayappointment + '","' + selected.earliestappointmenttime + '","' + selected.latestappointmenttime + '")');
    edit.append('Editer');
    var deleteButton = document.createElement('button');
    deleteButton.setAttribute('class', 'btn-delete btn-secondary');
    deleteButton.append('Supprimer');
    deleteButton.setAttribute('onclick', 'showPopup(' + selected.id + ')');
    buttons.appendChild(infos);
    buttons.appendChild(edit);
    buttons.appendChild(deleteButton);
    tr.appendChild(buttons);

    paginator = document.getElementById('paginator');
    paginator.style.display = 'none';
  }
}

/**
 * Allows to display all patients without any filter
 */
function displayAll() {
  var trs = document.querySelectorAll('#tableAppointment tr:not(.headerAppointment)');
  var input = document.getElementById('autocompleteInputPatientName');
  if (input.value == '') {
    for (let i = 0; i < trs.length; i++) {
      if (trs[i].style.display == 'none') {
        trs[i].style.display = 'table-row';
      }
      else if (trs[i].className != 'original') {
        trs[i].remove()
      }
    }
    paginator = document.getElementById('paginator');
    paginator.style.display = '';
  }
}

/**
 * Allows to display a popup to confirm the deletion of an appointment
 */
function showPopup(id) {
  document.getElementById("form-appointment-delete").action = "/appointment/" + id + "/delete"
  $('#modal-popup').modal('show')
}

function hideAuto() {
  $("#auto-modal").modal("hide");
}