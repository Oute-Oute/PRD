var calendar;
var datepicker;

document.addEventListener('DOMContentLoaded', function() 
{
    


    const height = document.querySelector('div').clientHeight
    var calendarEl = document.getElementById('calendar')
    calendar= new FullCalendar.Calendar(calendarEl, 
    {
      headerToolBar: {
        left: "prev,next today datepickerButton",
        center: "",
        right: "basicWeek,basicDay"
      },
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        initialView: 'resourceTimelineDay',
        slotDuration: '00:20:00',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        selectable: true,
        selectHelper: true,
        editable: false,
        contentHeight: height-120,
        handleWindowResize: true,
        eventDurationEditable: false,

        slotLabelFormat: { //modifie l'affichage des heures de la journée
            hour: '2-digit', //2-digit, numeric
            minute: '2-digit', //2-digit, numeric
            meridiem: false, //lowercase, short, narrow, false (display of AM/PM)
            hour12: false //true, false
          },
          
        resources: [
        {
            id: 'a',
           title: 'ressource a',
        },
        {
            id: 'b',
            title: 'ressource b',
        }
          ],
        events:[
            {
             id: "1", 
             resourceId: "a", 
             start: "2022-06-21 12:00:00", 
             end: "2022-06-21 17:30:00", 
             title: "event 1",
             color:'rgb(255,255,0)',
             textColor:'#000',
             textFont:'Trebuchet MS'
            }
            ]
    },
    );
    calendar.render();
});

$(function () {
  // ACTIVATION DU DATEPICKER 
  $('.form-control').datepicker({
    language: 'fr',
      clearBtn: true,
      format: "dd/mm/yyyy",
      autoclose: true,
      todayHighlight: true,
});
});

function changeDate(){
  calendar.gotoDate($( ".form-control" ).datepicker( "getDate" ))
  calendar.next()
}

function changePlanning(){
  var selectedItem = document.getElementById("displayList");
  var selectedVar = selectedItem.options [selectedItem.selectedIndex].value;
    calendar.getResourceById("a").remove()
    calendar.getResourceById("b").remove()
    calendar.addResource({
    id: '1',
    title: "Parcours 1",
  });
  calendar.addResource({
    id: '2',
    title: "Parcours 2",
  });
  console.warn(selectedVar);
  calendar.render();
}



