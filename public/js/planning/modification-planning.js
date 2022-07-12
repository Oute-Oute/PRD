var calendar;
var countAddEvent = 0;
var headerResources = "Ressources Humaines";
var currentDateStr = $_GET("date").replaceAll("%3A", ":");
var currentDate = new Date(currentDateStr);
var timerAlert;
var modifAlertTime = 480000;

var listEvents;

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

function alertOnload(){ 
  // Timeout pour afficher le popup (pour éviter une modif trop longue)
  if(document.getElementById('modifAlertTime')!=null){
    modifAlertTime = document.getElementById('modifAlertTime').value; // En millisecondes
  }
  setTimeout(showPopup, modifAlertTime);
}

document.addEventListener("DOMContentLoaded", function () {
  //Créer le calendar sous les conditions que l'on souhaite
  createCalendar(headerResources);
});

function unshowDiv(id) {
  document.getElementById(id).style.display = "none";
}

//function permettant la modification de l'activité
function modifyEvent() {
  var idEvent = document.getElementById("id-modified-event").value;
  var oldEvent = calendar.getEventById(idEvent);

  var currentDateModified = $_GET("date").substring(0, 10);
  var newStart = new Date(currentDateModified + " " + document.getElementById("start-modified-event").value);
  var newDelay = oldEvent.start.getTime() - 2 * 60 * 60 * 1000 - newStart.getTime();
  var editByClick = true;

  updateEventsAppointment(oldEvent, newDelay, editByClick);
  $("#modify-planning-modal").modal("toggle");
}

function formatDate(date) {
  return (
    [
      date.getFullYear(),
      (date.getMonth() + 1).toString().padStart(2, "0"),
      date.getDate().toString().padStart(2, "0"),
    ].join("-") +
    " " +
    [
      date.getHours().toString().padStart(2, "0"),
      date.getMinutes().toString().padStart(2, "0"),
      date.getSeconds().toString().padStart(2, "0"),
    ].join(":")
  );
}

function setEvents() {
  var listCurrentEvents = calendar.getEvents();
  let listResources = [];
  listCurrentEvents.forEach((currentEvent) => {
    var listResourceCurrentEvent = [];
    for (let i = 0; i < currentEvent._def.resourceIds.length; i++) {
      listResourceCurrentEvent.push(currentEvent._def.resourceIds[i]);
    }
    listResources.push(listResourceCurrentEvent);
  });
  document.getElementById("events").value = JSON.stringify(
    calendar.getEvents()
  );
  document.getElementById("list-resource").value = JSON.stringify(listResources);
  document.getElementById("validation-date").value = $_GET("date");
}

//function permettant l'ouverture de la modal d'ajout d'un parcours
function addEvent() {
  let selectContainerErrorTime = document.getElementById("time-selected-error");
  selectContainerErrorTime.style.display = "none";
  let listeAppointments = JSON.parse(
    document.getElementById("listeAppointments").value
  );
  let appointmentSelection = document.getElementById("select-appointment");

  //Reset toutes les options de la liste
  for (let i = appointmentSelection.options.length - 1; i >= 0; i--) {
    appointmentSelection.remove(i);
  }

  //Ajoute les appointment non plannifiés dans la liste
  var nbOptions = 0;
  for (let i = 0; i < listeAppointments.length; i++) {
    if (listeAppointments[i].scheduled == false) {
      appointmentSelection.options[nbOptions] = new Option(
        listeAppointments[i].idPatient[0].firstname +
          " " +
          listeAppointments[i].idPatient[0].lastname +
          " / " +
          listeAppointments[i].idPathway[0].title,
        listeAppointments[i].id
      );
      nbOptions++;
    }
  }

  $("#add-planning-modal").modal("show");

  let filter = document.getElementById("filterId"); //get the filter
  filter.style.display = "none"; //hide the filter
  while (filter.firstChild) {
    //while there is something in the filter
    filter.removeChild(filter.firstChild); //remove the old content
  }
}

