/**
 * @file statistics.js
 * @fileoverview This file contains the functions used to display the statistics page.
 * @author Thomas Blumstein
 * @version 1.0
 */

var currentDateStr = $_GET("date");
var numberOfErrors = 0;

/**
 * @function $_GET
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

/**
 * @function document.addEventListener
 * @brief This function is called when the page is loaded
 * @param {string} "DOMContentLoaded" The event is fired when the initial HTML document has been completely loaded and parsed, without waiting for stylesheets, images, and subframes to finish loading.
 * @param {function} function () This function is called when the page is loaded
 * @description This function is called when the page is loaded. It calls the functions to get the number of HR, MR, patients, the calendar, the errors and the waiting times and to display them.
 * @see getNumberOfHR
 * @see getNumberOfMR
 * @see getNumberOfPatients
 * @see getWaitingTimes
 * @see getoccupancyRates
 * @see createCalendar
 * @see GetDataErrors 
*/
document.addEventListener("DOMContentLoaded", function () {
	getNumberOfHR();
	getNumberOfMR();
	getNumberOfPatients();
	createCalendar("Ressources Humaines", "stats", "00:20:00");
	stats = true;
	GetDataErrors()
	getWaitingTimes()
	getoccupancyRates("RH")
});


/**
 * @function getNumberOfHR
 * @brief This function is called when we want the number of HR
 * @description This function is called when we want the number of HR. It gets the number of HR from the database and display it.
 * @returns void
 * @author Thomas Blumstein
 * @version 1.0
 */
function getNumberOfHR() {
	hr = JSON.parse(document.getElementById("human").value)
	document.getElementById("numberOfHR").innerHTML = hr.length - 1
}

/**
 * @function getNumberOfMR
 * @brief This function is called when we want the number of MR
 * @description This function is called when we want the number of MR. It gets the number of MR from the database and display it.
 * @returns void
 * @author Thomas Blumstein
 * @version 1.0
 */
function getNumberOfMR() {
	mr = JSON.parse(document.getElementById("material").value)
	document.getElementById("numberOfMR").innerHTML = mr.length - 1
}

/**
 * @function getNumberOfPatients
 * @brief This function is called when we want the number of patients
 * @description This function is called when we want the number of patients. It gets the number of patients from the database and display it.
 * @returns void
 * @author Thomas Blumstein
 * @version 1.0
 */
function getNumberOfPatients() {
	appointments = JSON.parse(document.getElementById("appointments").value)
	document.getElementById("numberOfPatients").innerHTML = appointments.length
}

/**
 * @function geetNumberOfErrors
 * @brief This function is called when we want the number of errors
 * @param {int} numberOfErrors the number of errors
 * @description This function is called when we want the number of errors. It gets the number of errors from the database and display it.
 * @returns void
 * @author Thomas Blumstein
 * @version 1.0
 */
function getNumberOFErrors(numberOfErrors) {
	document.getElementById("numberOfErrors").innerHTML = numberOfErrors
}

/**
 * @function getWaitingTimes
 * @brief This function is called when we want the waiting times
 * @description This function is called when we want the waiting times. It gets the waiting times from the database and display it.
 * @returns void
 * @author Thomas Blumstein
 * @version 2.0
 */
function getWaitingTimes() {
	eventsByAppointment = []
	calendar.getEvents().forEach(event => {
		if (eventsByAppointment[event.extendedProps.appointment] == undefined)
			eventsByAppointment[event.extendedProps.appointment] = []
		eventsByAppointment[event.extendedProps.appointment].push(event)
	})
	waitingTimesArray = []
	eventsByAppointment.forEach(appointment => {
		if (appointment != undefined) {
			appointment.sort((a, b) => (a.start > b.start) ? 1 : -1)
			for (i = 0; i < appointment.length - 1; i++) {
				diff =  appointment[i + 1].start-appointment[i].end
				diff=diff/1000/60
				waitingTimesArray.push(diff)
			}
		}
	})
	console.log(waitingTimesArray.length)
	min = Math.min(...waitingTimesArray)//get the minimum waiting time
	max = Math.max(...waitingTimesArray)//get the maximum waiting time
	mean = Math.round(waitingTimesArray.reduce((a, b) => a + b, 0) / waitingTimesArray.length)//get the mean waiting time
	console.log(min, max, mean)
	if (waitingTimesArray.length==0) {//if there is no activity planned
		document.getElementById("minWaitingTime").innerHTML = "Aucune activité planifiée"//display the message "Aucune activité planifiée"
		document.getElementById("maxWaitingTime").innerHTML = "Aucune activité planifiée"//display the message "Aucune activité planifiée"
		document.getElementById("meanWaitingTime").innerHTML = "Aucune activité planifiée"//display the message "Aucune activité planifiée"

	}
	else {//if there is an activity planned
		document.getElementById("minWaitingTime").innerHTML = min + " minutes"//display the minimum waiting time
		document.getElementById("maxWaitingTime").innerHTML = max + " minutes"//display the maximum waiting time
		document.getElementById("meanWaitingTime").innerHTML = mean + " minutes"//display the mean waiting time
	}
}

