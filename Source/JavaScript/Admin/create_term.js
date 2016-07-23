$(document).ready( function() {
    var startObj = $("#startDate");
    var endObj = $("#endDate");
    var dueObj = $("#dueDate");
    var statsStartToEndObj = $("#statsStartToEnd");
    var statsDueToStartObj = $("$statsDueToStart");

    function updateStats() {
        var msecs_to_day = 1000*60*60*24;
        var day_names = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var startDate = new Date(startObj.val());
        var endDate = new Date(endObj.val());
        var startMsec = startDate.getTime();
        var endMsec = endDate.getTime();
        var dueMsec = (new Date(dueObj.val())).getTime();
        var start_to_end_days = Math.ceil((endMsec - startMsec) / msecs_to_day);
        var start_to_end_weeks = math.ceil(start_to_end_weeks / 7);
        var due_to_start_days = Math.ceil((startMsec - dueMsec) / msecs_to_day);

        if (startObj.val() != "" && endObj.val() != "") {
            statsStartToEndObj.text("Term Duration: " + start_to_end_weeks + " weeks (" + start_to_end_days + " days), " + day_names[startDate.getDay()] + " to " + day_names[endDate.getDay()]);
        } else {
            statsStartToEndObj.text("Term Duration: UNKNOWN");
        }
        if (startObj.val() != "" && dueObj.val() != "") {
            statsDueToStartObj.text("Time Between Due Date and Start of Term: " + due_to_start_days + " days");
        } else {
            statsDueToStartObj.text("Time Between Due Date and Start of Term: UNKNOWN");
        }

        // statsObj.text("Due  --- " + due_to_start_days + " days --->  Start  --- " + start_to_end_weeks + " weeks --->  End");
    }

    startObj.datepicker();
    endObj.datepicker();
    dueObj.datepicker();

    startObj.change( function() {
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
            endObj.datepicker("option", {
                defaultDate: defaultEnd,
                minDate: endMin,
                disabled: false
            });
            endObj.val(defaultEnd.toLocaleDateString());
            dueObj.datepicker("option", {
                defaultDate: defaultDue,
                maxDate: dueMax,
                disabled: false
            });
            dueObj.val(defaultDue.toLocaleDateString());
            
            updateStats();
        }
    });

    endObj.change( function() {
        if ($(this).val() != "") { updateStats(); }
    });

    dueObj.change( function() {
        if ($(this).val() != "") { updateStats(); }
    });
});
