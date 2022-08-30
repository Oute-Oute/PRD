var calendar;
var isUpdated = true;
var countAddEvent = 0;
var headerResources = "Ressources Humaines";
var currentDateStr = $_GET("date").replaceAll("%3A", ":");
var currentDate = new Date(currentDateStr);
var timerAlert;
var modifAlertTime = 480000;
var messageUnscheduledAppointment = [];
var listScheduledAppointment = [];
var listErrorMessages = {
  messageUnscheduledAppointment: messageUnscheduledAppointment,
  listScheduledAppointment: listScheduledAppointment
};
var calendarResources=[]
var resourcesColumns = [{
  headerContent: "Nom", //set the label of the column
  field: "title", //set the field of the column
},
{
  headerContent: "Catégories", //set the label of the column
  field: "categoriesString", //set the field of the column
}]
var listEvents;
var historyEvents = [];

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

function alertOnload() {
  // Timeout pour afficher le popup (pour éviter une modif trop longue)
  if (document.getElementById('modifAlertTime') != null) {
    modifAlertTime = document.getElementById('modifAlertTime').value; // En millisecondes
  }
  setTimeout(showPopup, modifAlertTime);
}

function firstCalendar(){
  //Créer le calendar sous les conditions que l'on souhaite
  createCalendar(headerResources);
}

function unshowDiv(id) {
  document.getElementById(id).style.display = "none";
}

//function permettant la modification de l'activité
function modifyEvent() {
  var idEvent = document.getElementById("id-modified-event").value;
  var oldEvent = calendar.getEventById(idEvent);

  var currentDateModified = $_GET("date").substring(0, 10);
  var newStart = new Date(currentDateModified + " " + document.getElementById("start-modified-event").value);
  var newDelay = oldEvent.end.getTime() - oldEvent.start.getTime();
  oldEvent.setStart(new Date(newStart.getTime() + 2 * 60 * 60 * 1000))
  oldEvent.setEnd(new Date(newStart.getTime() + 2 * 60 * 60 * 1000 + newDelay))

  updateEventsAppointment(oldEvent);

  let listResource = [];
  oldEvent._def.resourceIds.forEach((resource) => {
    listResource.push(resource)
  })

  calendar.getEvents().forEach((currentEvent) => {
    if (currentEvent.display == "background") {
      if (oldEvent._def.publicId == currentEvent._def.extendedProps.idScheduledActivity) {
        if (listResource.length != 0) {
          currentEvent._def.resourceIds = listResource;
          currentEvent.setStart(oldEvent.start);
          currentEvent.setEnd(oldEvent.end);
        }
      }
    }
  })

  $("#modify-planning-modal").modal("toggle");
}

/**
 * @brief This function get a date and return it in string format
 * @param {*} date 
 * @returns a date in string format
 */
function formatDate(date) {
  return ([
    date.getFullYear(),
    (date.getMonth() + 1).toString().padStart(2, "0"),
    date.getDate().toString().padStart(2, "0"),
  ].join("-") + " " + [
    date.getHours().toString().padStart(2, "0"),
    date.getMinutes().toString().padStart(2, "0"),
    date.getSeconds().toString().padStart(2, "0"),
  ].join(":")
  );
}

/**
 * @brief This function set four input in the database update form for give the necessary informations 
 * @param {*} id user identifier
 */
function updateDatabase(id) {
  var listCurrentEvents = calendar.getEvents(); //get all scheduled activities
  let listResources = [];
  listCurrentEvents.forEach((currentEvent) => {
    var listResourceCurrentEvent = [];
    for (let i = 0; i < currentEvent._def.resourceIds.length; i++) {
      listResourceCurrentEvent.push(currentEvent._def.resourceIds[i]);
    }
    listResources.push(listResourceCurrentEvent);
  });
  document.getElementById("user-id").value = JSON.stringify(id); //set user identifier
  document.getElementById("events").value = JSON.stringify(calendar.getEvents()); //set all informations about the scheduled activities modified
  document.getElementById("list-resource").value = JSON.stringify(listResources); //set all resource identifiers
  document.getElementById("validation-date").value = $_GET("date"); //set the planning date modified
  document.getElementById("scheduled-appointments").value = document.getElementById("listeAppointments").value;
  isUpdated = true;
}