function AddEventValider() {
  //Récupération de la bdd nécéssaire à l'ajout d'un parcours
  var listeSuccessors = JSON.parse(
    document.getElementById("listeSuccessors").value
  );
  var listeActivities = JSON.parse(
    document.getElementById("listeActivities").value
  );
  var listeAppointments = JSON.parse(
    document.getElementById("listeAppointments").value
  );

  var listeActivitHumanResource = JSON.parse(
    document.getElementById("listeActivityHumanResource").value
  );
  var listeActivityMaterialResource = JSON.parse(
    document.getElementById("listeActivityMaterialResource").value
  );

  var appointmentid = document.getElementById("select-appointment").value;

  //Récupération du rdv choisit par l'utilisateur et de la place de l'élément dans listeAppointment
  var appointment;
  for (let i = 0; i < listeAppointments.length; i++) {
    if (listeAppointments[i]["id"] == appointmentid) {
      appointment = listeAppointments[i];
      listeAppointments[i].scheduled = true;
    }
  }

  document.getElementById("listeAppointments").value =
    JSON.stringify(listeAppointments);

  //Date de début du parcours
  var PathwayBeginTime = document.getElementById("timeBegin").value;
  var PathwayBeginDate = new Date(
    new Date(currentDateStr.substring(0, 10) + " " + PathwayBeginTime).getTime() +
      2 * 60 * 60000
  );

  //Test pour savoir si l'heure renseignée est comprise dans l'interval earliestappointmenttime et lastestappointmenttime
  var earliestAppointmentDate = new Date(
    appointment.earliestappointmenttime
  ).getTime();
  var latestAppointmentDate = new Date(
    appointment.latestappointmenttime
  ).getTime();
  var choosenAppointmentDate = new Date(
    "1970-01-01 " + PathwayBeginTime
  ).getTime();

  var EndPathwayDate = new Date(choosenAppointmentDate);

  //Récupération des activités du parcours
  var activitiesInPathwayAppointment = [];
  for (let i = 0; i < listeActivities.length; i++) {
    if (
      "pathway_" + listeActivities[i]["idPathway"] ==
      appointment["idPathway"][0].id
    ) {
      activitiesInPathwayAppointment.push(listeActivities[i]);
    }
  }

  for (let i = 0; i < activitiesInPathwayAppointment.length; i++) {
    EndPathwayDate = new Date(
      new Date(EndPathwayDate).getTime() +
        activitiesInPathwayAppointment[i].duration * 60000
    );
  }

  if (
    earliestAppointmentDate <= choosenAppointmentDate &&
    EndPathwayDate <= latestAppointmentDate
  ) {
    //On récupère l'ensemble des id activité b de la table successor pour trouver la première activité du parcours
    var successorsActivitybIdList = [];
    for (let i = 0; i < listeSuccessors.length; i++) {
      successorsActivitybIdList.push(listeSuccessors[i].idactivityb);
    }

    //get the forst activity of the pathway
    for (let i = 0; i < activitiesInPathwayAppointment.length; i++) {
      if (
        successorsActivitybIdList.includes(
          activitiesInPathwayAppointment[i].id
        ) == false
      ) {
        var firstActivityPathway = activitiesInPathwayAppointment[i];
      }
    }

    var idactivitya = firstActivityPathway.id;
    var activitya;
    var successoracivitya;

    //Début de la création des events
    do {
      var idactivityB = undefined;
      var quantityHumanResources = 0;
      var quantityMaterialResources = 0;
      var activityResourcesArray = [];
      //trouver l'activité correspondant à l'idactivitya
      for (let i = 0; i < listeActivities.length; i++) {
        if (listeActivities[i].id == idactivitya) {
          activitya = listeActivities[i];
        }
      }

      //Trouver pour chaques activités du parcours le nombre de resources humaines à définir
      for (let i = 0; i < listeActivitHumanResource.length; i++) {
        if (listeActivitHumanResource[i].activityId == idactivitya) {
          quantityHumanResources += listeActivitHumanResource[i].quantity;
        }
      }

      //Rentrer le nombre de resources humaines dans le tableau de Resources de l'event
      for (let i = 0; i < quantityHumanResources; i++) {
        activityResourcesArray.push("h-default");
      }

      //Trouver pour chaques activités du parcours le nombre de resources matérielles à définir
      for (let i = 0; i < listeActivityMaterialResource.length; i++) {
        if (listeActivityMaterialResource[i].activityId == idactivitya) {
          quantityMaterialResources +=
            listeActivityMaterialResource[i].quantity;
        }
      }

      //Rentrer le nombre de resources materielles dans le tableau de Resources de l'event
      for (let i = 0; i < quantityMaterialResources; i++) {
        activityResourcesArray.push("m-default");
      }

      //trouver dans la table successor le correspondant au activiteida
      for (let i = 0; i < listeSuccessors.length; i++) {
        if (listeSuccessors[i].idactivitya == idactivitya) {
          successoracivitya = listeSuccessors[i];
          idactivityB = listeSuccessors[i].idactivityb;
        }
      }
      //countAddEvent pour avoir un id different pour chaque events ajoutes
      countAddEvent++;
      //Ajout d'un event au calendar
      var event = calendar.addEvent({
        id: "new" + countAddEvent,
        description: "",
        resourceIds: activityResourcesArray,
        title: activitya.name.replaceAll("3aZt3r", " "),
        start: PathwayBeginDate,
        end: PathwayBeginDate.getTime() + activitya.duration * 60000,
        patient:
          appointment.idPatient[0].lastname +
          " " +
          appointment.idPatient[0].firstname,
        appointment: appointment.id,
        activity: activitya.id,
        type: "activity",
        humanResources: [],
        materialResources: [],
        pathway: appointment.idPathway[0].title,
      });
      console.log();

      //Detection de la dernière activite du parcours
      if (idactivityB != undefined) {
        idactivitya = idactivityB;
      }
      console.log(successoracivitya);
      PathwayBeginDate = new Date(
        PathwayBeginDate.getTime() +
          activitya.duration * 60000 +
          successoracivitya.delaymin * 60000
      );
      event._def.ui.backgroundColor = RessourcesAllocated(event);
      event._def.ui.borderColor = RessourcesAllocated(event);
      calendar.render();
    } while (idactivityB != undefined);
    calendar.render();

    $("#add-planning-modal").modal("toggle");
  } else {
    let selectContainerErrorTime = document.getElementById(
      "time-selected-error"
    );
    selectContainerErrorTime.style.display = "block";
  }
}

