$(document).ready( function() {
    var startInput = $("#startDate");
    var endInput = $("#endDate");
    var dueInput = $("#dueDate");
    var statsStartEndText = $("#statsStartToEnd");
    var statsDueStartText = $("#statsDueToStart");

    function updateStats() {
        var msecs_to_day = 1000*60*60*24;
        var day_names = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var startDate = new Date(startInput.val());
        var endDate = new Date(endInput.val());
        var startMsec = startDate.getTime();
        var endMsec = endDate.getTime();
        var dueMsec = (new Date(dueInput.val())).getTime();
        var start_to_end_days = Math.ceil((endMsec - startMsec) / msecs_to_day);
        var start_to_end_weeks = math.ceil(start_to_end_weeks / 7);
        var due_to_start_days = Math.ceil((startMsec - dueMsec) / msecs_to_day);

        if (startInput.val() != "" && endInput.val() != "") {
            statsStartEndText.text("Term Duration: " + start_to_end_weeks + " weeks (" + start_to_end_days + " days), " + day_names[startDate.getDay()] + " to " + day_names[endDate.getDay()]);
        } else {
            statsStartEndText.text("Term Duration: UNKNOWN");
        }
        if (startInput.val() != "" && dueInput.val() != "") {
            statsDueStartText.text("Due Date: " + due_to_start_days + " days before start of term");
        } else {
            statsDueStartText.text("Due Date: UNKNOWN");
        }

        // statsObj.text("Due  --- " + due_to_start_days + " days --->  Start  --- " + start_to_end_weeks + " weeks --->  End");
    }

    startInput.datepicker();
    endInput.datepicker();
    dueInput.datepicker();

    startInput.change( function() {
        if ($(this).val() != "") {
            var currentStart = $(this).datepicker("getDate");
            var defaultEnd = new Date(currentStart);
            var endMin = new Date(currentStart);
            var defaultDue = new Date(currentStart);
            var dueMax = new Date(currentStart);

            defaultEnd.setDate(currentStart.getDate() + 76);
            endMin.setDate(currentStart.getDate() + 1);
            defaultDue.setDate(currentStart.getDate() - 7);
            dueMax.setDate(currentStart.getDate() - 1);
            endInput.datepicker("option", {
                defaultDate: defaultEnd,
                minDate: endMin,
                disabled: false
            });
            endInput.val(defaultEnd.toLocaleDateString());
            dueInput.datepicker("option", {
                defaultDate: defaultDue,
                maxDate: dueMax,
                disabled: false
            });
            dueInput.val(defaultDue.toLocaleDateString());
            
            updateStats();
        }
    });

    endInput.change( function() {
        if ($(this).val() != "") { updateStats(); }
    });

    dueInput.change( function() {
        if ($(this).val() != "") { updateStats(); }
    });
});
