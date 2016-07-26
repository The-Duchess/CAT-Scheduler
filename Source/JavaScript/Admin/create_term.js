$(document).ready( function() {
    //  global variables
    var startInput = $("#startDate");
    var endInput = $("#endDate");
    var dueInput = $("#dueDate");
    var statsStartEndText = $("#statsStartToEnd");
    var statsDueStartText = $("#statsDueToStart");

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

        // statsObj.text("Due  --- " + due_to_start_days + " days --->  Start  --- " + start_to_end_weeks + " weeks --->  End");
    }

    //  initialized JQuery datepickers
    startInput.datepicker();
    endInput.datepicker();
    dueInput.datepicker();

    //  function for when start date is changed/selected
    startInput.change( function() {
        if ($(this).val() != "") {
            //  variables
            var currentStart = $(this).datepicker("getDate");
            var defaultEnd = new Date(currentStart);
            var endMin = new Date(currentStart);
            var defaultDue = new Date(currentStart);
            var dueMax = new Date(currentStart);

            //  calculate default dates for end and due dates
            defaultEnd.setDate(currentStart.getDate() + 76);
            endMin.setDate(currentStart.getDate() + 1);
            defaultDue.setDate(currentStart.getDate() - 7);
            dueMax.setDate(currentStart.getDate() - 1);

            //  enable end date, set default date and min date
            endInput.datepicker("option", {
                defaultDate: defaultEnd,
                minDate: endMin,
                disabled: false
            });
            //enable due date, set default date and max date
            endInput.val(defaultEnd.toLocaleDateString());
            dueInput.datepicker("option", {
                defaultDate: defaultDue,
                maxDate: dueMax,
                disabled: false
            });
            dueInput.val(defaultDue.toLocaleDateString());
         
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
});
