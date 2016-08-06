// given a timeStamp, return a string that represents the date in the format 'MM/DD/YYYY'
var formatDate = function(timeStamp) {
  var d = new Date(timeStamp*1000);
  //format month so that it starts at 1 and is two chars wide
  var month = String(d.getMonth()+1);
  if(month.length === 1){
    month = '0' + month;
  }
  //format day so that it is two chars wide
  var day = String(d.getDate());
  if(day.length === 1){
    day = '0' + day;
  }
  return  month + "/" + day + "/" +(d.getYear()+1900);
};

$(document).ready( function() {

   //  global variables
  startInput = $("#startDate");
  endInput = $("#endDate");
  dueInput = $("#dueDate");
  statsStartEndText = $("#statsStartToEnd");
  statsDueStartText = $("#statsDueToStart");

  //  initialized JQuery datepickers
  startInput.datepicker();
  endInput.datepicker();
  dueInput.datepicker();

  //update date info and min/max values for date fields
  if(document.getElementById("termForm") !== null){
    setMinMaxDates();
    updateStats();
  }
  
  //  function for when start date is changed/selected
  startInput.change( function() {
      if ($(this).val() != "") {
          //set min date for end of term and max date for due date
          setMinMaxDates();
          //  update term stats
          updateStats();
      }
  });

  //  update stats when end date changed/selected
  endInput.change( function() {
      if ($(this).val() != "") { updateStats(); }
  });

  //  update stats when due date changed/selected
  dueInput.change( function() {
      if ($(this).val() != "") { updateStats(); }
  });
  
  //  function to update statistics of term
  function updateStats() {
      //  horrifyingly large ammount of variables required for calculations
      var msecs_to_day = 1000*60*60*24;
      var day_names = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
      var startDate = new Date(startInput.val());
      var endDate = new Date(endInput.val());
      var startMsec = startDate.getTime();
      var endMsec = endDate.getTime();
      var dueMsec = (new Date(dueInput.val())).getTime();
      var start_to_end_days = Math.ceil((endMsec - startMsec) / msecs_to_day);
      var start_to_end_weeks = Math.ceil(start_to_end_days / 7);
      var due_to_start_days = Math.ceil((startMsec - dueMsec) / msecs_to_day);

      //  calculates term duration
      if (startInput.val() != "" && endInput.val() != "") {
          statsStartEndText.text("Term Duration: ~" + start_to_end_weeks + " weeks (" + start_to_end_days + " days), " + day_names[startDate.getDay()] + " to " + day_names[endDate.getDay()]);
      } else {
          statsStartEndText.text("Term Duration: UNKNOWN");
      }

      //  calculates time between due date and start of term
      if (startInput.val() != "" && dueInput.val() != "") {
          statsDueStartText.text("Due Date: " + due_to_start_days + " days before start");
      } else {
          statsDueStartText.text("Due Date: UNKNOWN");
      }
  }
  
  //set min date for end of term and max date for due date
  function setMinMaxDates(){
    var currentStart = startInput.datepicker("getDate");
    var endMin = new Date(currentStart);
    var dueMax = new Date(currentStart);

    //  calculate min and max dates for end of term and due date
    endMin.setDate(currentStart.getDate() + 1);
    dueMax.setDate(currentStart.getDate() - 1);

    //  set end of term min date
    endInput.datepicker("option", {
        minDate: endMin
    });
    //set due date max date
    dueInput.datepicker("option", {
        maxDate: dueMax
    });
  }
});
