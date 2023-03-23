/**
 * @file tests.js
 * @fileoverview This file contains the functions used to display the test page.
 * @author Thomas Blumstein
 * @version 1.0
 */

var currentDateStr = $_GET("date");


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

document.addEventListener("DOMContentLoaded", function () {// When the page is loaded
	getSimulations();// Get the simulations
	document.getElementById("load-large").style.visibility = "hidden";// Hide the loading screen
});


/**
 * @function newSimulation
 * @brief This function is called when we want to create a new simulation
 * @returns nothing
 */
function NewSimulation() {// Redirect to /newSimulation
	//redirect to /serializeDB
	window.location.href = "/newSimulation";
}

/**
 * @function saveSimulation
 * @brief This function is called when we want to save the current simulation
 * @returns nothing
 */
function SaveSimulation() {// Save the simulation
	$.ajax({//AJAX request
		url: "/saveSimulation",
		type: "GET",
		success: function (data) {// If the request is successful
			location.reload();// Reload the page
		},
		error: function (data) {// If the request failed
			console.log(data);// Log the error
		},
	});
}

/**
 * @function getSimulations
 * @brief This function is called when we want to get the simulations
 * @returns nothing
*/
function getSimulations() {// Get the simulations
	console.log(document.getElementById("simInfos").value)// Log the value of the hidden input
	data = JSON.parse(document.getElementById("simInfos").value)// Parse the value of the hidden input
	console.log(data)// Log the value of the hidden input
	for (var i = 0; i < data.length; i++) {// For each simulation
		testsGrid = document.getElementById('all-tests');// Get the div where the simulations will be displayed
		id = data[i]['id']// Get the id of the simulation
		console.log(id)// Log the id of the simulation
		var newTest = document.createElement('div');// Create a new div
		if (data[i]['isCurrent'] == true) {// If the simulation is the current one
			newTest.className = 'current-test-container';// Set the class of the div to current-test-container
		} else {// If the simulation is not the current one
			newTest.className = 'test-container';// Set the class of the div to test-container
		}
		newTest.id = 'test-container-' + id;// Set the id of the div to test-container-<id>
		newTest.setAttribute('onclick', "showChangePopup(" + id + ")")// Set the onclick attribute of the div to showChangePopup(<id>)
		newTestTitle = document.createElement('h3');// Create a new h3
		newTestTitle.className = 'test-title';// Set the class of the h3 to test-title
		newTestTitle.id = 'test-title-' + id;// Set the id of the h3 to test-title-<id>
		date = new Date(data[i]["simulationDateTime"]["date"])// Get the date of the simulation
		newTestTitle.innerHTML = 'Simulation du ' + date.toLocaleDateString('fr-FR') + ' à ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });// Set the innerHTML of the h3 to the date of the simulation
		newTest.appendChild(newTestTitle);// Add the h3 to the div
		newTestData = document.createElement('table');// Create a new table
		newTestData.className = 'test-data';// Set the class of the table to test-data
		newTestData.id = 'test-data-' + id;// Set the id of the table to test-data-<id>
		newTestFirstLine = document.createElement('tr');// Create a new tr
		newTestFirstLine.className = 'test-line';// Set the class of the tr to test-line
		newTestFirstLine.id = 'test-line-' + id;// Set the id of the tr to test-line-<id>
		newTestRHCell = document.createElement('td');// Create a new td
		newTestRHCell.className = 'test-cell';// Set the class of the td to test-cell
		newTestRHCell.id = 'test-cell-' + id;// Set the id of the td to test-cell-<id>
		newTestRHCell.innerHTML = data[i]["numberOfHumanResources"] + ' Ressources Humaines';// Set the innerHTML of the td to the number of human resources
		newTestPatientsCell = document.createElement('td');// Create a new td
		newTestPatientsCell.className = 'test-cell';// Set the class of the td to test-cell
		newTestPatientsCell.id = 'test-cell-' + id;// Set the id of the td to test-cell-<id>
		newTestPatientsCell.innerHTML = data[i]["numberOfPatients"] + ' Patients';// Set the innerHTML of the td to the number of patients
		newTestFirstLine.appendChild(newTestRHCell);// Add the td to the tr
		newTestFirstLine.appendChild(newTestPatientsCell);// Add the td to the tr
		newTestData.appendChild(newTestFirstLine);// Add the tr to the table
		newTestSecondLine = document.createElement('tr');// Create a new tr
		newTestSecondLine.className = 'test-line';// Set the class of the tr to test-line
		newTestSecondLine.id = 'test-line-' + id;// Set the id of the tr to test-line-<id>
		newTestRMCell = document.createElement('td');// Create a new td
		newTestRMCell.className = 'test-cell';// Set the class of the td to test-cell
		newTestRMCell.id = 'test-cell-' + id;// Set the id of the td to test-cell-<id>
		newTestRMCell.innerHTML = data[i]["numberOfMaterialResources"] + ' Ressources Matérielles';// Set the innerHTML of the td to the number of material resources
		newTestBinCell = document.createElement('td');// Create a new td
		newTestBinCell.className = 'bin-cell';// Set the class of the td to bin-cell
		newTestBinCell.id = 'test-cell-' + id;// Set the id of the td to test-cell-<id>
		newBinImg = document.createElement('img');// Create a new img
		newBinImg.className = 'bin';// Set the class of the img to bin
		newBinImg.id = 'bin-' + id;// Set the id of the img to bin-<id>
		newBinImg.src = '/img/bin.svg';// Set the src of the img to /img/bin.svg
		newBinImg.setAttribute("name", date.toLocaleDateString('fr-FR') + ' à ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }))// Set the name of the img to the date of the simulation
		newBinImg.addEventListener('click', function (e) {// Add an event listener to the img
			id = e.target.id.split('-')[1]// Get the id of the simulation
			simName = e.target.name;// Get the name of the simulation
			e.stopPropagation();// Stop the propagation of the event to the parent
			showDeletePopup('test-container-' + id, simName);// Show the delete popup
		});
		newTestBinCell.appendChild(newBinImg);// Add the img to the td
		newTestSecondLine.appendChild(newTestRMCell);// Add the td to the tr
		newTestSecondLine.appendChild(newTestBinCell);// Add the td to the tr
		newTestData.appendChild(newTestSecondLine);// Add the tr to the table
		newTest.appendChild(newTestData);// Add the table to the div
		testsGrid.appendChild(newTest);// Add the div to the grid

	}

}

/**
 * @function DeleteSimulation
 * @description Delete a simulation
 * @param {int} idToDelete  The id of the simulation to delete
 * @returns nothing
 */
function DeleteSimulation(idToDelete) {
	document.getElementById("load-large").style.visibility = "visible";// Show the loading screen
	$.ajax({// Send a POST request to /deleteSimulation
		url: "/deleteSimulation",
		type: "POST",
		data: {
			'id': idToDelete
		},
		success: function (data) {// If the request is successful
			location.reload();// Reload the page
		},
		error: function (data) {// If the request is not successful
			if (data.status == 200) {// If the status is 200
				location.reload();// Reload the page
			}
			else {// If the status is not 200
				console.log(data)// Log the data
			}
		},
	});
	$('#modal-delete').modal('hide')// Hide the delete popup

}

/**
 * @function showDeletePopup
 * @description Show the delete popup
 * @param {int} id  The id of the simulation
 * @returns nothing
 */
function showDeletePopup(id) {
	deleteMsg = document.getElementById("delete-msg")// Get the delete message
	deleteMsg.innerHTML = "Êtes-vous sûr de vouloir supprimer la simulation du " + simName + " ?"// Set the delete message
	idNumber = id.split('-')[2]// Get the id of the simulation
	document.getElementById("delete-test-button").setAttribute('onclick', "DeleteSimulation('" + idNumber + "')");// Set the onclick of the delete button to DeleteSimulation(<id>)
	$('#modal-delete').modal('show')// Show the delete popup
}


/**
 * @function ChangeSimulation
 * @description Change the simulation
 * @param {int} id  The id of the simulation
 * @returns nothing
 */
function showChangePopup(id) {
	document.getElementById("change-test-button").setAttribute('onclick', "ChangeSimulation('" + id + "')");// Set the onclick of the change button to ChangeSimulation(<id>)
	$('#modal-change').modal('show')// Show the change popup
}

/**
 * @function ChangeSimulation
 * @description Change the simulation
 * @param {int} id  The id of the simulation
 * @returns nothing
 */
function ChangeSimulation(id) {
	document.getElementById("load-large").style.visibility = "visible";// Show the loading screen
	$.ajax({
		url: "/changeSimulation",
		type: "POST",
		data: {
			'id': id
		},
		success: function (data) {// If the request is successful
			location.reload();// Reload the page
		},
		error: function (data) {// If the request is not successful
			if (data.status == 200) {// If the status is 200
				location.reload();// Reload the page
			}
			else {
				console.log(data)
			}
		},
	});
	$('#modal-change').modal('hide')// Hide the change popup
}