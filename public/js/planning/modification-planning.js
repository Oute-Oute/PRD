var calendar;
var countAddEvent = 0;
var countAddResource=0; 
var headerResources = "Ressources Humaines";
var currentDateStr = $_GET("date").replaceAll("%3A", ":");
var currentDate = new Date(currentDateStr);
var timerAlert;
var modifAlertTime = 480000;
var listErrorMessages = [];

var listEvents;
var historyEvents=[]; 

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
  var newDelay = oldEvent.end.getTime() - oldEvent.start.getTime();
  oldEvent.setStart(new Date(newStart.getTime() + 2 * 60 * 60 * 1000))
  oldEvent.setEnd(new Date(newStart.getTime() + 2 * 60 * 60 * 1000 + newDelay))

  updateEventsAppointment(oldEvent);

  let listResource = [];
  oldEvent._def.resourceIds.forEach((resource) => {
    listResource.push(resource)
  })

  calendar.getEvents().forEach((currentEvent) => {
    if(currentEvent.display == "background"){
      if(oldEvent._def.publicId == currentEvent._def.extendedProps.idScheduledActivity){
        if(listResource.length != 0){
          currentEvent._def.resourceIds = listResource;
          currentEvent.setStart(oldEvent.start);
          currentEvent.setEnd(oldEvent.end);
        }
      }
    }
  })
  
  
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
  document.getElementById("events").value = JSON.stringify(calendar.getEvents());
  document.getElementById("list-resource").value = JSON.stringify(listResources);
  document.getElementById("validation-date").value = $_GET("date");
}

function zoomChange() {
  newZoom = document.getElementById('zoom').value;
  calendar.setOption('slotDuration', newZoom)
}


