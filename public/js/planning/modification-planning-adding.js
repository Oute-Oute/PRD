var listeAppointments
var listeActivity
var listeSuccessors
var categoryHumanResource
var categoryMaterialResource
var listeActivityHumanResource
var listeActivityMaterialResource
var categoryOfHumanResource
var categoryOfMaterialResource
var resources
var categories

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
	var firstAppointmentInList = 0;
	var timeBegin
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
			if (nbOptions == 0) {
				timeBegin = document.getElementById("timeBegin");
				if (listeAppointments[i].earliestappointmenttime != "1970-01-01T00:00:00") {
					var earliestappointmenttime = listeAppointments[i].earliestappointmenttime.replaceAll("1970-01-01T", "").substring(0, 16);
				}
				else {
					var earliestappointmenttime = "00:00";
				}
			}
			nbOptions++;
		}
	}
	if (timeBegin != undefined) {
		timeBegin.value = earliestappointmenttime;
	}

	$("#add-planning-modal").modal("show");

	let filter = document.getElementById("filterId"); //get the filter
	filter.style.display = "none"; //hide the filter
	document.getElementById("load-large").style.visibility = "hidden";
}



/**
 * This function is called when clicking on the button 'Valider' into Add Modal. 
 * Add All the Activities from a choosen appointment in the Calendar
 */
