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

  //  initialized JQuery datepickers
  startInput.datepicker();
  endInput.datepicker();

  //update date info and min/max values for date fields
  if(document.getElementById("termForm") !== null){
    setMinMaxDates();
  }
  
  
  //set min date for end of term and max date for due date
  function setMinMaxDates(){
    var currentStart = startInput.datepicker("getDate");
    var endMin = new Date(currentStart);

    //  calculate min and max dates for end of term and due date
    endMin.setDate(currentStart.getDate() + 1);

    //  set end of term min date
    endInput.datepicker("option", {
        minDate: endMin
    });
  }
});
