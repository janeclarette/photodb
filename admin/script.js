document.addEventListener('DOMContentLoaded', function () {
    var prevMonthBtn = document.getElementById('prevMonthBtn');
    var nextMonthBtn = document.getElementById('nextMonthBtn');

    prevMonthBtn.addEventListener('click', function () {
        updateCalendar('previous');
    });

    nextMonthBtn.addEventListener('click', function () {
        updateCalendar('next');
    });
});

function updateCalendar(direction) {
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    var currentMonth = currentDate.getMonth() + 1; // Note: JavaScript months are zero-based

    if (direction === 'previous') {
        // Move to the previous month
        currentMonth--;
        if (currentMonth === 0) {
            currentMonth = 12;
            currentYear--;
        }
    } else if (direction === 'next') {
        // Move to the next month
        currentMonth++;
        if (currentMonth === 13) {
            currentMonth = 1;
            currentYear++;
        }
    }

    // Construct the new URL with updated month and year
    var newUrl = '?year=' + currentYear + '&month=' + currentMonth;

    // Redirect to the new URL
    window.location.href = newUrl;
}