function backToConsultation() {
  if (isUpdated) {
    window.location.assign('/ModificationDeleteOnUnload?dateModified=' + $_GET('date') + '&id=' + $_GET('id'));
  }
  else{
    $('#popup-back-consultation').modal('show');
  }
}


function showSelectDate() {
  let selectContainerDate = document.getElementById("select-container-date");
  selectContainerDate.style.display = "block";
}

//fonction qui permet de tester la mise à jour de la liste des events d'un appointment
function updateEventsAppointment(modifyEvent) {
  listeHumanResources = JSON.parse(document.getElementById('human').value.replaceAll('3aZt3r', ' '));
  listeMaterialResources = JSON.parse(document.getElementById('material').value.replaceAll('3aZt3r', ' '));
  //Ajoute la ressource allouée dans extendedProps -> human et material Resource afin d'afficher la ressource lorsque l'on clique sur l'event
  clearArray(modifyEvent._def.extendedProps.humanResources);
  clearArray(modifyEvent._def.extendedProps.materialResources)
  for (let i = 0; i < modifyEvent._def.resourceIds.length; i++) {
    if (modifyEvent._def.resourceIds[i] != 'h-default' && modifyEvent._def.resourceIds[i] != 'm-default' && modifyEvent._def.extendedProps.humanResources.includes(modifyEvent._def.resourceIds[i]) == false) {
      for (let j = 0; j < listeHumanResources.length; j++) {
        if (listeHumanResources[j].id == modifyEvent._def.resourceIds[i]) {
          var humanArray = { id: modifyEvent._def.resourceIds[i], title: listeHumanResources[j].title }
          modifyEvent._def.extendedProps.humanResources.push(humanArray);
        }
      }
      for (let j = 0; j < listeMaterialResources.length; j++) {
        if (listeMaterialResources[j].id == modifyEvent._def.resourceIds[i]) {
          var materialArray = { id: modifyEvent._def.resourceIds[i], title: listeMaterialResources[j].title }
          modifyEvent._def.extendedProps.materialResources.push(materialArray);
        }
      }
    }
  }

  let listResource = [];
  modifyEvent._def.resourceIds.forEach((resource) => {
    listResource.push(resource)
  })

  verifyHistoryPush(historyEvents, -1);
  updateErrorMessages();
}

/**
 * This function works with the Button 'Mode automatique from the ModificationPlanning.twig'. 
 * Add All pathways of the day at the same time
 */


function showSelectDate() {
  let selectContainerDate = document.getElementById("select-container-date");
  selectContainerDate.style.display = "block";
}



