$(function () {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('#calendar-holder').fullCalendar({
        header: {
            left: ' ',
            center: 'prev,title, next',
            right: 'month, basicWeek, basicDay,'
        },
      


        displayEventEnd: true,
        lazyFetching: true,
        timeFormat: {
            // for agendaWeek and agendaDay
            agenda: 'h:mmt',    // 5:00 - 6:30

            // for all other views
                    // 7p
        },
        
        eventSources: [
            {
                url: Routing.generate('fullcalendar_loader'),
                type: 'POST',
                // A way to add custom filters to your event listeners
                data: {
                },

                error: function() {
                   //alert('There was an error while fetching Google Calendar!');
                }
            }
        ]
    });


});
