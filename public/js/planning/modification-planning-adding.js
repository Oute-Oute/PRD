var listeAppointments
var listeActivity
var listeSuccessors
var categoryHumanResource
var categoryMaterialResource
var listeActivityHumanResource
var listeActivityMaterialResource
var categoryOfHumanResource
var categoryOfMaterialResource

/**
 * Open the modal to Add a pathway
 */
function displayAddPathway() {
  
      let appointmentSelection = document.getElementById("select-appointment");
  //Reset all options from the list
  for (let i = appointmentSelection.options.length - 1; i >= 0; i--) {
    appointmentSelection.remove(i);
  }

  //Add all non shculed appointments into the list
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
}

/**
 * This function is called when clicking on the button 'Valider' into Add Modal. 
 * Add All the Activities from a choosen appointment in the Calendar
 */
function addPathway() {
  //Get databases informations to add the activities appointment on the calendar
  var listeSuccessors = JSON.parse(document.getElementById("listeSuccessors").value);
  var listeActivities = JSON.parse(document.getElementById("listeActivity").value);
  var listeAppointments = JSON.parse(document.getElementById("listeAppointments").value);
  var categoryMaterialResourceJSON = JSON.parse(document.getElementById('categoryMaterialResource').value.replaceAll('3aZt3r', ' '));
  var listeActivityHumanResource = JSON.parse(document.getElementById("listeActivityHumanResource").value);
  var listeActivityMaterialResource = JSON.parse(document.getElementById("listeActivityMaterialResource").value);
  var categoryHumanResourceJSON = JSON.parse(document.getElementById('categoryHumanResource').value.replaceAll('3aZt3r', ' '));
  var appointmentid = document.getElementById("select-appointment").value;


  //Get the appointment choosed by user and the place of the appointment in the listAppointment
  var appointment;
  for (let i = 0; i < listeAppointments.length; i++) {
    if (listeAppointments[i]["id"] == appointmentid) {
      appointment = listeAppointments[i];
      listeAppointments[i].scheduled = true;
    }
  }
  document.getElementById("listeAppointments").value = JSON.stringify(listeAppointments);

  //Date of the begining of the pathway 
  var PathwayBeginTime = document.getElementById("timeBegin").value;
  var PathwayBeginDate = new Date(
    new Date(currentDateStr.substring(0, 10) + " " + PathwayBeginTime).getTime() +
    2 * 60 * 60000
  );


  //Get activities of the pathway
  var activitiesInPathwayAppointment = [];
  for (let i = 0; i < listeActivities.length; i++) {
    if (
      "pathway-" + listeActivities[i]["idPathway"] ==
      appointment["idPathway"][0].id
    ) {
      activitiesInPathwayAppointment.push(listeActivities[i]);
    }
  }

  //Get all activity b in the successors to find the ids of firsts activities in pathway
  var successorsActivitybIdList = [];
  for (let i = 0; i < listeSuccessors.length; i++) {
    successorsActivitybIdList.push(listeSuccessors[i].idactivityb);
  }

  //get the first activities of the pathway with ids 
  var firstActivitiesPathway = [];
  for (let i = 0; i < activitiesInPathwayAppointment.length; i++) {
    if (
      successorsActivitybIdList.includes(activitiesInPathwayAppointment[i].id) == false) {
      firstActivitiesPathway.push(activitiesInPathwayAppointment[i]);
    }
  }

  var activitiesA = [];
  //Array that stock all Activities A to be sure that we dont push the same activity A two times. 
  var allActivitiesA = [];
  for (let i = 0; i < firstActivitiesPathway.length; i++) {
    let activityA = { activity: firstActivitiesPathway[i], delaymin: 0 };
    activitiesA.push(activityA);
    allActivitiesA.push(firstActivitiesPathway[i].id);
  }
  do {

    //Creating Activities in FullCalendar
    for (let i = 0; i < activitiesA.length; i++) {
      var quantityHumanResources = 0;
      var quantityMaterialResources = 0;
      var activityResourcesArray = [];
      var humanAlreadyScheduled = [];
      var materialAlreadyScheduled = [];
      //Find for all Activities of the pathway, the number of Humanresources to define. 
      var categoryHumanResources = [];
      listeActivityHumanResource=Object.values(listeActivityHumanResource);
      for (let j = 0; j < listeActivityHumanResource.length; j++) {
        if (listeActivityHumanResource[j][0].activityId == activitiesA[i].activity.id) {
          for (let k = 0; k < categoryHumanResource.length; k++) {
            if (listeActivityHumanResource[j][0].humanResourceCategoryId == categoryHumanResource[k].idcategory && humanAlreadyScheduled.includes(listeActivityHumanResource[j][0]) == false) {
              humanAlreadyScheduled.push(listeActivityHumanResource[j][0]);
              categoryHumanResources.push({ id: listeActivityHumanResource[j][0].humanResourceCategoryId, quantity: listeActivityHumanResource[j][0].quantity, categoryname: categoryHumanResource[k].categoryname })
            }
          }
          quantityHumanResources += listeActivityHumanResource[j][0].quantity;
        }
      }
      
      var categoryMaterialResources = [];
      listeActivityMaterialResource=Object.values(listeActivityMaterialResource);
      for (let j = 0; j < listeActivityMaterialResource.length; j++) {
        if (listeActivityMaterialResource[j].activityId == activitiesA[i].activity.id) {
          for (let k = 0; k < categoryMaterialResource.length; k++) {
            if (listeActivityMaterialResource[j][0].materialResourceCategoryId == categoryMaterialResource[k].idcategory && materialAlreadyScheduled.includes(listeActivityMaterialResource[j][0]) == false) {
              materialAlreadyScheduled.push(listeActivityMaterialResource[j][0]);
              categoryMaterialResources.push({ id: listeActivityMaterialResource[j][0].materialResourceCategoryId, quantity: listeActivityMaterialResource[j][0].quantity, categoryname: categoryMaterialResource[k].categoryname })
            }
          }
          quantityMaterialResources += listeActivityMaterialResource[j][0].quantity;
        }
      }

      //Put the number of human resouorces in the ResourcesArray of the event
      for (let j = 0; j < quantityHumanResources; j++) {
        activityResourcesArray.push("h-default");
      }

      for (let j = 0; j < quantityMaterialResources; j++) {
        activityResourcesArray.push("m-default");
      }


      //counting for the ids of events
      countAddEvent++;

      //Add one event in the Calendar
      var event = calendar.addEvent({
        id: "new" + countAddEvent,
        description: "",
        resourceIds: activityResourcesArray,
        title: activitiesA[i].activity.name.replaceAll("3aZt3r", " "),
        start: PathwayBeginDate.getTime() + activitiesA[i].delaymin * 60000,
        end: PathwayBeginDate.getTime() + activitiesA[i].activity.duration * 60000,
        patient: appointment.idPatient[0].lastname + " " + appointment.idPatient[0].firstname,
        appointment: appointment.id,
        activity: activitiesA[i].activity.id,
        type: "activity",
        humanResources: [],
        materialResources: [],
        pathway: appointment.idPathway[0].title.replaceAll("3aZt3r", " "),
        categoryMaterialResource: categoryMaterialResources,
        categoryHumanResource: categoryHumanResources,
      });
    }

    var successorsActivitiesA = [];
    //On reset le tableau successorsActivitiesA
    for (let i = successorsActivitiesA.length - 1; i > 0; i--) {
      successorsActivitiesA.splice(i);
    }
    //Get each Activities B for each Activities A 

    for (let i = 0; i < activitiesA.length; i++) {
      for (let j = 0; j < listeSuccessors.length; j++) {
        if (activitiesA[i].activity.id == listeSuccessors[j].idactivitya) {
          let successor = { delaymin: listeSuccessors[j].delaymin, activityB: listeSuccessors[j].idactivityb };
          successorsActivitiesA.push(successor);
        }
      }
    }

    //Keeping for each différent activityB in successorsActivitiesA the biggest delaymin
    for (let i = 0; i < successorsActivitiesA.length; i++) {
      for (let j = 0; j < successorsActivitiesA.length; j++) {
        if (successorsActivitiesA[i].activityB == successorsActivitiesA[j].activityB && i != j) {
          if (successorsActivitiesA[i].delaymin < successorsActivitiesA[j].delaymin) {
            successorsActivitiesA.splice(i);
          }
          else {
            successorsActivitiesA.splice(j);
          }
        }
      }
    }

    //Put SuccessorsActivitiesA in ActivitiesA
    //Get the longestActivity for all ActivityA.
    var biggestDuration = 0;
    for (let i = 0; i < activitiesA.length; i++) {
      if (biggestDuration < activitiesA[i].activity.duration) {
        biggestDuration = activitiesA[i].activity.duration;
      }
    }
    //Deleting All activities A
    for (let i = activitiesA.length - 1; i >= 0; i--) {
      activitiesA.splice(i);
    }

    //Put activitiesA into AllActivitiesA and ActivitiesB in ActivitiesA
    for (let i = 0; i < successorsActivitiesA.length; i++) {
      for (let j = 0; j < listeActivities.length; j++) {
        if (successorsActivitiesA[i].activityB == listeActivities[j].id) {
          for (let k = 0; k < allActivitiesA.length; k++) {
            if (allActivitiesA.includes(listeActivities[j].id) == false) {
              let activityA = { activity: listeActivities[j], delaymin: successorsActivitiesA[i].delaymin }
              activitiesA.push(activityA);
              allActivitiesA.push(listeActivities[j].id);
            }
          }
        }
      }
    }
    let biggestdelay = 0;
    for (let i = 0; i < activitiesA.length; i++) {
      if (activitiesA[i].delaymin > biggestdelay) {
        biggestdelay = activitiesA[i].delaymin;
      }
    }
    PathwayBeginDate = new Date(PathwayBeginDate.getTime() + biggestDuration * 60000 + biggestdelay * 60000);

  } while (successorsActivitiesA.length != 0);
  verifyHistoryPush(historyEvents, appointmentid);
  calendar.render();

  $("#add-planning-modal").modal("toggle");
  
  calendar.getEvents().forEach((currentEvent) => {
    currentEvent._def.ui.backgroundColor = RessourcesAllocated(currentEvent);
    currentEvent._def.ui.borderColor = RessourcesAllocated(currentEvent);
    currentEvent.setEnd(currentEvent.end);
  })
  isUpdated = false;
}

