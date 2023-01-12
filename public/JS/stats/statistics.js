var currentDateStr = $_GET("date");
var numberOfErrors=0;
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

document.addEventListener("DOMContentLoaded", function () {
getNumberOfHR();
getNumberOfMR();
getNumberOfPatients();
createCalendar("Ressources Humaines", "stats", "00:20:00");
stats=true;
GetDataErrors()
getWaitingTimes()
});



function getNumberOfHR(){
  hr=JSON.parse(document.getElementById("human").value)
  document.getElementById("numberOfHR").innerHTML=hr.length-1
}

function getNumberOfMR(){
  mr=JSON.parse(document.getElementById("material").value)
  document.getElementById("numberOfMR").innerHTML=mr.length-1
}

function getNumberOfPatients(){
  appointments=JSON.parse(document.getElementById("appointments").value)
  document.getElementById("numberOfPatients").innerHTML=appointments.length
}
function getNumberOFErrors(numberOfErrors){
  document.getElementById("numberOfErrors").innerHTML=numberOfErrors
}
function getWaitingTimes(){
  waitingTimes=JSON.parse(document.getElementById("waitingTimes").value)
  min=waitingTimes.minimum
  max=waitingTimes.maximum
  mean=Math.round(waitingTimes.mean)
  if(min=="Aucune activité planifiée"){
    document.getElementById("minWaitingTime").innerHTML=waitingTimes.minimum
    document.getElementById("maxWaitingTime").innerHTML=waitingTimes.maximum
    document.getElementById("meanWaitingTime").innerHTML=waitingTimes.mean

  }
  else{
  document.getElementById("minWaitingTime").innerHTML=min+" minutes"
  document.getElementById("maxWaitingTime").innerHTML=max+" minutes"
  document.getElementById("meanWaitingTime").innerHTML=mean+" minutes"
  }
}


/**
 * @brief This function is called when we want to create or recreate the calendar
 * @param {*} typeResource the type of resources to display (Patients, Resources...)
 */
function createCalendar(typeResource,useCase,resourcesToDisplay = undefined) {
  var events = JSON.parse(document.getElementById("events").value.replaceAll("3aZt3r", " ")); //get the events from the hidden input
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
    scrollTimeReset:false, //dont change the view of the calendar
    locale: "fr", //set the language in french
    timeZone: "Europe/Paris", //set the timezone for France
    selectable: false, //set the calendar to be selectable
    editable: false, //set the calendar not to be editable
    height: $(window).height()*0.75, //set the height of the calendar to fit with a standard display
    handleWindowResize: true, //set the calendar to be resizable
    eventDurationEditable: false, //set the event duration not to be editable
    nowIndicator: true, //display the current time
    selectConstraint: "businessHours", //set the select constraint to be business hours
    eventMinWidth: 1, //set the minimum width of the event
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
    resourceOrder: 'type, title', //display the resources in the alphabetical order of their names except for the "noResource" resource
    resourceAreaWidth: "20%", //set the width of the resources area
    events: events, //set the events
    filterResourcesWithEvents: true,
  });
  //change the type of the calendar(Patients, Resources...)
  switch (typeResource) {
    case "Ressources Humaines": //if we want to display by the resources

      var tempArray = JSON.parse(document.getElementById("human").value.replaceAll("3aZt3r", " ")); //get the data of the resources
    
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i]; //get the resources data
        if (calendar.getResourceById(temp["id"]) == null) {
          //if the resource is not already in the calendar
          var businessHours = []; //create an array to store the working hours
          for (var j = 0; j < temp["businessHours"].length; j++) {
            businesstemp = {
              //create a new business hour
              startTime: temp["businessHours"][j]["startTime"], //set the start time
              endTime: temp["businessHours"][j]["endTime"], //set the end time
              daysOfWeek: [temp["businessHours"][j]["day"]], //set the day
            };
            businessHours.push(businesstemp); //add the business hour to the array
          }
          categories=temp["categories"];
          var categoriesStr = ""; //create a string with the human resources names
          var categoriesArray=[];
          if (categories.length > 0) {
            for (var i = 0; i < categories.length - 1; i++) {
              //for each human resource except the last one
              categoriesStr += categories[i]["name"] + ", "; //add the human resource name to the string with a ; and a space
              categoriesArray.push(categories[i]["name"]);
            }
            categoriesStr += categories[i]["name"]; //add the last human resource name to the string
          } else categoriesStr = "Défaut";
          calendar.addResource({
            //add the resources to the calendar
            id: temp["id"], //set the id
            title: temp["title"], //set the title            
            categoriesString: categoriesStr, //set the type
            businessHours: businessHours, //get the business hours
            categories:categoriesArray,
            type: temp["type"], //set the type
          });
        }
      }
      break;
    case "Ressources Matérielles": //if we want to display by the resources
    if(resourcesToDisplay!=undefined){
      var tempArray=resourcesToDisplay
    }
    else{
      var tempArray = JSON.parse(
        document.getElementById("material").value.replaceAll("3aZt3r", " ")
      ); //get the data of the resources
    }
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i]; //get the resources data
        if (temp != undefined) {
          if (calendar.getResourceById(temp["id"]) == null) {
            //if the resource is not already in the calendar
            categories=temp["categories"];
          var categoriesStr = ""; //create a string with the human resources names
          var categoriesArray=[];
          if (categories.length > 0) {
            for (var i = 0; i < categories.length - 1; i++) {
              
            
              //for each human resource except the last one
              categoriesStr += categories[i]["name"] + ", "; //add the human resource name to the string with a ; and a space
              categoriesArray.push(categories[i]["name"]);
            }
            categoriesStr += categories[i]["name"]; //add the last human resource name to the string
          } else categoriesStr = "Défaut";
            calendar.addResource({
              //add the resources to the calendar
              id: temp["id"], //set the id
              title: temp["title"], //set the title
              categoriesString: categoriesStr, //set the type
              categories:categoriesArray,
              type: temp["type"], //set the type
            });
          }
        }
      }
      break;
  }
  headerResources = typeResource;
  calendar.gotoDate(date); //go to the start date of the calendar
  calendar.render(); //display the calendar
}

function RessourcesAllocated(event) {
  if (event._def.ui.display == "background") {
    return "#ff0000";
  }
  if (isFullyScheduled(event)) {
    return "#339d39";
  }
  else {
    return "#841919";
  }
}
/**
 * @brief This function check if the scheduled activity is fully scheduled or not
 * @param {*} event 
 * @returns true if the scheduled activity have error, false if not.
 */
function isFullyScheduled(event) {
  var isFullyScheduled = true;

  repertoryListErrors().repertoryAppointmentSAError.forEach((appointmentError) => {
    appointmentError.repertorySAErrorId.forEach((scheduledActivityId) => { //check all scheduled activities with errors
      if (scheduledActivityId == event._def.publicId) { //if the scheduled activity check is on the list
        //return false
        isFullyScheduled = false;
      }
    })
  })

  return isFullyScheduled;
}

/**
 * @brief This function is called when we want to change the date of the page
 */
function newDate() {
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
    changeDate(dateStr);
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
    changeDate(dateStr);
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
    changeDate(dateStr);
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
    changeDate(dateStr);
  }
  
  function changeDate(dateStr){
    window.location.assign("/statistics?date=" +dateStr)
  }

