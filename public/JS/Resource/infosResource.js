var idHR;
var idMR;

/**
 * Allows to display a modal that shows data about the selected human resource
 */
function showInfosModalHuman(idHumanResource, resourceName) {
  document.getElementById("human-resource1").innerHTML = resourceName;
  document.getElementById("human-resource2").innerHTML = resourceName;
  document.getElementById("human-resource3").innerHTML = resourceName;
  idHR = idHumanResource;
  var tableBody = document.getElementById("tbody-human-resource");
  tableBody.innerHTML = "";
  date = new Date();
  getAjaxHumanResources(idHumanResource, date, tableBody);
  createCalendarResource("humanresource");
  getWorkingHours(idHumanResource);
  change_tab_human_infos("planning-infos");
  $("#infos-human-resource-modal").modal("show");
  document.getElementById("load-large").style.visibility = "visible";
  document.getElementById("empty-planning").style.visibility = "hidden";
}


/**
 * Allows to display a modal that shows data about the selected human resource category
 */
function showInfosModalHumanCateg(idHumanResourceCategory, resourceCategName) {
  document.getElementById("human-resource-category1").innerHTML = resourceCategName;
  document.getElementById("human-resource-category2").innerHTML = resourceCategName;

  var tableBody = document.getElementById("tbody-human-resource-category");
  var tableBodyActivity = document.getElementById("tbody-category-activities");
  tableBody.innerHTML = "";

  $.ajax({
    type: "POST",
    url: "/ajaxHumanResource",
    data: { idHumanResourceCategory: idHumanResourceCategory },
    dataType: "json",
    success: function (data) {
      tableResource(tableBody, data, "humanresourcecategory");
    },
    error: function () {
      console.log("error");
    },
  });

  $.ajax({
    type: "POST",
    url: "/ajaxHumanResourceCategoriesActivities",
    data: { idHumanResourceCategory: idHumanResourceCategory },
    dataType: "json",
    success: function (data) {
      tableActitivies(tableBodyActivity, data)
    },
    error: function () {
      console.log("error");
    },
  });

  change_tab_human_categ_infos("resources-by-categories");

  $("#infos-human-resource-category-modal").modal("show");
}


/**
 * Allows to display a modal that shows data about the selected material resource
 */
function showInfosModalMaterial(idMaterialResource, resourceName) {
  document.getElementById("material-resource1").innerHTML = resourceName;
  document.getElementById("material-resource2").innerHTML = resourceName;
  idMR = idMaterialResource;
  var tableBody = document.getElementById("tbody-material-resource");
  tableBody.innerHTML = "";
  date = new Date();
  getAjaxMaterialResources(idMaterialResource, date, tableBody);
  createCalendarResource("materialresource");
  change_tab_material_infos("planning-mr");
  $("#infos-material-resource-modal").modal("show");
  document.getElementById("load-large").style.visibility = "visible";  
  document.getElementById("empty-planning").style.visibility = "hidden";
}

/**
 * Allows to display a modal that shows data about the selected material resource category
 */
function showInfosModalMaterialCateg(
  idMaterialResourceCategory,
  resourceCategName
) {
  document.getElementById("material-resource-category").innerHTML =
    resourceCategName;

  var tableBody = document.getElementById("tbody-material-resource-category");
  tableBody.innerHTML = ""; // On supprime ce qui a précédemment été écrit dans la modale

  $.ajax({
    type: "POST",
    url: "/ajaxMaterialResource",
    data: { idMaterialResourceCategory: idMaterialResourceCategory },
    dataType: "json",
    success: function (data) {
      tableResource(tableBody, data, "materialresourcecategory");
    },
    error: function () {
      console.log("error");
    },
  });

  $("#infos-material-resource-category-modal").modal("show");
}
function getAjaxHumanResources(idHumanResource, date, tableBody) {
  dateStr =
    date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
  $.ajax({
    type: "POST",
    url: "/ajaxHumanResource",
    data: { idHumanResource: idHumanResource, date: dateStr },
    dataType: "json",
    success: function (data) {
      tableResource(tableBody, data["categories"], "humanresource");
      addToCalendar(data);
    },
    error: function () {
      console.log("error");
    },
  });
}
function getAjaxMaterialResources(idMaterialResource, date, tableBody) {
  dateStr = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
  $.ajax({
    type: "POST",
    url: "/ajaxMaterialResource",
    data: { idMaterialResource: idMaterialResource, date: dateStr },
    dataType: "json",
    success: function (data) {
      tableResource(tableBody, data["categories"], "materialresource");
      addToCalendar(data);
    },
    error: function () {
      console.log("error");
    },
  });
}