function addPathway() {
	document.getElementById("load-large").style.visibility = "visible";
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
			listeActivityHumanResource = Object.values(listeActivityHumanResource);
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
			if (quantityHumanResources == 0) {
				quantityHumanResources = 1;
			}

			var categoryMaterialResources = [];
			listeActivityMaterialResource = Object.values(listeActivityMaterialResource);
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
			if (quantityMaterialResources == 0) {
				quantityMaterialResources = 1;
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


	isUpdated = false;
	document.getElementById("load-large").style.visibility = "hidden";
}

function autoAddAllPathway(iteration = 0) {
	document.getElementById("load-large").style.visibility = "visible";
	do {
		//Refreshing menu select-appointment
		displayAddPathway();
		//in case of rollBack, we decide to stop everything
		if (selectAppointment != undefined && document.getElementById('select-appointment').value == selectionAppointmentValue) {
			break;
		}
		//get the id for the previous ifs
		var selectAppointment = document.getElementById('select-appointment');
		var selectionAppointmentValue = [];
		selectionAppointmentValue.push(selectAppointment.value);
		//call the adding pathway function
		autoAddPathway(iteration);

	} while (selectAppointment.options.length != 1); //while there are still pathways to be planed
	var appointment;
	var appointmentid = document.getElementById("select-appointment").value;
	var allScheduled = true;
	if (iteration == 0) {
		for (let i = 0; i < listeAppointments.length; i++) {
			if (listeAppointments[i]["id"] == appointmentid && listeAppointments[i].scheduled == false) {
				appointment = listeAppointments[i];
				allScheduled = false;
			}
		}
		if (allScheduled == false) {
			autoAddAllPathway(1);
		}
	}
	document.getElementById("load-large").style.visibility = "hidden";
}

/**
* Add Automatically a pathway with good resources (resources that are in the category of resources)
*/
function autoAddPathway(iteration = 0) {
	//Get databases informations to add the activities appointment on the calendar
	document.getElementById("load-large").style.visibility = "visible";
	var appointmentid = document.getElementById("select-appointment").value;
	var hResource = JSON.parse(document.getElementById("human").value.replaceAll('3aZt3r', ''));
	var workingHours = [];
	for (let i = 0; i < hResource.length; i++) {
		workingHours[hResource[i]['id']] = { 'start': hResource[i]['businessHours'][0]['startTime'], 'end': hResource[i]['businessHours'][0]['endTime'] }
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
	var PathwayBeginDate =
		new Date(currentDateStr);
	PathwayBeginDate.setUTCHours(PathwayBeginTime.split(":")[0]);
	PathwayBeginDate.setMinutes(PathwayBeginTime.split(":")[1]);
	PathwayBeginDate.setSeconds(0);

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

	var eventScheduledTomorrow = false;

	var listeActivityHR = Object.values(listeActivityHumanResource)
	for (let j = 0; j < listeActivityHR.length; j++) {
		listeActivityHR[j] = listeActivityHR[j][0]
	}
	var listeActivityMR = Object.values(listeActivityMaterialResource)
	for (let j = 0; j < listeActivityMR.length; j++) {
		listeActivityMR[j] = listeActivityMR[j][0]
	}

	var tempCategoryOfHR = Object.values(categoryOfHumanResource)
	var categoryOfHR = new Array()
	for (let j = 0; j < tempCategoryOfHR.length; j++) {
		for (let k = 0; k < tempCategoryOfHR[j].length; k++) {
			categoryOfHR.push({
				'idcategory': tempCategoryOfHR[j][k].idcategory, 'idresource': tempCategoryOfHR[j][k].idresource,
				'categoryname': tempCategoryOfHR[j][k].categoryname, 'resourcename': tempCategoryOfHR[j][k].resourcename
			})
		}
	}
	var tempCategoryOfMR = Object.values(categoryOfMaterialResource)
	var categoryOfMR = new Array()
	for (let j = 0; j < tempCategoryOfMR.length; j++) {
		for (let k = 0; k < tempCategoryOfMR[j].length; k++) {
			categoryOfMR.push({
				'idcategory': tempCategoryOfMR[j][k].idcategory, 'idresource': tempCategoryOfMR[j][k].idresource,
				'categoryname': tempCategoryOfMR[j][k].categoryname, 'resourcename': tempCategoryOfMR[j][k].resourcename
			})
		}
	}

	do {

		//Creating Activities in FullCalendar
		for (let i = 0; i < activitiesA.length; i++) {
			var isSlotPossible = true;
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

			if (categoryHumanResources.length == 0) {
				categoryHumanResources.push({ id: '', quantity: 1, categoryname: 'h-default' })
			}
			//get the good human resources for this activity
			var humanResources = [];
			for (let j = 0; j < categoryHumanResources.length; j++) {
				let countResources = 0;
				if (categoryHumanResources[j].id == '') {
					var nbResourceOfcategory = 1
				}
				else {
					var nbResourceOfcategory = 0
					for (k = 0; k < categoryOfHR.length; k++) {
						if (categoryHumanResources[j].id == categoryOfHR[k].idcategory) {
							nbResourceOfcategory++
						}
					};
				}
				var counterNbResourceOfCategory = 0;
				var endTime = PathwayBeginDate.getTime() + activitiesA[i].activity.duration * 60000;
				for (let categoryOfHumanResourceIt = 0; categoryOfHumanResourceIt < categoryOfHR.length; categoryOfHumanResourceIt++) {
					var allEvents = calendar.getEvents();
					var slotAlreadyScheduled = false;
					if (categoryHumanResources[j].id == categoryOfHR[categoryOfHumanResourceIt].idcategory || categoryHumanResources[j].id == '') {
						if (countResources < categoryHumanResources[j].quantity) {
							for (allEventsIterator = 0; allEventsIterator < allEvents.length; allEventsIterator++) {


								//if the resources is from the category and not free at this time during all the activity
								if (allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (endTime) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() <= (endTime) && //si le debut de l'activité est avant la fin de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (endTime) ||//si la fin de l'activité est apres la fin de l'evenement
									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement

									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() == (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() == (endTime) || //si la fin de l'activité est apres le debut de l'evenement

									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									(PathwayBeginDate.getTime()) <= allEvents[allEventsIterator].start.getTime() && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (endTime) &&
									allEvents[allEventsIterator].start.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement


									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) &&
									(endTime) <= allEvents[allEventsIterator].start.getTime() || //si la fin de l'activité est apres le debut de l'evenement

									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.resourceIds.includes('h-default') &&
									allEvents[allEventsIterator]._def.extendedProps.appointment == appointment.id &&
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) &&
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) ||
									allEvents[allEventsIterator]._def.resourceIds.includes('h-default') &&
									allEvents[allEventsIterator]._def.extendedProps.appointment == appointment.id &&
									allEvents[allEventsIterator].start.getTime() <= (endTime) &&
									allEvents[allEventsIterator].end.getTime() >= (endTime) ||
									allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (endTime) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() <= (endTime) && //si le debut de l'activité est avant la fin de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (endTime) ||//si la fin de l'activité est apres la fin de l'evenement
									allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (endTime) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] && //si la ressource est dans la categorie
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() <= (endTime) //si la fin de l'activité est apres le debut de l'evenement
								) {
									slotAlreadyScheduled = true;
								}
								if (allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfHR[categoryOfHumanResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() == (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() == (endTime)  //si la fin de l'activité est apres le debut de l'evenement
								) {
									slotAlreadyScheduled = true;
								}
							}
							var startDate = new Date(); endDate = new Date()
							startTimeSplit = workingHours[categoryOfHR[categoryOfHumanResourceIt].idresource]['start'].split(':')
							endTimeSplit = workingHours[categoryOfHR[categoryOfHumanResourceIt].idresource]['end'].split(':')
							startDate.setDate(PathwayBeginDate.getDate())
							startDate.setFullYear(PathwayBeginDate.getFullYear())
							startDate.setMonth(PathwayBeginDate.getMonth())
							startDate.setUTCHours(startTimeSplit[0])
							startDate.setMinutes(startTimeSplit[1])
							startDate.setSeconds(0)
							endDate.setDate(PathwayBeginDate.getDate())
							endDate.setFullYear(PathwayBeginDate.getFullYear())
							endDate.setMonth(PathwayBeginDate.getMonth())
							if (iteration == 0) {
								endDate.setUTCHours(endTimeSplit[0])
								endDate.setMinutes(endTimeSplit[1])
								endDate.setSeconds(0)
							} else {
								endDate.setUTCHours(23)
								endDate.setMinutes(59)
								endDate.setSeconds(59)
							}
							var nextDay = new Date();
							nextDay.setDate(PathwayBeginDate.getDate() + 1)
							nextDay.setFullYear(PathwayBeginDate.getFullYear())
							nextDay.setMonth(PathwayBeginDate.getMonth())
							nextDay.setUTCHours(0)
							nextDay.setMinutes(0)
							nextDay.setSeconds(0)


							if (slotAlreadyScheduled == false && categoryHumanResources[j].id == '') {
								humanResources.push({ 'id': 'h-default', 'title': '' })
								countResources++;
							}
							if (slotAlreadyScheduled == false && categoryHumanResources[j].id != '' && startDate.getTime() <= PathwayBeginDate.getTime() && endDate.getTime() >= endTime) {
								humanResources.push({ 'id': categoryOfHR[categoryOfHumanResourceIt].idresource, 'title': categoryOfHR[categoryOfHumanResourceIt].resourcename });
								countResources++;
							}
							if (endDate.getTime() >= nextDay.getTime()) {
								break;
							}
							if (slotAlreadyScheduled || (categoryHumanResources[j].id != '' && !(startDate.getTime() <= PathwayBeginDate.getTime() && endDate.getTime() >= endTime))) {
								//check the other ressources of the same category at the same time
								if (counterNbResourceOfCategory < nbResourceOfcategory) {
									counterNbResourceOfCategory++;
									//change the begin date to see if ressources are free 20 minutes later
									if (counterNbResourceOfCategory == nbResourceOfcategory) {
										PathwayBeginDate = new Date(PathwayBeginDate.getTime() + 5 * 60000);
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
			for (let j = 0; j < humanResources.length; j++) {
				activityResourcesArray.push(humanResources[j].id);
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
			if (categoryMaterialResources.length == 0) {
				categoryMaterialResources.push({ id: '', quantity: 1, categoryname: 'm-default' })
			}
			var materialResources = [];
			for (let j = 0; j < categoryMaterialResources.length; j++) {
				let countResources = 0;
				if (categoryMaterialResources[j].id == '') {
					var nbResourceOfcategory = 1
				}
				else {
					var nbResourceOfcategory = 0
					for (k = 0; k < categoryOfMR.length; k++) {
						if (categoryMaterialResources[j].id == categoryOfMR[k].idcategory) {
							nbResourceOfcategory++
						}
					};
				}
				var counterNbResourceOfCategory = 0;
				var endTime = PathwayBeginDate.getTime() + activitiesA[i].activity.duration * 60000;
				for (let categoryOfMaterialResourceIt = 0; categoryOfMaterialResourceIt < categoryOfMR.length; categoryOfMaterialResourceIt++) {
					var allEvents = calendar.getEvents();
					var slotAlreadyScheduled = false;
					if (categoryMaterialResources[j].id == categoryOfMR[categoryOfMaterialResourceIt].idcategory || categoryMaterialResources[j].id == '') {
						if (countResources < categoryMaterialResources[j].quantity) {
							for (allEventsIterator = 0; allEventsIterator < allEvents.length; allEventsIterator++) {
								if (allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (endTime) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement
									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() <= (endTime) && //si le debut de l'activité est avant la fin de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (endTime) ||//si la fin de l'activité est apres la fin de l'evenement
									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement

									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].start.getTime() == (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() == (endTime) || //si la fin de l'activité est apres le debut de l'evenement

									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource) && //si la ressource est dans la categorie
									(PathwayBeginDate.getTime()) <= allEvents[allEventsIterator].start.getTime() && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() >= (endTime) &&
									allEvents[allEventsIterator].start.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement


									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) &&
									(endTime) <= allEvents[allEventsIterator].start.getTime() || //si la fin de l'activité est apres le debut de l'evenement

									allEvents[allEventsIterator]._def.resourceIds.includes(categoryOfMR[categoryOfMaterialResourceIt].idresource) && //si la ressource est dans la categorie
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) && //si le debut de l'activité est avant le debut de l'evenement
									allEvents[allEventsIterator].end.getTime() <= (endTime) || //si la fin de l'activité est apres le debut de l'evenement

									allEvents[allEventsIterator]._def.resourceIds.includes('m-default') &&
									allEvents[allEventsIterator]._def.extendedProps.appointment == appointment.id &&
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) &&
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) ||
									allEvents[allEventsIterator]._def.resourceIds.includes('m-default') &&
									allEvents[allEventsIterator]._def.extendedProps.appointment == appointment.id &&
									allEvents[allEventsIterator].start.getTime() <= (endTime) &&
									allEvents[allEventsIterator].end.getTime() >= (endTime) ||
									allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] &&
									allEvents[allEventsIterator].start.getTime() <= (PathwayBeginDate.getTime()) &&
									allEvents[allEventsIterator].end.getTime() >= (PathwayBeginDate.getTime()) ||
									allEvents[allEventsIterator]._def.extendedProps.patientId == appointment.idPatient[0].id.split('_')[1] &&
									allEvents[allEventsIterator].start.getTime() <= (endTime) &&
									allEvents[allEventsIterator].end.getTime() >= (endTime)) {
									slotAlreadyScheduled = true;
								}
							}
							if (slotAlreadyScheduled == false && categoryMaterialResources[j].id == '') {
								materialResources.push({ 'id': 'm-default', 'title': '' })
								countResources++;
							}
							if (slotAlreadyScheduled == false && categoryMaterialResources[j].id != '') {
								materialResources.push({ 'id': categoryOfMR[categoryOfMaterialResourceIt].idresource, 'title': categoryOfMR[categoryOfMaterialResourceIt].resourcename });
								countResources++;
							}
							if (slotAlreadyScheduled) {
								//check the other ressources of the same category at the same time
								if (counterNbResourceOfCategory < nbResourceOfcategory) {
									counterNbResourceOfCategory++;
									//change the begin date to see if ressources are free 20 minutes later
									if (counterNbResourceOfCategory == nbResourceOfcategory) {
										isSlotPossible = false;
										break;
									}
								}
							}
						}
					}
				}
			}
			if (isSlotPossible) {
				for (let j = 0; j < materialResources.length; j++) {
					activityResourcesArray.push(materialResources[j].id);
				}

				if (categoryHumanResources[0].categoryname == "h-default") {
					categoryHumanResources[0].quantity = 0
				}
				if (categoryMaterialResources[0].categoryname == "m-default") {
					categoryMaterialResources[0].quantity = 0
				}
				//counting for the ids of events
				countAddEvent++;
				//Add one event in the Calendar
				var start;
				var end;
				if (PathwayBeginDate.getTimezoneOffset() == -60) {
					start = PathwayBeginDate.getTime() + 3600000
					end = PathwayBeginDate.getTime() + 3600000
				}
				if (PathwayBeginDate.getTimezoneOffset() == -120) {
					start = PathwayBeginDate.getTime()
					end = PathwayBeginDate.getTime()
				}
				else {
					start = PathwayBeginDate.getTime()
					end = PathwayBeginDate.getTime()
				}
				var event = calendar.addEvent({
					id: "new" + countAddEvent,
					description: "",
					resourceIds: activityResourcesArray,
					title: activitiesA[i].activity.name.replaceAll("3aZt3r", " "),
					start: start,
					end: end + activitiesA[i].activity.duration * 60000,
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
			}
			else {
				PathwayBeginDate = new Date(PathwayBeginDate.getTime() + 5 * 60000);
				i--;
			}
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
		if (PathwayBeginDate.getDate().toString().length == 1) {
			if (PathwayBeginDate.getDate().toString() != currentDateStr.substring(9, 10)) {
				eventScheduledTomorrow = true;
			}
		}
		else {
			if (PathwayBeginDate.getDate().toString() != currentDateStr.substring(8, 10)) {
				eventScheduledTomorrow = true;
			}
		}


	} while (successorsActivitiesA.length != 0);
	verifyHistoryPush(historyEvents, appointmentid);
	calendar.render();

	isUpdated = false;
	if (eventScheduledTomorrow == true) {
		document.getElementById('alert-scheduled-tomorrow').style.display = 'block';
		var appointmentid = document.getElementById("select-appointment").value;
		for (let i = 0; i < listeAppointments.length; i++) {
			if (listeAppointments[i]["id"] == appointmentid) {
				appointment = listeAppointments[i];
				listeAppointments[i].scheduled = false;
			}
		}
		undoEvent();
	}
	else {
		document.getElementById('alert-scheduled-tomorrow').style.display = 'none';
		$("#add-planning-modal").modal("toggle");
	}
	document.getElementById("load-large").style.visibility = "hidden";
}

function getDataAdd() {
	document.getElementById("load-large").style.visibility = "visible";
	if (document.getElementById('listeActivityHumanResource').value == "") {
		var dateStr = document.getElementById("date").value
		$.ajax({
			type: 'POST',
			url: '/GetErrorsInfos',
			data: { dateModified: dateStr },
			dataType: "json",
			success: function (data) {
				document.getElementById("listeActivityHumanResource").value = JSON.stringify(data["listeActivityHumanResources"]);
				document.getElementById("listeActivityMaterialResource").value = JSON.stringify(data["listeActivityMaterialResources"]);
				document.getElementById("categoryOfHumanResource").value = JSON.stringify(data["categoryOfHumanResource"]);
				document.getElementById("categoryOfMaterialResource").value = JSON.stringify(data["categoryOfMaterialResource"]);
				document.getElementById("categoryMaterialResource").value = JSON.stringify(data["categoryMaterialResource"]);
				document.getElementById("categoryHumanResource").value = JSON.stringify(data["categoryHumanResource"]);
				document.getElementById("listeAppointments").value = JSON.stringify(data["listeAppointments"]);
				document.getElementById("listeActivity").value = JSON.stringify(data["listeActivity"]);
				document.getElementById("listeSuccessors").value = JSON.stringify(data["listeSuccessors"]);
				categoryHumanResource = JSON.parse(document.getElementById('categoryHumanResource').value.replaceAll('3aZt3r', ' '));
				categoryMaterialResource = JSON.parse(document.getElementById('categoryMaterialResource').value.replaceAll('3aZt3r', ' '));
				listeActivityHumanResource = JSON.parse(document.getElementById("listeActivityHumanResource").value.replaceAll('3aZt3r', ' '));
				listeActivityMaterialResource = JSON.parse(document.getElementById("listeActivityMaterialResource").value.replaceAll('3aZt3r', ' '));
				categoryOfHumanResource = JSON.parse(document.getElementById("categoryOfHumanResource").value.replaceAll('3aZt3r', ' '));
				categoryOfMaterialResource = JSON.parse(document.getElementById("categoryOfMaterialResource").value.replaceAll('3aZt3r', ' '));
				listeActivity = JSON.parse(document.getElementById("listeActivity").value.replaceAll('3aZt3r', ' '));
				listeAppointments = JSON.parse(document.getElementById("listeAppointments").value.replaceAll('3aZt3r', ' '));
				listeSuccessors = JSON.parse(document.getElementById("listeSuccessors").value.replaceAll('3aZt3r', ' '));
				displayAddPathway();

			},
			error: function () {
				console.log("error")
			}
		});
	}
	else {
		categoryHumanResource = JSON.parse(document.getElementById('categoryHumanResource').value.replaceAll('3aZt3r', ' '));
		categoryMaterialResource = JSON.parse(document.getElementById('categoryMaterialResource').value.replaceAll('3aZt3r', ' '));
		listeActivityHumanResource = JSON.parse(document.getElementById("listeActivityHumanResource").value.replaceAll('3aZt3r', ' '));
		listeActivityMaterialResource = JSON.parse(document.getElementById("listeActivityMaterialResource").value.replaceAll('3aZt3r', ' '));
		categoryOfHumanResource = JSON.parse(document.getElementById("categoryOfHumanResource").value.replaceAll('3aZt3r', ' '));
		categoryOfMaterialResource = JSON.parse(document.getElementById("categoryOfMaterialResource").value.replaceAll('3aZt3r', ' '));
		listeActivity = JSON.parse(document.getElementById("listeActivity").value.replaceAll('3aZt3r', ' '));
		listeAppointments = JSON.parse(document.getElementById("listeAppointments").value.replaceAll('3aZt3r', ' '));
		listeSuccessors = JSON.parse(document.getElementById("listeSuccessors").value.replaceAll('3aZt3r', ' '));
		displayAddPathway();
	}
}

/**
 * @function CloseImportModal
 * @brief Function that is called when the user wants to close the import modal
 * @description Due to an unknow error, the modal is not closing when the button dismiss the modal. Instead, this function is used to close the modal.
 * @returns {void}
 * @version 1.0
 * @since 1.0
 * @author Thomas Blumstein
 */
function CloseImportModal() {
	$("#error-import-modal").modal("toggle");
	$("#error-import-modal").modal("toggle");
}

/**
 * @function externalPlanner
 * @brief Function that is called when the user wants to use the external planner
 * @description This function is called when the user wants to use the external planner. It will get the data from the database and display the modal to use it.
 * @returns {void}
 * @version 1.0
 * @since 1.0
 * @author Thomas Blumstein
 * 
 */
function externalPlanner() {
	var hResource = JSON.parse(document.getElementById("human").value.replaceAll('3aZt3r', ''));//get the human resources 
	hResource.forEach(resource => {
		cat = resource["categories"];
		cat.forEach(categorie => {
			categorie["id"] = "human_" + categorie["id"]; //foreach resources, we add the type of resource to the id of the category to avoid confilct between human and material resources
		});
	});
	var mResource = JSON.parse(document.getElementById("material").value.replaceAll('3aZt3r', ''));
	mResource.forEach(resource => {
		cat = resource["categories"];
		cat.forEach(categorie => {
			categorie["id"] = "material_" + categorie["id"]; //foreach resources, we add the type of resource to the id of the category to avoid confilct between human and material resources
		});
	});
	resources = hResource.concat(mResource);//we add the human and material resources to the resources array
	resources.forEach(resource => {
		if (resource["id"] == "h-default" || resource["id"] == "m-default") {//if the resource is the default resource, we remove it from the array
			index = resources.indexOf(resource);
			resources.splice(index, 1);
		}
	});
	categoryHumanResource.forEach(categorie => {
		categorie["idcategory"] = "human_" + categorie["idcategory"]//foreach categories, we add the type of resource to the id of the category to avoid confilct between human and material resources
	});
	categoryMaterialResource.forEach(categorie => {
		categorie["idcategory"] = "material_" + categorie["idcategory"]//foreach categories, we add the type of resource to the id of the category to avoid confilct between human and material resources
	});
	categories = categoryHumanResource.concat(categoryMaterialResource);//we add the human and material categories to the categories array

	$("#auto-add-modal").modal("show");//we display the modal to use the external planner
}

/**
 * @function displayAddPathway
 * @brief Function that is called when the user wants to export the data to the external planner
 * @description This function is called when the user wants to export the data to the external planner. It will get the data from the database,create a .msrcmp file and download it.
 * @returns {void}
 * @version 1.0
 * @since 1.0
 * @author Thomas Blumstein
 */
function exportData() {
	fileContent = listeAppointments.length.toString() + "\t" + (resources.length).toString() + "\t" + (categories.length) + "\n" + "\n"; //number of appointments, number of resources, number of categories as first line of the file
	//Creation of the first paragraph of the file
	//This pragraph contains the start and end time of the business hours of each resource and the categories of each resource
	resources.forEach(resource => {
		if (resource["businessHours"] != undefined) {
			businessHoursStart = new Date("1970-01-01T" + resource["businessHours"][0]["startTime"] + ":00");//we get the start and end time of the business hours of the resource
			businessHoursEnd = new Date("1970-01-01T" + resource["businessHours"][0]["endTime"] + ":00");//we get the start and end time of the business hours of the resource
			startHour = businessHoursStart.getHours() * 60 + businessHoursStart.getMinutes();//we convert the time to minutes
			endHour = businessHoursEnd.getHours() * 60 + businessHoursEnd.getMinutes();//we convert the time to minutes
		}
		//if the resource has no business hours, we set the start and end time to 0 and 1440 (24h in minutes)
		else {
			startHour = 0;
			endHour = 1440;
		}
		categoriesOfResources = resource["categories"];//we get the categories of the resource
		categoriesContent = "";//we create a string that will contain the categories of the resource
		categories.forEach(category => {
			inResource = false;
			categoriesOfResources.forEach(categoryOfResource => {
				if (category["idcategory"] == categoryOfResource["id"]) {
					inResource = true;
				}
			})
			categoriesContent += +inResource + "\t";//if the category is not in the resource, we add 0 to the string
		})
		categoriesContent = categoriesContent.slice(0, -1);//we remove the last tabulation
		fileContent += startHour + "\t" + endHour + "\t" + categoriesContent + "\n"//we add the start and end time of the business hours and the categories of the resource to the file
	});
	fileContent += "\n";
	//Creation of the others paragraphs of the file
	//Thess paragraphs contains the data of each appointment
	listeAppointments.forEach(appointment => {
		earliestappointmenttime = new Date(appointment["earliestappointmenttime"]);//we get the earliest and latest appointment time of the appointment
		earliestappointmenthour = earliestappointmenttime.getHours() * 60 + earliestappointmenttime.getMinutes();//we convert the time to minutes
		latestappointmenttime = new Date(appointment["latestappointmenttime"]);//we get the earliest and latest appointment time of the appointment
		latestappointmenthour = latestappointmenttime.getHours() * 60 + latestappointmenttime.getMinutes();//we convert the time to minutes
		activities = appointment["idPathway"][0]["activities"];//we get the activities of the appointment
		fileContent += activities.length + "\t" + earliestappointmenthour + "\t" + latestappointmenthour + "\n";//we add the number of activities, the earliest and latest appointment time of the appointment to the file
		categoriesContent = "";
		humanPerAppointment = []; //liste des humains dans l'activité
		materialPerAppointment = []; //liste des matériels dans l'activité
		activities.forEach(activity => {
			activity["materialResources"].forEach(material => {
				if (material["id"] != "h-default") {
					materialPerAppointment.push("material_" + material["id"]);//we add the id of the material to the list of materials of the appointment
				}
			});
			activity["humanResources"].forEach(human => {
				if (human["id"] != "h-default") {
					humanPerAppointment.push("human_" + human["id"]);//we add the id of the human to the list of humans of the appointment
				}

			});
		})
		resourcesPerAppointment = humanPerAppointment.concat(materialPerAppointment);//we add the list of humans and materials of the appointment to the list of resources of the appointment
		categories.forEach(category => {
			inActivity = false;//if the category is in the appointment or not
			resourcesPerAppointment.forEach(categoryPerAppointment => {
				if (category["idcategory"] == categoryPerAppointment) {
					inActivity = true;//set to true if the category is in the appointment
				}
			})
			categoriesContent += +inActivity + "\t";//add 1 if the category is in the appointment, 0 otherwise
		})
		categoriesContent = categoriesContent.slice(0, -1);//we remove the last tabulation
		fileContent += categoriesContent + "\n";//we add the categories of the appointment to the file
		successorsPerAppointment = [];
		//we add the data of each activity of the appointment to the file
		activities.forEach(activity => {

			activityResources = [];
			resourcesQuantities = [];
			activity["materialResources"].forEach(material => {
				if (material["id"] != "h-default") {
					key = "material_" + material["id"];
					activityResources.push({ [key]: material["quantity"] });//we add the id of the material to the list of materials of the activity
				}
			});
			activity["humanResources"].forEach(human => {
				if (human["id"] != "h-default") {
					key = "human_" + human["id"];
					activityResources.push({ [key]: human["quantity"] });//we add the id of the human to the list of humans of the activity
				}
			});
			fileContent += activity["duration"] + "\t";//we add the duration of the activity to the file
			categories.forEach(category => {

				inActivity = 0;//if the category is in the activity or not
				activityResources.forEach(activityResource => {
					if (category["idcategory"] == Object.keys(activityResource)[0]) {
						inActivity = activityResource[Object.keys(activityResource)[0]];//set to the number of resources of the category in the activity
					}
				})
				fileContent += inActivity + "\t";//add 1 if the category is in the activity, 0 otherwise
			})
			//we add the data of the successors of the activity to end of the line
			fileContent += activity["successors"].length + "\t";//we add the number of successors of the activity to the file
			activity["successors"].forEach(successor => {
				successorsPerAppointment.push(successor);//we add the successor to the list of successors of the appointment
			})
			successors = activity["successors"];//we get the successors of the activity
			successors.forEach(successor => {
				indexOfSuccessor = activities.findIndex(activity => activity["id"] == successor["idactivityb"]);//we get the index of the successor in the list of activities
				fileContent += indexOfSuccessor + "\t";//we add the index of the successor to the file
			})
			fileContent = fileContent.slice(0, -1);//we remove the last tabulation
			fileContent += "\n";//we add a new line
		})
		//we add the data of the successors of the appointment to the file in a subpart
		fileContent += successorsPerAppointment.length + "\n";//we add the number of successors of the appointment to the file
		successorsPerAppointment.forEach(successors => {
			if (successors["id"] != undefined) {
				indexOfSuccessorA = activities.findIndex(activity => activity["id"] == successors["idactivitya"]);//we get the index of the successor in the list of activities
				indexOfSuccessorB = activities.findIndex(activity => activity["id"] == successors["idactivityb"]);//we get the index of the successor in the list of activities
				delaymin = successors["delaymin"];//we get the minimum delay of the successor
				delaymax = successors["delaymax"];//we get the maximum delay of the successor
				fileContent += indexOfSuccessorA + "\t" + indexOfSuccessorB + "\t" + delaymin + "\t" + delaymax + "\n";//we add the data of the successor to the file
			}
		})
		fileContent += "\n";//we add a new line
	})

	//we create a file with the content of the fileContent variable
	var fileToDownload = document.body.appendChild(
		document.createElement("a")
	);
	date = document.getElementById("date").value;//we get the date of the appointment
	date = date.replace("T12:00:00", "");//we remove the time of the date
	fileToDownload.download = "data_" + date + ".msrcmp";//we set the name of the file with the date of the appointment
	fileToDownload.href = "data:text," + fileContent;//we set the content of the file
	fileToDownload.click();//we download the file
}

/**
 * @function importData
 * @description import the data of the file
 * @returns {void}
 * @version 1.0
 * @since 1.0
 * @author Thomas Blumstein
 */
function importData() {
	filepicker = document.getElementById("filepicker");
	date = document.getElementById("date").value;//we get the date of the appointment
	date = date.replace("T12:00:00", "");//we remove the time of the date
	error = 0
	fr = new FileReader();
	fileContent = "";
	fileName = filepicker.files[0].name
	if (!fileName.includes(date)) {
		error = 1
	}
	fr.onload = function () {
		fileContent = fr.result;
		console.log(fileContent)
		i = 0;
		words = [];
		appointments = []
		lines = fileContent.split("\r\n");
		numberOfResources = resources.length;
		//numberOfResources = 8 // TODO: remove this test line
		lines.forEach(line => {
			if (line != "" && line.split("\t").length == 3 || line.split("\t").length == numberOfResources + 1 && error == 0) {
				words[i] = line.split("\t");
			}
			else if (line != "" && line.split("\t").length != numberOfResources + 1 && line.split("\t").length != 3 && error == 0) {
				error = 2
			}
			i++;
		})
		if (error == 0) {
			words.forEach(word => {
				word.forEach(w => {
					if (isNaN(w)) {
						error = 3
					}
				})

				if (word.length != 3 && error == 0) {
					for (i = 1; i < word.length; i++) {
						if (word[i] != "1" && word[i] != "0") {
							error = 4
						}
					}

				}
			})
		}
		i = 0
		j = 0
		while (i < words.length && error == 0) {
			appointment = []
			while (words[i] != undefined && error == 0) {
				appointment.push(words[i])
				i++
			}
			appointments.push(appointment)
			i++
			j++
		}
		if (appointments.length != listeAppointments.length && error == 0) {
			error = 5 //TODO : uncomment this line
		}
		console.log(listeAppointments)
		for (i = 0; i < appointments.length; i++) {
			if (appointments[i].length - 1 != listeAppointments[i]["idPathway"][0]["activities"].length && error == 0) {
				console.log(appointments[i].length - 1)
				console.log(listeAppointments[i]["idPathway"][0]["activities"].length)
				error = 6
			}
		}
		console.log(appointments)
		if (error != 0) {
			errorImport = document.getElementById("error-import")
			switch (error) {
				case 1:
					errorImport.innerHTML = "Le fichier ne correspond pas à la date voulue. </br> Veuillez vérifier le fichier."
					break;
				case 2:
					errorImport.innerHTML = "Il y a une erreur sur le nombre de catégories dans le fichier. </br> Il doit y avoir "
						+ numberOfResources + " ressources possibles dans le fichier. </br> Veuillez vérifier le fichier."
					break;
				case 3:
					errorImport.innerHTML = "Il y a une erreur sur le format du fichier. </br> Il doit y avoir que des nombres dans le fichier. </br> Veuillez vérifier le fichier."
					break;
				case 4:
					errorImport.innerHTML = "Il y a une erreur sur le format du fichier. </br> Au moins une ressource est utilisée plus d'une fois par la même activité. </br> Veuillez vérifier le fichier."
					break;
				case 5:
					errorImport.innerHTML = "Il y a une erreur sur le nombre de rendez-vous dans le fichier, il doit y avoir "
						+ listeAppointments.length + " rendez-vous. </br> Veuillez vérifier le fichier."
					break;
				case 6:
					errorImport.innerHTML = "Il y a une erreur sur le nombre d'activités d'au moins un rendez-vous. </br> Veuillez vérifier le fichier."
					break;
			}
			alert = document.getElementById("error-import-modal")
			alert.style.display = "block"
		}
		else {
			countAddEvent = 0
			console.log(resources)
			for (i = 0; i < appointments.length; i++) {
				appFromList = listeAppointments[i]
				//console.log(appFromList)
				//console.log(appointments[i])
				for (j = 1; j < appointments[i].length; j++) {
					startTime = appointments[i][j][0]
					hour = Math.floor(startTime / 60)
					if (hour < 10) {
						hour = "0" + hour
					}
					minute = startTime % 60
					if (minute < 10) {
						minute = "0" + minute
					}
					start = new Date(date + "T" + hour + ":" + minute + ":00")
					activityResourcesArray = []
					appointment.forEach(app => {
						console.log(app)
						for(k = 1; k < app.length; k++){
							console.log(app[k])
							if (app[k] == 1) {
								activityResourcesArray.push(resources[k-1]["id"])
								console.log(activityResourcesArray)
							}
						}
					})
					console.log(appFromList["idPathway"][0]["activities"][j - 1])
					calendar.addEvent({
						id: "new" + countAddEvent,
						description: "",
						resourceIds: activityResourcesArray,
						title: appFromList["idPathway"][0]["activities"][j - 1]["title"],
						start: start,
						end: new Date(start.getTime() + appFromList["idPathway"][0]["activities"][j - 1]["duration"] * 60000),
						patient: appFromList["idPatient"][0]["lastname"] + " " + appFromList["idPatient"][0]["firstname"],
						appointment: appFromList["id"],
						activity: appFromList["idPathway"][0]["activities"][j - 1]["id"],
						type: "activity",
						//humanResources: humanResources,
						//materialResources: materialResources,
						pathway: appFromList["idPathway"][0]["title"],
						//categoryMaterialResources: categoryMaterialResources,
						//categoryHumanResources: categoryHumanResources,
						patientId: appFromList["idPatient"][0]["id"].split('_')[1],
					})
					countAddEvent++
					console.log(activityResourcesArray)
					while(activityResourcesArray.length > 0){
						activityResourcesArray.pop()
					}
					console.log(activityResourcesArray)
				}
			}
		}
		console.log(calendar.getEvents())
		$("#auto-add-modal").modal("hide")
		$("#add-planning-modal").modal("hide")
	}
	fr.readAsText(filepicker.files[0]);

}
// var event = calendar.addEvent({
// 	id: "new" + countAddEvent,
// 	description: "",
// 	resourceIds: activityResourcesArray,
// 	title: activitiesA[i].activity.name.replaceAll("3aZt3r", " "),
// 	start: start,
// 	end: end + activitiesA[i].activity.duration * 60000,
// 	patient: appointment.idPatient[0].lastname + " " + appointment.idPatient[0].firstname,
// 	appointment: appointment.id,
// 	activity: activitiesA[i].activity.id,
// 	type: "activity",
// 	humanResources: humanResources,
// 	materialResources: materialResources,
// 	pathway: appointment.idPathway[0].title.replaceAll("3aZt3r", " "),
// 	categoryMaterialResource: categoryMaterialResources,
// 	categoryHumanResource: categoryHumanResources,
// 	patientId: appointment.idPatient[0].id.split('_')[1]
// });