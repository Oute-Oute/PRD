//var date = document.getElementById(date).value;
//alert(document.getElementById(date).value);
var calendar;
document.addEventListener('DOMContentLoaded', function() 
{
    const height = document.querySelector('div').clientHeight;
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, 
    {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        initialView: 'resourceTimelineDay',
        slotDuration: '00:20:00',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        selectable: true,
        selectHelper: true,
        editable: true,
        contentHeight: height-120,
        handleWindowResize: true,
        eventDurationEditable: false,
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'resourceTimelineDay'
        },
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
            ],
        select: function(event, element) {
            $('#modify-planning-modal').modal('toggle');
            },
    },
    );
    //alert(date);
    //calendar.goToDate(date);
    calendar.render();
});

function modifyEvent(){
    
    document.getElementById("succ").innerHTML="Ajout compte réussi";
    document.getElementById("success").style.display="block";
    unshowDiv('modify-planning-modal');
    setTimeout(()=>{document.getElementById("success").style.display="none";},6000);
    
}