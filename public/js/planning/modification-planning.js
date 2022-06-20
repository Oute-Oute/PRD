document.addEventListener('DOMContentLoaded', function() 
{
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
        contentHeight: 600,
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
             start: "2022-06-17 12:00:00", 
             end: "2022-06-17 17:30:00", 
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