function autoAddAllPathway(){
   
    do{
       //Refreshing menu select-appointment
       displayAddPathway();
       //in case of rollBack, we decide to stop everything
       if(selectAppointment!=undefined && document.getElementById('select-appointment').value==selectionAppointmentValue){
           break;
       } 
       //get the id for the previous ifs
       var selectAppointment = document.getElementById('select-appointment');
       var selectionAppointmentValue=[]; 
       selectionAppointmentValue.push(selectAppointment.value);
       //call the adding pathway function
       autoAddPathway(); 
      
     } while(selectAppointment.options.length!=1); //while there are still pathways to be planed
   }
   
/**
* Add Automatically a pathway with good resources (resources that are in the category of resources)
*/
function autoAddPathway(){
  //Get databases informations to add the activities appointment on the calendar
  
  var appointmentid = document.getElementById("select-appointment").value;
  var hResource=JSON.parse(document.getElementById("human").value.replaceAll('3aZt3r',''));
  var workingHours = [];
  for (let i = 0; i < hResource.length; i++) {
    workingHours[hResource[i]['id']] = {'start' : hResource[i]['businessHours'][0]['startTime'], 'end' : hResource[i]['businessHours'][0]['endTime']}
  }
  //Get the appointment choosed by user and the place of the appointment in the listAppointment
  var appointment;
  for (let i = 0; i < listeAppointments.length; i++) {
    if (listeAppointments[i]["id"] == appointmentid) {
      appointment = listeAppointments[i];
      listeAppointments[i].scheduled = true;
    }
  }
  document.getElementById("listeAppointments").value =
    JSON.stringify(listeAppointments);

  //Date of the begining of the pathway 
  var PathwayBeginTime = document.getElementById("timeBegin").value;
  var PathwayBeginDate = new Date(
    new Date(currentDateStr.substring(0, 10) + " " + PathwayBeginTime).getTime() +2*60*60000
  );

  //Get activities of the pathway
  var activitiesInPathwayAppointment = [];
  for (let i = 0; i < listeActivity.length; i++) {
    if (
      "pathway-" + listeActivity[i]["idPathway"] ==
      appointment["idPathway"][0].id
    ) {
      activitiesInPathwayAppointment.push(listeActivity[i]);
    }
  }

  //Get all activity b in the successors to find the ids of firsts activities in pathway
  var successorsActivitybIdList = [];
  for (let i = 0; i < listeSuccessors.length; i++) {
    successorsActivitybIdList.push(listeSuccessors[i].idactivityb);
  }

  //get the first activities of the pathway with ids 
  var firstActivitiesPathway = [];
  for (let i = 0; i < activitiesInPathwayAppointment.length; i++) {
    if (
      successorsActivitybIdList.includes(activitiesInPathwayAppointment[i].id) == false) {
      firstActivitiesPathway.push(activitiesInPathwayAppointment[i]);
    }
  }

  var activitiesA = [];
  //Array that stock all Activities A to be sure that we dont push the same activity A two times. 
  var allActivitiesA = [];
  for (let i = 0; i < firstActivitiesPathway.length; i++) {
    let activityA = { activity: firstActivitiesPathway[i], delaymin: 0 };
    activitiesA.push(activityA);
    allActivitiesA.push(firstActivitiesPathway[i].id);
  }

  var eventScheduledTomorrow=false; 

  var listeActivityHR=Object.values(listeActivityHumanResource)
  for (let j = 0; j < listeActivityHR.length; j++){
    listeActivityHR[j] = listeActivityHR[j][0]
  }
  var listeActivityMR=Object.values(listeActivityMaterialResource)
  for (let j = 0; j < listeActivityMR.length; j++){
    listeActivityMR[j] = listeActivityMR[j][0]
  }

  var tempCategoryOfHR = Object.values(categoryOfHumanResource)
  var categoryOfHR = new Array()
  for (let j = 0; j < tempCategoryOfHR.length; j++){
    for(let k = 0; k < tempCategoryOfHR[j].length; k++){
      categoryOfHR.push({'idcategory': tempCategoryOfHR[j][k].idcategory, 'idresource': tempCategoryOfHR[j][k].idresource, 'categoryname': tempCategoryOfHR[j][k].categoryname})
    }
  }
  var tempCategoryOfMR = Object.values(categoryOfMaterialResource)
  var categoryOfMR = new Array()
  for (let j = 0; j < tempCategoryOfMR.length; j++){
    for(let k = 0; k < tempCategoryOfMR[j].length; k++){
      categoryOfMR.push({'idcategory': tempCategoryOfMR[j][k].idcategory, 'idresource': tempCategoryOfMR[j][k].idresource, 'categoryname': tempCategoryOfMR[j][k].categoryname})
    }
  }

  do {

    //Creating Activities in FullCalendar
    for (let i = 0; i < activitiesA.length; i++) {
      var activityResourcesArray = [];
      var humanAlreadyScheduled = [];
      var materialAlreadyScheduled = [];
      //Find for all Activities of the pathway, the number of Humanresources to define. 
      var categoryHumanResources = [];
      
      for (let j = 0; j < listeActivityHR.length; j++) {
        if (listeActivityHR[j].activityId == activitiesA[i].activity.id) {
          for (let k = 0; k < categoryHumanResource.length; k++) {
            if (listeActivityHR[j].humanResourceCategoryId == categoryHumanResource[k].idcategory &&
                humanAlreadyScheduled.includes(listeActivityHR[j]) == false) {
              humanAlreadyScheduled.push(listeActivityHR[j]);
              categoryHumanResources.push({ id: listeActivityHR[j].humanResourceCategoryId, quantity: listeActivityHR[j].quantity, categoryname: categoryHumanResource[k].categoryname })
            }
          }
        }
      }
      
      if(categoryHumanResources.length == 0){
        categoryHumanResources.push({ id: '', quantity : 1, categoryname: 'h-default'})
      }
      //get the good human resources for this activity
      var humanResources=[];  
      for(let j=0; j<categoryHumanResources.length; j++){
        let countResources=0;
        if(categoryHumanResources[j].id == ''){
          var nbResourceOfcategory = 1
        }
        else{
          var nbResourceOfcategory = 0
          for (k = 0; k < categoryOfHR.length; k++) {
            if (categoryHumanResources[j].id == categoryOfHR[k].idcategory) {
              nbResourceOfcategory++
            }
          };
        }

        var counterNbResourceOfCategory=0; 
        var endTime=PathwayBeginDate.getTime()+activitiesA[i].activity.duration * 60000;
        for(let categoryOfHumanResourceIt=0;categoryOfHumanResourceIt<categoryOfHR.length; categoryOfHumanResourceIt++){
          var allEvents=calendar.getEvents();
          var slotAlreadyScheduled=false;
          if(categoryHumanResources[j].id==categoryOfHR[categoryOfHumanResourceIt].idcategory || categoryHumanResources[j].id==''){
            if(countResources<categoryHumanResources[j].quantity){
              for(allEventsIterator=0; allEventsIterator<allEvents.length; allEventsIterator++){
                //if the resources is from the category and free at this time during all the activity
                if(allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && 
                  allEvents[allEventsIterator].start.getTime()<=(PathwayBeginDate.getTime()) && 
                  allEvents[allEventsIterator].end.getTime()>=(PathwayBeginDate.getTime()) || 
                  allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && 
                  allEvents[allEventsIterator].start.getTime()<=(endTime) &&
                  allEvents[allEventsIterator].end.getTime()>=(endTime) ||
                  allEvents[allEventsIterator]._def.resourceIds.includes('h-default') &&
                  allEvents[allEventsIterator]._def.extendedProps.appointment == appointment.id &&
                  allEvents[allEventsIterator].start.getTime()<=(PathwayBeginDate.getTime()) && 
                  allEvents[allEventsIterator].end.getTime()>=(PathwayBeginDate.getTime()) || 
                  allEvents[allEventsIterator]._def.resourceIds.includes('h-default') &&
                  allEvents[allEventsIterator]._def.extendedProps.appointment == appointment.id &&
                  allEvents[allEventsIterator].start.getTime()<=(endTime) &&
                  allEvents[allEventsIterator].end.getTime()>=(endTime) ||
                  allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] &&
                  allEvents[allEventsIterator].start.getTime()<=(PathwayBeginDate.getTime()) && 
                  allEvents[allEventsIterator].end.getTime()>=(PathwayBeginDate.getTime()) ||
                  allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] &&
                  allEvents[allEventsIterator].start.getTime()<=(endTime) &&
                  allEvents[allEventsIterator].end.getTime()>=(endTime)){
                    slotAlreadyScheduled=true;
                }
              }
              var startDate = new Date(); endDate = new Date()
              startTimeSplit = workingHours[categoryOfHR[categoryOfHumanResourceIt].idresource]['start'].split(':')
              endTimeSplit = workingHours[categoryOfHR[categoryOfHumanResourceIt].idresource]['end'].split(':')
              startDate.setDate(PathwayBeginDate.getDate())
              startDate.setHours(startTimeSplit[0])
              startDate.setMinutes(startTimeSplit[1])
              startDate.setSeconds(0)
              endDate.setDate(PathwayBeginDate.getDate())
              endDate.setHours(endTimeSplit[0])
              endDate.setMinutes(endTimeSplit[1])
              endDate.setSeconds(0)

              if(slotAlreadyScheduled==false && categoryHumanResources[j].id==''){
                humanResources.push('h-default')
                countResources++;
              }
              if(slotAlreadyScheduled==false && categoryHumanResources[j].id!='' && startDate.getTime() + 2*60*60000 <= PathwayBeginDate.getTime() && endDate.getTime()+ 2*60*60000 >= endTime){
                humanResources.push(categoryOfHR[categoryOfHumanResourceIt].idresource);
                countResources++;
              }

              if(slotAlreadyScheduled || (categoryHumanResources[j].id!='' && !(startDate.getTime() + 2*60*60000 <= PathwayBeginDate.getTime() && endDate.getTime()+ 2*60*60000 >= endTime))){
                //check the other ressources of the same category at the same time
                if(counterNbResourceOfCategory<nbResourceOfcategory){
                  counterNbResourceOfCategory++; 
                  //change the begin date to see if ressources are free 20 minutes later
                  if(counterNbResourceOfCategory==nbResourceOfcategory){
                    PathwayBeginDate=new Date(PathwayBeginDate.getTime() + 5*60000);
                    //do the same iteration
                    j--;
                    break;  
                  }
                }   
              }
            }
          }
        }
      }
      //pushing into the resources array of FullCalendar
      for(let j=0; j<humanResources.length; j++){
        activityResourcesArray.push(humanResources[j]);
      }

      //get the good material resources for this activity
      var categoryMaterialResources = [];

      
      for (let j = 0; j < listeActivityMR.length; j++) {
        if (listeActivityMR[j].activityId == activitiesA[i].activity.id) {
          for (let k = 0; k < categoryMaterialResource.length; k++) {
            //if the resources is from the category and free at this time during all the activity
            if (listeActivityMR[j].materialResourceCategoryId == categoryMaterialResource[k].idcategory && materialAlreadyScheduled.includes(listeActivityMR[j]) == false) {
              materialAlreadyScheduled.push(listeActivityMR[j]);
              categoryMaterialResources.push({ id: listeActivityMR[j].materialResourceCategoryId, quantity: listeActivityMR[j].quantity, categoryname: categoryMaterialResource[k].categoryname })
            }
          }
        }
      }
      if(categoryMaterialResources.length == 0){
        categoryMaterialResources.push({ id: '', quantity : 1, categoryname: 'm-default'})
      }
      var materialResources=[];
      for(let j=0; j<categoryMaterialResources.length; j++){
        let countResources=0; 
        if(categoryMaterialResources[j].id == ''){
          var nbResourceOfcategory = 1
        }
        else{
          var nbResourceOfcategory = 0
          for (k = 0; k < categoryOfMR.length; k++) {
            if (categoryMaterialResources[j].id == categoryOfMR[k].idcategory) {
              nbResourceOfcategory++
            }
          };
        }
        var counterNbResourceOfCategory=0; 
        var endTime=PathwayBeginDate.getTime()+activitiesA[i].activity.duration * 60000;
        for(let categoryOfMaterialResourceIt=0;categoryOfMaterialResourceIt<categoryOfMR.length; categoryOfMaterialResourceIt++){
          var allEvents=calendar.getEvents();
          var slotAlreadyScheduled=false;
          if(categoryMaterialResources[j].id==categoryOfMR[categoryOfMaterialResourceIt].idcategory || categoryMaterialResources[j].id==''){ 
            if(countResources<categoryMaterialResources[j].quantity){
              for(allEventsIterator=0; allEventsIterator<allEvents.length; allEventsIterator++){
                if(allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource)==true &&
                  allEvents[allEventsIterator].start.getTime()<=(PathwayBeginDate.getTime()) &&
                  allEvents[allEventsIterator].end.getTime()>=(PathwayBeginDate.getTime()) ||
                  allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource)==true &&
                  allEvents[allEventsIterator].start.getTime()<=(endTime) &&
                  allEvents[allEventsIterator].end.getTime()>=(endTime) ||
                  allEvents[allEventsIterator]._def.resourceIds.includes('m-default') &&
                  allEvents[allEventsIterator]._def.extendedProps.appointment == appointment.id &&
                  allEvents[allEventsIterator].start.getTime()<=(PathwayBeginDate.getTime()) && 
                  allEvents[allEventsIterator].end.getTime()>=(PathwayBeginDate.getTime()) || 
                  allEvents[allEventsIterator]._def.resourceIds.includes('m-default') && 
                  allEvents[allEventsIterator]._def.extendedProps.appointment == appointment.id &&
                  allEvents[allEventsIterator].start.getTime()<=(endTime) &&
                  allEvents[allEventsIterator].end.getTime()>=(endTime) ||
                  allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] &&
                  allEvents[allEventsIterator].start.getTime()<=(PathwayBeginDate.getTime()) && 
                  allEvents[allEventsIterator].end.getTime()>=(PathwayBeginDate.getTime()) ||
                  allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] &&
                  allEvents[allEventsIterator].start.getTime()<=(endTime) &&
                  allEvents[allEventsIterator].end.getTime()>=(endTime)){
                  slotAlreadyScheduled=true;
                }
              }
              if(slotAlreadyScheduled==false && categoryMaterialResources[j].id==''){
                materialResources.push('m-default')
                countResources++;
              }
              if(slotAlreadyScheduled == false && categoryMaterialResources[j].id !=''){
                materialResources.push(categoryOfMR[categoryOfMaterialResourceIt].idresource); 
                countResources++; 
              }
              if(slotAlreadyScheduled){
                //check the other ressources of the same category at the same time
                if(counterNbResourceOfCategory<nbResourceOfcategory){
                  counterNbResourceOfCategory++; 
                  //change the begin date to see if ressources are free 20 minutes later
                  if(counterNbResourceOfCategory==nbResourceOfcategory){
                    PathwayBeginDate=new Date(PathwayBeginDate.getTime() + 5*60000);
                    j--;
                    break;  
                  }
                }   
              }
            }
          }
        }
      }

      for(let j=0; j<materialResources.length; j++){
        activityResourcesArray.push(materialResources[j]); 
      }
      //counting for the ids of events
      countAddEvent++;
      //Add one event in the Calendar
      var event = calendar.addEvent({
        id: "new" + countAddEvent,
        description: "",
        resourceIds: activityResourcesArray,
        title: activitiesA[i].activity.name.replaceAll("3aZt3r", " "),
        start: PathwayBeginDate.getTime() + activitiesA[i].delaymin * 60000,
        end: PathwayBeginDate.getTime() + activitiesA[i].activity.duration * 60000,
        patient: appointment.idPatient[0].lastname + " " + appointment.idPatient[0].firstname,
        appointment: appointment.id,
        activity: activitiesA[i].activity.id,
        type: "activity",
        humanResources: humanResources,
        materialResources: materialResources,
        pathway: appointment.idPathway[0].title.replaceAll("3aZt3r", " "),
        categoryMaterialResource: categoryMaterialResources,
        categoryHumanResource: categoryHumanResources,
        patientId: appointment.idPatient[0].id.split('_')[1]
      });
      console.log(categoryHumanResources, categoryMaterialResources)
    }
    var successorsActivitiesA = [];
    //On reset le tableau successorsActivitiesA
    for (let i = successorsActivitiesA.length - 1; i > 0; i--) {
      successorsActivitiesA.splice(i);
    }
    //Get each Activities B for each Activities A 

    for (let i = 0; i < activitiesA.length; i++) {
      for (let j = 0; j < listeSuccessors.length; j++) {
        if (activitiesA[i].activity.id == listeSuccessors[j].idactivitya) {
          let successor = { delaymin: listeSuccessors[j].delaymin, activityB: listeSuccessors[j].idactivityb };
          successorsActivitiesA.push(successor);
        }
      }
    }

    //Keeping for each différent activityB in successorsActivitiesA the biggest delaymin
    for (let i = 0; i < successorsActivitiesA.length; i++) {
      for (let j = 0; j < successorsActivitiesA.length; j++) {
        if (successorsActivitiesA[i].activityB == successorsActivitiesA[j].activityB && i != j) {
          if (successorsActivitiesA[i].delaymin < successorsActivitiesA[j].delaymin) {
            successorsActivitiesA.splice(i);
          }
          else {
            successorsActivitiesA.splice(j);
          }
        }
      }
    }

    //Put SuccessorsActivitiesA in ActivitiesA
    //Get the longestActivity for all ActivityA.
    var biggestDuration = 0;
    for (let i = 0; i < activitiesA.length; i++) {
      if (biggestDuration < activitiesA[i].activity.duration) {
        biggestDuration = activitiesA[i].activity.duration;
      }
    }
    //Deleting All activities A
    for (let i = activitiesA.length - 1; i >= 0; i--) {
      activitiesA.splice(i);
    }

    //Put activitiesA into AllActivitiesA and ActivitiesB in ActivitiesA
    for (let i = 0; i < successorsActivitiesA.length; i++) {
      for (let j = 0; j < listeActivity.length; j++) {
        if (successorsActivitiesA[i].activityB == listeActivity[j].id) {
          for (let k = 0; k < allActivitiesA.length; k++) {
            if (allActivitiesA.includes(listeActivity[j].id) == false) {
              let activityA = { activity: listeActivity[j], delaymin: successorsActivitiesA[i].delaymin }
              activitiesA.push(activityA);
              allActivitiesA.push(listeActivity[j].id);
            }
          }
        }
      }
    }
    let biggestdelay = 0;
    for (let i = 0; i < activitiesA.length; i++) {
      if (activitiesA[i].delaymin > biggestdelay) {
        biggestdelay = activitiesA[i].delaymin;
      }
    }
    PathwayBeginDate = new Date(PathwayBeginDate.getTime() + biggestDuration * 60000 + biggestdelay * 60000);
    if(PathwayBeginDate.getDate().toString().length==1){
      if(PathwayBeginDate.getDate().toString()!=currentDateStr.substring(9,10)){
        eventScheduledTomorrow=true; 
      }
    }
    else{
      if(PathwayBeginDate.getDate().toString()!=currentDateStr.substring(8,10)){
        eventScheduledTomorrow=true; 
      }
    }
  

  } while (successorsActivitiesA.length != 0);
  verifyHistoryPush(historyEvents, appointmentid);
  calendar.render();
  calendar.getEvents().forEach((currentEvent) => {
    currentEvent._def.ui.backgroundColor = RessourcesAllocated(currentEvent);
    currentEvent._def.ui.borderColor = RessourcesAllocated(currentEvent);
    currentEvent.setEnd(currentEvent.end);
  })
  isUpdated = false;
  if(eventScheduledTomorrow==true){
    document.getElementById('alert-scheduled-tomorrow').style.display='block'; 
    undoEvent(); 
  }
  else{
    document.getElementById('alert-scheduled-tomorrow').style.display='none';
    $("#add-planning-modal").modal("toggle");
  }
}

