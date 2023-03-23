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
	var hResource = JSON.parse(document.getElementById("human").value.replaceAll('3aZt3r', ' '));//get the human resources 
	hResource.forEach(resource => {
		cat = resource["categories"];
		cat.forEach(categorie => {
			categorie["id"] = "human_" + categorie["id"]; //foreach resources, we add the type of resource to the id of the category to avoid confilct between human and material resources
		});
	});
	var mResource = JSON.parse(document.getElementById("material").value.replaceAll('3aZt3r', ' '));
	mResource.forEach(resource => {
		resource.businessHours = [{ startTime: "00:00", endTime: "23:59" }]
		cat = resource["categories"];
		cat.forEach(categorie => {
			categorie["id"] = "material_" + categorie["id"]; //foreach resources, we add the type of resource to the id of the category to avoid confilct between human and material resources
		});
	});
	resources = hResource.concat(mResource);//we add the human and material resources to the resources array
	eventsAlreadyPlanned = calendar.getEvents()
	if (eventsAlreadyPlanned.length > 0) {
		eventsAlreadyPlanned.forEach(event => {
			startTime = event.start.getHours() * 60 + event.start.getMinutes()
			endTime = event.end.getHours() * 60 + event.end.getMinutes()
			if (event.start.getTimezoneOffset() == -120) {
				startTime -= 120
				endTime -= 120
			}
			else {
				startTime -= 60
				endTime -= 60
			}
			event._def.resourceIds.forEach(resource => {
				for (i = 0; i < resources.length; i++) {
					startBusinessHour = resources[i]["businessHours"][0]["startTime"].split(":")
					endBusinessHour = resources[i]["businessHours"][0]["endTime"].split(":")
					startBusinessTime = parseInt(startBusinessHour[0]) * 60 + parseInt(startBusinessHour[1])
					endBusinessTime = parseInt(endBusinessHour[0]) * 60 + parseInt(endBusinessHour[1])
					if (resources[i]["id"] != 'h-default' && resources[i]["id"] != 'm-default') {
						if (resources[i]["id"] == resource && startTime >= startBusinessTime && endTime <= endBusinessTime) {
							currentDate = document.getElementById("date").value;//we get the date of the appointment
							currentDate = currentDate.replaceAll("T12:00:00", "")//we remove the time
							currentDate = currentDate.split("-")//we split the date to get the year, month and day
							currentDateInDays = currentDate[0] * 365 + currentDate[1] * 30 + currentDate[2]//we convert the date to days
							eventStartDate = event.start.toISOString()//we get the start date of the event
							eventStartDate = eventStartDate.split("T")//we split the datetime to get the date
							eventStartDate = eventStartDate[0].split("-")//we split the date to get the year, month and day
							eventStartInDays = eventStartDate[0] * 365 + eventStartDate[1] * 30 + eventStartDate[2]//we convert the date to days
							eventEndDate = event.end.toISOString()//we get the end date of the event
							eventEndDate = eventEndDate.split("T")//we split the datetime to get the date
							eventEndDate = eventEndDate[0].split("-")//we split the date to get the year, month and day
							eventEndInDays = eventEndDate[0] * 365 + eventEndDate[1] * 30 + eventEndDate[2]//we convert the date to days
							removeFirst = false
							removeLast = false
							if (eventStartInDays < currentDateInDays && currentDateInDays < eventEndInDays) {//if the event surround the current date, we remove the resource from the resources array
								resources.splice(i, 1)
								break;
							}
							else if (currentDateInDays == eventStartInDays && currentDateInDays != eventEndInDays) {//if the event start on the current date and finish after
								eventStartHour = event.start.getHours()
								eventEndHour = 23
								eventStartMinute = event.start.getMinutes()
								eventEndMinute = 59
								removeLast = true//we remove the last event of the resource (the resource is not available after the event)
							}
							else if (currentDateInDays != eventStartInDays && currentDateInDays == eventEndInDays) {//if the event start before the current date and finish on the current date
								eventEndHour = event.end.getHours()
								eventEndMinute = event.end.getMinutes()
								eventStartHour = "00"
								eventStartMinute = "00"
								removeFirst = true//we remove the first event of the resource (the resource is not available before the event)
							}
							else {//if the event start and finish on the current date
								eventStartMinute = event.start.getMinutes()
								eventEndMinute = event.end.getMinutes()
								if (eventStartMinute < 10) {
									eventStartMinute = "0" + eventStartMinute
								}
								if (eventEndMinute < 10) {
									eventEndMinute = "0" + eventEndMinute
								}
								eventStartHour = event.start.getHours()
								eventEndHour = event.end.getHours()
							}
							if (event.start.getTimezoneOffset() == -120) {//correct the time if the timezone is UTC+2
								eventStartHour -= 2
								eventEndHour -= 2
							}
							else {//correct the time if the timezone is UTC+1
								eventStartHour -= 1
								eventEndHour -= 1
							}
							if (eventStartHour < 10) {//add a 0 if the hour is less than 10
								eventStartHour = "0" + eventStartHour
							}
							if (eventEndHour < 10) {//add a 0 if the hour is less than 10
								eventEndHour = "0" + eventEndHour
							}
							//we create a copy of the resource and we change the business hours of the resource
							if (resources[i]["businessHours"][0]["startTime"] != eventStartHour + ":" + eventStartMinute) {
								resourceCopy = JSON.parse(JSON.stringify(resources[i]))
								resourceCopy["businessHours"][0]["startTime"] = eventEndHour + ":" + eventEndMinute
								resources[i]["businessHours"][0]["endTime"] = eventStartHour + ":" + eventStartMinute
								resources.push(resourceCopy)
							}
							else {//if the resource is not available before the event, we change the business hours of the resource
								resources[i]["businessHours"][0]["startTime"] = eventEndHour + ":" + eventEndMinute
							}
							if (removeFirst) {//if the resource is not available before the end of the event, we remove the first event of the resource
								resources.splice(i, 1)
							}
							if (removeLast) {//if the resource is not available after the start of the event, we remove the last event of the resource
								resources.splice(resources.length - 1, 1)
							}
							break;
						}
					}
				}
			})
		})
	}

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
 * @function exportData
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
		error = 1//there is an error on the file name
	}
	fr.onload = function () {
		fileContent = fr.result;
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
				error = 2//there is an error on the file content
			}
			i++;
		})
		if (error == 0) {
			words.forEach(word => {
				word.forEach(w => {
					if (isNaN(w)) {
						error = 3//there is an error on the file content
					}
				})
				if (word.length != 3 && error == 0) {
					for (i = 1; i < word.length; i++) {
						if (word[i] != "1" && word[i] != "0") {
							error = 4//there is an error on the file content
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
			error = 5//there is an error on the number of appointments
		}
		for (i = 0; i < appointments.length; i++) {
			if (appointments[i].length - 1 != listeAppointments[i]["idPathway"][0]["activities"].length && error == 0) {
				error = 6//there is an error on the number of activities
			}
		}
		if (error != 0) {//if there is an error
			errorImport = document.getElementById("error-import")
			switch (error) {//we display the correct error
				case 1:
					errorImport.innerHTML = "Le fichier ne correspond pas à la date voulue. </br> Veuillez vérifier le fichier."
					break;
				case 2:
					errorImport.innerHTML = "Il y a une erreur sur les ressources dans le fichier. (Soit le nombre de ressource est incohérent soit certaines ressources sont devenues en partie indisponibles entre temps)</br> Veuillez vérifier le fichier."
					break;
				case 3:
					errorImport.innerHTML = "Il y a une erreur sur le format du fichier. </br> Il ne doit y avoir que des nombres dans le fichier. </br> Veuillez vérifier le fichier."
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
		else {//if there is no error
			countAddEvent = 0
			for (i = 0; i < appointments.length; i++) {//for each appointment
				appFromList = listeAppointments[i]
				for (j = 1; j < appointments[i].length; j++) {//for each activity of the appointment
					endTime = appointments[i][j][0]
					hour = Math.floor(endTime / 60)
					//format the hour
					if (hour < 10) {
						hour = "0" + hour
					}
					//format the minutes
					minute = endTime % 60
					if (minute < 10) {
						minute = "0" + minute
					}
					//correct the time zone
					end = new Date(date + "T" + hour + ":" + minute + ":00")
					if (end.getTimezoneOffset() == -120) {
						end = new Date(end.getTime() + 2 * 60 * 60 * 1000)
					}
					else if (end.getTimezoneOffset() == -60) {
						end = new Date(end.getTime() + 1 * 60 * 60 * 1000)
					}
					//get the resources of the event
					activityResourcesArray = []
					humanResourcesArray = []
					materialResourcesArray = []
					for (k = 1; k < appointments[i][j].length; k++) {
						if (appointments[i][j][k] == "1") {
							activityResourcesArray.push(resources[k - 1]["id"])//set the resources of the event
							if (resources[k - 1]["id"].includes("human")) {
								humanResourcesArray.push(resources[k - 1])//set the human resources of the event (for the details)
							}
							else {
								materialResourcesArray.push(resources[k - 1])//set the material resources of the event (for the details)
							}
						}

						//check if dafault resources needed
						humans = appFromList["idPathway"][0]["activities"][j - 1]["humanResources"]
						materials = appFromList["idPathway"][0]["activities"][j - 1]["materialResources"]
						humans.forEach(human => {
							if (human["id"] == "h-default") {
								humanResourcesArray.push("h-default")
								activityResourcesArray.push("h-default")
							}
						});
						materials.forEach(material => {
							if (material["id"] == "m-default") {
								materialResourcesArray.push("m-default")
								activityResourcesArray.push("m-default")
							}
						})
					}
					//create the event and add it to the calendar
					calendar.addEvent({
						id: "new" + countAddEvent,
						description: "",
						resourceIds: activityResourcesArray,
						title: appFromList["idPathway"][0]["activities"][j - 1]["title"],
						start: new Date(end.getTime() - appFromList["idPathway"][0]["activities"][j - 1]["duration"] * 60000),
						end: end,
						patient: appFromList["idPatient"][0]["lastname"] + " " + appFromList["idPatient"][0]["firstname"],
						appointment: appFromList["id"],
						activity: appFromList["idPathway"][0]["activities"][j - 1]["id"],
						type: "activity",
						humanResources: humanResourcesArray,
						materialResources: materialResourcesArray,
						pathway: appFromList["idPathway"][0]["title"],
						categoryMaterialResource: appFromList["idPathway"][0]["activities"][j - 1]['materialResources'],
						categoryHumanResource: appFromList["idPathway"][0]["activities"][j - 1]['humanResources'],
						patientId: appFromList["idPatient"][0]["id"].split('_')[1],
					})
					activityResourcesArray = []
					countAddEvent++
				}
			}
			let appointmentSelection = document.getElementById("select-appointment");
			//Reset all options from the list
			for (let i = appointmentSelection.options.length - 1; i >= 0; i--) {
				appointmentSelection.options.remove(i);
			}
			//set All the event to "Scheduled"
			for (let i = 0; i < listeAppointments.length; i++) {
				listeAppointments[i].scheduled = true;
			}
			document.getElementById("listeAppointments").value = JSON.stringify(listeAppointments);
		}
		$("#auto-add-modal").modal("hide")
		$("#add-planning-modal").modal("hide")
	}
	fr.readAsText(filepicker.files[0]);
}