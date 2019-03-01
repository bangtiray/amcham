$(document).ready(function() {
  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();  
        
   var calendar =  $('#calendar').fullCalendar({
            header: {
              left: 'prev',
              center: 'title',
              right: 'next'
            },
            editable: false,
            firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
            selectable: false,
            defaultView: 'month',
            
            axisFormat: 'h:mm',
            columnFormat: {
                      month: 'ddd',    // Mon
                      week: 'ddd d', // Mon 7
                      day: 'dddd M/d',  // Monday 9/7
                      agendaDay: 'dddd d'
                  },
                  titleFormat: {
                      month: 'MMMM yyyy', // September 2009
                      week: "MMMM yyyy", // September 2009
                      day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
                  },
            allDaySlot: false,
            selectHelper: true,
            select: function(start, end, allDay) {
              var title = prompt('Event Title:');
              if (title) {
                calendar.fullCalendar('renderEvent',
                  {
                    title: title,
                    start: start,
                    end: end,
                    allDay: allDay
                  },
                  true // make the event "stick"
                );
              }
              calendar.fullCalendar('unselect');
            },
            events :  {event_data} ,
            // events : [{"title":"amcham event","type_event":"Non-AmCham events","category":"education","start":"2018-01-02 12 : 00","end":"2018-01-02 12 : 40","allDay":false,"className":"info","url":"http:\/\/localhost\/amcham\/en\/event\/detail\/amcham-event"},{"title":"non event amcham","type_event":"Amcham Event","category":"Annual Golf Turnament","start":"2018-01-02 15:00","end":"2018-01-02 17:00","allDay":false,"className":"info","url":"http:\/\/localhost\/amcham\/en\/event\/detail\/non-event-amcham"},{"title":"annual golf","type_event":"Non-AmCham events","category":"Social & networking","start":"2018-01-01","end":"2018-01-30","allDay":false,"className":"info","url":"http:\/\/localhost\/amcham\/en\/event\/detail\/annual-golf"},{"title":"policy event","type_event":"Non-AmCham events","category":"Policy","start":"2018-01-22","end":"2018-01-31","allDay":false,"className":"info","url":"http:\/\/localhost\/amcham\/en\/event\/detail\/policy-event"},{"title":"evente terbaru","type_event":"Non-AmCham events","category":"education","start":"2018-02-01","end":"2018-02-02","allDay":false,"className":"info","url":"http:\/\/localhost\/amcham\/en\/event\/detail\/evente-terbaru"},{"title":"awdad","type_event":"Amcham Event","category":"Annual Golf Turnament","start":null,"end":null,"allDay":false,"className":"info","url":"http:\/\/localhost\/amcham\/en\/event\/detail\/awdad"}]  ,
                eventRender: function(event, element) { 
                    element.find('.fc-event-title').append("<br/><b>" + event.category + " - " + event.type_event +"</b>");
                },
                eventClick: function(event) {
                if (event.url) {
                    window.open(event.url, "_blank");
                    return false;
                }
            }
          });


  // var calendar =  $('#calendar').fullCalendar({
  //   header: {
  //     left: 'prev',
  //     center: 'title',
  //     right: 'next'
  //   },
  //   editable: false,
  //   firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
  //   selectable: true,
  //   defaultView: 'month',
    
  //   axisFormat: 'h:mm',
  //   columnFormat: {
  //             month: 'ddd',    // Mon
  //             week: 'ddd d', // Mon 7
  //             day: 'dddd M/d',  // Monday 9/7
  //             agendaDay: 'dddd d'
  //         },
  //         titleFormat: {
  //             month: 'MMMM yyyy', // September 2009
  //             week: "MMMM yyyy", // September 2009
  //             day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
  //         },
  //   allDaySlot: false,
  //   selectHelper: false,
  //   // select: function(start, end, allDay) {
  //   //   var title = prompt('Event Title:');
  //   //   if (title) {
  //   //     calendar.fullCalendar('renderEvent',
  //   //       {
  //   //         title: title,
  //   //         start: start,
  //   //         end: end,
  //   //         allDay: allDay
  //   //       },
  //   //       true // make the event "stick"
  //   //     );
  //   //   }
  //   //   calendar.fullCalendar('unselect');
  //   // },
  //   droppable: false, // this allows things to be dropped onto the calendar !!!
  //   // drop: function(date, allDay) { // this function is called when something is dropped
    
  //   //   // retrieve the dropped element's stored Event Object
  //   //   var originalEventObject = $(this).data('eventObject');
      
  //   //   // we need to copy it, so that multiple events don't have a reference to the same object
  //   //   var copiedEventObject = $.extend({}, originalEventObject);
      
  //   //   // assign it the date that was reported
  //   //   copiedEventObject.start = date;
  //   //   copiedEventObject.allDay = allDay;
      
  //   //   // render the event on the calendar
  //   //   // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
  //   //   $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
      
  //   //   // is the "remove after drop" checkbox checked?
  //   //   if ($('#drop-remove').is(':checked')) {
  //   //     // if so, remove the element from the "Draggable Events" list
  //   //     $(this).remove();
  //   //   }
      
  //   // },
    
  //   events: [
  //     {
  //       title: 'All Day Event',
  //       start: new Date(y, m, 1)
  //     },
  //     {
  //       id: 999,
  //       title: 'Repeating Event',
  //       start: new Date(y, m, d-3, 16, 0),
  //       allDay: false,
  //       className: 'red-back'
  //     },
  //     {
  //       id: 999,
  //       title: 'Repeating Event',
  //       start: new Date(y, m, d+4, 16, 0),
  //       allDay: false,
  //       className: 'info'
  //     },
  //     {
  //       title: 'Meeting',
  //       start: new Date(y, m, d, 10, 30),
  //       allDay: false,
  //       className: 'important'
  //     },
  //     {
  //       title: 'Lunch',
  //       start: new Date(y, m, d, 12, 0),
  //       end: new Date(y, m, d, 14, 0),
  //       allDay: false,
  //       className: 'important'
  //     },
  //     {
  //       title: 'Birthday Party',
  //       start: new Date(y, m, d+1, 19, 0),
  //       end: new Date(y, m, d+1, 22, 30),
  //       allDay: false,
  //     },
  //     {
  //       title: 'Click for Google',
  //       start: new Date(y, m, 28),
  //       end: new Date(y, m, 29),
  //       url: 'http://google.com/',
  //       className: 'success'
  //     }
  //   ],      
  // });
  
  
});