/**
 * @brief This function is called when we want to change the date of the page
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

//function permettant d'ouvrir la modale d'ajout d'un rendez-vous
function addAppointment() {
  while (tagsPathways.length > 0) {
    tagsPathways.pop();
  }
  while (tagsPatients.length > 0) {
    tagsPatients.pop();
  }
  $("#add-appointment-modal").modal("show");
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
function getTargetsbyMonth(date,pathwayName) {
  dateStr = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
  $.ajax({
    type: 'POST',
    url: '/ajaxAppointment',
    data: { pathway: pathwayName, date: dateStr },
    dataType: "json",
    success: function (data) {
      console.log(data);
      addTargetsToCalendar(data[0]["targets"]);
    },
    error: function (data) {
      console.log("error");
    }
  });
}

//function permettant d'ouvrir la modale d'ajout d'un rendez-vous
function openDayModale(type) {
  var dateToGet = new Date();
  console.log(type)
  if (type == "new") {
  var pathwayName= $('#autocompletePathwayAdd').val();
  }
  if (type == "edit") {
    console.log($('#autocompletePathwayEdit').val())
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

//function permettant d'ouvrir la modale d'édition d'un rendez-vous
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
  //on initialise les informations affichées avec les données du rendez-vous modifié
  document.getElementById("idappointment").value = idappointment;
  document.getElementById("autocompletePatientEdit").value = lastnamepatient + " " + firstnamepatient;
  document.getElementById("autocompletePathwayEdit").value = idpathway;

  split1 = dayappointment.split('-')
  split2 = split1[0].split(' ')
  newDateFormat = split1[2] + '/' + split1[1] + '/20' + split2[1]
  document.getElementById("dayAppointment").value = newDateFormat
  console.log((earliestappointmenttime))
  
  //document.getElementById("dayAppointment").value = dayappointment.replaceAll('-', '/');
  document.getElementById("earliestappointmenttime").value = earliestappointmenttime;
  document.getElementById("latestappointmenttime").value = latestappointmenttime;

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
 * Permet de cacher la fenêtre modale d'ajout
 */
function hideNewModalForm() {
  $("#add-appointment-modal").modal("hide");
}

/**
 * Permet de cacher la fenêtre modale de selection du jour
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

function validate(type) {

  split1 = document.getElementById("dateTemp").value.split('-')
  newDateFormat = split1[2] + '/' + split1[1] + '/' + split1[0]

  if (type == "new") {
    document.getElementById("dateSelected").value = newDateFormat
    //document.getElementById("dateSelected").value = document.getElementById("dateTemp").value.replaceAll('-', '/');;//set the date from the hidden input in the real input
    console.log(document.getElementById("dateSelected").value);
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
  getTargetsbyMonth(newDate,document.getElementById("pathwayHidden").value)
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
  getTargetsbyMonth(newDate,document.getElementById("pathwayHidden").value)
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
  getTargetsbyMonth(today,document.getElementById("pathwayHidden").value)
  calendar.gotoDate(today)
  document.getElementById('load').style.visibility = "visible";
}

function showAppointment(id) {
  console.log(id);
  document.getElementById('load').style.visibility = "visible";
  $.ajax({
    type: 'POST',
    url: '/ajaxInfosAppointment',
    data: { id:id },
    dataType: "json",
    success: function (data) {
      console.log(data)
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
      day=data[0]["dayAppointment"]
      calendar.gotoDate(day+"T12:00:00");
      activities=data[0]["activities"];
      for(var i = 0; i < activities.length; i++){
        calendar.addEvent({
          start: day+"T"+activities[i]['startTime'],
          end: day+"T"+activities[i]['endTime'],
          title: activities[i]['activity'],
          description: activities[i]['activity']
          })
        }
        console.log(data[0])
        calendar.addEvent({
          start: day+"T00:00:00",
          end: day+"T"+data[0]["earliestAppointmentTime"],
          description: "Patient Absent",
          display: 'background',
          color: '#000000'
        })

        calendar.addEvent({
          end: day+"T23:59:59",
          start: day+"T"+data[0]["latestAppointmentTime"],
          description: "Patient Absent",
          display: 'background',
          color: '#000000'})
      calendar.render(); //render the calendar
      console.log(calendar.getEvents())
  document.getElementById('load').style.visibility = "hidden";
    },
      error: function (data) {
        console.log("error");
      },
    
    });

  $('#infos-appointment-modal').modal("show");
}

function filterPathway(idInput) {

  var trs = document.querySelectorAll('#tableAppointment tr:not(.AppointmentPathway)');
  var filter = document.querySelector('#' + idInput).value;
  for (let i = 0; i < trs.length; i++) {
    var regex = new RegExp(filter, 'i');
    var pathwayName1 = trs[i].cells[2].outerText;
    if (regex.test(pathwayName1) == false) {
      trs[i].style.display = 'none';
    }
    else {
      trs[i].style.display = '';
    }
  }
}

function filterPatient(idInput) {

  var trs = document.querySelectorAll('#tableAppointment tr:not(.AppointmentPathway)');
  var filter = document.querySelector('#' + idInput).value;
  for (let i = 0; i < trs.length; i++) {
    var regex = new RegExp(filter, 'i');
    var patientName = trs[i].cells[1].outerText;
    if (regex.test(patientName) == false) {
      trs[i].style.display = 'none';
    }
    else {
      trs[i].style.display = '';
    }
  }
}