function DisplayAppointmentInformation(eventClicked) {
  $("#modify-planning-modal").modal('hide');
  eventClicked = JSON.parse(eventClicked);
  var listAppointment = JSON.parse(document.getElementById('listeAppointments').value.replaceAll("3aZt3r", " "));
  var listActivities = JSON.parse(document.getElementById('listeActivities').value.replaceAll("3aZt3r", " "));
  var listSuccessors = JSON.parse(document.getElementById('listeSuccessors').value);
  var listActivitiesPathway = [];
  var listSuccessorsPathway = [];
  var activitiesInlistSuccessorsPathway = [];
  var id = eventClicked.el.fcSeg.eventRange.def.publicId; //get the id of the event
  var activity = calendar.getEventById(id); //get the event with the id
  var appointment;
  var title = activity._def.extendedProps.patient + " / " + activity._def.extendedProps.pathway;

  for (let i = 0; i < listAppointment.length; i++) {
    if (activity._def.extendedProps.appointment == listAppointment[i].id) {
      appointment = listAppointment[i];
    }
  }

  for (let i = 0; i < listActivities.length; i++) {
    if (appointment.idPathway[0].id.replaceAll('pathway-', '') == listActivities[i].idPathway) {
      listActivitiesPathway.push(listActivities[i]);
    }
  }

  for (let i = 0; i < listSuccessors.length; i++) {
    for (let j = 0; j < listActivitiesPathway.length; j++) {
      if (listActivitiesPathway[j].id == listSuccessors[i].idactivitya && activitiesInlistSuccessorsPathway.includes(listSuccessors[i].idactivitya) == false) {
        listSuccessorsPathway.push(listSuccessors[i]);
        activitiesInlistSuccessorsPathway.push(listSuccessors[i].idactivitya);
      }
    }
  }

  var listSuccessorsActivitiesPathway = [];
  for (let i = 0; i < listSuccessorsPathway.length; i++) {
    var nameActivitya;
    var nameActivityb;
    var activityId;
    for (let j = 0; j < listActivitiesPathway.length; j++) {
      if (listActivitiesPathway[j].id == listSuccessorsPathway[i].idactivitya) {
        nameActivitya = listActivitiesPathway[j].name;
        activityId = listActivitiesPathway[j].id;
      }
    }
    for (let j = 0; j < listActivitiesPathway.length; j++) {
      if (listActivitiesPathway[j].id == listSuccessorsPathway[i].idactivityb) {
        nameActivityb = listActivitiesPathway[j].name;
      }
    }
    listSuccessorsActivitiesPathway.push({ nameactivitya: nameActivitya, nameactivityb: nameActivityb, delaymin: listSuccessorsPathway[i].delaymin, delaymax: listSuccessorsPathway[i].delaymax, activityId: activityId });
  }

  //removing before display
  var nodesNotification = document.getElementById('input-container-onWhite-pathway').childNodes;                             //Get the div in lateral-panel-bloc
  while (nodesNotification.length != 3) {                                                                         //the 3 first div are not notifications
    document.getElementById('input-container-onWhite-pathway').removeChild(nodesNotification[nodesNotification.length - 1]);  //Removing div 
  }
  for (let i = 0; i < listSuccessorsActivitiesPathway.length; i++) {
    var div = document.createElement('div');
    div.setAttribute('class', 'alert alert-dark')
    div.setAttribute('role', 'alert');
    div.setAttribute('style', 'display: flex; flex-direction : column;font-weight:bold');
    div.innerHTML = listSuccessorsActivitiesPathway[i].nameactivitya;

    //Div to put input in row 
    var divRow = document.createElement('div');
    divRow.setAttribute('style', 'display: flex; flex-direction : column;');
    div.append(divRow);

    var successor = document.createElement('label');
    successor.setAttribute('class', 'label-event-solid');
    successor.innerHTML = 'Successeur : ' + listSuccessorsActivitiesPathway[i].nameactivityb;
    divRow.append(successor);

    var inputDelaymin = document.createElement('label');
    inputDelaymin.setAttribute('class', 'label-event-solid');
    inputDelaymin.innerHTML = 'Délai minimum : ' + listSuccessorsActivitiesPathway[i].delaymin + ' min';
    divRow.append(inputDelaymin);

    var inputDelaymax = document.createElement('label');
    inputDelaymax.setAttribute('class', 'label-event-solid');
    inputDelaymax.innerHTML = 'Délai maximum : ' + listSuccessorsActivitiesPathway[i].delaymax + ' min';
    divRow.append(inputDelaymax);


    document.getElementById('input-container-onWhite-pathway').append(div);
  }


  //set data to display in the modal window

  document.getElementById("show-information-appointment-title").innerHTML = title; //set the title of the event
  $("#input-modal-earliestappointmentdate").val(appointment.earliestappointmenttime.substring(11, 19));
  $("#input-modal-latestappointmentdate").val(appointment.latestappointmenttime.substring(11, 19));

  $("#display-appointment-modal").modal("show"); //open the window
}



function displayModalModifyEvent() {
  $("#modify-planning-modal").modal("show"); //open the window
}

