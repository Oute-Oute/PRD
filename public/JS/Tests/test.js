/**
 * @file tests.js
 * @fileoverview This file contains the functions used to display the test page.
 * @author Thomas Blumstein
 * @version 1.0
 */

numberOfTests = 0;
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

function NewTest() {
	testsGrid = document.getElementById('all-tests');
	numberOfTests = document.getElementsByClassName('test-container').length
	var newTest = document.createElement('div');
	newTest.className = 'test-container';
	newTest.id = 'test-container-' + numberOfTests;
	newTestTitle = document.createElement('h2');
	newTestTitle.className = 'test-title';
	newTestTitle.id = 'test-title-' + numberOfTests;
	newTestTitle.innerHTML = 'Test n°' + (numberOfTests + 1);
	newTest.appendChild(newTestTitle);
	newTestData = document.createElement('table');
	newTestData.className = 'test-data';
	newTestData.id = 'test-data-' + numberOfTests;
	newTestFirstLine = document.createElement('tr');
	newTestFirstLine.className = 'test-line';
	newTestFirstLine.id = 'test-line-' + numberOfTests;
	newTestRHCell = document.createElement('td');
	newTestRHCell.className = 'test-cell';
	newTestRHCell.id = 'test-cell-' + numberOfTests;
	newTestRHCell.innerHTML = 'XX Ressources Humaines';
	newTestPatientsCell = document.createElement('td');
	newTestPatientsCell.className = 'test-cell';
	newTestPatientsCell.id = 'test-cell-' + numberOfTests;
	newTestPatientsCell.innerHTML = 'XX Patients';
	newTestFirstLine.appendChild(newTestRHCell);
	newTestFirstLine.appendChild(newTestPatientsCell);
	newTestData.appendChild(newTestFirstLine);
	newTestSecondLine = document.createElement('tr');
	newTestSecondLine.className = 'test-line';
	newTestSecondLine.id = 'test-line-' + numberOfTests;
	newTestRMCell = document.createElement('td');
	newTestRMCell.className = 'test-cell';
	newTestRMCell.id = 'test-cell-' + numberOfTests;
	newTestRMCell.innerHTML = 'XX Ressources Matérielles';
	newTestBinCell = document.createElement('td');
	newTestBinCell.className = 'bin-cell';
	newTestBinCell.id = 'test-cell-' + numberOfTests;
	newBinImg = document.createElement('img');
	newBinImg.className = 'bin';
	newBinImg.id = 'bin-' + numberOfTests;
	newBinImg.src = '/img/bin.svg';
	newBinImg.setAttribute('onclick', "showPopup('test-container-" + numberOfTests + "')");
	newTestBinCell.appendChild(newBinImg);
	newTestSecondLine.appendChild(newTestRMCell);
	newTestSecondLine.appendChild(newTestBinCell);
	newTestData.appendChild(newTestSecondLine);
	newTest.appendChild(newTestData);
	testsGrid.appendChild(newTest);
	numberOfTests++;
}

function DeleteTest(id) {
	console.log(id)
	console.log(document.getElementById(id))
	document.getElementById(id).remove();
	$('#modal-popup').modal('hide')

}
function showPopup(id) {
	console.log(id)
	console.log(document.getElementById("delete-test-button"))
	document.getElementById("delete-test-button").setAttribute('onclick', "DeleteTest('" + id + "')");
	$('#modal-popup').modal('show')
}