/**
 * @function changeResources
 * @brief This function is called when we want to change the resources displayed in the table
 * @description This function is called when we want to change the resources use in the table. It calls the function @see getoccupancyRates with the type of the resources we want to display.
 * @returns void
 * @author Thomas Blumstein
 * @version 1.0
 * @see getoccupancyRates
 */
function changeResources() {
	selected = document.getElementById("selectResources").options[document.getElementById("selectResources").selectedIndex].text;
	if (selected == "Ressources humaines") {//if we want to display the human resources
		getoccupancyRates("RH")//call the function getoccupancyRates with the type "RH"
	}
	else if (selected == "Ressources matérielles") {//if we want to display the material resources
		getoccupancyRates("RM")//call the function getoccupancyRates with the type "RM"
	}
	else if (selected == "Catégories de ressources humaines") {//if we want to display the human categories
		getoccupancyRates("CRH")//call the function getoccupancyRates with the type "CRH"
	}
	else if (selected == "Catégories de ressources matérielles") {//if we want to display the material categories
		getoccupancyRates("CRM")//call the function getoccupancyRates with the type "CRM"
	}
}

/**
 * @function getoccupancyRates
 * @brief This function is called when we want the occupancy rates
 * @param {string} type the type of the resources we want to display from @see changeResources
 * @description This function is called when we want the occupancy rates. It gets the occupancy rates from the database and display it.
 * @returns void
 * @author Thomas Blumstein
 * @version 1.0
 * @see changeResources
 */
