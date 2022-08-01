/**
 * @file utils-planning.js
 * @brief This file contains the js scripts used to modify the window or the calendar in consultation mode
 * @author Thomas Blumstein
 * @version 1.0
 * @date 2022/07
 */

var humanCategoriesToDisplay = [];
var materialCategoriesToDisplay = [];
var firstCreationFilter=true;

/**
 * @brief This function is called when we want to go to display the filter window, called when click on the filter button
 */
function filterShow() {
  var filter = document.getElementById("filterId");
  if (filter.style.display != "none") {
    //if the filter is already displayed
    filter.style.display = "none"; //hide the filter
    while (filter.firstChild) {
      //while there is something in the filter
      filter.removeChild(filter.firstChild); //remove the old content
    }
  }
  else {
    var allCategories = []; //create an array to store the resources to display
    switch (headerResources) {
      case "Ressources Humaines": //if we want to display by the patients
        var categoriesArray = JSON.parse(
          document.getElementById("human").value.replaceAll("3aZt3r", " ")
        ); //get the data of the appointments
        for (var i = 0; i < categoriesArray.length; i++) {
          var categories = categoriesArray[i]["categories"];
          for (var j = 0; j < categories.length; j++) {
            if (allCategories.indexOf(categories[j]["name"]) == -1) {
              allCategories.push(categories[j]["name"]); //add the resource to the array if it is not already in it
            }
          }
        }
        if (humanCategoriesToDisplay.length == 0) {
          humanCategoriesToDisplay.push(allCategories);
        }
        break;
      case "Ressources Matérielles": //if we want to display by the patients
        var categoriesArray = JSON.parse(
          document.getElementById("material").value.replaceAll("3aZt3r", " ")
        ); //get the data of the appointments
        for (var i = 0; i < categoriesArray.length; i++) {
          var categories = categoriesArray[i]["categories"];
          for (var j = 0; j < categories.length; j++) {
            if (allCategories.indexOf(categories[j]["name"]) == -1) {
              allCategories.push(categories[j]["name"]); //add the resource to the array if it is not already in it
            }
          }
        }
        if (materialCategoriesToDisplay.length == 0) {
          materialCategoriesToDisplay.push(allCategories);
        }
        break;
    }
    filter.style.display = "inline-block"; //display the filter
    if (allCategories.length == 0) {
      //if there is no resource in the calendar
      var label = document.createElement("label"); //display a label
      label.innerHTML = "Aucune Catégorie à filtrer"; //telling "no resources"
      filter.appendChild(label); //add the label to the filter
    } else {
      //for all the resources in the calendar
      for (var i = 0; i < allCategories.length; i++) {
        if (document.getElementById(allCategories[i]) == null) {
          var input = document.createElement("input"); //create a input
          input.type = "checkbox"; //set the type of the input to checkbox
          input.id = allCategories[i]; //set the name of the input to the title of the resource
          switch (headerResources) {
            case "Ressources Humaines":
              input.onchange = function () {
                //set the onchange event
                changeFilter(this.id, allCategories, 'human'); //call the changeFilter function with the id of the resource
              };
              if (input.checked == false) {
                for (var j = 0; j < humanCategoriesToDisplay.length; j++) {
                  if (input.id == humanCategoriesToDisplay[j]||firstCreationFilter==true) {
                    input.checked = true; //set the checkbox to checked
                    j = calendar.getResources().length - 1; //stop the loop
                  }
                  else {
                    input.checked = false; //set the checkbox to unchecked
                  }
                }
              }
              break;
            case "Ressources Matérielles":
              input.onchange = function () {
                //set the onchange event
                changeFilter(this.id, allCategories, 'material'); //call the changeFilter function with the id of the resource
              };
              if (input.checked == false) {
                for (var j = 0; j < materialCategoriesToDisplay.length; j++) {
                  if (input.id == materialCategoriesToDisplay[j]||firstCreationFilter==true) {
                    input.checked = true; //set the checkbox to checked
                    j = calendar.getResources().length - 1;
                  }
                }
              }
              break;
          }
          filter.appendChild(input); //add the input to the filter
          var label = document.createElement("label"); //create a label
          label.htmlFor = allCategories[i]; //set the htmlFor of the label to the id of the resource
          label.innerHTML = "&nbsp;" + allCategories[i]; //set the text of the label to the title of the resource
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
function changeFilter(id, allCategories, type) {
  var resources = [];
  var categoriesToDisplay = [];
  var resourcesToDisplay = [];
  firstCreationFilter=false;
  var zoom = document.getElementById('zoom').value;


  switch (type) {
    case "human":
      for (var i = 0; i < allCategories.length; i++) {
        if (document.getElementById(allCategories[i]).checked == true) {
          if (humanCategoriesToDisplay.indexOf(document.getElementById(allCategories[i]).id) == -1) {
            humanCategoriesToDisplay.push(document.getElementById(allCategories[i]).id);
          }
        }
        if (document.getElementById(allCategories[i]).checked == false) {
          if(humanCategoriesToDisplay.indexOf(document.getElementById(allCategories[i]).id)!=-1){
            humanCategoriesToDisplay.splice(humanCategoriesToDisplay.indexOf(document.getElementById(allCategories[i]).id), 1);
          }
        }
        resources = JSON.parse(document.getElementById("human").value.replaceAll("3aZt3r", " "));
        categoriesToDisplay = humanCategoriesToDisplay;
        headerResources = "Ressources Humaines";
      }
      break;
    case "material":
      for (var i = 0; i < allCategories.length; i++) {
        if (document.getElementById(allCategories[i]).checked == true) {
          if (materialCategoriesToDisplay.indexOf(document.getElementById(allCategories[i]).id) == -1) {
            materialCategoriesToDisplay.push(document.getElementById(allCategories[i]).id);
          }
        }
        if (document.getElementById(allCategories[i]).checked == false) {
          if(materialCategoriesToDisplay.indexOf(document.getElementById(allCategories[i]).id)!=-1){
            materialCategoriesToDisplay.splice(materialCategoriesToDisplay.indexOf(document.getElementById(allCategories[i]).id), 1);
          }
        }
        resources = JSON.parse(document.getElementById("material").value.replaceAll("3aZt3r", " "));
        categoriesToDisplay = materialCategoriesToDisplay;
        headerResources = "Ressources Matérielles";
      }
      break;
  }
  for (var i = 0; i < resources.length; i++) {
    for (var j = 0; j < resources[i]["categories"].length; j++) {
      if (categoriesToDisplay.indexOf(resources[i]["categories"][j]["name"]) != -1) {
        resourcesToDisplay.push(resources[i]);
      }
    }
  }
  createCalendar(headerResources, "create",zoom, resourcesToDisplay);

}

/**
 * @brief This function is called when we want to change the type of the calendar (Patients, Resources...)
 */
function changePlanning() {
  var zoom = document.getElementById('zoom').value;

  if (document.getElementById("filterId").style.display != "none") {
    filterShow(); 
  }
  var header =
    document.getElementById("displayList").options[
      document.getElementById("displayList").selectedIndex
    ].text; //get the type of resources to display in the list
  headerResources = header; //update the header of the list
  createCalendar(header,'create',zoom); //rerender the calendar with the new type of resources
  if (header == "Patients") {
    document.getElementById("filterbutton").disabled = true;
    document.getElementById("filterbutton").style.color = "#666666";
    document.getElementById("filterbutton").style.backgroundColor = "#cccccc";

  }
  else {
    
    document.getElementById("filterbutton").disabled = false;
    document.getElementById("filterbutton").style.color = "";
    document.getElementById("filterbutton").style.backgroundColor = "";
    firstCreationFilter=true;

  }
}

/**
 * @brief This function is called when we want to go to the modification page
 */
function modify(id = 1) {
  var day = calendar.getDate().getDate(); //get the day
  var month = calendar.getDate().getMonth() + 1; //get the month (add 1 because it starts at 0)
  var year = calendar.getDate().getFullYear(); //get the year
  if (day < 10) {
    day = "0" + day;
  } //if the day is less than 10, add a 0 before to fit with DateTime format
  if (month < 10) {
    month = "0" + month;
  } //if the month is less than 10, add a 0 before to fit with DateTime format
  dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
  window.location.assign("/ModificationPlanning?date=" + dateStr + "&id=" + id); //goto the modification page with the date and user id
}

/**
 * @brief This function is called when we want to change the date of the page
 */
function changeDate() {
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
  window.location.assign(
    "/ConsultationPlanning?date=" +
    dateStr +
    "&headerResources=" +
    headerResources
  ); //rerender the page with a new date
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
  window.location.assign(
    "/ConsultationPlanning?date=" +
    dateStr +
    "&headerResources=" +
    headerResources
  ); //rerender the page with a new date
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
  window.location.assign(
    "/ConsultationPlanning?date=" +
    dateStr +
    "&headerResources=" +
    headerResources
  ); //rerender the page with a new date
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
  window.location.assign(
    "/ConsultationPlanning?date=" +
    dateStr +
    "&headerResources=" +
    headerResources
  ); //rerender the page with a new date
}

function zoomChange() {
  newZoom = document.getElementById('zoom').value;
  calendar.setOption('slotDuration', newZoom);
  calendar.render()
}

function categoryShow() {
  var displayButtonStyle = document.getElementById('displayCategory').style;
  var labelDisplayButtonStyle = document.getElementById('labelDisplayCategory');
  var zoom = document.getElementById('zoom').value;

  if (resourcesColumns.length == 1) {
    displayButtonStyle.opacity = 0.7;
    labelDisplayButtonStyle.textContent = "Cacher Catégories";
    resourcesColumns = [{
      headerContent: "Nom", //set the label of the column
      field: "title", //set the field of the column
    },
    {
      headerContent: "Catégories", //set the label of the column
      field: "categoriesString", //set the field of the column
    }]
    createCalendar(headerResources,'create',zoom);
    calendar.setOption('resourceAreaWidth','25%');
    calendar.render()
  }
  else {
    displayButtonStyle.opacity = 1;
    labelDisplayButtonStyle.textContent = "Afficher Catégories";
    resourcesColumns = [{
      headerContent: "Nom", //set the label of the column
      field: "title", //set the field of the column
    }]
    createCalendar(headerResources,'create',zoom);
    calendar.setOption('resourceAreaWidth','15%');
    calendar.render()
  }

}