function tableResource(tableBody, data, switch_param) {
  if (data.length <= 0) {
    var tr = document.createElement("TR");
    tableBody.appendChild(tr);
    var td = document.createElement("TD");
    td.setAttribute("colspan", 5);
    switch (switch_param) {
      case "humanresource":
        td.append("Pas de catégorie associée à cette ressource !");
        break;
      case "humanresourcecategory":
        td.append("Pas de ressource associée à cette catégorie !");
        break;
      case "materialresource":
        td.append("Pas de catégorie associée à cette ressource !");
        break;
      case "materialresourcecategory":
        td.append("Pas de ressource associée à cette catégorie !");
        break;
    }
    tr.appendChild(td);
  } else {
    for (i = 0; i < data.length; i++) {
      var tr = document.createElement("TR");
      tableBody.appendChild(tr);
      var td = document.createElement("TD");
      switch (switch_param) {
        case "humanresource":
          td.append(data[i]["humanresourcecategory"]);
          break;
        case "humanresourcecategory":
          td.append(data[i]["humanresource"]);
          break;
        case "materialresource":
          td.append(data[i]["materialresourcecategory"]);
          break;
        case "materialresourcecategory":
          td.append(data[i]["materialresource"]);
          break;
      }
      tr.appendChild(td);
    }
  }
}

function tableActitivies(tableBody, data){
  tableBody.innerHTML = ""
  if(data.length <= 0){
      var tr = document.createElement('TR');
      tableBody.appendChild(tr);
      var td = document.createElement('TD');
      td.setAttribute('colspan', 5);
      td.append("Il n'existe pas d'activités nécessitant cette catégorie");
      tr.appendChild(td);
  }
  else{
      headerPathway = document.getElementById("header-pathway")
      headerPathway.innerHTML = ""
      let imgDownArrow = new Image();
      imgDownArrow.src = '../img/chevron_up.svg';
      imgDownArrow.setAttribute('id', 'pathway_imgdown');
      imgDownArrow.setAttribute('onclick', "hideAllActivities(" + data.length + ")")
      imgDownArrow.setAttribute('title', 'Cacher toutes les activités');
      imgDownArrow.style.width = '20px';
      imgDownArrow.style.cursor = 'pointer';
      headerPathway.style.display = "flex"
      headerPathway.style.justifyContent = "space-between"
      headerPathway.append("Parcours", imgDownArrow)

      for(i = 0; i < data.length; i++){
          var tr = document.createElement('TR');
          tableBody.appendChild(tr);
          let imgDownArrow = new Image();
          imgDownArrow.src = '../img/chevron_up.svg';
          imgDownArrow.setAttribute('id', 'pathway_imgdown-' + i);
          imgDownArrow.setAttribute('onclick', "hideActivities(" + i + ")")
          imgDownArrow.setAttribute('title', 'Cacher les activités');
          imgDownArrow.style.width = '20px';
          imgDownArrow.style.cursor = 'pointer';
          var td1 = document.createElement('TD');
          var td2 = document.createElement('TD');
          var td3 = document.createElement('TD');
          td1.style.display = "flex"
          td1.style.justifyContent = "space-between"
          td2.style.width = "300px"
          td1.append(data[i]['pathwayname'], imgDownArrow);
          tr.appendChild(td1);tr.appendChild(td2);tr.appendChild(td3);
          for(j = 0; j < data[i]['activities'].length; j++){
            var actj = data[i]['activities'][j]

            var tr = document.createElement('TR');
            tr.classList.add("activityline" + i)
            tableBody.appendChild(tr);

            var td1 = document.createElement('TD');
            var td2 = document.createElement('TD');
            var td3 = document.createElement('TD');

            td2.append(actj['activityname'])
            td3.append(actj['quantity'])
            tr.appendChild(td1);tr.appendChild(td2);tr.appendChild(td3);
          }
      }
  }
}

