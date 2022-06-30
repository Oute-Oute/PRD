/**
     * @file consultation-planning.js
     * @brief This file contains the js scripts for the consultation planning page, essentially the calendar.
     * @author Thomas Blumstein
     * @version 1.0
     * @date 2022/06
     */


var calendar; // var globale pour le calendrier
var date=new Date();
var dateStr=date.toDateString();
var headerResources="Patients";
const height = document.querySelector('div').clientHeight


/**
 * 
 * @brief This function is called when the page is loaded. It creates the calendar.
 * @param {*} param 
 * @returns 
 */
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
}function getObjKey(obj, value) {
  return Object.keys(obj).find(key => obj[key] == value);
}


function createCalendar(resources){
  
    var events=JSON.parse(document.getElementById('events').value.replaceAll("3aZt3r", " "));
  if(document.getElementById("Date").value!=null){
    dateStr=document.getElementById("Date").value
  }
  date=new Date(dateStr)
  //var resources=document.getElementById('resources').value.replaceAll("3aZt3r", " ");   
  //var resourcearray=JSON.parse(resources); 
  //var patientsarray=JSON.parse(document.getElementById('patients').value.replaceAll("3aZt3r", " "));
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
        contentHeight: height*12/16,
        handleWindowResize: true,
        eventDurationEditable: false,
        nowIndicator: true,
        headerToolbar: {
          start: null,
          center: null,
          end: null
      },

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


        eventClick: function(event) {
          //récupération des données
          var id = event.event._def.publicId;
          var activity = calendar.getEventById(id);
          var start = activity.start;
          var end = activity.end;
        
          //set les données à afficher par défault
          $('#start').val(start.toISOString().substring(0,19));
          $('#end').val(end.toISOString().substring(0,19));
          document.getElementById('show-title').innerHTML = activity.title;
          $('#id').val(id);
          console.log(activity.resourceId);
          $('#patient').val(activity.extendedProps.patient);

        
          //ouvre la modal
          $('#modify-planning-modal').modal("show");
        },
    },
    );
    switch(resources){
      case "Patients":
        var tempArray=JSON.parse(document.getElementById('appointment').value.replaceAll("3aZt3r", " "));
        for(var i=0;i<tempArray.length;i++){
          var temp=tempArray[i];
          patient=temp['patient'];
          calendar.addResource({
            id: patient[0]['id'],
            title: patient[0]['title']});
        }
        break;
      case "Parcours":
        var tempArray=JSON.parse(document.getElementById('appointment').value.replaceAll("3aZt3r", " "));
        for(var i=0;i<tempArray.length;i++){
          var temp=tempArray[i];
          pathway=temp['pathway'];
          calendar.addResource({
            id: pathway[0]['id'],
            title: pathway[0]['title']});
        }
        break;
        case "Ressources Humaines":
          var resourcesArray=JSON.parse(document.getElementById('human').value.replaceAll("3aZt3r", " "));

          for(var i=0;i<resourcesArray.length;i++){
            var temp=resourcesArray[i];
            calendar.addResource({
              id: temp['id'],
              title: temp['title']});
          }
          break;
        case "Ressources Matérielles":
        var resourcesArray=JSON.parse(document.getElementById('material').value.replaceAll("3aZt3r", " "));
        for(var i=0;i<resourcesArray.length;i++){
          var temp=resourcesArray[i];
          calendar.addResource({
            id: temp['id'],
            title: temp['title']});
        }
        break;
      }
    
    calendar.gotoDate(date);
    calendar.render();
}


function PreviousDay(){
  var oldDate=new Date(document.getElementById("Date").value)
  var newDate=new Date(oldDate.getFullYear(),oldDate.getMonth(),oldDate.getDate()-1)
  var day=newDate.getDate();
  var month=newDate.getMonth()+1;
  var year=newDate.getFullYear();
  if (day<10){day="0"+day;}
  if (month<10){month="0"+month;}
  dateStr=year+"-"+month+"-"+day+"T12:00:00";
  window.location.assign("/ConsultationPlanning?date="+dateStr);

}
function NextDay(){
  var oldDate=new Date(document.getElementById("Date").value)
  var newDate=new Date(oldDate.getFullYear(),oldDate.getMonth(),oldDate.getDate()+1)
  var day=newDate.getDate();
  var month=newDate.getMonth()+1;
  var year=newDate.getFullYear();
  if (day<10){day="0"+day;}
  if (month<10){month="0"+month;}
  dateStr=year+"-"+month+"-"+day+"T12:00:00";
  window.location.assign("/ConsultationPlanning?date="+dateStr);
}

function Today(){
  var today=new Date();
  var day=today.getDate();
  var month=today.getMonth()+1;
  var year=today.getFullYear();
  if (day<10){day="0"+day;}
  if (month<10){month="0"+month;}
  dateStr=year+"-"+month+"-"+day+"T12:00:00";
  window.location.assign("/ConsultationPlanning?date="+dateStr);
}