function createCalendar(typeResource, useCase, slotDuration, resourcesToDisplay = undefined) {
  const height = document.querySelector("div").clientHeight;
  createResources(typeResource,resourcesToDisplay);
  var calendarEl = document.getElementById("calendar");
  var first;
  var listEvent;
  var idNowIndicator=[];

  let listResource = [];
  if (listEvents == undefined) {
    first = true;
  }

  else {
    first = false;
    switch (useCase) {
      case 'recreate':
        if (historyEvents[historyEvents.length - 2] != undefined) {
          if (historyEvents[historyEvents.length - 1].idAppointment != -1) {    //test to know if we remove an 'add' modification 
            var listeAppointments = JSON.parse(document.getElementById("listeAppointments").value);  //get the list appointments
            for (let i = 0; i < listeAppointments.length; i++) {
              if (listeAppointments[i]["id"] == historyEvents[historyEvents.length - 1].idAppointment) { //searching the right appointment that match with the id given
                listeAppointments[i].scheduled = false; //define appointment on not scheduled to be selectionnable in the adding modal
              }
            }
            document.getElementById("listeAppointments").value = JSON.stringify(listeAppointments); //update appointment list
          }
          listEvent = historyEvents[historyEvents.length - 2].events; //gives all events to recreate the calendar
          historyEvents.splice(historyEvents.length - 1, 1);  //removing the latest modification in historyEvents because we undo it
        }
        break;
      default:
        listEvent = calendar.getEvents();
        break;
    }
    listEvent.forEach((event) => {
      let eventResources = [];
      for (let i = 0; i < event._def.resourceIds.length; i++) {
        eventResources.push(event._def.resourceIds[i]);
      }
      listResource.push(eventResources);
    });
  }
  if(slotDuration === undefined){
    // if the slot duartion is not defined, we make sure to apply the default one
    slotDuration = "00:20:00"
  }

  calendar = new FullCalendar.Calendar(calendarEl, {
    //clé de la license pour utiliser la librairie à des fin non commerciale
    schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives",
    resourceOrder: 'type, title',
    //initialise la vue en colonne par ressource par jour en horaire française
    initialView: "resourceTimelineDay",
    slotDuration: slotDuration,
    locale: "fr",
    timeZone: "Europe/Paris",

    scrollTimeReset: false,
    height: $(window).height() * 0.70,
    //permet de modifier les events dans le calendar
    selectable: false,
    //eventConstraint:"businessHours",
    editable: true,
    eventDurationEditable: false,
    handleWindowResize: false,
    nowIndicator: true,
    selectConstraint: "businessHours", //set the select constraint to be business hours
    eventMinWidth: 1, //set the minimum width of the event
    resourceAreaColumns: resourcesColumns, //set the type of columns for the resources
    resources: calendarResources,
    //modifie l'affichage de l'entête du calendar pour ne pas l'afficher
    headerToolbar: false,

    //modifie l'affichage des heures de la journée
    slotLabelFormat: {
      hour: "2-digit",
      minute: "2-digit",
      meridiem: false,
      hour12: false,
    },
    resourceAreaWidth: "25%",

    //permet d'ouvrir la modal pour la modification d'une activité lorsque l'on click dessus
    eventClick: function (event) {
      if (event.event.display != "background") {
        var id = event.event._def.publicId; //get the id of the event
        var activity = calendar.getEventById(id); //get the event with the id
        var start = activity.start; //get the start date of the event
        var humanResources = activity.extendedProps.humanResources; //get the human resources of the event
        var humanResourcesNames = ""; //create a string with the human resources names
        if (humanResources != undefined) {
          for (var i = 0; i < humanResources.length; i++) {
            //for each human resource except the last one

            if (humanResources[i].title != undefined) {
              //if the human resource exist
              humanResourcesNames += humanResources[i].title + "; "; //add the human resource name to the string with a ; and a space
            }

          }
        }
        else {
          humanResourcesNames = "Aucune ressource humaine allouée";
        }

        var categoryHumanResources = "";
        if (event.event._def.extendedProps.categoryHumanResource.length != 0) {
          for (let i = 0; i < event.event._def.extendedProps.categoryHumanResource.length; i++) {
            categoryHumanResources = categoryHumanResources + event.event._def.extendedProps.categoryHumanResource[i].quantity + ' ' + event.event._def.extendedProps.categoryHumanResource[i].categoryname + ';'
          }
        }
        else {
          categoryHumanResources = "L'activité ne nécéssite aucune ressource humaine";
        }


        var categoryMaterialResources = "";
        if (event.event._def.extendedProps.categoryMaterialResource.length != 0) {
          for (let i = 0; i < event.event._def.extendedProps.categoryMaterialResource.length; i++) {
            categoryMaterialResources = categoryMaterialResources + event.event._def.extendedProps.categoryMaterialResource[i].quantity + ' ' + event.event._def.extendedProps.categoryMaterialResource[i].categoryname + ';'
          }
        }
        else {
          categoryMaterialResources = "L'activité ne nécéssite aucune ressource materielle";
        }

        var materialResources = activity.extendedProps.materialResources; //get the material resources of the event

        var materialResourcesNames = ""; //create a string with the material resources names
        if (materialResources != undefined) {
          for (var i = 0; i < materialResources.length; i++) {
            //for each material resource except the last one
            if (materialResources[i].title != undefined) {
              //if the material resource exist
              materialResourcesNames += materialResources[i].title + "; "; //add the material resource name to the string with a ; and a space
            }

          }
        }
        else {
          materialResourcesNames = "Aucune ressource matérielle allouée";
        }

        //set data to display in the modal window
        $("#start-modified-event").val(start.toISOString().substring(11, 19)); //set the start date of the event
        document.getElementById("show-modified-event-title").innerHTML = activity.title; //set the title of the event
        $("#parcours-modified-event").val(activity.extendedProps.pathway); //set the pathway of the event
        $("#patient-modified-event").val(activity.extendedProps.patient); //set the patient of the event
        $("#category-human-resource-modified-event").val(categoryHumanResources); //set the human resources of the event
        $("#human-resource-modified-event").val(humanResourcesNames); //set the human resources of the event
        $("#category-material-resource-modified-event").val(categoryMaterialResources); //set the material resources of the event
        $("#material-resource-modified-event").val(materialResourcesNames); //set the material resources of the event
        $("#id-modified-event").val(id);
        $("#modify-planning-modal").modal("show"); //open the window

      }
    },

    eventDrop: function (event) {
      var modifyEvent = event.event;
        updateEventsAppointment(modifyEvent)
        modifyEvent._def.ui.textColor = "#fff";
        modifyEvent._def.ui.backgroundColor = RessourcesAllocated(modifyEvent);
        modifyEvent._def.ui.borderColor = RessourcesAllocated(modifyEvent);
        modifyEvent.setEnd(modifyEvent.end);
        isUpdated = false;
        if(event.oldEvent._def.resourceIds.length != event.event._def.resourceIds.length){
          $("#error-fusion-modal").modal("show"); //open the window
          undoEvent()
        }
    },
    eventDragStart: function (event) {
      for(var i = 0; i < calendar.getResources().length; i=i+5){
        idNowIndicator.push("now"+i)
        calendar.addEvent(
          {
            start: event.event._instance.range.start,
            end: event.event._instance.range.end,
            resourceId: calendar.getResources()[i].id,
            display: 'background',
            color: '#00f',
            id: "now"+i,
          }
        )
      }
    },
    eventDragStop: function (event) {
      
      while(idNowIndicator.length != 0){
        calendar.getEventById(idNowIndicator[0]).remove()
        idNowIndicator.shift()
      }
    }
  });
 
  

  if (first == true) {
    listEvents = JSON.parse(document.getElementById("listScheduledActivitiesJSON").value.replaceAll("3aZt3r", " "));
    console.log(listEvents)
  } 
  else {
    let setEvents = [];
    var index = 0;
    listEvent.forEach((eventModify) => {
      var start = new Date(eventModify.start - 2 * 60 * 60 * 1000);
      var end = new Date(eventModify.end - 2 * 60 * 60 * 1000);
      if (eventModify.display != "background") {
        var start = new Date(eventModify.start - 2 * 60 * 60 * 1000);
        var end = new Date(eventModify.end - 2 * 60 * 60 * 1000);
        setEvents.push({
          id: eventModify.id,
          start: formatDate(start).replace(" ", "T"),
          end: formatDate(end).replace(" ", "T"),
          title: eventModify.title,
          resourceIds: listResource[index],
          patient: eventModify.extendedProps.patient,
          appointment: eventModify.extendedProps.appointment,
          activity: eventModify.extendedProps.activity,
          type: eventModify.extendedProps.type,
          humanResources: eventModify.extendedProps.humanResources,
          materialResources: eventModify.extendedProps.materialResources,
          categoryMaterialResource: eventModify.extendedProps.categoryMaterialResource,
          categoryHumanResource: eventModify.extendedProps.categoryHumanResource,
          pathway: eventModify.extendedProps.pathway,
        });
      }
      else {
        var start = new Date(eventModify.start - 2 * 60 * 60 * 1000);
        var end = new Date(eventModify.end - 2 * 60 * 60 * 1000);
        setEvents.push({
          id: eventModify.id,
          start: formatDate(start).replace(" ", "T"),
          end: formatDate(end).replace(" ", "T"),
          resourceIds: listResource[index],
          type: eventModify.extendedProps.type,
          description: eventModify.extendedProps.description,
          display: eventModify.display,
          color: eventModify.color,
        }
        );
      }
      index++;
    });
    listEvents = setEvents;
  }
  for (var i = 0; i < listEvents.length; i++) {
    calendar.addEvent(listEvents[i]);
  }

  if (historyEvents.length == 0) {
    verifyHistoryPush(historyEvents, -1);
  }
  //affiche le calendar
  calendar.gotoDate(currentDate);

  calendar.render();
  //updateErrorMessages();

  let listCurrentEvent = calendar.getEvents();
  listCurrentEvent.forEach((currentEvent) => {
    currentEvent._def.ui.textColor = "#fff";
    currentEvent._def.ui.backgroundColor = RessourcesAllocated(currentEvent);
    currentEvent._def.ui.borderColor = RessourcesAllocated(currentEvent);
    currentEvent.setEnd(currentEvent.end);
  });
  isUpdated = true;
}