function showAllActivities(length){
  for(i = 0; i < length; i++){
    $(".activityline" + i).show();
    chevron = document.getElementById('pathway_imgdown-' + i)
    chevron.setAttribute('onclick', "hideActivities(" + i + ")")
    chevron.setAttribute('src', '../img/chevron_up.svg')
  }

  chevron = document.getElementById('pathway_imgdown')
  chevron.setAttribute('onclick', "hideAllActivities(" + length + ")")
  chevron.setAttribute('src', '../img/chevron_up.svg')
}

function hideAllActivities(length){
  for(i = 0; i < length; i++){
    $(".activityline" + i).hide();
    chevron = document.getElementById('pathway_imgdown-' + i)
    chevron.setAttribute('onclick', "showActivities(" + i + ")")
    chevron.setAttribute('src', '../img/chevron_down.svg')
  }

  chevron = document.getElementById('pathway_imgdown')
  chevron.setAttribute('onclick', "showAllActivities(" + length + ")")
  chevron.setAttribute('src', '../img/chevron_down.svg')
}

function showActivities(index){
  $(".activityline" + index).show();
  chevron = document.getElementById('pathway_imgdown-' + index)
  chevron.setAttribute('onclick', "hideActivities(" + index + ")")
  chevron.setAttribute('src', '../img/chevron_up.svg')
}

function hideActivities(index){
  $(".activityline" + index).hide();
  chevron = document.getElementById('pathway_imgdown-' + index)
  chevron.setAttribute('onclick', "showActivities(" + index + ")")
  chevron.setAttribute('src', '../img/chevron_down.svg')
} 

function getWorkingHours(id) {
  var WORKING_HOURS_FILTERED = WORKING_HOURS.filter(function (WORKING_HOUR) {
    return WORKING_HOUR.humanresource_id == id;
  });

  let beginHours = document.getElementById("working-hours-infos-begin");
  let endHours = document.getElementById("working-hours-infos-end");
  for (let y = 0; y < WORKING_HOURS_FILTERED.length; y++) {
    switch (WORKING_HOURS_FILTERED[y].dayweek) {
      case 0:
        beginHours.children[6].value = WORKING_HOURS_FILTERED[
          y
        ].starttime.date.substring(11, 16);
        endHours.children[6].value =
          WORKING_HOURS_FILTERED[0].endtime.date.substring(11, 16);

        break;

      case 1:
        beginHours.children[0].value = WORKING_HOURS_FILTERED[
          y
        ].starttime.date.substring(11, 16);
        endHours.children[0].value = WORKING_HOURS_FILTERED[
          y
        ].endtime.date.substring(11, 16);

        break;

      case 2:
        beginHours.children[1].value = WORKING_HOURS_FILTERED[
          y
        ].starttime.date.substring(11, 16);
        endHours.children[1].value = WORKING_HOURS_FILTERED[
          y
        ].endtime.date.substring(11, 16);

        break;

      case 3:
        beginHours.children[2].value = WORKING_HOURS_FILTERED[
          y
        ].starttime.date.substring(11, 16);
        endHours.children[2].value = WORKING_HOURS_FILTERED[
          y
        ].endtime.date.substring(11, 16);

        break;

      case 4:
        beginHours.children[3].value = WORKING_HOURS_FILTERED[
          y
        ].starttime.date.substring(11, 16);
        endHours.children[3].value = WORKING_HOURS_FILTERED[
          y
        ].endtime.date.substring(11, 16);

        break;

      case 5:
        beginHours.children[4].value = WORKING_HOURS_FILTERED[
          y
        ].starttime.date.substring(11, 16);
        endHours.children[4].value = WORKING_HOURS_FILTERED[
          y
        ].endtime.date.substring(11, 16);

        break;

      case 6:
        beginHours.children[5].value = WORKING_HOURS_FILTERED[
          y
        ].starttime.date.substring(11, 16);
        endHours.children[5].value = WORKING_HOURS_FILTERED[
          y
        ].endtime.date.substring(11, 16);

        break;
    }
  }
}

