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

document.addEventListener("DOMContentLoaded", function () {
	getSimulations();
	document.getElementById("load-large").style.visibility = "hidden";
});

function NewSimulation() {
	//redirect to /serializeDB
	window.location.href = "/newSimulation";
}

function SaveSimulation() {
	$.ajax({
		url: "/saveSimulation",
		type: "GET",
		success: function (data) {
			location.reload();
		},
		error: function (data) {
			console.log(data);
		},
	});
}

function getSimulations() {
	console.log(document.getElementById("simInfos").value)
	data = JSON.parse(document.getElementById("simInfos").value)
	console.log(data)
	for (var i = 0; i < data.length; i++) {
		testsGrid = document.getElementById('all-tests');
		id = data[i]['id']
		console.log(id)
		var newTest = document.createElement('div');
		if (data[i]['isCurrent'] == true) {
			newTest.className = 'current-test-container';
		} else {
			newTest.className = 'test-container';
		}
		newTest.id = 'test-container-' + id;
		newTest.setAttribute('onclick', "showChangePopup(" + id + ")")
		newTestTitle = document.createElement('h3');
		newTestTitle.className = 'test-title';
		newTestTitle.id = 'test-title-' + id;
		date = new Date(data[i]["simulationDateTime"]["date"])
		newTestTitle.innerHTML = 'Simulation du ' + date.toLocaleDateString('fr-FR') + ' à ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
		newTest.appendChild(newTestTitle);
		newTestData = document.createElement('table');
		newTestData.className = 'test-data';
		newTestData.id = 'test-data-' + id;
		newTestFirstLine = document.createElement('tr');
		newTestFirstLine.className = 'test-line';
		newTestFirstLine.id = 'test-line-' + id;
		newTestRHCell = document.createElement('td');
		newTestRHCell.className = 'test-cell';
		newTestRHCell.id = 'test-cell-' + id;
		newTestRHCell.innerHTML = data[i]["numberOfHumanResources"] + ' Ressources Humaines';
		newTestPatientsCell = document.createElement('td');
		newTestPatientsCell.className = 'test-cell';
		newTestPatientsCell.id = 'test-cell-' + id;
		newTestPatientsCell.innerHTML = data[i]["numberOfPatients"] + ' Patients';
		newTestFirstLine.appendChild(newTestRHCell);
		newTestFirstLine.appendChild(newTestPatientsCell);
		newTestData.appendChild(newTestFirstLine);
		newTestSecondLine = document.createElement('tr');
		newTestSecondLine.className = 'test-line';
		newTestSecondLine.id = 'test-line-' + id;
		newTestRMCell = document.createElement('td');
		newTestRMCell.className = 'test-cell';
		newTestRMCell.id = 'test-cell-' + id;
		newTestRMCell.innerHTML = data[i]["numberOfMaterialResources"] + ' Ressources Matérielles';
		newTestBinCell = document.createElement('td');
		newTestBinCell.className = 'bin-cell';
		newTestBinCell.id = 'test-cell-' + id;
		newBinImg = document.createElement('img');
		newBinImg.className = 'bin';
		newBinImg.id = 'bin-' + id;
		newBinImg.src = '/img/bin.svg';
		newBinImg.setAttribute("name", date.toLocaleDateString('fr-FR') + ' à ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }))
		newBinImg.addEventListener('click', function (e) {
			id = e.target.id.split('-')[1]
			simName = e.target.name;
			e.stopPropagation();
			showDeletePopup('test-container-' + id, simName);
		});
		newTestBinCell.appendChild(newBinImg);
		newTestSecondLine.appendChild(newTestRMCell);
		newTestSecondLine.appendChild(newTestBinCell);
		newTestData.appendChild(newTestSecondLine);
		newTest.appendChild(newTestData);
		testsGrid.appendChild(newTest);
		id++;
	}

}

function DeleteSimulation(idToDelete) {
	document.getElementById("load-large").style.visibility = "visible";
	$.ajax({
		url: "/deleteSimulation",
		type: "POST",
		data: {
			'id': idToDelete
		},
		success: function (data) {
			location.reload();
		},
		error: function (data) {
			if (data.status == 200) {
				location.reload();
			}
			else {
				console.log(data)
			}
		},
	});
	$('#modal-delete').modal('hide')

}
function showDeletePopup(id) {
	deleteMsg = document.getElementById("delete-msg")
	deleteMsg.innerHTML = "Êtes-vous sûr de vouloir supprimer la simulation du " + simName + " ?"
	idNumber = id.split('-')[2]
	document.getElementById("delete-test-button").setAttribute('onclick', "DeleteSimulation('" + idNumber + "')");
	$('#modal-delete').modal('show')
}

function showChangePopup(id) {
	document.getElementById("change-test-button").setAttribute('onclick', "ChangeSimulation('" + id + "')");
	$('#modal-change').modal('show')
}

function ChangeSimulation(id) {
	document.getElementById("load-large").style.visibility = "visible";
	$.ajax({
		url: "/changeSimulation",
		type: "POST",
		data: {
			'id': id
		},
		success: function (data) {
			location.reload();
		},
		error: function (data) {
			if (data.status == 200) {
				location.reload();
			}
			else {
				console.log(data)
			}
		},
	});
	$('#modal-change').modal('hide')
}