$(window).resize(function () {
  calendar.setOption('height', $(window).height() * 0.75);
});

function showPopup() {
  $("#divPopup").show();

  timerAlert = setInterval(function () {
    var count = $("span.countdown").html();
    if (count > 1) {
      $("span.countdown").html(count - 1);
    } else {
      clearInterval(timerAlert);
      window.location.assign(
        "/ModificationDeleteOnUnload?dateModified=" + $_GET("date")
      );
    }
  }, 1000);
}

function closePopup() {
  $("#divPopup").hide();
  clearInterval(timerAlert);
  $("span.countdown").html(60);
  setTimeout(showPopup, modifAlertTime);
}

function deleteModifInDB() {
  window.location.assign(
    "/ModificationDeleteOnUnload?dateModified=" + $_GET("date")
  );
}

/**
 * @brief This function gives the color to apply to an event on the planning. 
 * red if the Activity is not associated to the riht resources (material and human)
 * green if the Activity have all resources that it need. 
 * unavailabilities are red in any case.  
 * @param {*} event 
 * @returns color of the event
 */
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
 * This function clears an array of all his rows
 * @param {*} array 
 */
function clearArray(array) {
  while (array.length) {
    array.pop();      //removing rows by rows 
  }
}

/**
 * @brief This function is called when clicking on 'Retour en arrière button', recreate the calendar before  the last  modification
 */