function getoccupancyRates(type) {
	rates = JSON.parse(document.getElementById("occupancyRates").value)//get the occupancy rates from the database
	if (type == "RH") {//if we want to display the human resources
		rates = rates.humanResources//get the human resources rates from the json
	}
	else if (type == "RM") {//if we want to display the material resources
		rates = rates.materialResources//get the material resources rates from the json
	}
	else if (type == "CRH") {//if we want to display the human categories
		rates = rates.humanCategories//get the human categories rates from the json
	}
	else if (type == "CRM") {//if we want to display the material categories
		rates = rates.materialCategories//get the material categories rates from the json
	}

	var trs = document.querySelectorAll('#resourcesTable tr:not(.headerPatient)');//get all the rows of the table
	for (let i = 0; i < trs.length; i++) {
		trs[i].style.display = 'none';//hide all the previous rows
	}
	if (rates != "Aucune ressource planifiée") {//if there is a resource planned
		document.getElementById("noResources").hidden = true//hide the message "Aucune ressource planifiée"
		for (i = 0; i <= Object.keys(rates)[Object.keys(rates).length - 1]; i++) {//for each resource
			if (rates[i] != undefined) {//if the resource exists
				table = document.getElementById('resourcesTable');//get the table
				var tr = document.createElement('tr');//create a new row
				tr.className = "occupancy-row";//add the class occupancy-row
				table.appendChild(tr);//add the row to the table
				var resourcename = document.createElement('td');//create a new cell
				resourcename.className = "occupancy-cell";//add the class occupancy-cell
				resourcename.innerHTML = rates[i].title;//add the name of the resource
				tr.appendChild(resourcename);//add the cell to the row
				occupancies = rates[i].occupancies//get the occupancies of the resource
				numberOfResources = rates[i].numberOfResources//get the number of resources
				var firstSlot = document.createElement('td');//create a new cell
				firstSlot.className = "occupancy-cell";//add the class occupancy-cell
				firstSlot.innerHTML = (Math.round(((occupancies[0].occupancy / numberOfResources) / 180) * 100) + "%");//add the occupancy rate of the first slot
				tr.appendChild(firstSlot);//add the cell to the row
				var secondSlot = document.createElement('td');//create a new cell
				secondSlot.className = "occupancy-cell";//add the class occupancy-cell
				secondSlot.innerHTML = (Math.round(((occupancies[1].occupancy / numberOfResources) / 180) * 100) + "%");//add the occupancy rate of the second slot
				tr.appendChild(secondSlot);//add the cell to the row
				var thirdSlot = document.createElement('td');//create a new cell
				thirdSlot.className = "occupancy-cell";//add the class occupancy-cell
				thirdSlot.innerHTML = (Math.round(((occupancies[2].occupancy / numberOfResources) / 180) * 100) + "%");//add the occupancy rate of the third slot
				tr.append(thirdSlot);//add the cell to the row
				var fourthSlot = document.createElement('td');//create a new cell
				fourthSlot.className = "occupancy-cell";//add the class occupancy-cell
				fourthSlot.innerHTML = (Math.round(((occupancies[3].occupancy / numberOfResources) / 180) * 100) + "%");//add the occupancy rate of the fourth slot
				tr.appendChild(fourthSlot);//add the cell to the row
				var fifthSlot = document.createElement('td');//create a new cell
				fifthSlot.className = "occupancy-fifth-cell";//add the class occupancy-fifth-cell
				fifthSlot.innerHTML = (Math.round(((occupancies[4].occupancy / numberOfResources) / 180) * 100) + "%");//add the occupancy rate of the fifth slot
				tr.appendChild(fifthSlot);//add the cell to the row
				var totalSlot = document.createElement('td');//create a new cell
				totalSlot.className = "occupancy-total-cell";//add the class occupancy-total-cell
				var totalOccupancy = 0;//create a variable to store the total occupancy
				for (j = 0; j < 5; j++) {//for each slot
					totalOccupancy += occupancies[j].occupancy//add the occupancy of the slot to the total occupancy
				}
				totalSlot.innerHTML = (Math.round(((totalOccupancy / numberOfResources) / 900) * 100) + "%");//add the total occupancy rate
				tr.appendChild(totalSlot);  //add the cell to the row
			}
		}
	}
	else {//if there is no resource
		document.getElementById("noResources").hidden = false;//display the message "Aucune ressource planifiée"
	}
}


/**
 * @brief This function is called when we want to create or recreate the calendar
 * @param {*} typeResource the type of resources to display (Patients, Resources...)
 */
function createCalendar(typeResource, useCase, resourcesToDisplay = undefined) {
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
		scrollTimeReset: false, //dont change the view of the calendar
		locale: "fr", //set the language in french
		timeZone: "Europe/Paris", //set the timezone for France
		selectable: false, //set the calendar to be selectable
		editable: false, //set the calendar not to be editable
		height: $(window).height() * 0.75, //set the height of the calendar to fit with a standard display
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
					categories = temp["categories"];
					var categoriesStr = ""; //create a string with the human resources names
					var categoriesArray = [];
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
						categories: categoriesArray,
						type: temp["type"], //set the type
					});
				}
			}
			break;
		case "Ressources Matérielles": //if we want to display by the resources
			if (resourcesToDisplay != undefined) {
				var tempArray = resourcesToDisplay
			}
			else {
				var tempArray = JSON.parse(
					document.getElementById("material").value.replaceAll("3aZt3r", " ")
				); //get the data of the resources
			}
			for (var i = 0; i < tempArray.length; i++) {
				var temp = tempArray[i]; //get the resources data
				if (temp != undefined) {
					if (calendar.getResourceById(temp["id"]) == null) {
						//if the resource is not already in the calendar
						categories = temp["categories"];
						var categoriesStr = ""; //create a string with the human resources names
						var categoriesArray = [];
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
							categories: categoriesArray,
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

function changeDate(dateStr) {
	window.location.assign("/statistics?date=" + dateStr)
}

