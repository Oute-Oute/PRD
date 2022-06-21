document.addEventListener('DOMContentLoaded', function() 
{
    
    const height = document.querySelector('div').clientHeight;
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, 
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
            start: null,
            center: 'title',
            end: null
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
            eventClick: function(event, element) {
                var id = event.event._def.publicId;
                var activity = calendar.getEventById(id);
                var tmp = activity.end - activity.start;
                time = Math.floor((tmp/1000/60));
    
                const options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' };
                date = activity.start.toLocaleDateString(undefined, options);
    
                $('#new-start').val(date);
                $('#new-title').val(activity.title);
                $('#new-time').val(time);
                $('#modify-planning-modal').modal("show");
            },
    },
    );

    calendar.render();
});

function modifyEvent(){
    
    document.getElementById("succ").innerHTML="Modification de l'activité réussie";
    document.getElementById("success").style.display="block";
    unshowDiv('modify-planning-modal');
    setTimeout(()=>{document.getElementById("success").style.display="none";},6000);
    
}