function undoEvent() {;
  var zoom = document.getElementById('zoom-value').value;

  if (historyEvents.length != 1) {
    createCalendar(headerResources, 'recreate', zoom);
  }
  isUpdated = false;
}

/**
 * @brief This function is called when the user clicks a key
 */
document.addEventListener('keydown', function (event) {
  if (event.ctrlKey && event.key === 'z') { //if user clicks ctrl + z
    //we call the function undoEvent 
    undoEvent();
  }
  if (event.ctrlKey && event.altKey && event.key === 's') { //if user clicks ctrl + alt + s
    //we call the function undoEvent 
    var id = document.getElementById("user-id").value;
    updateDatabase('save', id);
    document.getElementById("update-database-form").submit();
  }
});

/**
 * This function stock in an array the history of all modifications on the Calendar, for performance reasons, we save only the last 10 modifications. 
 * @param {*} array           //get historyEvents array 
 * @param {*} idAppointment   //gives information on the appointment, usefull when undo is applied on added appointment (to get it back into the list of selectionnable appoinments to add)
 */
function verifyHistoryPush(array, idAppointment) {

  if (array.length > 10) {  //10 for performance reasons
    for (let i = 0; array.length >= 10; i++) {  //remove before push 
      array.splice(i, 1);
    }

  }
  array.push({ events: calendar.getEvents(), idAppointment: idAppointment });   //push into the history of modifications
}

