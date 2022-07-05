// Timeout pour afficher le popup (pour éviter une modif trop longue)
var popupClicked = false;
var modifAlertTime = 1680000; // En millisecondes
//setTimeout(showPopup, modifAlertTime);
//setTimeout(deleteModifInDB, modifAlertTime+60000);

var calendar;
var CoundAddEvent = 0;
var headerResources = "Ressources Matérielles";
var dateStr = $_GET("date").replaceAll("%3A", ":");
var date = new Date(dateStr);

var resourcearray;
var eventsarray;

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

console.log(dateStr);

document.addEventListener("DOMContentLoaded", function () {
  //Créer le calendar sous les conditions que l'on souhaite
  createCalendar(headerResources);
});

function unshowDiv(id) {
  document.getElementById(id).style.display = "none";
}

//function permettant la modification de l'activité
function modifyEvent() {
  var id = document.getElementById("id").value;
  var event = calendar.getEventById(id);
  var today = $_GET("date").substring(0, 10);
  var start = today + "T" + document.getElementById("new-start").value;
  var length = document.getElementById("length").value;
  var date = new Date(start.replace("T", " "));

  var end = new Date(date.getTime() + length * 60 * 1000);

  event.setStart(start);
  event.setEnd(formatDate(end).replace(" ", "T"));
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
  var events = calendar.getEvents();
  let resources = [];
  events.forEach((event) => {
    var listResource = [];
    for (let i = 0; i < event._def.resourceIds.length; i++) {
      listResource.push(event._def.resourceIds[i]);
    }
    resources.push(listResource);
  });
  document.getElementById("events").value = JSON.stringify(
    calendar.getEvents()
  );
  document.getElementById("list-resource").value = JSON.stringify(resources);
  document.getElementById("validation-date").value = $_GET("date");
}

//function permettant l'ouverture de la modal d'ajout d'un parcours
function addEvent() {
  let selectContainerErrorTime = document.getElementById("time-selected-error");
  selectContainerErrorTime.style.display = "none";
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

  //Récupération du rdv choisit par l'utilisateur
  var appointment;
  for (let i = 0; i < listeAppointments.length; i++) {
    if (listeAppointments[i]["id"] == appointmentid) {
      appointment = listeAppointments[i];
    }
  }

  //Date de début du parcours
  var PathwayBeginTime = document.getElementById("timeBegin").value;
  var PathwayBeginDate = new Date(
    new Date(dateStr.substring(0, 10) + " " + PathwayBeginTime).getTime() +
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
      CoundAddEvent++;
      //Ajout d'un event au calendar
      calendar.addEvent({
        id: "new" + CoundAddEvent,
        resourceIds: activityResourcesArray,
        title: activitya.name.replaceAll("3aZt3r", " "),
        start: PathwayBeginDate,
        end: PathwayBeginDate.getTime() + activitya.duration * 60000,
        patient: appointment.idPatient,
        appointment: appointment.id,
        activity: activitya.id,
      });

      //Detection de la dernière activite du parcours
      if (idactivityB != undefined) {
        idactivitya = idactivityB;
      }
      PathwayBeginDate = new Date(
        PathwayBeginDate.getTime() + activitya.duration * 60000
      );
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
    filter.style.display = "inline-block"; //display the filter
    if (calendar.getResources().length == 0) {
      //if there is no resource in the calendar
      var label = document.createElement("label"); //display a label
      label.innerHTML = "Aucune ressource à filtrer"; //telling "no resources"
      filter.appendChild(label); //add the label to the filter
    }
    for (var i = 0; i < calendar.getResources().length; i++) {
      //fo all the resources in the calendar
      var input = document.createElement("input"); //create a input
      input.type = "checkbox"; //set the type of the input to checkbox
      input.id = calendar.getResources()[i].id; //set the id of the input to the id of the resource
      input.name = calendar.getResources()[i].title; //set the name of the input to the title of the resource
      input.checked = true; //set the checkbox to checked
      input.onchange = function () {
        //set the onchange event
        changeFilter(this.id); //call the changeFilter function with the id of the resource
      };
      filter.appendChild(input); //add the input to the filter
      var label = document.createElement("label"); //create a label
      label.htmlFor = calendar.getResources()[i].id; //set the htmlFor of the label to the id of the resource
      label.innerHTML = "&nbsp;" + calendar.getResources()[i].title; //set the text of the label to the title of the resource
      filter.appendChild(label); //add the label to the filter
      filter.appendChild(document.createElement("br")); //add a br to the filter for display purpose
    }
  }
}

/**
 * @brief This function is called when we want to filter the resources of the calendar
 * @param {*} id the id of resource to filter
 */