function showSelectDate() {
  let selectContainerDate = document.getElementById("select-container-date");
  selectContainerDate.style.display = "block";
}

/**
 * @brief This function is called when we want to go to display the filter window, called when click on the filter button
 */
function filterShow() {
  let filter = document.getElementById("filterId");
  if (filter.style.display != "none") {
    //if the filter is already displayed
    filter.style.display = "none"; //hide the filter
    while (filter.firstChild) {
      //while there is something in the filter
      filter.removeChild(filter.firstChild); //remove the old content
    }
  } else {
    var resourcesToDisplay = []; //create an array to store the resources to display
    switch (headerResources) {
      case "Patients": //if we want to display by the patients
        var tempArray = JSON.parse(
          document
            .getElementById("appointments")
            .value.replaceAll("3aZt3r", " ")
        ); //get the data of the appointments
        for (var i = 0; i < tempArray.length; i++) {
          var temp = tempArray[i];
          resourcesToDisplay.push(temp["patient"][0]); //get the resources data
        }
        break;
      case "Parcours": //if we want to display by the patients
        var tempArray = JSON.parse(
          document
            .getElementById("appointments")
            .value.replaceAll("3aZt3r", " ")
        ); //get the data of the appointments
        for (var i = 0; i < tempArray.length; i++) {
          var temp = tempArray[i];
          resourcesToDisplay.push(temp["pathway"][0]); //get the resources data
        }
        break;
      case "Ressources Humaines": //if we want to display by the patients
        var tempArray = JSON.parse(
          document.getElementById("human").value.replaceAll("3aZt3r", " ")
        ); //get the data of the appointments
        for (var i = 0; i < tempArray.length; i++) {
          var temp = tempArray[i];
          resourcesToDisplay.push(temp); //get the resources data
        }
        break;
      case "Ressources Matérielles": //if we want to display by the patients
        var tempArray = JSON.parse(
          document.getElementById("material").value.replaceAll("3aZt3r", " ")
        ); //get the data of the appointments
        for (var i = 0; i < tempArray.length; i++) {
          var temp = tempArray[i];
          resourcesToDisplay.push(temp); //get the resources data
        }
        break;
    }
    filter.style.display = "inline-block"; //display the filter
    if (resourcesToDisplay.length == 0) {
      //if there is no resource in the calendar
      var label = document.createElement("label"); //display a label
      label.innerHTML = "Aucune ressource à filtrer"; //telling "no resources"
      filter.appendChild(label); //add the label to the filter
    } else {
      //fo all the resources in the calendar
      console.log(resourcesToDisplay);
      for (var i = 0; i < resourcesToDisplay.length; i++) {
        if (document.getElementById(resourcesToDisplay[i].id) == null) {
          var input = document.createElement("input"); //create a input
          input.type = "checkbox"; //set the type of the input to checkbox
          input.id = resourcesToDisplay[i].id; //set the id of the input to the id of the resource
          input.name = resourcesToDisplay[i].title; //set the name of the input to the title of the resource
          input.value = i; //set the value of the input to the title of the resource
          if (calendar.getResourceById(resourcesToDisplay[i].id) == null) {
            input.checked = false; //set the checkbox to unchecked
          } else {
            input.checked = true; //set the checkbox to checked
          }
          input.onchange = function () {
            //set the onchange event
            changeFilter(this.id, resourcesToDisplay); //call the changeFilter function with the id of the resource
          };
          filter.appendChild(input); //add the input to the filter
          var label = document.createElement("label"); //create a label
          label.htmlFor = resourcesToDisplay[i].id; //set the htmlFor of the label to the id of the resource
          label.innerHTML = "&nbsp;" + resourcesToDisplay[i].title; //set the text of the label to the title of the resource
          filter.appendChild(label); //add the label to the filter
          filter.appendChild(document.createElement("br")); //add a br to the filter for display purpose
        }
      }
    }
  }
}

