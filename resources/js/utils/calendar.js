export function initCalendar(onDateChange) {
  $(function () {
    const start = moment();
    const end = moment();

    function cb(start, end) {
      $('#kt_daterangepicker_1').val(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));

      if (typeof onDateChange === 'function') {
        onDateChange(start, end); // send selected dates back to Vue
      }
    }

    $('#kt_daterangepicker_1').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
      }
    }, cb);

    cb(start, end); // set initial text value
  });
}
