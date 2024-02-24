document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['dayGrid', 'interaction'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: 'get_events.php', // PHP file to fetch events from database
        dateClick: function(info) {
            calendar.gotoDate(info.date); // Navigate to the clicked date
            calendar.changeView('dayGridMonth'); // Switch to month view
        },
        // Navigation buttons
        customButtons: {
            prevYear: {
                text: '<<', // Custom text for previous year
                click: function() {
                    calendar.prevYear(); // Navigate to the previous year
                }
            },
            nextYear: {
                text: '>>', // Custom text for next year
                click: function() {
                    calendar.nextYear(); // Navigate to the next year
                }
            }
        },
        headerToolbar: {
            left: 'prevYear,prev,next,nextYear today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        }
    });

    calendar.render();
});