/**
 * @brief This function is called when we want to filter the resources of the calendar
 * @param {*} id the id of resource to filter
 */
function changeFilter(id, resourcesToDisplay) {
  if (document.getElementById(id).checked == true) {
    //if the resource is checked

    calendar.addResource({
      //add the resource to the calendar
      id: id, //set the id of the resource
      title: document.getElementById(id).name, //set the title of the resource
    });
  } else {
    var resource = calendar.getResourceById(id); //get the resource with the id from the calendar
    resource.remove(); //remove the resource from the calendar
  }
}

function changePlanning() {
  var header =
    document.getElementById("displayList").options[
      document.getElementById("displayList").selectedIndex
    ].text; //get the type of resources to display in the list
  headerResources = header; //update the header of the list
  createCalendar(header); //rerender the calendar with the new type of resources
  let filter = document.getElementById("filterId"); //get the filter
  filter.style.display = "none"; //hide the filter
  while (filter.firstChild) {
    //while there is something in the filter
    filter.removeChild(filter.firstChild); //remove the old content
  }
}

//fonction qui permet de tester la mise à jour de la liste des events d'un appointment
function updateEventsAppointment(oldEvent, newDelay, editByClick) {
  var listEvent = calendar.getEvents();
  let listOldEvent = calendar.getEvents();
  var appointmentId = oldEvent._def.extendedProps.appointment;
  var materialResources = JSON.parse(document.getElementById("material").value.replaceAll("3aZt3r", " "));
  var humanResources = JSON.parse(document.getElementById("human").value.replaceAll("3aZt3r", " "));
  var listEventAppointment = [];
  listEvent.forEach((currentEvent) => {
    if (currentEvent._def.extendedProps.appointment == appointmentId) {
      if (currentEvent._def.publicId == oldEvent._def.publicId) {
        listEventAppointment.push(oldEvent);
      } else {
        listEventAppointment.push(currentEvent);
      }
    }
  });

  var eventFirst = listEventAppointment[0];
  var eventLast = listEventAppointment[0];
  listEventAppointment.forEach((eventAppointment) => {
    if (eventAppointment.end > eventLast.end) {
      eventLast = eventAppointment;
    }
    if (eventAppointment.start < eventFirst.start) {
      eventFirst = eventAppointment;
    }
  });

  var listeAppointments = JSON.parse(
    document.getElementById("listeAppointments").value
  );
  var appointment;
  for (let i = 0; i < listeAppointments.length; i++) {
    if (listeAppointments[i]["id"] == appointmentId) {
      appointment = listeAppointments[i];
    }
  }
  let earliestAppointmentDate = new Date(
    currentDateStr.split("T")[0] +
      " " +
      appointment.earliestappointmenttime.split("T")[1]
  );
  let latestAppointmentDate = new Date(
    currentDateStr.split("T")[0] +
      " " +
      appointment.latestappointmenttime.split("T")[1]
  );

  var isEditable = true;
  if (
    earliestAppointmentDate <=
      new Date(eventFirst.start.getTime() - 2 * 60 * 60 * 1000 - newDelay) &&
    new Date(eventLast.end.getTime() - 2 * 60 * 60 * 1000 - newDelay) <=
      latestAppointmentDate
  ) {
    calendar.getEventById(oldEvent._def.publicId)._def.ui.backgroundColor = RessourcesAllocated(calendar.getEventById(oldEvent._def.publicId));
    calendar.getEventById(oldEvent._def.publicId)._def.ui.borderColor = RessourcesAllocated(calendar.getEventById(oldEvent._def.publicId));
    calendar.getEventById(oldEvent._def.publicId).setEnd(calendar.getEventById(oldEvent._def.publicId).end);
    listEventAppointment.forEach((eventAppointment) => {
      if (editByClick) {
        var startDate = new Date(eventAppointment.start.getTime() - 2 * 60 * 60 * 1000 - newDelay);
        var startStr = formatDate(startDate).replace(" ", "T");
        var endDate = new Date(eventAppointment.end.getTime() - 2 * 60 * 60 * 1000 - newDelay);
        var endStr = formatDate(endDate).replace(" ", "T");
        eventAppointment.setStart(startStr);
        eventAppointment.setEnd(endStr);
        eventAppointment._def.ui.backgroundColor = RessourcesAllocated(eventAppointment);
        eventAppointment._def.ui.borderColor = RessourcesAllocated(eventAppointment);
      } 
      else if (eventAppointment._def.publicId != oldEvent._def.publicId) {
        var startDate = new Date(eventAppointment.start.getTime() - 2 * 60 * 60 * 1000 - newDelay);
        var startStr = formatDate(startDate).replace(" ", "T");
        var endDate = new Date(eventAppointment.end.getTime() - 2 * 60 * 60 * 1000 - newDelay);
        var endStr = formatDate(endDate).replace(" ", "T");
        eventAppointment.setStart(startStr);
        eventAppointment.setEnd(endStr);
        eventAppointment._def.ui.backgroundColor = RessourcesAllocated(eventAppointment);
        eventAppointment._def.ui.borderColor = RessourcesAllocated(eventAppointment);
      }
      else {
        eventAppointment._def.ui.backgroundColor = RessourcesAllocated(eventAppointment);
        eventAppointment._def.ui.borderColor = RessourcesAllocated(eventAppointment);
      }
    });
    listEventAppointment.forEach((currentModifyEvent) => {
      listOldEvent.forEach((oldEventSet) => {
        var newEventAppointment = currentModifyEvent;
        if(currentModifyEvent._def.publicId == oldEvent._def.publicId){
          newEventAppointment = calendar.getEventById(oldEvent._def.publicId);
        }
        oldEventSet._def.resourceIds.forEach((oldResource) => {
          newEventAppointment._def.resourceIds.forEach((newResource) => {
            if(newResource != "h-default" && newResource != "m-default"){
              if(newResource == oldResource) {
                if(newEventAppointment._def.extendedProps.appointment != oldEventSet._def.extendedProps.appointment){
                  if(!(newEventAppointment.start > oldEventSet.end || newEventAppointment.end < oldEventSet.start) || (newEventAppointment.start < oldEventSet.start && newEventAppointment.end > oldEventSet.end) || (newEventAppointment.start == oldEventSet.start && newEventAppointment.end == oldEventSet.end)){
                    var resourceTitle = "";
                    if (newResource.substring(0, 8) == "material"){
                      materialResources.forEach((material) => {
                        if(material.id == newResource){
                          resourceTitle = material.title;
                        }
                      })
                    }
                    else if (newResource.substring(0, 5) == "human"){
                      humanResources.forEach((human) => {
                        if(human.id == newResource){
                          resourceTitle = human.title;
                        }
                      })
                    }
                    alert(oldEventSet._def.extendedProps.patient + " est déjà prévu sur ce crénaux avec " + resourceTitle + " pour l'activité " + oldEventSet._def.title + ", on ne peut donc pas mettre " + newEventAppointment._def.extendedProps.patient + " sur ce même créneau pour l'activité " + newEventAppointment._def.title + ".");
                    isEditable = false;
                  }
                }
              }
            }
          })
        })
      })
    })
  }
  else {
    alert("Le parcours doit être compris entre : " + earliestAppointmentDate.getHours().toString().padStart(2, "0") + ":" + earliestAppointmentDate.getMinutes().toString().padStart(2, "0") + " et " + latestAppointmentDate.getHours().toString().padStart(2, "0") + ":" + latestAppointmentDate.getMinutes().toString().padStart(2, "0"));
    isEditable = false;
  }
      
      if (!isEditable){
        listEventAppointment.forEach((newEventAppointment) => {
          listOldEvent.forEach((oldEventSet) => {
            if(newEventAppointment._def.publicId == oldEventSet._def.publicId){
              if(newEventAppointment._def.publicId == oldEvent._def.publicId){
                calendar.getEventById(oldEvent._def.publicId)._def.ui.backgroundColor = RessourcesAllocated(calendar.getEventById(oldEvent._def.publicId));
                calendar.getEventById(oldEvent._def.publicId)._def.ui.borderColor = RessourcesAllocated(calendar.getEventById(oldEvent._def.publicId));
                var startDate = new Date(oldEvent.start.getTime()-(2*60*60*1000));
                var startStr = formatDate(startDate).replace(" ", "T");
                var endDate = new Date(oldEvent.end.getTime()-(2*60*60*1000));
                var endStr = formatDate(endDate).replace(" ", "T");
                calendar.getEventById(oldEvent._def.publicId)._def.resourceIds = oldEvent._def.resourceIds;
                calendar.getEventById(oldEvent._def.publicId).setStart(startStr);
                calendar.getEventById(oldEvent._def.publicId).setEnd(endStr);
              }
              else {
                newEventAppointment._def.ui.backgroundColor = RessourcesAllocated(oldEventSet);
                newEventAppointment._def.ui.borderColor = RessourcesAllocated(oldEventSet);
                var startDate = new Date(oldEventSet.start.getTime()-(2*60*60*1000));
                var startStr = formatDate(startDate).replace(" ", "T");
                var endDate = new Date(oldEventSet.end.getTime()-(2*60*60*1000));
                var endStr = formatDate(endDate).replace(" ", "T");
                newEventAppointment.setStart(startStr);
                newEventAppointment.setEnd(endStr);
              }
            }
          })
        })
      }
}