//function permettant l'ouverture de la modal d'ajout d'un parcours
function addEvent() {
  let listeAppointments = JSON.parse(
    document.getElementById("listeAppointments").value.replaceAll("3aZt3r", " ")
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

  if (earliestAppointmentDate >= choosenAppointmentDate || EndPathwayDate >= latestAppointmentDate) {
      alert("l'heure de début définie ne correspond pas avec les paramètres du rendez-vous")
    }

    //On récupère l'ensemble des id activité b de la table successor pour trouver la première activité du parcours
    var successorsActivitybIdList = [];
    for (let i = 0; i < listeSuccessors.length; i++) {
      successorsActivitybIdList.push(listeSuccessors[i].idactivityb);
    }

    //get the first activities of the pathway
    var firstActivitiesPathway=[]; 
    for (let i = 0; i < activitiesInPathwayAppointment.length; i++) {
      if (
        successorsActivitybIdList.includes(activitiesInPathwayAppointment[i].id) == false) {
        firstActivitiesPathway.push(activitiesInPathwayAppointment[i]);
      }
    }

    var activitiesA=[];
    //Tableau permettant de vérifier qu'il n'y ai pas la même activityB qui est push dans le tableau activtiesA
    var allActivtiesA=[]; 
    for(let i=0; i<firstActivitiesPathway.length; i++){
      let activityA={activity:firstActivitiesPathway[i],delaymin:0}; 
      activitiesA.push(activityA); 
      allActivtiesA.push(firstActivitiesPathway[i].id); 
    }
    do{

      //Création des activités dans FullCalendar
      for(let i=0; i<activitiesA.length; i++){
        var quantityHumanResources = 0;
        var quantityMaterialResources = 0; 
        var activityResourcesArray=[]; 
        //Trouver pour chaques activités du parcours le nombre de resources humaines à définir
        for (let j = 0; j < listeActivitHumanResource.length; j++) {
          if (listeActivitHumanResource[j].activityId == activitiesA[i].activity.id) {
            quantityHumanResources += listeActivitHumanResource[j].quantity;
          }
        }

        //Rentrer le nombre de resources humaines dans le tableau de Resources de l'event
        for (let j = 0; j< quantityHumanResources; j++) {
          activityResourcesArray.push("h-default");
        }

        //Trouver pour chaques activités du parcours le nombre de resources matérielles à définir
        for (let j = 0; j < listeActivityMaterialResource.length; j++) {
          if (listeActivityMaterialResource[j].activityId == activitiesA[i].activity.id) {
            quantityMaterialResources +=
            listeActivityMaterialResource[j].quantity;
          }
        }
        countAddEvent++;
        //Ajout d'un event au calendar
        var event = calendar.addEvent({
          id: "new" + countAddEvent,
          description: "",
          resourceIds: activityResourcesArray,
          title: activitiesA[i].activity.name.replaceAll("3aZt3r", " "),
          start: PathwayBeginDate.getTime()+activitiesA[i].delaymin*60000,
          end: PathwayBeginDate.getTime() + activitiesA[i].activity.duration * 60000,
          patient:appointment.idPatient[0].lastname +" " +appointment.idPatient[0].firstname,
          appointment: appointment.id,
          activity: activitiesA[i].activity.id,
          type: "activity",
          humanResources: [],
          materialResources: [],
          pathway: appointment.idPathway[0].title.replaceAll("3aZt3r", " "),
        });

        event._def.ui.backgroundColor = RessourcesAllocated(event);
        event._def.ui.borderColor = RessourcesAllocated(event);
        event.setEnd(event.end); 
      }
      
      var successorsActivitiesA=[]; 
       //On reset le tableau successorsActivitiesA
       for(let i=successorsActivitiesA.length-1; i>0; i--){
        successorsActivitiesA.splice(i);
      }
      //Récupération de chaque idActivityB pour chaque Activités A 
      
      for(let i=0; i<activitiesA.length; i++){
        for(let j=0; j<listeSuccessors.length; j++){
          if(activitiesA[i].activity.id==listeSuccessors[j].idactivitya){
            let successor={delaymin:listeSuccessors[j].delaymin,activityB:listeSuccessors[j].idactivityb}; 
            successorsActivitiesA.push(successor); 
          }
        }
      }

      //On garde pour chaque activityB différentes dans successorsActivitiesA celle qui a le delaymin le plus grand
      for(let i=0; i<successorsActivitiesA.length; i++){
        for(let j=0; j<successorsActivitiesA.length;j++){
          if(successorsActivitiesA[i].activityB==successorsActivitiesA[j].activityB && i!=j){
            if(successorsActivitiesA[i].delaymin<successorsActivitiesA[j].delaymin){
              successorsActivitiesA.splice(i); 
            }
            else{
              successorsActivitiesA.splice(j); 
            }
          }
        }
      }

      //On passe les SuccessorsActivitiesA dans le tableau ActivitiesA
      //on récupère tout d'aboprd la plus longue activité pour toutes les Activities A
      var biggerDuration=0; 
      for(let i=0; i<activitiesA.length; i++){ 
          if(biggerDuration<activitiesA[i].activity.duration){
            biggerDuration=activitiesA[i].activity.duration; 
          }
      }
      //On supprime les éléments de ActivitiesA
      for(let i=activitiesA.length-1;i>=0;i--){
        activitiesA.splice(i); 
      }
      
      //On retrouve les Activités dans la liste d'activités et on les ajoutes au tableau
      for(let i=0; i<successorsActivitiesA.length; i++){
        for(let j=0; j<listeActivities.length; j++){
          
          if(successorsActivitiesA[i].activityB==listeActivities[j].id){ 
            
            for(let k=0; k<allActivtiesA.length;k++){
              if(allActivtiesA.includes(listeActivities[j].id)==false){
                let activityA={activity:listeActivities[j],delaymin:successorsActivitiesA[i].delaymin}
                activitiesA.push(activityA); 
                allActivtiesA.push(listeActivities[j].id); 
              }
            }
          }
        }
      }
      let biggerdelay=0; 
      for(let i=0; i<activitiesA.length; i++){
          if(activitiesA[i].delaymin>biggerdelay){
            biggerdelay=activitiesA[i].delaymin; 
          }
      }
      PathwayBeginDate=new Date(PathwayBeginDate.getTime()+biggerDuration*60000+biggerdelay*60000); 

    } while (successorsActivitiesA.length!=0);
    verifyHistoryPush(historyEvents,appointmentid); 
    calendar.render();

    $("#add-planning-modal").modal("toggle");
  
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
function updateEventsAppointment(oldEvent) {
  verifyHistoryPush(historyEvents,-1);
  var listEvent = calendar.getEvents();
  let listOldEvent = calendar.getEvents();
  var appointmentId = oldEvent._def.extendedProps.appointment;
  var materialResources = JSON.parse(document.getElementById("material").value.replaceAll("3aZt3r", " "));
  var humanResources = JSON.parse(document.getElementById("human").value.replaceAll("3aZt3r", " "));
  var categoryOfMaterialResource = JSON.parse(document.getElementById("categoryOfMaterialResourceJSON").value.replaceAll("3aZt3r", " "));
  var categoryOfHumanResource = JSON.parse(document.getElementById("categoryOfHumanResourceJSON").value.replaceAll("3aZt3r", " "));
  var listActivityHumanResource = JSON.parse(document.getElementById("listeActivityHumanResource").value.replaceAll("3aZt3r", " "));
  var listActivityMaterialResource = JSON.parse(document.getElementById("listeActivityMaterialResource").value.replaceAll("3aZt3r", " "));
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

  var newEvent = calendar.getEventById(oldEvent._def.publicId);

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

  var currentDate = new Date(currentDateStr.split("T")[0] + " 00:00:00");
  var workingHoursStart = new Date(currentDateStr.split("T")[0] + " 00:00:00");
  var workingHoursEnd = new Date(currentDateStr.split("T")[0] + " 23:59:00");

  var newEventAppointment = calendar.getEventById(oldEvent._def.publicId);
  let categoryHumanResourceOld = [];
  let categoryHumanResourceNew = [];
  let categoryMaterialResourceOld = [];
  let categoryMaterialResourceNew = [];

    humanResources.forEach((resource) => {
      newEventAppointment._def.resourceIds.forEach((resourceId => {
        if(resource.id == resourceId){
          var isOld = false;
          oldEvent._def.resourceIds.forEach((oldResourceId) => {
            if(resource.id == oldResourceId){
              isOld = true;
              categoryOfHumanResource.forEach((humanEventRelation) => {
                if(humanEventRelation.idresource == resource.id){
                  if(categoryHumanResourceOld != []){
                    var alreadyExist = false;
                    categoryHumanResourceOld.forEach((categoryHuman) => {
                      if(categoryHuman.id == humanEventRelation.idcategory){
                        alreadyExist = true;
                        categoryHuman.quantity = categoryHuman.quantity + 1;
                      }
                    })
                    if(!alreadyExist){
                      categoryHumanResourceOld.push({
                        id: humanEventRelation.idcategory,
                        quantity: 1
                      });
                    }
                  }
                  else {
                    categoryHumanResourceOld.push({
                      id: humanEventRelation.idcategory,
                      quantity: 1
                    });
                  }
                }
              })
            }
          })
          if(isOld == false){
            workingHoursStart = new Date(currentDateStr.split("T")[0] + " " + resource.workingHours[0].startTime + ":00")
            workingHoursEnd = new Date(currentDateStr.split("T")[0] + " " + resource.workingHours[0].endTime + ":00")
            if(!(workingHoursStart <= new Date(newEvent.start.getTime() - 2 * 60 * 60 * 1000) && 
            new Date(newEvent.start.getTime() - 2 * 60 * 60 * 1000) <= workingHoursEnd)){
              alert(resource.title + " n'est pas en horaire de travail, il risque d'y avoir un conflit.");
            }
            categoryOfHumanResource.forEach((humanEventRelation) => {
              if(humanEventRelation.idresource == resource.id){
                if(categoryHumanResourceNew != []){
                  var alreadyExist = false;
                  categoryHumanResourceNew.forEach((categoryHuman) => {
                    if(categoryHuman.id == humanEventRelation.idcategory){
                      alreadyExist = true;
                      categoryHuman.quantity = categoryHuman.quantity + 1;
                    }
                  })
                  if(!alreadyExist){
                    categoryHumanResourceNew.push({
                      id: humanEventRelation.idcategory,
                      quantity: 1
                    });
                  }
                }
                else {
                  categoryHumanResourceNew.push({
                    id: humanEventRelation.idcategory,
                    quantity: 1
                  });
                }
              }
            })
          }
        }
      }))
    })

    materialResources.forEach((resource) => {
      newEventAppointment._def.resourceIds.forEach((resourceId => {
        if(resource.id == resourceId){
          var isOld = false;
          oldEvent._def.resourceIds.forEach((oldResourceId) => {
            if(resource.id == oldResourceId){
              isOld = true;
              categoryOfMaterialResource.forEach((materialEventRelation) => {
                if(materialEventRelation.idresource == resource.id){
                  if(categoryMaterialResourceOld != []){
                    var alreadyExist = false;
                    categoryMaterialResourceOld.forEach((categoryMaterial) => {
                      if(categoryMaterial.id == materialEventRelation.idcategory){
                        alreadyExist = true;
                        categoryMaterial.quantity = categoryMaterial.quantity + 1;
                      }
                    })
                    if(!alreadyExist){
                      categoryMaterialResourceOld.push({
                        id: materialEventRelation.idcategory,
                        quantity: 1
                      });
                    }
                  }
                  else {
                    categoryMaterialResourceOld.push({
                      id: materialEventRelation.idcategory,
                      quantity: 1
                    });
                  }
                }
              })
            }
          })
          if(isOld == false){
            categoryOfMaterialResource.forEach((materialEventRelation) => {
              if(materialEventRelation.idresource == resource.id){
                if(categoryMaterialResourceNew != []){
                  var alreadyExist = false;
                  categoryMaterialResourceNew.forEach((categoryMaterial) => {
                    if(categoryMaterial.id == materialEventRelation.idcategory){
                      alreadyExist = true;
                      categoryMaterial.quantity = categoryMaterial.quantity + 1;
                    }
                  })
                  if(!alreadyExist){
                    categoryMaterialResourceNew.push({
                      id: materialEventRelation.idcategory,
                      quantity: 1
                    });
                  }
                }
                else {
                  categoryMaterialResourceNew.push({
                    id: materialEventRelation.idcategory,
                    quantity: 1
                  });
                }
              }
            })
          }
        }
      }))
    })

    if(categoryHumanResourceNew != []){
      categoryHumanResourceNew.forEach((newCategoryHumanResource) => {
        var categoryIsGood = false;
        listActivityHumanResource.forEach((activityHumanResource) => {
          if(newCategoryHumanResource.id == activityHumanResource.humanResourceCategoryId){
            categoryIsGood = true;
            var quantity = newCategoryHumanResource.quantity;
            categoryHumanResourceOld.forEach((oldCategoryHumanResource) => {
              if(oldCategoryHumanResource. id == newCategoryHumanResource.id){
                quantity = quantity + oldCategoryHumanResource.quantity;
              }
            })
            if(quantity > activityHumanResource.quantity){
              alert("L'employé attribué n'est pas nécessaire, il y a assez de (catégorie de la ressource) pour cette activité.")
            }
          }
        })
        if(categoryIsGood == false){
          alert("L'employé attribué n'est pas adapté pour cette activité, cell-ci n'a pas besoin de (catégorie de la ressource).");
        }
      })
    }
    else if(categoryMaterialResourceNew != []){
      categoryMaterialResourceNew.forEach((newCategoryMaterialResource) => {
        var categoryIsGood = false;
        listActivityMaterialResource.forEach((activityMaterialResource) => {
          if(newCategoryMaterialResource.id == activityMaterialResource.materialResourceCategoryId){
            categoryIsGood = true;
            var quantity = newCategoryMaterialResource.quantity;
            categoryMaterialResourceOld.forEach((oldCategoryMaterialResource) => {
              if(oldCategoryMaterialResource. id == newCategoryMaterialResource.id){
                quantity = quantity + oldCategoryMaterialResource.quantity;
              }
            })
            if(quantity > activityMaterialResource.quantity){
              alert("L'employé attribué n'est pas nécessaire, il y a assez de (catégorie de la ressource) pour cette activité.")
            }
          }
        })
        if(categoryIsGood == false){
          alert("L'employé attribué n'est pas adapté pour cette activité, cell-ci n'a pas besoin de (catégorie de la ressource).");
        }
      })
    }

    if (
      earliestAppointmentDate <= new Date(newEvent.start.getTime() - 2 * 60 * 60 * 1000) &&
      new Date(newEvent.end.getTime() - 2 * 60 * 60 * 1000) <= latestAppointmentDate
    ) {
      calendar.getEventById(oldEvent._def.publicId)._def.ui.backgroundColor = RessourcesAllocated(calendar.getEventById(oldEvent._def.publicId));
      calendar.getEventById(oldEvent._def.publicId)._def.ui.borderColor = RessourcesAllocated(calendar.getEventById(oldEvent._def.publicId));
      calendar.getEventById(oldEvent._def.publicId).setEnd(calendar.getEventById(oldEvent._def.publicId).end);

      listOldEvent.forEach((oldEventSet) => {
        if(oldEventSet._def.extendedProps.type == "activity"){
          oldEventSet._def.resourceIds.forEach((oldResource) => {
            newEventAppointment._def.resourceIds.forEach((newResource) => {
              if(newResource != "h-default" && newResource != "m-default"){
                  if(!(newEventAppointment.start > oldEventSet.end || newEventAppointment.end < oldEventSet.start) || (newEventAppointment.start < oldEventSet.start && newEventAppointment.end > oldEventSet.end) || (newEventAppointment.start == oldEventSet.start && newEventAppointment.end == oldEventSet.end)){
                    if(newResource == oldResource) {
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
                      if(newEventAppointment._def.extendedProps.appointment != oldEventSet._def.extendedProps.appointment){
                        alert(oldEventSet._def.extendedProps.patient + " est déjà prévu sur ce crénaux avec " + resourceTitle + " pour l'activité " + oldEventSet._def.title + ", il risque d'y avoir un conflit avec " + newEventAppointment._def.extendedProps.patient + " sur ce même créneau pour l'activité " + newEventAppointment._def.title + ".");
                      }
                      else if(newEventAppointment._def.publicId != oldEventSet._def.publicId){
                        alert(newEventAppointment._def.extendedProps.patient + " est déjà sur ce même créneaux avec " + resourceTitle + ", cela risque de créer un conflit.")
                      }
                    }
                    else {
                      
                    }
                }
              }
            })
          })
        }
        })
    }
    else {
      alert("Le parcours n'est plus compris entre " + earliestAppointmentDate.getHours().toString().padStart(2, "0") + ":" + earliestAppointmentDate.getMinutes().toString().padStart(2, "0") + " et " + latestAppointmentDate.getHours().toString().padStart(2, "0") + ":" + latestAppointmentDate.getMinutes().toString().padStart(2, "0"));
    }
    
}



function createCalendar(typeResource,useCase) {
  const height = document.querySelector("div").clientHeight;
  var calendarEl = document.getElementById("calendar");
  var first;
  var listEvent;

  let listResource = [];
    if (listEvents == undefined) {
      first = true;
    } 

    else {
      first = false;
      switch(useCase){
        case 'recreate':
          //Test pour savoir si il s'agit d'un ajout
            if(historyEvents[historyEvents.length-2]!=undefined){
              if(historyEvents[historyEvents.length-1].idAppointment!=-1){
                //récupère la liste des Appointments
                var listeAppointments = JSON.parse(document.getElementById("listeAppointments").value);  
                for (let i = 0; i < listeAppointments.length; i++) {
                  if (listeAppointments[i]["id"] == historyEvents[historyEvents.length-1].idAppointment) {
                    //On défini le rdv comme non plannifié
                    listeAppointments[i].scheduled = false;
                  }
                }
                //On update la liste de rendez-vous
                document.getElementById("listeAppointments").value =JSON.stringify(listeAppointments);
              }
            
              listEvent=historyEvents[historyEvents.length-2].events; 
              historyEvents.splice(historyEvents.length-1,1);  
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
    calendar = new FullCalendar.Calendar(calendarEl, {
      //clé de la license pour utiliser la librairie à des fin non commerciale
      schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives",
      resourceOrder:'type',
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

      resourceAreaWidth: "20%",
      resourceAreaHeaderContent: headerResources,

      eventDidMount: function (info) {
        $(info.el).tooltip({
          title: info.event.extendedProps.description,
          placement: "top",
          trigger: "hover",
          container: "body",
        });
      },

      //permet d'ouvrir la modal pour la modification d'une activité lorsque l'on click dessus
      eventClick: function (event) {
        if (event.event.display != "background"){
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
      }
    },

    eventDrop: function (event) {
      var oldEvent = event.oldEvent;
      var modifyEvent = event.event;
      updateEventsAppointment(oldEvent);
      calendar.render();
      updateErrorMessages();
      
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
              var materialArray={id:modifyEvent._def.resourceIds[i],title:listeMaterialResources[j].title}
              modifyEvent._def.extendedProps.materialResources.push(materialArray); 
            }  
          }
        }
      }

      let listResource = [];
      modifyEvent._def.resourceIds.forEach((resource) => {
        listResource.push(resource)
      })
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
        countAddResource++; 
        calendar.addResource({
          //add the resources to the calendar
          id: temp["id"], //set the id
          title: temp["title"], //set the title
          businessHours: businessHours, //get the business hours
          type:countAddResource,
        });
        calendar.addResource({
          id: "h-default",
          title: "Aucune ressource allouée",
          type:0,
        });
        }
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
            type:countAddResource,

          });
          calendar.addResource({
            id: "m-default",
            title: "Aucune ressource allouée",
            type:0,
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
            categoryMaterialResource: eventModify.extendedProps.categoryMaterialResource,
            categoryHumanResource: eventModify.extendedProps.categoryHumanResource,
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
        index++;
      });
      listEvents = setEvents;
    }
    for (var i = 0; i < listEvents.length; i++) {
      calendar.addEvent(listEvents[i]);
    }
    let listCurrentEvent = calendar.getEvents();
    listCurrentEvent.forEach((currentEvent) => {
      currentEvent._def.ui.backgroundColor = RessourcesAllocated(currentEvent);
      currentEvent._def.ui.borderColor = RessourcesAllocated(currentEvent);
    });
    if(historyEvents.length==0){
      verifyHistoryPush(historyEvents,-1); 
    }
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

function undoEvent(){ 
  if(historyEvents.length!=1){
    createCalendar(headerResources,'recreate');
  }
}

function verifyHistoryPush(array, idAppointment){
  
  if(array.length<10){
    array.push({events:calendar.getEvents(),idAppointment:idAppointment}); 
  }
  else{
    for(let i=0; array.length>=10; i++){
      array.splice(i,1); 
    }
    array.push({events:calendar.getEvents(),idAppointment:idAppointment});  
  };
}

function updateErrorMessages() {
  var listScheduledActivities = calendar.getEvents();
  listScheduledActivities.forEach((scheduledActivity) => {
    if(scheduledActivity.display != "background"){
      console.log(scheduledActivity)
      var appointmentAlreadyExist = false;
      if(listErrorMessages != []){
        listErrorMessages.forEach((errorMessage) => {
          if(scheduledActivity._def.extendedProps.appointment == errorMessage.appointmentId){
            appointmentAlreadyExist = true;
            var scheduledActivityAlreadyExist = false;
            errorMessage.listScheduledActivity.forEach((existingScheduledActivity) => {
              if(existingScheduledActivity.scheduledActivityId == scheduledActivity._def.publicId){
                scheduledActivityAlreadyExist = true;
              }
            })
            if(scheduledActivityAlreadyExist == false){
              errorMessage.listScheduledActivity.push({
                scheduledActivityId: scheduledActivity._def.publicId,
                scheduledActivityName: scheduledActivity._def.title,
                messageDelay: getMessageDelay(listScheduledActivities, scheduledActivity),
                listCategoryHumanResources: getListCategoryHumanResources(scheduledActivity),
                listCategoryMaterialResources: getListCategoryMaterialResources(scheduledActivity)
              })
            }
          }
        })
      }
      if(appointmentAlreadyExist == false){
        listErrorMessages.push({
          appointmentId: scheduledActivity._def.extendedProps.appointment,
          patientName: scheduledActivity._def.extendedProps.patient,
          pathwayName: scheduledActivity._def.extendedProps.pathway,
          messageEarliestAppointmentTime: getMessageEarliestAppointmentTime(listScheduledActivities, scheduledActivity._def.extendedProps.appointment),
          messageLatestAppointmentTime: getMessageLatestAppointmentTime(listScheduledActivities, scheduledActivity._def.extendedProps.appointment),
          listScheduledActivity: [{
            scheduledActivityId: scheduledActivity._def.publicId,
            scheduledActivityName: scheduledActivity._def.title,
            messageDelay: getMessageDelay(listScheduledActivities, scheduledActivity),
            listCategoryHumanResources: getListCategoryHumanResources(scheduledActivity),
            listCategoryMaterialResources: getListCategoryMaterialResources(scheduledActivity)
          }]
        })
      }
    }
  })
  console.log(listErrorMessages)
}

function getMessageEarliestAppointmentTime(listScheduledActivities, appointmentId){
  var message = "";

  var listeAppointments = JSON.parse(document.getElementById("listeAppointments").value);
  var appointment;
  listeAppointments.forEach((currentAppointment) => {
    if(currentAppointment.id == appointmentId){
      appointment = currentAppointment
    }
  })
  let earliestAppointmentDate = new Date(currentDateStr.split("T")[0] + " " + appointment.earliestappointmenttime.split("T")[1]);

  listScheduledActivities.forEach((scheduledActivity) => {
    if(scheduledActivity._def.extendedProps.appointment == appointmentId){
      if(new Date(scheduledActivity.start.getTime() - 2 * 60 * 60 * 1000) < earliestAppointmentDate){
        message = message + scheduledActivity._def.title + " commence avant : " + earliestAppointmentDate.getHours().toString().padStart(2, "0") + ":" + earliestAppointmentDate.getMinutes().toString().padStart(2, "0") +" qui est l'heure d'arrivé au plus tôt du patient. ";
      }
    }
  })

  return message;
}

function getMessageLatestAppointmentTime(listScheduledActivities, appointmentId){
  var message = "";

  var listeAppointments = JSON.parse(document.getElementById("listeAppointments").value);
  var appointment;
  listeAppointments.forEach((currentAppointment) => {
    if(currentAppointment.id == appointmentId){
      appointment = currentAppointment
    }
  })
  let latestAppointmentDate = new Date(currentDateStr.split("T")[0] + " " + appointment.latestappointmenttime.split("T")[1]);

  listScheduledActivities.forEach((scheduledActivity) => {
    if(scheduledActivity._def.extendedProps.appointment == appointmentId){
      if(new Date(scheduledActivity.end.getTime() - 2 * 60 * 60 * 1000) > latestAppointmentDate){
        message = message + scheduledActivity._def.title + " finit après : " + latestAppointmentDate.getHours().toString().padStart(2, "0") + ":" + latestAppointmentDate.getMinutes().toString().padStart(2, "0") +" qui est l'heure de fin au plus tard du patient. ";
      }
    }
  })

  return message;
}

function getMessageDelay(listScheduledActivities, scheduledActivity){
  var messages = [];
  
  var listSuccessors = JSON.parse(document.getElementById("listeSuccessors").value);
  listSuccessors.forEach((successor) => {
    if(successor.idactivitya == scheduledActivity._def.extendedProps.activity){
      listScheduledActivities.forEach((scheduledActivityB) => {
        if(successor.idactivityb == scheduledActivityB._def.extendedProps.activity){
          var duration = (scheduledActivityB.start.getTime() - scheduledActivity.end.getTime())/(60*1000);
          if(duration < successor.delaymin){
            var message = "Le delay entre " + scheduledActivity._def.title + " et " + scheduledActivityB._def.title + " est de : " + duration + " minutes ce qui est inférieur à : " + successor.delaymin + " minutes qui est le délai minimum.";
            messages.push(message);
          }
          if(duration > successor.delaymax){
            var message = "Le delay entre " + scheduledActivity._def.title + " et " + scheduledActivityB._def.title + " est de : " + duration + " minutes ce qui est supèrieur à : " + successor.delaymax + " minutes qui est le délai maximum.";
            messages.push(message);
          }
        }
      })
    }
  })

  return messages;
}

function getListCategoryHumanResources(scheduledActivity){
  var listCategoryHumanResources = [];

  return listCategoryHumanResources;
}

function getListCategoryMaterialResources(scheduledActivity){
  var listCategoryMaterialResources = [];

  return listCategoryMaterialResources;
}

function getMessageCategoryQuantity(categoryResource){
  var message = "";

  return message;
}

function getMessageWrongCategory(categoryResource){
  var message = "";

  return message;
}

function getMessageUnavailability(listScheduledActivities, scheduledActivity, resource){
  var message = "";

  return message;
}

function getMessageAlreadyExist(listScheduledActivities, scheduledActivity, resource){
  var message = "";

  return message;
}

function getMessageWorkingHours(scheduledActivity, humanResource){
  var message = "";

  return message;
}
  function displayListErrorMessages(){
    var lateralPannelBloc=document.querySelectorAll('#'+'lateral-panel-bloc'); 
    var lateralPannel=document.querySelectorAll('#'+'lateral-panel');
    var lateralPannelInput=document.getElementById('lateral-panel-input').checked;
    if(lateralPannelInput==true){
      lateralPannelBloc[0].style.display='block'; 
      lateralPannel[0].style.width='40em';
      updateListErrorMessages();
    }
    else{
      lateralPannelBloc[0].style.display='';
      lateralPannel[0].style.width='';
    }
    
  }

  function updateListErrorMessages(){
    var nodesNotification=document.getElementById('lateral-panel-bloc').childNodes; 
    while(nodesNotification.length!=3){
      document.getElementById('lateral-panel-bloc').removeChild(nodesNotification[nodesNotification.length-1]); 
    }
    for(let i=0; i<listErrorMessages.length; i++){
      var div = document.createElement('div');
      div.setAttribute('class', 'alert alert-warning');
      div.setAttribute('role','alert');
      div.setAttribute('id','notification');
      div.setAttribute('style','display: flex; flex-direction : column;'); 
      var divRow=document.createElement('divRow'); 
      divRow.setAttribute('style','display: flex; flex-direction : row;'); 
      div.append(divRow);
      var img = document.createElement("img");
      img.src="/img/exclamation-triangle-fill.svg"; 
      var text=document.createElement('h3'); 
      text.innerHTML=listErrorMessages[i].patientName + ' / '+ listErrorMessages[i].pathwayName; 
      divRow.append(img,text);

      //messageEarliestAppointmentTime
      if(listErrorMessages[i].messageEarliestAppointmentTime!=''){
        var divColumn=document.createElement('divColumn');
        div.append(divColumn); 
        var messageEarliestAppointmentTime= document.createElement('earliestAppointmentDate').innerHTML=listErrorMessages[i].messageEarliestAppointmentTime;  
        divColumn.append(messageEarliestAppointmentTime);
      }

      //messageLatestAppointmentTime
      if(listErrorMessages[i].messageLatestAppointmentTime!=''){
        var divColumn=document.createElement('divColumn');
        div.append(divColumn); 
        var messageLatestAppointmentTime= document.createElement('messageLatestAppointmentTime').innerHTML=listErrorMessages[i].messageLatestAppointmentTime;  
        divColumn.append(messageLatestAppointmentTime);
      }
      
      //messageDelay for each ScheduledActivity
      for(let listeSAiterator=0; listeSAiterator<listErrorMessages[i].listScheduledActivity.length; listeSAiterator++){
          if(listErrorMessages[i].listScheduledActivity[listeSAiterator].messageDelay!=''){
            var divColumn=document.createElement('divColumn');
            div.append(divColumn); 
            var messageDelay= document.createElement('messageDelay').innerHTML=listErrorMessages[i].listScheduledActivity[listeSAiterator].messageDelay;  
            divColumn.append(messageDelay);
          }

          for(let listCategoryHumanResourcesItorator=0;listCategoryHumanResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources.length; listCategoryHumanResourcesItorator++){
            if(listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageCategoryQuantity!=''){
              var divColumn=document.createElement('divColumn');
              div.append(divColumn); 
              var messageCategoryQuantity= document.createElement('messageCategoryQuantity').innerHTML=listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageCategoryQuantity;  
              divColumn.append(messageCategoryQuantity);
            }
            if(listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageWrongCategory!=''){
              var divColumn=document.createElement('divColumn');
              div.append(divColumn);
              var messageWrongCategory= document.createElement('messageWrongCategory').innerHTML=listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageWrongCategory;  
              divColumn.append(messageWrongCategory);
            }
          
            for(let listHumanResourcesIterator=0; listHumanResourcesIterator<listCategoryHumanResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].listHumanResources.length; listHumanResourcesIterator++ ){
              if(listCategoryHumanResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].listHumanResources[listHumanResourcesIterator].messageWorkingHours!=''){
                var divColumn=document.createElement('divColumn');
                div.append(divColumn);
                var messageWorkingHours= document.createElement('messageWorkingHours').innerHTML=listCategoryHumanResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].listHumanResources[listHumanResourcesIterator].messageWorkingHours;  
                divColumn.append(messageWorkingHours);
              }

              if(listCategoryHumanResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].listHumanResources[listHumanResourcesIterator].messageUnavailability!=''){
                var divColumn=document.createElement('divColumn');
                div.append(divColumn);
                var messageUnavailability= document.createElement('messageUnavailability').innerHTML=listCategoryHumanResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].listHumanResources[listHumanResourcesIterator].messageUnavailability;  
                divColumn.append(messageUnavailability);
              }

              if(listCategoryHumanResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].listHumanResources[listHumanResourcesIterator].messageAlreadyScheduled!=''){
                var divColumn=document.createElement('divColumn');
                div.append(divColumn);
                var messageAlreadyScheduled= document.createElement('messageAlreadyScheduled').innerHTML=listCategoryHumanResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].listHumanResources[listHumanResourcesIterator].messageAlreadyScheduled;  
                divColumn.append(messageAlreadyScheduled);
              }
            }

          }
          
          for(let listCategoryMaterialResourcesItorator=0;listCategoryMaterialResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources.length; listCategoryMaterialResourcesItorator++){
            if(listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageCategoryQuantity!=''){
              var divColumn=document.createElement('divColumn');
              div.append(divColumn);
              var messageCategoryQuantity= document.createElement('messageCategoryQuantity').innerHTML=listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageCategoryQuantity;  
              divColumn.append(messageCategoryQuantity);
            }
            if(listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageWrongCategory!=''){
              var divColumn=document.createElement('divColumn');
              div.append(divColumn);
              var messageWrongCategory= document.createElement('messageWrongCategory').innerHTML=listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageWrongCategory;  
              divColumn.append(messageWrongCategory);
            }
          
            for(let listMaterialResourcesIterator=0; listMaterialResourcesIterator<listCategoryMaterialResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].listMaterialResources.length; listMaterialResourcesIterator++ ){
              
              if(listCategoryMaterialResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].listMaterialResources[listMaterialResourcesIterator].messageUnavailability!=''){
                var divColumn=document.createElement('divColumn');
                div.append(divColumn);
                var messageUnavailability= document.createElement('messageUnavailability').innerHTML=listCategoryMaterialResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].listMaterialResources[listMaterialResourcesIterator].messageUnavailability;  
                divColumn.append(messageUnavailability);
              }

              if(listCategoryMaterialResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].listMaterialResources[listMaterialResourcesIterator].messageAlreadyScheduled!=''){
                var divColumn=document.createElement('divColumn');
                div.append(divColumn);
                var messageAlreadyScheduled= document.createElement('messageAlreadyScheduled').innerHTML=listCategoryMaterialResourcesItorator<listErrorMessages[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].listMaterialResources[listMaterialResourcesIterator].messageAlreadyScheduled;  
                divColumn.append(messageAlreadyScheduled);
              }
            }

          }

        }
      document.getElementById('lateral-panel-bloc').appendChild(div);
    }
  }
