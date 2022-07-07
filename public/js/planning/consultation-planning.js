/**
 * @file consultation-planning.js
 * @brief This file contains the js scripts for the consultation planning page, essentially the calendar.
 * @author Thomas Blumstein
 * @version 2.0
 * @date 2022/07
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
dateStr = $_GET("date");
date = new Date(dateStr);

if ($_GET("headerResources") != null) {
  headerResources = $_GET("headerResources"); //get the type of resources to display in the list
  headerResources = headerResources.replaceAll("%20", " "); //set the space in the header
  headerResources = headerResources.replaceAll("%C3%A9", "é"); //set the comma in the header
  console.log(headerResources);
} else {
  headerResources = "Patients";
}
document.addEventListener("DOMContentLoaded", function () {
  createCalendar(headerResources); //create the calendar
});

/**
 * @brief This function create the list of events to display in the calendar
 * @returns a list of the events of the calendar
 */
function createEvents() {
  var events = JSON.parse(
    document.getElementById("events").value.replaceAll("3aZt3r", " ")
  ); //get the events from the hidden input
  var materialUnavailabilities;
  var humanUnavailabilities;
  var unavailabilities;
  if (document.getElementById("MaterialUnavailables") != null) {
    materialUnavailabilities = JSON.parse(
      document.getElementById("MaterialUnavailables").value
    );
  }
  if (document.getElementById("HumanUnavailables") != null) {
    humanUnavailabilities = JSON.parse(
      document.getElementById("HumanUnavailables").value
    );
  }
  if (humanUnavailabilities.length > 0 && materialUnavailabilities.length > 0) {
    unavailabilities = materialUnavailabilities.concat(humanUnavailabilities);
  } else if (humanUnavailabilities.length == 0) {
    unavailabilities = materialUnavailabilities;
  } else if (materialUnavailabilities.length == 0) {
    unavailabilities = humanUnavailabilities;
  }
  events = events.concat(unavailabilities); //add the unavailabilities to the events

  return events;
}

/**
 * @brief This function is called when we want to create or recreate the calendar
 * @param {*} resources the type of resources to display (Patients, Resources...)
 */
