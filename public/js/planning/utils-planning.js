/**
 * @file utils-planning.js
 * @brief This file contains the js scripts used to modify the window or the calendar in consultation mode
 * @author Luc Chereau
 * @version 1.0
 * @date 2022/07
 */

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
      case "Ressources Humaines": //if we want to display by the patients
        var tempArray = JSON.parse(
          document.getElementById("human").value.replaceAll("3aZt3r", " ")
        ); //get the data of the appointments
        for (var i = 0; i < tempArray.length; i++) {
          console.log(tempArray[i]["categories"]);
          var temp = tempArray[i]["categories"];
          for (var j = 0; j < temp.length; j++) {
            if (resourcesToDisplay.indexOf(temp[j]["name"]) == -1) {
              resourcesToDisplay.push(temp[j]["name"]); //add the resource to the array if it is not already in it
            }
          }
          console.log(resourcesToDisplay);
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
      label.innerHTML = "Aucune Catégorie à filtrer"; //telling "no resources"
      filter.appendChild(label); //add the label to the filter
    } else {
      //for all the resources in the calendar
      for (var i = 0; i < resourcesToDisplay.length; i++) {
        if (document.getElementById(resourcesToDisplay[i]) == null) {
          var input = document.createElement("input"); //create a input
          input.type = "checkbox"; //set the type of the input to checkbox
          input.id = resourcesToDisplay[i]; //set the name of the input to the title of the resource
          input.value = i; //set the value of the input to the title of the resource
          console.log(calendar.getResources());
          input.onchange = function () {
            //set the onchange event
            changeFilter(this.id, resourcesToDisplay); //call the changeFilter function with the id of the resource
          };
          if (input.checked == false) {
            for (var j = 0; j < calendar.getResources().length; j++) {
              console.log(calendar.getResources()[j].extendedProps);
              for ( var k = 0;k < calendar.getResources()[j].extendedProps.categories[0].length; k++
              ) {
                console.log(
                  calendar.getResources()[j].extendedProps.categories[0][k].name
                );
                if (
                  input.id ==
                  calendar.getResources()[j].extendedProps.categories[0][k].name
                ) {
                  input.checked = true; //set the checkbox to unchecked
                  j = calendar.getResources().length - 1;
                }
              }
            }
          }
          filter.appendChild(input); //add the input to the filter
          var label = document.createElement("label"); //create a label
          label.htmlFor = resourcesToDisplay[i]; //set the htmlFor of the label to the id of the resource
          label.innerHTML = "&nbsp;" + resourcesToDisplay[i]; //set the text of the label to the title of the resource
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
    switch (headerResources) {
      case "Ressources Humaines":
        var businessHours = []; //create an array to store the working hours
        idTemp = [document.getElementById(id).value];
        console.log(resourcesToDisplay);
        for (
          var j = 0;
          j < resourcesToDisplay[idTemp]["workingHours"].length;
          j++
        ) {
          businesstemp = {
            //create a new business hour
            startTime:
              resourcesToDisplay[idTemp]["workingHours"][j]["startTime"], //set the start time
            endTime: resourcesToDisplay[idTemp]["workingHours"][j]["endTime"], //set the end time
            daysOfWeek: [resourcesToDisplay[idTemp]["workingHours"][j]["day"]], //set the day
          };
          businessHours.push(businesstemp); //add the business hour to the array
        }
        calendar.addResource({
          //add the resource to the calendar
          id: id, //set the id of the resource
          title: document.getElementById(id).name, //set the title of the resource
          businessHours: businessHours, //set the business hours of the resource
        });
        break;
      default:
        calendar.addResource({
          //add the resource to the calendar
          id: id, //set the id of the resource
          title: document.getElementById(id).name, //set the title of the resource
        });
        break;
    }
  } else {
    var resource = calendar.getResourceById(id); //get the resource with the id from the calendar
    resource.remove(); //remove the resource from the calendar
  }
}

/**
 * @brief This function is called when we want to change the type of the calendar (Patients, Resources...)
 */
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
