/**
 * @file consultation-planning.js
 * @brief This file contains the js scripts for the consultation planning page, essentially the calendar.
 * @author Thomas Blumstein
 * @version 1.0
 * @date 2022/06
 */

var calendar; // var globale pour le calendrier
var date = new Date(); //create a default date
var dateStr = date.toDateString();
var headerResources = "Patients";
const height = document.querySelector("div").clientHeight;

/**

 * @brief This function is called when we want the value in the url
 * @param {*} param the value to get
 * @returns the value of the param
 */
function $_GET(param) {
  var vars = {};
  window.location.href.replace(location.hash, "").replace(
    /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
    function (m, key, value) {
      // callback
      vars[key] = value !== undefined ? value : "";
    }
  );

  if (param) {
    return vars[param] ? vars[param] : null;
  }
  return vars;
}

//update the date with the date in url
var dateStr = $_GET("date");
date = new Date(dateStr);

document.addEventListener("DOMContentLoaded", function () {
  createCalendar("Patients");
});

/**
 * @brief This function is called when we want to change the type of the calendar (Patients, Resources...)
 */
function changePlanning() {
  var header =
    document.getElementById("displayList").options[
      document.getElementById("displayList").selectedIndex
    ].text; //get the type of resources to display in the list
  headerResources = header; //update the header of the list
  createCalendar(header); //rerender the calendar with the new type of resources
}

/**
 * @brief This function is called when we want to go to the modification page
 */
function modify(id = 1) {
  var day = calendar.getDate().getDate(); //get the day
  var month = calendar.getDate().getMonth() + 1; //get the month (add 1 because it starts at 0)
  var year = calendar.getDate().getFullYear(); //get the year
  if (day < 10) {
    day = "0" + day;
  } //if the day is less than 10, add a 0 before to fit with DateTime format
  if (month < 10) {
    month = "0" + month;
  } //if the month is less than 10, add a 0 before to fit with DateTime format
  dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
  window.location.assign("/ModificationPlanning?date=" + dateStr + "&id=" + id); //goto the modification page with the date and user id
}

/**
 * @brief This function is called when we want to go to display the filter window, called when click on the filter button
 */
function filterShow() {
  if (document.getElementById("filterId").style.display != "none") {
    //if the filter is already displayed
    document.getElementById("filterId").style.display = "none"; //hide the filter
  } else {
    document.getElementById("filterId").style.display = "inline-block"; //display the filter
  }
}

/**
 * @brief This function is called when we want to go to create or recreate the calendar
 * @param {*} resources the type of resources to display (Patients, Resources...)
 */