/**
 * Allows to change the selected tab in the modal infos of a human resource
 */
function change_tab_human_infos(id) {
  liHeader = document.getElementsByClassName("li-header");
  selectedDivs = liHeader[0].getElementsByClassName("selected");
  for(i = 0; i < selectedDivs.length; i++){
    selectedDivs[i].className="notselected";
  }

  document.getElementById(id).className = "selected";

  let planning = document.getElementById("human-resource-planning");
  let workinghours = document.getElementById("human-resource-working-hours");
  let categories = document.getElementById("human-resource-categories");

  switch (id) {
    case "planning-infos":
      planning.style.display = "block";
      workinghours.style.display = "none";
      categories.style.display = "none";
      document.getElementById("modal-dialog").style.maxWidth = "1000px";
      break;
    case "workinghours":
      planning.style.display = "none";
      workinghours.style.display = "block";
      categories.style.display = "none";
      document.getElementById("modal-dialog").style.maxWidth = "600px";
      break;
    case "categoriesbyresource":
      planning.style.display = "none";
      workinghours.style.display = "none";
      categories.style.display = "block";
      document.getElementById("modal-dialog").style.maxWidth = "600px";
      break;
  }
}

function change_tab_human_categ_infos(id) {
  liHeader = document.getElementsByClassName("header-human-category");
  selectedDivs = liHeader[0].getElementsByClassName("selected");
  for(i = 0; i < selectedDivs.length; i++){
    selectedDivs[i].className="notselected";
  }

  document.getElementById(id).className = "selected";

  let resources = document.getElementById("human-resource-category-resources");
  let activities = document.getElementById("human-resource-category-activities");

  switch (id) {
    case "resources-by-categories":
      resources.style.display = "block";
      activities.style.display = "none";
      document.getElementById("modal-dialog").style.maxWidth = "600px";
      break;
    case "activities-by-categories":
      resources.style.display = "none";
      activities.style.display = "block";
      document.getElementById("modal-dialog").style.maxWidth = "600px";
      break;
  }
}


/**
 * Allows to change the selected tab in the modal infos of a material resource
 */
function change_tab_material_infos(id) {
  document.getElementById("planning-mr").className = "notselected";
  document.getElementById("categoriesbyresource").className = "notselected";
  document.getElementById(id).className = "selected";

  let planning = document.getElementById("material-resource-planning");
  let categories = document.getElementById("material-resource-categories");

  switch (id) {
    case "planning-mr":
      planning.style.display = "block";
      categories.style.display = "none";
      document.getElementById("modal-dialog").style.maxWidth = "1000px";
      break;
    case "categoriesbyresource":
      planning.style.display = "none";
      categories.style.display = "block";
      document.getElementById("modal-dialog").style.maxWidth = "600px";
      break;
  }
}

/**
 * @brief This function is called when we want to create or recreate the calendar
 * @param {*} resources the type of resources to display (Patients, Resources...)
 */