function createCalendar(resources) {
  var events = createEvents();
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
    selectable: false, //set the calendar to be selectable
    editable: false, //set the calendar not to be editable
    contentHeight: (height * 12) / 16, //set the height of the calendar to fit with a standard display
    handleWindowResize: true, //set the calendar to be resizable
    eventDurationEditable: false, //set the event duration not to be editable
    nowIndicator: true, //display the current time
    selectConstraint: "businessHours", //set the select constraint to be business hours
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

    eventDidMount: function (info) {
      $(info.el).tooltip({
        title: info.event.extendedProps.description,
        placement: "top",
        trigger: "hover",
        container: "body",
      });
    },

    //when we click on an event, display a modal window with the event information
    eventClick: function (event) {
      //get the data of the event
      var id = event.event._def.publicId; //get the id of the event
      var activity = calendar.getEventById(id); //get the event with the id
      var start = activity.start; //get the start date of the event
      var end = activity.end; //get the end date of the event
      var humanResources = activity.extendedProps.humanResources; //get the human resources of the event
      var humanResourcesNames = ""; //create a string with the human resources names
      if (humanResources.length > 1) {
        for (var i = 0; i < humanResources.length - 1; i++) {
          //for each human resource except the last one
          if (humanResources[i][1] != undefined) {
            //if the human resource exist
            humanResourcesNames += humanResources[i][1] + "; "; //add the human resource name to the string with a ; and a space
          }
        }
        humanResourcesNames += humanResources[i][1]; //add the last human resource name to the string
      } else humanResourcesNames = "Aucune ressource associée";

      var materialResources = activity.extendedProps.materialResources; //get the material resources of the event
      var materialResourcesNames = ""; //create a string with the material resources names
      console.log(materialResources);

      if (materialResources.length > 1) {
        console.log("test");
        for (var i = 0; i < materialResources.length - 1; i++) {
          //for each material resource except the last one
          if (materialResources[i][1] != undefined) {
            //if the material resource exist
            materialResourcesNames += materialResources[i][1] + "; "; //add the material resource name to the string with a ; and a space
          }
        }

        materialResourcesNames += materialResources[i][1]; //add the last material resource name to the string
      } else materialResourcesNames = "Aucune ressource associée";

      //set data to display in the modal window
      $("#start").val(start.toISOString().substring(0, 19)); //set the start date of the event
      $("#end").val(end.toISOString().substring(0, 19)); //set the end date of the event
      document.getElementById("show-title").innerHTML = activity.title; //set the title of the event
      $("#parcours").val(activity.extendedProps.pathway); //set the pathway of the event
      $("#patient").val(activity.extendedProps.patient); //set the patient of the event
      $("#rh").val(humanResourcesNames); //set the human resources of the event
      $("#rm").val(materialResourcesNames); //set the material resources of the event

      $("#modify-planning-modal").modal("show"); //open the window
    },
  });
  //change the type of the calendar(Patients, Resources...)
  switch (resources) {
    case "Patients": //if we want to display by the patients
      var tempArray = JSON.parse(
        document.getElementById("appointments").value.replaceAll("3aZt3r", " ")
      ); //get the data of the appointments
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i];
        patient = temp["patient"]; //get the resources data
        if (calendar.getResourceById(patient[0]["id"]) == null) {
          //if the resource is not already in the calendar
          calendar.addResource({
            //add the resources to the calendar
            id: patient[0]["id"], //set the id of the resource
            title: patient[0]["title"], //set the title of the resource
            businessHours: {
              //set the business hours of the resource
              startTime: patient[0]["businessHours"]["startTime"], //set the start time of the business hours
              endTime: patient[0]["businessHours"]["endTime"], //set the end time of the business hours
            },
          });
        }
      }
      break;
    case "Parcours": //if we want to display by the parcours
      var tempArray = JSON.parse(
        document.getElementById("appointments").value.replaceAll("3aZt3r", " ")
      ); //get the data of the appointments
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i];
        pathway = temp["pathway"]; //get the resources data
        if (calendar.getResourceById(pathway[0]["id"]) == null) {
          //if the resource is not already in the calendar
          calendar.addResource({
            //add the resources to the calendar
            id: pathway[0]["id"], //set the id of the resource
            title: pathway[0]["title"], //set the title of the resource
          });
        }
      }
      break;
    case "Ressources Humaines": //if we want to display by the resources
      var tempArray = JSON.parse(
        document.getElementById("human").value.replaceAll("3aZt3r", " ")
      ); //get the data of the resources
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i]; //get the resources data
        if (calendar.getResourceById(temp["id"]) == null) {
          //if the resource is not already in the calendar
          var businessHours = []; //create an array to store the working hours
          for (var j = 0; j < temp["workingHours"].length; j++) {
            businesstemp = {
              //create a new business hour
              startTime: temp["workingHours"][j]["startTime"], //set the start time
              endTime: temp["workingHours"][j]["endTime"], //set the end time
              daysOfWeek: [temp["workingHours"][j]["day"]], //set the day
            };
            businessHours.push(businesstemp); //add the business hour to the array
          }
          calendar.addResource({
            //add the resources to the calendar
            id: temp["id"], //set the id
            title: temp["title"], //set the title
            businessHours: businessHours, //get the business hours
          });
        }
      }
      break;
    case "Ressources Matérielles": //if we want to display by the resources
      var tempArray = JSON.parse(
        document.getElementById("material").value.replaceAll("3aZt3r", " ")
      ); //get the data of the resources
console.log( document.getElementById("material").value)
      console.log(tempArray);
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i]; //get the resources data
        if (temp != undefined) {
          if (calendar.getResourceById(temp["id"]) == null) {
            //if the resource is not already in the calendar
            calendar.addResource({
              //add the resources to the calendar
              id: temp["id"], //set the id
              title: temp["title"], //set the title
            });
          }
        }
      }
      break;
  }
  headerResources = resources;
  calendar.gotoDate(date); //go to the date we want to display
  calendar.render(); //display the calendar
}