/**
 * This function works with autoAddPathway. 
 * Check the number of occurences of the value in an array. 
 * @param {*} val  value to check
 * @param {*} array arrray to look over
 * @returns  the number of the occurences. 
 */
function countOccurencesInArray(val,array){
  let counter=0; 
  for(let i=0; i<array.length; i++){
    if(array[i].idcategory==val){
      counter++; 
    }
  }
  return counter; 
}

function createResources(typeResource,resourcesToDisplay){
  switch (typeResource) {
    case "Ressources Humaines": //if we want to display by the resources
      if (resourcesToDisplay != undefined) {
        var resourcesArray = resourcesToDisplay
      }
      else {
        var resourcesArray = JSON.parse(
          document.getElementById("human").value.replaceAll("3aZt3r", " ")
        ); //get the data of the resources
      }
      for (var i = 0; i < resourcesArray.length; i++) {
        var temp = resourcesArray[i]; //get the resources data
        var categoriesStr = ""; //create a string with the human resources names
        categories = temp["categories"];
        var categoriesResourceArray = [];
        for (let k = 0; k < categories.length - 1; k++) {
          categoriesStr += categories[k]["name"] + ", ";
          categoriesResourceArray.push(categories[k]["name"]);
        }
        categoriesStr += categories[categories.length - 1]["name"];
        categoriesResourceArray.push(categories[categories.length - 1]["name"]);
        calendarResources.push({
          //add the resources to the calendar
          id: temp["id"], //set the id
          title: temp["title"], //set the title
          categoriesString: categoriesStr, //set the type
          businessHours: temp["businessHours"][0], //get the business hours
          type: 1,
          categories: categoriesResourceArray,
        });
        calendarResources.push({
          id: "h-default",
          title: "Aucune ressource allouée",
          type: 0,
          categoriesString: [["Aucune Catégorie"]],
          categories: ["default"],
        });
      
    }
      break;
    case "Ressources Matérielles": //if we want to display by the resources
      if (resourcesToDisplay != undefined) {
        var resourcesArray = resourcesToDisplay
      }
      else {
        var resourcesArray = JSON.parse(
          document.getElementById("material").value.replaceAll("3aZt3r", " ")
        ); //get the data of the resources
      }
      for (var i = 0; i < resourcesArray.length; i++) {
        var temp = resourcesArray[i]; //get the resources data
        var categoriesStr = ""; //create a string with the human resources names
        categories = temp["categories"];
        var categoriesResourceArray = [];
        if (categories.length > 0) {
          for (var j = 0; j < categories.length - 1; j++) {
            //for each human resource except the last one
            categoriesStr += categories[j]["name"] + ", "; //add the material resource name to the string with a ; and a space
            categoriesResourceArray.push(categories[j]["name"]);
          }
          categoriesStr += categories[categories.length - 1]["name"]; //add the last material resource name to the string
          categoriesResourceArray.push(categories[categories.length - 1]["name"]);
          
        } else categoriesStr = "Pas de Catégorie";
        calendarResources.push({
          //add the resources to the calendar
          id: temp["id"],
          categoriesString: categoriesStr, //set the type
          title: temp["title"],
          type: 1,
          categories: categoriesResourceArray,

        });
        calendarResources.push({
          id: "m-default",
          title: "Aucune ressource allouée",
          type: 0,
          categoriesString: [["Aucune Catégorie"]],
          categories: ["default"],
        });
        categoriesStr = "";
      }
      break;
  }
}