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
var reloadTime = 600000;

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
        if (humanCategoriesToDisplay.length == 0 && firstCreationFilter) {
          humanCategoriesToDisplay=Object.values(allCategories);//copy all data in the array if it is empty
          firstCreationFilter=false;
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
        if (materialCategoriesToDisplay.length == 0&&firstCreationFilter) {
          materialCategoriesToDisplay=Object.values(allCategories);//copy all data in the array if it is empty
          firstCreationFilter=false
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
      var inputAll = document.createElement("input"); //create a input
      inputAll.type = "checkbox"; //set the type of the input to checkbox
      inputAll.id = "all"; //set the name of the input to all
      var inputNothing = document.createElement("input"); //create a input
      inputNothing.type = "checkbox"; //set the type of the input to checkbox
      inputNothing.id = "nothing"; //set the name of the input to nothing
      switch (headerResources) {
        case "Ressources Humaines":
          inputAll.onchange = function () {
            //set the onchange event
            changeFilter(this.id, allCategories, 'human'); //call the changeFilter function with the id of the resource
          };
          inputNothing.onchange = function () {
            //set the onchange event
            changeFilter(this.id, allCategories, 'human'); //call the changeFilter function with the id of the resource
          };
          if(humanCategoriesToDisplay.length==allCategories.length){

            inputAll.checked = true; //set the checkbox to checked if all the resources are selected
          }
          if(humanCategoriesToDisplay.length==0){

            inputNothing.checked = true; //set the checkbox to checked if all the resources are selected
          }
          else if(humanCategoriesToDisplay.length!=allCategories.length && humanCategoriesToDisplay[0].length!=0){

            inputAll.checked = false; //set the checkbox to unchecked if all the resources are not selected
            inputNothing.checked = false; //set the checkbox to unchecked if all the resources are not selected
          }

          break;
        case "Ressources Matérielles":
          inputAll.onchange = function () {
            //set the onchange event
            changeFilter(this.id, allCategories, 'material'); //call the changeFilter function with the id of the resource
          }
          inputNothing.onchange = function () {
            //set the onchange event
            changeFilter(this.id, allCategories, 'material'); //call the changeFilter function with the id of the resource
          }

          if(materialCategoriesToDisplay.length==allCategories.length){
            inputAll.checked = true; //set the checkbox to checked if all the resources are selected
          }
          if(materialCategoriesToDisplay.length==0){
            inputNothing.checked = true; //set the checkbox to checked if all the resources are selected
          }
          else if(materialCategoriesToDisplay.length!=allCategories.length && materialCategoriesToDisplay[0].length!=0){
            inputAll.checked = false; //set the checkbox to unchecked if all the resources are not selected
            inputNothing.checked = false; //set the checkbox to unchecked if all the resources are not selected
          }
          break;
        }
        filter.appendChild(inputAll); //add the input to the filter
        var labelAll = document.createElement("label"); //create a label
        labelAll.innerHTML = "&nbsp;" + "Tout sélectionner"; //set the label to "all"
        filter.appendChild(labelAll); //add the label to the filter
        filter.appendChild(document.createElement("br")); //add a br to the filter for display purpose
        filter.appendChild(inputNothing); //add the input to the filter
        var labelNothing = document.createElement("label"); //create a label
        labelNothing.innerHTML = "&nbsp;" + "Tout déselectionner"; //set the label to "nothing"
        filter.appendChild(labelNothing); //add the label to the filter
        filter.appendChild(document.createElement("br")); //add a br to the filter for display purpose
        filter.appendChild(document.createElement("br")); //add a br to the filter for display purpose
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
 * @param {*} allCategories the array of all resources of the calendar
 * @param {*} type the type of resource to filter
 */
function changeFilter(id, allCategories, type) {
  var resources = [];
  var categoriesToDisplay = [];
  var resourcesToDisplay = [];
  firstCreationFilter=false;
  var zoom = document.getElementById('zoom-value').value;

  if(id=="all"){//if we want to select all the resources
    if(document.getElementById('all').checked==true){//if the checkbox is checked'))
      for (var i = 0; i < allCategories.length; i++) {
        document.getElementById(allCategories[i]).checked=true;//set the checkbox to checked
      }
      document.getElementById('nothing').checked=false;//set the checkbox to unchecked
    }
  }
  if(id=="nothing"){//if we want to deselect all the resources
    if(document.getElementById('nothing').checked==true){//if the checkbox is checked'))
      for(var i=0;i<allCategories.length;i++){
        document.getElementById(allCategories[i]).checked=false;//set the checkbox to unchecked
      }
      document.getElementById('all').checked=false;//set the checkbox to unchecked
    }
  }
  switch (type) {
    case "human"://if we want to filter by the human resources
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
    case "material"://if we want to filter by the material resources
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
      inputAll=document.getElementById("all");
      inputNothing=document.getElementById("nothing");
      if(categoriesToDisplay.length==allCategories.length){

        inputAll.checked = true; //set the checkbox to checked if all the resources are selected
      }
      if(categoriesToDisplay.length==0){

        inputNothing.checked = true; //set the checkbox to checked if all the resources are selected
      }
      else if(categoriesToDisplay.length!=allCategories.length && humanCategoriesToDisplay[0].length!=0){

        inputAll.checked = false; //set the checkbox to unchecked if all the resources are not selected
        inputNothing.checked = false; //set the checkbox to unchecked if all the resources are not selected
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
  var zoom = document.getElementById('zoom-value').value;

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
  else {//if we want to display the resources
    
    document.getElementById("filterbutton").disabled = false;
    document.getElementById("filterbutton").style.color = "";
    document.getElementById("filterbutton").style.backgroundColor = "";
    firstCreationFilter=true;

  }
  document.querySelectorAll("#header-type")[0].innerText=headerResources;
  firstCreationFilter=true;
}

/**
 * @brief This function is called when we want to go to the modification page
 */
function modify(id) {
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

  $.ajax({
    type: 'POST',
    url: '/GetModifications',
    data: { idUser: id, dateModified: dateStr },
    dataType: "json",
    success: function (data) {
        if(data.length > 0){
          console.log(data)
          showAlertModif(data, id, dateStr)
        }
        else{
          window.location.assign("/ModificationPlanning?date=" + dateStr + "&id=" + id);
        }
    },
    error: function () {
        console.log("error : can't access modifications");
        window.location.assign("/ModificationPlanning?date=" + dateStr + "&id=" + id); //goto the modification page with the date and user id
      }
  });
}

function showAlertModif(data, userId, dateAlert){
  console.log(data[0])
  document.getElementById("alert-body").innerHTML = "Une modification de " + data[0].lastname +" "+ data[0].firstname + " pour le " + data[0].dateModified + " est déjà en cours, voulez-vous continuer ?"
  document.getElementById("goto-modif-button").setAttribute('onclick', 'window.location.assign("/ModificationPlanning?date=' + dateAlert + '&id=' + userId + '")')
  $("#alert-modif-modal").show()
}

/**
 * @brief This function is called when we want to change the date of the page
 */
function newDate(type) {
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
  changeDate(dateStr,type,headerResources);
}

/**
 * @brief This function is called when we want to go to the previous day
 */
function PreviousDay(type) {
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
  changeDate(dateStr,type,headerResources);
}

/**
 * @brief This function is called when we want to go to the next day
 */
function NextDay(type) {
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
  changeDate(dateStr,type,headerResources);
}

/**
 * @brief This function is called when we want to go to the date of today
 */
function Today(type) {
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
  changeDate(dateStr,type,headerResources);
}

function changeDate(dateStr,type,headerResources){
  if(type == "consultation"){
  window.location.assign(
    "/ConsultationPlanning?date=" +
    dateStr +
    "&headerResources=" +
    headerResources
  ); //rerender the page with a new date
  }
  if(type == "ethics"){
    window.location.assign(
      "/ethics?date=" +
      dateStr +
      "&headerResources=" +
      headerResources
    ); //rerender the page with a new date
  }
}

function zoomChange(change) {
  zooms = ['02:00:00', '01:00:00', '00:40:00', '00:20:00', '00:10:00', '00:05:00', '00:02:30']
  zoomValue = document.getElementById('zoom-value').value;
  indexZoom = zooms.indexOf(zoomValue)
  if(indexZoom != -1){
    if(change == "plus"){
      if(indexZoom < zooms.length-1){
        calendar.setOption('slotDuration', zooms[indexZoom+1]);
        document.getElementById('zoom-value').value = zooms[indexZoom+1]
      }
    }
    if(change == 'minus'){
      if(indexZoom > 0){
        calendar.setOption('slotDuration', zooms[indexZoom-1]);
        document.getElementById('zoom-value').value = zooms[indexZoom-1]
      }
    }
    if(change == 'default'){
      calendar.setOption('slotDuration', "00:20:00");
      document.getElementById('zoom-value').value = "00:20:00" 
    }
    calendar.render()
  }
  else{
    calendar.setOption('slotDuration', "00:20:00");
    document.getElementById('zoom-value').value = "00:20:00"
  calendar.render()
  }
  
  scrollableDiv = document.getElementsByClassName("fc-scroller fc-scroller-liquid-absolute")[1]
  scrollableDiv.scrollLeft = (scrollableDiv.scrollWidth-scrollableDiv.clientWidth)/2
}

function categoryShow() {
  var displayButtonStyle = document.getElementById('displayCategory').style;
  var labelDisplayButtonStyle = document.getElementById('labelDisplayCategory');
  var zoom = document.getElementById('zoom-value').value;

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

function reload(type){  
  var date = new Date(document.getElementById("Date").value);
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
  if(document.getElementById('reloadTime')!=null){
    reloadTime = document.getElementById('reloadTime').value; // En millisecondes
  }
  setTimeout(function(){
    if(type=="consultation"){
    window.location.assign("/ConsultationPlanning?date=" + dateStr);
    }
    if(type=="ethics"){
    window.location.assign("/ethics?date=" + dateStr);
    }
  }, reloadTime);

}
