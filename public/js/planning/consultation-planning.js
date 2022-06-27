var calendar;
var datepicker;
var date=new Date();
var dateStr=date.toDateString();
var headerResources="Patients";
const height = document.querySelector('div').clientHeight

function $_GET(param) {
	var vars = {};
	window.location.href.replace( location.hash, '' ).replace( 
		/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
		function( m, key, value ) { // callback
			vars[key] = value !== undefined ? value : '';
		}
	);

	if ( param ) {
		return vars[param] ? vars[param] : null;	
	}
	return vars;
}
var dateStr=($_GET('date'))
date=new Date(dateStr)
document.addEventListener('DOMContentLoaded', function() 
{
  createCalendar("Patients");

});


function changeDate(){
  var jsDate=new Date(document.getElementById("Date").value)
  var day=jsDate.getDate();
  var month=jsDate.getMonth()+1;
  var year=jsDate.getFullYear();
  date=new Date(year,month,day);
  var temp = new Date
  if (day<10){day="0"+day;}
  if (month<10){month="0"+month;}
  dateStr=year+"-"+month+"-"+day+"T12:00:00";
  window.location.assign("/ConsultationPlanning?date="+dateStr);
}

function changePlanning(){
  var header=document.getElementById("displayList").options[document.getElementById('displayList').selectedIndex].text;
    headerResources=header;
    createCalendar(header);
}
    

function modify(){
  var temp = new Date
  if(temp.getDate()==date.getDate() && temp.getMonth()==date.getMonth() && temp.getFullYear()==date.getFullYear()){var day=calendar.getDate().getDate();}
  else {var day=calendar.getDate().getDate();}
  if (day<10){day="0"+day;}
  var month=calendar.getDate().getMonth()+1;
  if (month<10){month="0"+month;}
  var year=calendar.getDate().getFullYear();
  dateStr=year+"-"+month+"-"+day+"T12:00:00";
  window.location.assign("/ModificationPlanning?date="+dateStr);
}

function filterShow(){
  if(document.getElementById("filterId").style.display != "none"){
    document.getElementById("filterId").style.display = "none";
  } else {
    document.getElementById("filterId").style.display = "inline-block";
  }
}


function createCalendar(resources){
  switch(resources){
    case "Patients":
      var resourcesArray=JSON.parse(document.getElementById('patients').value.replaceAll("3aZt3r", " "));
      break;
    case "Parcours":
      var resourcesArray=JSON.parse(document.getElementById('parcours').value.replaceAll("3aZt3r", " "));
      break;
      case "Ressources Humaines":
      var resourcesArray=JSON.parse(document.getElementById('hr').value.replaceAll("3aZt3r", " "));
      break;
      case "Ressources Matérielles":
      var resourcesArray=JSON.parse(document.getElementById('mr').value.replaceAll("3aZt3r", " "));
      console.log(resourcesArray);
      break;
    }
    var events=JSON.parse(document.getElementById('events').value.replaceAll("3aZt3r", " "));
    console.log(events);
  if(document.getElementById("Date").value!=null){
    dateStr=document.getElementById("Date").value
  }
  date=new Date(dateStr)
  //var resources=document.getElementById('resources').value.replaceAll("3aZt3r", " ");   
  //var resourcearray=JSON.parse(resources); 
  var patientsarray=JSON.parse(document.getElementById('patients').value.replaceAll("3aZt3r", " "));
  console.log(patientsarray)
  var calendarEl = document.getElementById('calendar');

  calendar=new FullCalendar.Calendar(calendarEl, 
    {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        initialView: 'resourceTimelineDay',
        slotDuration: '00:20:00',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        selectable: true,
        editable: false,
        contentHeight: height*3/4,
        handleWindowResize: true,
        eventDurationEditable: false,
        nowIndicator: true,

        slotLabelFormat: { //modifie l'affichage des heures de la journée
            hour: '2-digit', //2-digit, numeric
            minute: '2-digit', //2-digit, numeric
            meridiem: false, //lowercase, short, narrow, false (display of AM/PM)
            hour12: false //true, false
          },
        resourceOrder: 'title',
        resourceAreaWidth: '20%',
        resourceAreaHeaderContent: headerResources,
        resources: resourcesArray,
        events: events,
    },
    );
    
    calendar.gotoDate(date);
    calendar.render();
}