function changeFilter(id) {
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

function createCalendar(typeResource) {
  const height = document.querySelector("div").clientHeight;
  var calendarEl = document.getElementById("calendar");
  var first;
  var listEvent;

  let listResource = [];
  if (eventsarray == undefined) {
    first = true;
  } else {
    first = false;
    listEvent = calendar.getEvents();
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
    selectable: true,
    editable: true,
    eventDurationEditable: false,
    contentHeight: (9 / 12) * height,
    handleWindowResize: true,
    nowIndicator: true,

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

    //permet d'ouvrir la modal pour la modification d'une activité lorsque l'on click dessus
    eventClick: function (event, element) {
      //récupération des données
      var id = event.event._def.publicId;
      var activity = calendar.getEventById(id);
      var start = activity.start;
      var tmp = activity.end - start;

      //calcul de la durée de l'activité
      length = Math.floor(tmp / 1000 / 60);

      //set les données à afficher par défault
      $("#new-start").val(start.toISOString().substring(11, 16));
      document.getElementById("show-title").innerHTML = activity.title;
      $("#title").val(activity.title);
      $("#length").val(length);
      $("#id").val(id);

      //ouvre la modal
      $("#modify-planning-modal").modal("show");
    },

    eventDragStop: function (event, jsEvent) {
      var listEvent = calendar.getEvents();
      var oldEvent = event.event._def;
      var modifyEvent = event.el.fcSeg;
      var appointmentId = event.event._def.extendedProps.appointment;
      var listEventAppointment = [];
      listEvent.forEach((oldEvents) => {
        if (oldEvents._def.extendedProps.appointment == appointmentId) {
          listEventAppointment.push(oldEvents);
        }
      });

      var isFirst = true;
      listEventAppointment.forEach((eventAppointment) => {
        if (eventAppointment._def.start < oldEvent.start) {
          console.log(eventAppointment);
          isFirst = false;
        }
      });

      if (isFirst) {
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
          appointment.earliestappointmenttime
        );
        let latestAppointmentDate = new Date(appointment.latestappointmenttime);

        if (
          earliestAppointmentDate <= event.event._def.start &&
          event.event._def.start <= latestAppointmentDate
        ) {
        }
      }
    },
  });
  switch (typeResource) {
    /*case "Patients": //if we want to display by the patients
      var tempArray = JSON.parse(
        document
          .getElementById("listeAppointments")
          .value.replaceAll("3aZt3r", " ")
      ); //get the data of the appointments
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i];
        patient = temp["idPatient"]; //get the resources data
        calendar.addResource({
          //add the resources to the calendar
          id: patient[0]["id"],
          title: patient[0]["title"],
        });
      }
      break;
    case "Parcours": //if we want to display by the parcours
      var tempArray = JSON.parse(
        document
          .getElementById("listeAppointments")
          .value.replaceAll("3aZt3r", " ")
      ); //get the data of the appointments
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i];
        pathway = temp["idPathway"]; //get the resources data
        calendar.addResource({
          //add the resources to the calendar
          id: pathway[0]["id"],
          title: pathway[0]["title"],
        });
      }
      break;*/
    case "Ressources Humaines": //if we want to display by the resources
      var resourcesArray = JSON.parse(
        document.getElementById("human").value.replaceAll("3aZt3r", " ")
      ); //get the data of the resources
      for (var i = 0; i < resourcesArray.length; i++) {
        var temp = resourcesArray[i]; //get the resources data
        calendar.addResource({
          //add the resources to the calendar
          id: temp["id"],
          title: temp["title"],
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
    eventsarray = JSON.parse(
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

      setEvents.push({
        id: eventModify.id,
        start: formatDate(start).replace(" ", "T"),
        end: formatDate(end).replace(" ", "T"),
        title: eventModify.title,
        resourceIds: listResource[index],
        patient: eventModify.extendedProps.patient,
        appointment: eventModify.extendedProps.appointment,
        activity: eventModify.extendedProps.activity,
      });
      index++;
    });
    eventsarray = setEvents;
  }
  for (var i = 0; i < eventsarray.length; i++) {
    calendar.addEvent(eventsarray[i]);
  }

  //affiche le calendar
  calendar.gotoDate(date);
  calendar.render();
}

function showPopup() {
  $("#divPopup").show();
}

function closePopup() {
  $("#divPopup").hide();
  popupClicked = true;
  setTimeout(showPopup, modifAlertTime);
}

/*document.addEventListener('DOMContentLoaded', function() {
    var userData = document.querySelector('.js-data');
    var userId = userData.dataset.userId;
});*/

function deleteModifInDB(popupClicked) {
  if (popupClicked) {
    popupClicked = false;
    setTimeout(deleteModifInDB, modifAlertTime);
  } else {
    // Supprimer modif sur la BDD
  }
}