function createCalendar(typeResource) {
  const height = document.querySelector("div").clientHeight;
  var calendarEl = document.getElementById("calendar");
  var first;
  var listEvent;

  let listResource = [];
  if (listEvents == undefined) {
    first = true;
  } else {
    first = false;
    listEvent = calendar.getEvents();
    console.log(listEvent);
    listEvent.forEach((event) => {
      let eventResources = [];
      for (let i = 0; i < event._def.resourceIds.length; i++) {
        eventResources.push(event._def.resourceIds[i]);
      }
      listResource.push(eventResources);
    });
  }
  calendar = new FullCalendar.Calendar(calendarEl, {
    //clé de la license pour utiliser la librairie à des fin non commerciale
    schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives",

    //initialise la vue en colonne par ressource par jour en horaire française
    initialView: "resourceTimelineDay",
    slotDuration: "00:20:00",
    locale: "fr",
    timeZone: "Europe/Paris",

    //permet de modifier les events dans le calendar
    selectable: false,
    //eventConstraint:"businessHours",
    editable: true,
    eventDurationEditable: false,
    contentHeight: (9 / 12) * height,
    handleWindowResize: true,
    nowIndicator: true,
    selectConstraint: "businessHours", //set the select constraint to be business hours
    eventMinWidth: 1, //set the minimum width of the event

    //modifie l'affichage de l'entête du calendar pour ne laisser que la date du jour
    headerToolbar: {
      start: null,
      center: "title",
      end: null,
    },

    //modifie l'affichage des heures de la journée
    slotLabelFormat: {
      hour: "2-digit",
      minute: "2-digit",
      meridiem: false,
      hour12: false,
    },

    //à supprimer
    resourceOrder: "title",
    resourceAreaWidth: "20%",
    resourceAreaHeaderContent: headerResources,

    /*eventDidMount: function (info) {
      $(info.el).tooltip({
        title: info.event.extendedProps.description,
        placement: "top",
        trigger: "hover",
        container: "body",
      });
    },*/

    //permet d'ouvrir la modal pour la modification d'une activité lorsque l'on click dessus
    eventClick: function (event) {
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
      //humanResourcesNames += humanResources[i].resourceName; //add the last human resource name to the string
      

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
      // materialResourcesNames += materialResources[i].resourceName; //add the last material resource name to the string

      //set data to display in the modal window
      $("#start-modified-event").val(start.toISOString().substring(11, 19)); //set the start date of the event
      document.getElementById("show-modified-event-title").innerHTML = activity.title; //set the title of the event
      $("#parcours-modified-event").val(activity.extendedProps.pathway); //set the pathway of the event
      $("#patient-modified-event").val(activity.extendedProps.patient); //set the patient of the event
      $("#human-resource-modified-event").val(humanResourcesNames); //set the human resources of the event
      $("#material-resource-modified-event").val(materialResourcesNames); //set the material resources of the event
      $("#id-modified-event").val(id);

      $("#modify-planning-modal").modal("show"); //open the window
    },

    eventDrop: function (event) {
      var oldEvent = event.oldEvent;
      var modifyEvent = event.event;
      var newDelay = oldEvent.start.getTime() - modifyEvent.start.getTime();
      var editByClick = false;
      updateEventsAppointment(oldEvent, newDelay, editByClick);
      calendar.render();
      
      listeHumanResources=JSON.parse(document.getElementById('human').value.replaceAll('3aZt3r',' ')); 
      listeMaterialResources=JSON.parse(document.getElementById('material').value.replaceAll('3aZt3r',' '));
      //Ajoute la ressource allouée dans extendedProps -> human et material Resource afin d'afficher la ressource lorsque l'on clique sur l'event
      clearArray(modifyEvent._def.extendedProps.humanResources); 
      clearArray(modifyEvent._def.extendedProps.materialResources)
      for(let i=0; i<modifyEvent._def.resourceIds.length; i++){
        if(modifyEvent._def.resourceIds[i]!='h-default' && modifyEvent._def.resourceIds[i]!='m-default' && modifyEvent._def.extendedProps.humanResources.includes(modifyEvent._def.resourceIds[i])==false){
          for(let j=0; j<listeHumanResources.length; j++){
            if(listeHumanResources[j].id==modifyEvent._def.resourceIds[i]){
              var humanArray={id:modifyEvent._def.resourceIds[i],title:listeHumanResources[j].title}
              modifyEvent._def.extendedProps.humanResources.push(humanArray); 
            }  
          }
          for(let j=0; j<listeMaterialResources.length;j++){
            if(listeMaterialResources[j].id==modifyEvent._def.resourceIds[i]){
              console.log('alo');
              var materialArray={id:modifyEvent._def.resourceIds[i],title:listeMaterialResources[j].title}
              modifyEvent._def.extendedProps.materialResources.push(materialArray); 
            }  
          }
        }
      }
    },
  });
  switch (typeResource) {
    case "Ressources Humaines": //if we want to display by the resources
      var resourcesArray = JSON.parse(
        document.getElementById("human").value.replaceAll("3aZt3r", " ")
      ); //get the data of the resources
      for (var i = 0; i < resourcesArray.length; i++) {
        var temp = resourcesArray[i]; //get the resources data
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
      calendar.addResource({
        id: "h-default",
        title: "Aucune ressource allouée",
      });
      break;
    case "Ressources Matérielles": //if we want to display by the resources
      var resourcesArray = JSON.parse(
        document.getElementById("material").value.replaceAll("3aZt3r", " ")
      ); //get the data of the resources
      for (var i = 0; i < resourcesArray.length; i++) {
        var temp = resourcesArray[i]; //get the resources data
        calendar.addResource({
          //add the resources to the calendar
          id: temp["id"],
          title: temp["title"],
        });
        calendar.addResource({
          id: "m-default",
          title: "Aucune ressource allouée",
        });
      }
      break;
  }

  if (first == true) {
    listEvents = JSON.parse(
      document
        .getElementById("listScheduledActivitiesJSON")
        .value.replaceAll("3aZt3r", " ")
    );
  } else {
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
        pathway: eventModify.extendedProps.pathway,
      });
      }
      else{
        var start = new Date(eventModify.start - 2 * 60 * 60 * 1000);
        var end = new Date(eventModify.end - 2 * 60 * 60 * 1000);
        setEvents.push({
        id: eventModify.id,
        start: formatDate(start).replace(" ", "T"),
        end: formatDate(end).replace(" ", "T"),
        resourceIds: listResource[index],
        type: eventModify.extendedProps.type,
        description: eventModify.extendedProps.description,
        display : eventModify.display,
        color : eventModify.color,
        }
        );
        }
      console.log(setEvents);
      index++;
    });
    listEvents = setEvents;
  }
  console.log(listEvents);
  for (var i = 0; i < listEvents.length; i++) {
    calendar.addEvent(listEvents[i]);
  }
  let listCurrentEvent = calendar.getEvents();
  listCurrentEvent.forEach((currentEvent) => {
    currentEvent._def.ui.backgroundColor = RessourcesAllocated(currentEvent);
    currentEvent._def.ui.borderColor = RessourcesAllocated(currentEvent);
  });

  //affiche le calendar
  calendar.gotoDate(currentDate);
  calendar.render();
}

function showPopup() {
  $("#divPopup").show();

  timerAlert = setInterval(function () {
    var count = $("span.countdown").html();
    if (count > 1) {
      $("span.countdown").html(count - 1);
    } else {
      clearInterval(timerAlert);
      window.location.assign(
        "/ModificationDeleteOnUnload?dateModified=" + $_GET("date") + "&id=" + $_GET("id")
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

function RessourcesAllocated(event) {
  if (event._def.resourceIds.includes("m-default")) {
    return "rgba(173, 11, 11, 0.753)";
  } else if (event._def.resourceIds.includes("h-default")) {
    return "rgba(173, 11, 11, 0.753)";
  } else if (event._def.ui.display == "background") {
    //get the unavailabilities events
    return "#ff0000";
  } else {
    return "#20c997";
  }
}

function clearArray(array){
  while (array.length) {
    array.pop();
  }
}

