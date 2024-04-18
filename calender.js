document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    themeSystem: 'bootstrap5',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },

    buttonText: {
      prev: '先月',
      next: '来月',
      today: '今日',
      dayGridMonth: '月',
      timeGridWeek: '週',
      timeGridDay: '日',
      listMonth: 'リスト',
    },

    locale: 'ja',
    timeZone: 'Asia/Tokyo',
    eventLimit: true,
    navLinks: true, // can click day/week names to navigate views
    selectable: true,
    selectMirror: true,

    select: function(arg) {
      var title = prompt('Event Title:');
      if (title) {
        calendar.addEvent({
          title: title,
          start: arg.start,
          end: arg.end,
          allDay: arg.allDay
        })
      }
      calendar.unselect()
    },

    eventClick: function(arg) {
      if (confirm('Are you sure you want to delete this event?')) {
        arg.event.remove()
      }
    },

    editable: true, //noの予定
    dayMaxEvents: true, // allow "more" link when too many events
    events: [
      {
        title: 'All Day Event',
        start: '2024-04-01'
      },
      {
      url: 'getShifts.php',
      failure: function() {
        alert('シフト情報を取得できませんでした。');
      }
    }
    ]
  });

  calendar.render();
});