function createCalendarResource(type) {
  date = new Date(); //create a new date with the date in the hidden input
  if (type == "humanresource") {
    var calendarEl = document.getElementById("calendar-hr"); //create the calendar variable
  } else {
    var calendarEl = document.getElementById("calendar-mr");
  }
  //create the calendar
  calendar = new FullCalendar.Calendar(calendarEl, {
    schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives", //we use a non commercial license
    timeZone: "UTC",
    initialView: "timeGridWeek",
    contentHeight: "500px",
    locale: "frLocale", //set the language in french
    firstDay: 1, //set the first day of the week to monday
    timeZone: "Europe/Paris", //set the timezone for France
    selectable: false, //set the calendar to be selectable
    editable: false, //set the calendar not to be editable
    allDaySlot: false,
    headerToolbar: {
      left: "",
      center: "",
      right: "",
    },
    eventDidMount: function (info) {
      $(info.el).tooltip({
        title: info.event.extendedProps.description,
        placement: "top",
        trigger: "hover",
        container: "body",
      });
    },
    eventClick: function (event) {
      //get the data of the event
      var id = event.event._def.publicId; //get the id of the event
      var activity = calendar.getEventById(id); //get the event with the id
      var start = activity.start; //get the start date of the event
      var end = activity.end; //get the end date of the event

      //set data to display in the modal window
      $("#start").val(start.toISOString().substring(0, 19)); //set the start date of the event
      $("#end").val(end.toISOString().substring(0, 19)); //set the end date of the event
      document.getElementById("show-title").innerHTML = activity.title; //set the title of the event
      $("#parcours").val(activity.extendedProps.pathway); //set the pathway of the event
      $("#patient").val(activity.extendedProps.patient); //set the patient of the event

      $("#planning-modal").modal("show"); //open the window
    },
  });
  calendar.render(); //render the calendar
}
function addToCalendar(data) {
  console.log(data);
  events = data["activities"];
  unavailability = data["unavailability"];
  workinghours = data["workingHours"];
  for (let i = 0; i < events.length; i++) {
    events[i].starttime
    calendar.addEvent({
      title: events[i].activity,
      start: events[i].dayappointment + "T" + events[i].starttime,
      end: events[i].dayappointment + "T" + events[i].endtime,
      description: events[i].activity,
      extendedProps: {
        pathway: events[i].pathway,
        patient: events[i].patient,
      },
    });
  }
  for (let i = 0; i < unavailability.length; i++) {
    calendar.addEvent({
      start: unavailability[i].starttime,
      end: unavailability[i].endtime,
      description: "Indisponible",
      display: "background",
      color: "#ff0000",
    });
  }
  if (workinghours != undefined) {
    for (let i = 0; i < workinghours.length; i++) {
      calendar.addEvent({
        startTime: "00:00:00",
        endTime: workinghours[i].starttime,
        description: "",
        display: "background",
        color: "#000000",
        daysOfWeek: [workinghours[i].dayweek],
      });
      calendar.addEvent({
        startTime: workinghours[i].endtime,
        endTime: "23:59",
        description: "",
        display: "background",
        color: "#000000",
        daysOfWeek: [workinghours[i].dayweek],
      });
    }
  }
  if(events.length == 0){
    document.getElementById("empty-planning").style.visibility = "visible";
  }
  else{
    document.getElementById("empty-planning").style.visibility = "hidden";
  }
  document.getElementById("load-large").style.visibility = "hidden";
  calendar.render();
}

/**
 * @brief This function is called when we want to go to the previous day
 */
function PreviousWeek(type) {
  var oldDate = calendar.getDate(); //get the old day in the calendar
  var newDate = new Date(
    oldDate.getFullYear(),
    oldDate.getMonth(),
    oldDate.getDate() - 7
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
  calendar.removeAllEvents();
  if (type == "human") {
    getAjaxHumanResources(
      idHR,
      newDate,
      document.getElementById("tbody-human-resource")
    );
  } else {
    getAjaxMaterialResources(
      idMR,
      newDate,
      document.getElementById("tbody-material-resource")
    );
  }
  calendar.gotoDate(newDate);
  document.getElementById("load-large").style.visibility = "visible";
}

/**
 * @brief This function is called when we want to go to the next day
 */
function NextWeek(type) {
  var oldDate = calendar.getDate(); //get the old day in the calendar
  var newDate = new Date(
    oldDate.getFullYear(),
    oldDate.getMonth(),
    oldDate.getDate() + 7
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
  calendar.removeAllEvents();
  if (type == "human") {
    getAjaxHumanResources(
      idHR,
      newDate,
      document.getElementById("tbody-human-resource")
    );
  } else {
    getAjaxMaterialResources(
      idMR,
      newDate,
      document.getElementById("tbody-material-resource")
    );
  }
  calendar.gotoDate(newDate);
  document.getElementById("load-large").style.visibility = "visible";
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
  calendar.removeAllEvents();
  if (type == "human") {
    getAjaxHumanResources(
      idHR,
      today,
      document.getElementById("tbody-human-resource")
    );
  } else {
    getAjaxMaterialResources(
      idMR,
      today,
      document.getElementById("tbody-material-resource")
    );
  }
  calendar.gotoDate(today);
  document.getElementById("load-large").style.visibility = "visible";
}