function createCalendar(resources) {
  var events = JSON.parse(
    document.getElementById("events").value.replaceAll("3aZt3r", " ")
  ); //get the events from the hidden input
  console.log(events);
  if (document.getElementById("Date").value != null) {
    //if the date is not null (if the page is not the first load)
    dateStr = document.getElementById("Date").value; //get the date from the hidden input
  }
  date = new Date(dateStr); //create a new date with the date in the hidden input
  var calendarEl = document.getElementById("calendar"); //create the calendar variable

  //create the calendar
  calendar = new FullCalendar.Calendar(calendarEl, {
    schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives", //we use a non commercial license
    initialView: "resourceTimelineDay", //set teh format of the calendar
    slotDuration: "00:20:00", //set the duration of the slot
    locale: "fr", //set the language in french
    timeZone: "Europe/Paris", //set the timezone for France
    selectable: true, //set the calendar to be selectable
    editable: false, //set the calendar not to be editable
    contentHeight: (height * 12) / 16, //set the height of the calendar to fit with a standard display
    handleWindowResize: true, //set the calendar to be resizable
    eventDurationEditable: false, //set the event duration not to be editable
    nowIndicator: true, //display the current time
    headerToolbar: {
      //delete the toolbar
      start: null,
      center: null,
      end: null,
    },

    slotLabelFormat: {
      //set the format of the time
      hour: "2-digit", //2-digit, numeric
      minute: "2-digit", //2-digit, numeric
      meridiem: false, //lowercase, short, narrow, false (display of AM/PM)
      hour12: false, //set to 24h format
    },
    resourceOrder: "title", //display the resources in the alphabetical order of their names
    resourceAreaWidth: "20%", //set the width of the resources area
    resourceAreaHeaderContent: headerResources, //set the title of the resources area
    events: events, //set the events

    //when we click on an event, display a modal window with the event information
    eventClick: function (event) {
      //get the data of the event
      var id = event.event._def.publicId; //get the id of the event
      var activity = calendar.getEventById(id); //get the event with the id
      var start = activity.start; //get the start date of the event
      var end = activity.end; //get the end date of the event
      var humanResources = activity.extendedProps.humanResources;//get the human resources of the event
      var humanResourcesNames = "";//create a string with the human resources names
      for (var i = 0; i < humanResources.length-1; i++) {//for each human resource except the last one
        if (humanResources[i][1] != undefined) { //if the human resource exist
          humanResourcesNames += humanResources[i][1] + "; ";//add the human resource name to the string with a ; and a space
        }
      }
      humanResourcesNames += humanResources[i][1] //add the last human resource name to the string

      var materialResources = activity.extendedProps.materialResources;//get the material resources of the event
      var materialResourcesNames = "";//create a string with the material resources names
      for (var i = 0; i < materialResources.length-1; i++) {//for each material resource except the last one
        if (materialResources[i][1] != undefined) {//if the material resource exist
          materialResourcesNames += materialResources[i][1] + "; ";//add the material resource name to the string with a ; and a space
        }
      }
      materialResourcesNames += materialResources[i][1]//add the last material resource name to the string

      //set data to display in the modal window
      $("#start").val(start.toISOString().substring(0, 19));//set the start date of the event
      $("#end").val(end.toISOString().substring(0, 19));//set the end date of the event
      document.getElementById("show-title").innerHTML = activity.title;//set the title of the event
      $("#parcours").val(activity.extendedProps.pathway);//set the pathway of the event
      $("#patient").val(activity.extendedProps.patient);//set the patient of the event
      $("#rh").val(humanResourcesNames);//set the human resources of the event
      $("#rm").val(materialResourcesNames);//set the material resources of the event

      $("#modify-planning-modal").modal("show");//open the window
    },
  });
  //change the type of the calendar(Patients, Resources...)
  switch (resources) {
    case "Patients": //if we want to display by the patients
      var tempArray = JSON.parse(
        document.getElementById("appointment").value.replaceAll("3aZt3r", " ")
      ); //get the data of the appointments
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i];
        patient = temp["patient"]; //get the resources data
        calendar.addResource({
          //add the resources to the calendar
          id: patient[0]["id"],
          title: patient[0]["title"],
        });
      }
      break;
    case "Parcours": //if we want to display by the parcours
      var tempArray = JSON.parse(
        document.getElementById("appointment").value.replaceAll("3aZt3r", " ")
      ); //get the data of the appointments
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i];
        pathway = temp["pathway"]; //get the resources data
        calendar.addResource({
          //add the resources to the calendar
          id: pathway[0]["id"],
          title: pathway[0]["title"],
        });
      }
      break;
    case "Ressources Humaines": //if we want to display by the resources
      var tempArray = JSON.parse(
        document.getElementById("human").value.replaceAll("3aZt3r", " ")
      ); //get the data of the resources
      console.log(tempArray);
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i]; //get the resources data
        calendar.addResource({
          //add the resources to the calendar
          id: temp["id"],
          title: temp["title"],
        });
      }
      break;
    case "Ressources Matérielles": //if we want to display by the resources
      var tempArray = JSON.parse(
        document.getElementById("material").value.replaceAll("3aZt3r", " ")
      ); //get the data of the resources
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i]; //get the resources data
        calendar.addResource({
          //add the resources to the calendar
          id: temp["id"],
          title: temp["title"],
        });
      }
      break;
  }

  calendar.gotoDate(date); //go to the date we want to display
  calendar.render(); //display the calendar
}

/**
 * @brief This function is called when we want to change the date of the page
 */
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
  window.location.assign("/ConsultationPlanning?date=" + dateStr); //rerender the page with a new date
}

/**
 * @brief This function is called when we want to go to the previous day
 */
function PreviousDay() {
  var oldDate = new Date(document.getElementById("Date").value); //get the old day in the calendar
  var newDate = new Date(
    oldDate.getFullYear(),
    oldDate.getMonth(),
    oldDate.getDate() - 1
  ); //create a new day before the old one
  var day = newDate.getDate(); //get the day
  var month = newDate.getMonth() + 1; //get the month (add 1 because it starts at 0)
  var year = newDate.getFullYear(); //get the year
  if (day < 10) {
    day = "0" + day;
  } //if the day is less than 10, add a 0 before to fit with DateTime format
  if (month < 10) {
    month = "0" + month;
  } //if the month is less than 10, add a 0 before to fit with DateTime format
  dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
  window.location.assign("/ConsultationPlanning?date=" + dateStr); //rerender the page with a new date
}

/**
 * @brief This function is called when we want to go to the next day
 */
function NextDay() {
  var oldDate = new Date(document.getElementById("Date").value); //get the old day in the calendar
  var newDate = new Date(
    oldDate.getFullYear(),
    oldDate.getMonth(),
    oldDate.getDate() + 1
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
  dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
  window.location.assign("/ConsultationPlanning?date=" + dateStr); //rerender the page with a new date
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
  dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
  window.location.assign("/ConsultationPlanning?date=" + dateStr); //rerender the page with a new date
}