function getDataAdd(){
  if(document.getElementById('listeActivityHumanResource').value==""){
    var dateStr=document.getElementById("date").value
    $.ajax({
      type: 'POST',
      url: '/GetErrorsInfos',
      data: {dateModified: dateStr },
      dataType: "json",
      success: function (data) {
        document.getElementById("listeActivityHumanResource").value =JSON.stringify(data["listeActivityHumanResources"]);
        document.getElementById("listeActivityMaterialResource").value =JSON.stringify(data["listeActivityMaterialResources"]);
        document.getElementById("categoryOfHumanResource").value =JSON.stringify(data["categoryOfHumanResource"]);
        document.getElementById("categoryOfMaterialResource").value =JSON.stringify(data["categoryOfMaterialResource"]);
        document.getElementById("categoryMaterialResource").value =JSON.stringify(data["categoryMaterialResource"]);
        document.getElementById("categoryHumanResource").value =JSON.stringify(data["categoryHumanResource"]);
        document.getElementById("listeAppointments").value =JSON.stringify(data["listeAppointments"]);
        document.getElementById("listeActivity").value =JSON.stringify(data["listeActivity"]);
        document.getElementById("listeSuccessors").value =JSON.stringify(data["listeSuccessors"]);
        categoryHumanResource=JSON.parse(document.getElementById('categoryHumanResource').value.replaceAll('3aZt3r', ' '));
        categoryMaterialResource=JSON.parse(document.getElementById('categoryMaterialResource').value.replaceAll('3aZt3r', ' '));
        listeActivityHumanResource=JSON.parse(document.getElementById("listeActivityHumanResource").value.replaceAll('3aZt3r',' '));
        listeActivityMaterialResource=JSON.parse(document.getElementById("listeActivityMaterialResource").value.replaceAll('3aZt3r',' '));
        categoryOfHumanResource=JSON.parse(document.getElementById("categoryOfHumanResource").value.replaceAll('3aZt3r',' '));
        categoryOfMaterialResource=JSON.parse(document.getElementById("categoryOfMaterialResource").value.replaceAll('3aZt3r',' '));
        listeActivity=JSON.parse(document.getElementById("listeActivity").value.replaceAll('3aZt3r',' '));
        listeAppointments=JSON.parse(document.getElementById("listeAppointments").value.replaceAll('3aZt3r',' '));
        listeSuccessors=JSON.parse(document.getElementById("listeSuccessors").value.replaceAll('3aZt3r',' '));
        displayAddPathway();
        
      },
      error: function () {
        console.log("error")
        }
    });
  }
  else{
      categoryHumanResource=JSON.parse(document.getElementById('categoryHumanResource').value.replaceAll('3aZt3r', ' '));
      categoryMaterialResource=JSON.parse(document.getElementById('categoryMaterialResource').value.replaceAll('3aZt3r', ' '));
      listeActivityHumanResource=JSON.parse(document.getElementById("listeActivityHumanResource").value.replaceAll('3aZt3r',' '));
      listeActivityMaterialResource=JSON.parse(document.getElementById("listeActivityMaterialResource").value.replaceAll('3aZt3r',' '));
      categoryOfHumanResource=JSON.parse(document.getElementById("categoryOfHumanResource").value.replaceAll('3aZt3r',' '));
      categoryOfMaterialResource=JSON.parse(document.getElementById("categoryOfMaterialResource").value.replaceAll('3aZt3r',' '));
      listeActivity=JSON.parse(document.getElementById("listeActivity").value.replaceAll('3aZt3r',' '));
      listeAppointments=JSON.parse(document.getElementById("listeAppointments").value.replaceAll('3aZt3r',' '));
      listeSuccessors=JSON.parse(document.getElementById("listeSuccessors").value.replaceAll('3aZt3r',' '));
      displayAddPathway();
  }
}
